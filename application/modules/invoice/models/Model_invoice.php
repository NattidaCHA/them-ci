<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_invoice extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getInvoice($condition = [])
    {
        $val = json_decode(json_encode($condition));
        $result = [];

        if (!empty($val->cus_no)) {
            $this->db->where('T1.mcustno', $val->cus_no);
        }

        if (!empty($val->type)) {
            $this->db->where('T1.msaleorg', $val->type);
        }

        $sql = $this->db->select('T1.mcustno,T1.mdoctype,SUM(T1.mnetamt) as total_mnetamt,MAX(T1.mcustname) as cus_name,MAX(T1.msaleorg) as msaleorg,MAX(T1.mduedate) as end_date,MAX(T1.mpostdate) as start_date,MAX(T3.mday) as send_date')
            ->where("(T1.mpostdate >='$val->startDate' AND T1.mduedate <='$val->endDate')")
            ->join('cust_notification T3', 'T3.mforsend1 = T1.mcustno', 'T3.mforsend2 = T1.mcustno', 'left')
            ->where('T3.mday', $val->dateSelect)
            ->group_by('T1.mdoctype')
            ->group_by('T1.mcustno')
            ->get('vw_billpay_txt02 T1');
        $result = $sql->result();
        $sql->free_result();
        $data = [];

        if (!empty($result)) {
            foreach ($result as $row) {
                $data[$row->mcustno][] = $row;
            }
        }

        return $this->processData($data);
    }

    public function processData($result)
    {
        $lists = [];
        foreach ($result as $key => $rows) {
            $data = (object)[
                'cus_no' => $key
            ];

            foreach ($rows as $val) {
                $data->cus_name = !empty($val->cus_name) || $val->cus_name != '-' ? $val->cus_name : '-';
                $data->msaleorg = !empty($val->msaleorg) ? $val->msaleorg : '-';
                $data->start_date = !empty($val->start_date) ? $val->start_date : '-';
                $data->end_date = !empty($val->end_date) ? $val->end_date : '-';
                $data->send_date = !empty($val->send_date) ? $val->send_date : '-';

                if ($val->mdoctype == 'DC') {
                    $data->DC = floatval($val->total_mnetamt);
                }
                if ($val->mdoctype == 'RA') {
                    $data->RA = floatval($val->total_mnetamt);
                }
                if ($val->mdoctype == 'RB') {
                    $data->RB = floatval($val->total_mnetamt);
                }
                if ($val->mdoctype == 'RC') {
                    $data->RC = floatval($val->total_mnetamt);
                }
                if ($val->mdoctype == 'RD') {
                    $data->RD = floatval($val->total_mnetamt);
                }
                if ($val->mdoctype == 'RE') {
                    $data->RE = floatval($val->total_mnetamt);
                }
            }
            $lists[$key] = $data;
        }

        return  $this->calculateTotall($lists);
    }

    public function getCustomerChain($result)
    {

        $lists = [];
        foreach ($result as $rows) {
            $condition = (object) [
                'cus_no' => '',
                'start_date' => $rows->start_date,
                'end_date' => $rows->end_date,
                'send_date' => $rows->send_date,
            ];

            $val = $this->findChildCustomer($rows->cus_no);

            if (!empty($val)) {
                foreach ($val as $bill) {
                    $condition->cus_no = $bill->cus_no;
                    $bills = $this->getBillChild($condition);
                    if (!empty($bills)) {
                        $lists[$rows->cus_no]['childs'][] = $bills;
        
                    }
                }
            }
        }

        return $lists;
    }

    public function calculateTotall($result)
    {
        foreach ($result as $res) {
            $total = 0;
            if (!empty($res->RA)) {
                $total = $total + $res->RA;
            }
            if (!empty($res->RD)) {
                $total = $total + $res->RD;
            }
            if (!empty($res->DC)) {
                $total = $total - $res->DC;
            }
            if (!empty($res->RB)) {
                $total = $total - $res->RB;
            }
            if (!empty($res->RC)) {
                $total = $total - $res->RC;
            }
            if (!empty($res->RE)) {
                $total = $total - $res->RE;
            }
            $res->balance =  $total;
        }

        return $result;
    }

    public function findChildCustomer($id)
    {
        $result = [];
        $sql = $this->db->select('MAX(mcustno) as cus_no')
            ->where('mcustno_sendto', $id)
            ->group_by('mcustno')
            ->get('cust_notifcation_sendto');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getBillChild($condition)
    {
        $result = [];
        $sql = $this->db->select('T1.mcustno,T1.mdoctype,SUM(T1.mnetamt) as total_mnetamt,MAX(T1.mcustname) as cus_name,MAX(T1.msaleorg) as msaleorg')
            ->where("T1.mcustno", $condition->cus_no)
            ->where("(T1.mpostdate >='$condition->start_date' AND T1.mduedate <='$condition->end_date')")
            ->join('cust_notification T3', 'T3.mcustno = T1.mcustno', 'left')
            ->where('T3.mday', $condition->send_date)
            ->group_by('T1.mdoctype')
            ->group_by('T1.mcustno')
            ->get('vw_billpay_txt02 T1');
        $result = $sql->result();
        $sql->free_result();

        $data = [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $data[$row->mcustno][] = $row;
            }
        }

        return $this->processData($data);
    }
}
