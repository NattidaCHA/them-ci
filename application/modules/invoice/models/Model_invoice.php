<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_invoice extends MY_Model
{

    private $tableAllowFieldsInvoice = ['uuid', 'cus_main', 'cus_no', 'bill_no', 'is_email', 'created_date', 'is_sms', 'is_bill_email', 'start_date', 'end_date', 'created_by','is_receive_bill'];
    private $tableAllowFieldsInvoiceDatail = ['uuid', 'bill_no', 'bill_id', 'macctdoc', 'cus_no', 'cus_main', 'mdoctype', 'mbillno', 'mpostdate', 'mduedate', 'msaleorg', 'mpayterm', 'mnetamt', 'mtext', 'msort'];

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

        // if (!empty($val->type)) {
        //     $this->db->where('T1.msaleorg', $val->type);
        // }

        $sql = $this->db->select('T1.mcustno,T1.mdoctype,SUM(T1.mnetamt) as total_mnetamt,MAX(T1.mcustname) as cus_name,MAX(T1.msaleorg) as msaleorg,MAX(T1.mduedate) as end_date,MAX(T1.mpostdate) as start_date,MAX(T3.mday) as send_date')
            ->where("(T1.mpostdate >='$val->startDate' AND T1.mduedate <='$val->endDate')")
            ->join('cust_notification T3', 'T3.mforsend1 = T1.mcustno', 'T3.mforsend2 = T1.mcustno', 'left')
            // ->join('cust_notification T3', 'T3.mcustno = T1.mcustno', 'left')
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

    // public function getCustomerChain($result)
    // {

    //     $lists = [];
    //     foreach ($result as $rows) {
    //         $val = $this->findChildCustomer((object)['cus_no' => $rows->cus_no]);
    //         $lists[$rows->cus_no] = $val;
    //     }
    //     return $lists;
    // }

    public function getCustomerChain($id)
    {

        $lists = [];
        $lists = $this->findChildCustomer((object)['cus_no' => $id]);
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

    // public function findChildCustomer($condition)
    // {
    //     $result = [];
    //     $sql = $this->db->select('MAX(T1.mcustno) as cus_no, MAX(T2.mcustname) as cus_name,MAX(T2.msaleorg) as saleorg')
    //         ->where('T1.mcustno_sendto', $condition->cus_no)
    //         // ->where('T1.mcustno_sendto', '0002000386')
    //         ->join('vw_Customer_DWH T2', 'T2.mcustno = T1.mcustno', 'left')
    //         ->group_by('T1.mcustno')
    //         ->get('cust_notifcation_sendto T1');
    //     $result = $sql->result();
    //     $sql->free_result();
    //     return  $result;
    // }

    public function findChildCustomer($condition)
    {
        $result = [];
        $sql = $this->db->select('MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg')
            ->where('msendto', $condition->cus_no)
            ->group_by('mcustno')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();

        return  $result;
    }

    public function getBillChild($condition)
    {
        $result = [];
        $sql = $this->db->select('T1.macctdoc,T1.mdoctype,T1.mnetamt,T1.msaleorg,T1.mduedate,T1.mbillno')
            ->where("(T1.mcustno = '$condition->cus_no' AND T1.mpostdate >='$condition->start_date' AND T1.mduedate <='$condition->end_date')")
            // ->where("(T1.mcustno = '0002000387' AND T1.mpostdate >='$condition->start_date' AND T1.mduedate <='$condition->end_date')")
            ->join('cust_notification T2', 'T2.mcustno = T1.mcustno', 'left')
            ->where('T2.mday', $condition->send_date)
            ->get('vw_billpay_txt02 T1');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getDetailCustomer($res)
    {
        $childs = [];
        $val = json_decode(json_encode($res));

        if (!empty($val)) {
            $sql = $sql = $this->db->select('mcustname')->where('mcustno', $val->cus_no)->get('tbl_custtel');
            $customer = $sql->row();
            $res = $this->findChildCustomer($val);
            foreach ($res as $bill) {
                $val->cus_no = $bill->cus_no;
                $bills = $this->getBillChild($val);
                $data = (object)[
                    'info' => (object)[
                        'cus_no' => $bill->cus_no,
                        'cus_name' => $bill->cus_name,
                        'saleorg' =>  $bill->msaleorg
                    ],
                    'bills' => [],
                    'balance' => 0
                ];
                if (!empty($bills)) {
                    $data->bills = $bills;
                    $data->balance = $this->calculateTotallChild($bills);
                    $childs[$bill->cus_no] = $data;
                }
            }
        }

        $sql->free_result();
        $lists = (object) [
            'cus_no' => $val->cus_no,
            'cus_name' => !empty($customer) && !empty($customer->mcustname)  ? $customer->mcustname : '-',
            'childs' => $childs,
        ];
        return $lists;
    }

    public function processCustomerId($result)
    {
    }

    public function calculateTotallChild($result)
    {
        $total = 0;
        foreach ($result as $res) {
            if (!empty($res->mdoctype == 'RA')) {
                $total = $total + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RD')) {
                $total = $total + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'DC')) {
                $total = $total - $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RB')) {
                $total = $total - $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RC')) {
                $total = $total - $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RE')) {
                $total = $total - $res->mnetamt;
            }
            $res->balance =  $total;
        }

        return $total;
    }


    public function createInvoice($params)
    {

        $checkFields = array_fill_keys($this->tableAllowFieldsInvoice, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('report_notification', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function createDetailInvoice($params)
    {
        $checkFields = array_fill_keys($this->tableAllowFieldsInvoiceDatail, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('report_notification_detail', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function getReportId($main_id)
    {
        $result = [];
        $sql = $this->db->select('MAX(bill_no) as bill_no,MAX(created_date) as created_date,MAX(cus_main) as cus_main')
            ->where("cus_main", $main_id)
            ->group_by('report_notification.bill_no')
            ->order_by('report_notification.bill_no', 'desc')
            ->get('report_notification');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }


    public function getReportUuid($uuid)
    {
        $result = [];
        $sql = $this->db->select('*')
            ->where("uuid", $uuid)
            ->get('report_notification');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }

    public function getReport()
    {
        $result = [];
        $sql = $this->db->select('*')
            ->order_by('report_notification.created_date', 'desc')
            ->get('report_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getBillNo()
    {
        $result = [];
        $sql = $this->db->select('*')->get('report_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getBill($condition)
    {
        $val = json_decode(json_encode($condition));
        $result = [];

        if (!empty($val->cus_no)) {
            $this->db->where('T1.cus_no', $val->cus_no);
        }

        if (!empty($val->bill_no)) {
            $this->db->where('T1.bill_no', $val->bill_no);
        }

        if (!empty($val->created_date)) {
            $this->db->where('T1.created_date', $val->created_date);
        }

        $sql = $this->db->select('T1.uuid,T1.bill_no,MAX(CONVERT(int,T1.is_email)) as is_email,MAX(T1.cus_main) as cus_main,MAX(T1.created_date) as created_date,MAX(T1.cus_no) as cus_no,MAX(T2.memail) as memail,MAX(T2.mfax) as mfax,MAX(T2.mcustname) as mcustname')
            ->join('tbl_custtel T2', 'T2.mcustno = T1.cus_no', 'left')
            ->order_by('bill_no', 'desc')
            ->group_by('T1.uuid')
            ->group_by('T1.bill_no')
            ->get('report_notification T1');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getItem($macctdoc)
    {
        $sql = $this->db->where('macctdoc', $macctdoc)->get('vw_billpay_txt02');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }
}
