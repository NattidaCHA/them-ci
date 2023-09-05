<?php

use Mpdf\Tag\Em;

(defined('BASEPATH')) or exit('No direct script access allowed');

class Model_report extends MY_Model
{
    private $tableAllowFieldsCfCall = ['uuid', 'report_uuid', 'tel', 'cus_main', 'cf_call', 'receive_call'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getBillNo()
    {
        $result = [];
        $sql = $this->db->select('*')->get('report_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getEmailById($cus_no, $cus_main)
    {
        $result = [];
        $lists = [];
        $isCheck = $this->checkSendto($cus_no, $cus_main);


        foreach ($isCheck as $val) {
            $sql = $this->db->where('cus_main', $val->cus_main)->get('email_customer');
            $result = $sql->result();
            $sql->free_result();

            foreach ($result as $val) {
                $lists[$cus_no][] = $val;
            }
        }

        return  $lists;
    }

    public function getTelById($cus_no, $cus_main)
    {
        $result = [];
        $lists = [];
        $isCheck = $this->checkSendto($cus_no, $cus_main);

        foreach ($isCheck as $val) {
            $sql = $this->db->where('cus_main', $val->cus_main)->get('tel_customer');
            $result = $sql->result();
            $sql->free_result();

            foreach ($result as $val) {
                $lists[$cus_no][] = $val;
            }
        }
        return  $lists;
    }

    public function getReceivecall($reportUuid)
    {
        $result = [];
        $lists = [];
        $sql = $this->db->where('report_uuid', $reportUuid)->where('receive_call !=', NULL)->get('cf_call_report');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            $lists[$result->report_uuid][] = $val;
        }

        return  $lists;
    }

    public function checkSendto($cus_no, $cus_main)
    {
        $result = (object)[];
        $sql = $this->db->select("cus_main,MAX(CONVERT(int,is_check)) as is_check")
            ->where("(cus_main = '$cus_no' OR cus_main = '$cus_main')")
            ->where("is_check =", 1)
            ->group_by('cus_main')
            ->get('sendto_customer');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
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
            $startDate = $val->created_date . ' 00:00:00';
            $endDate = $val->created_date . ' 23:59:59';
            $this->db->where("(T1.created_date >='$startDate' AND T1.created_date <='$endDate')");
        }

        $sql = $this->db->select('T1.uuid,T1.bill_no,MAX(CONVERT(int,T1.is_bill_email)) as is_bill_email,MAX(CONVERT(int,T1.is_receive_bill)) as is_receive_bill,MAX(CONVERT(int,T1.is_email)) as is_email,MAX(T1.cus_main) as cus_main,MAX(T1.created_date) as created_date,MAX(T1.cus_no) as cus_no,MAX(T2.mcustname) as mcustname,MAX(T1.created_by) as created_by,MAX(T1.receive_call) as receive_call')
            ->join('vw_Customer_DWH T2', 'T2.mcustno = T1.cus_no', 'left')
            ->group_by('T1.uuid')
            ->group_by('T1.bill_no')
            ->order_by('bill_no', 'desc')
            ->get('report_notification T1');

        $result = $sql->result();
        $sql->free_result();
        // echo '<pre>';
        // var_dump($result);
        // echo '</pre>';
        return $result;
    }

    public function getListItem($bill_id)
    {
        //MAX(uuid) as uuid,MAX(bill_no) as bill_no,MAX(bill_id) as bill_id,MAX(macctdoc) as macctdoc,MAX(cus_no) as cus_no,MAX(cus_main) as cus_main,MAX(mdoctype) as mdoctype,MAX(CONVERT(varchar,mbillno)) as mbillno,MAX(mpostdate) as mpostdate,MAX(mduedate) as mduedate,MAX (msaleorg) as msaleorg,MAX(mpayterm) as mpayterm,MAX(CONVERT(float,mnetamt)) as mnetamt,MAX(mtext) as mtext,MAX(msort) as msort
        $sql = $this->db->select('*')
            ->where('bill_id', $bill_id)
            ->order_by('msort', 'mbillno', 'mpostdate', 'mduedate', 'mpayterm', 'mnetamt', 'asc')
            // ->order_by('mpostdate','asc')
            ->get('report_notification_detail');
        $result = $sql->result();
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

    public function genPDF($bill_id)
    {
        $data = (object)[
            'info' => (object)[],
            'bill_info' => (object)[],
            'lists' => [],
            'total' => (object)[
                'total_debit' => 0,
                'total_credit' => 0,
                'total_summary' => 0
            ],
            'total_items' => 0,
            'total_page' => 0,
            'payment' => [],
            'barcode' => (object)[
                'code' => '',
                'image' => ''
            ],
            'qrcode' => ''
        ];

        if (!empty($bill_id)) {
            $bill_info = $this->getReportUuid($bill_id);
            $itemLists = $this->getListItem($bill_id);
            $data->payment = $this->getPayment();
            $info = (object)[];

            if (!empty($bill_info)) {
                $data->bill_info = $bill_info;
                $info = $this->getCustomerInfo($bill_info->cus_main);

                if (!empty($info)) {
                    $data->info = $info;
                }
            }

            if (!empty($itemLists)) {
                foreach ($itemLists as $val) {
                    if (!empty($val)) {
                        $val->type = $this->genTypet($val->mdoctype)->type;
                        $val->sortType = $this->genTypet($val->mdoctype)->sortType;
                    }
                }

                // usort($itemLists, function ($a, $b) {
                //     return $a->mpostdate > $b->mpostdate;
                // });
                // echo '<pre>';
                // var_dump($bill_info);
                // echo '</pre>';
                // exit;
                $data->total_items = count($itemLists);
                $size = count($itemLists) > 40 ? 55 : 40;
                $count = ceil(count($itemLists) / $size);
                $data->total_page = $count;
                $data->total = $this->calculateTotallChild($itemLists);
                $code =  "|0105562170352\n00\n" . $info->mcustno .  "\n" . str_replace('N', '8', $bill_info->bill_no) .   "\n" . str_replace('.', '', (str_replace('-', '', $data->total->total_summary)));
                $data->qrcode = $this->qrcode($code);
                $data->barcode->image = $this->barcode($code);
                $data->barcode->code = $code;

                for ($i = 1; $i <= $count; $i++) {
                    $sumList = $this->paginate($itemLists, $size, $i);
                    $sumList['total'] = $this->calculateTotallChild($sumList)->total_summary;
                    $data->lists[$i] = $sumList;
                }
            }


            // usort($data->lists, function ($a, $b) {
            //     return $a->sortType < $b->sortType;
            // });
            // echo '<pre>';
            // var_dump($data);
            // echo '</pre>';
            // exit;
        }
        return $data;
    }

    public function paginate($array, $page_size, $page_number)
    {
        return array_slice($array, ($page_number - 1) * $page_size, $page_size);
    }

    public function getCustomerInfo($cus_no)
    {
        $result = [];
        $sql = $this->db->where("mcustno", $cus_no)->get('vw_Customer_DWH');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }

    public function calculateTotallChild($result)
    {
        $total_summary = 0;
        $total_debit = 0;
        $total_credit = 0;
        foreach ($result as $res) {
            if (!empty($res->mdoctype == 'RA')) {
                $total_summary = $total_summary + $res->mnetamt;
                $total_debit = $total_debit + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RD')) {
                $total_summary = $total_summary + $res->mnetamt;
                $total_debit = $total_debit + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'DC')) {
                $total_summary = $total_summary - $res->mnetamt;
                $total_credit = $total_credit + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RB')) {
                $total_summary = $total_summary - $res->mnetamt;
                $total_credit = $total_credit + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RC')) {
                $total_summary = $total_summary - $res->mnetamt;
                $total_credit = $total_credit + $res->mnetamt;
            }
            if (!empty($res->mdoctype == 'RE')) {
                $total_summary = $total_summary - $res->mnetamt;
                $total_credit = $total_credit + $res->mnetamt;
            }
        }

        return (object)['total_debit' => $total_debit, 'total_credit' => $total_credit, 'total_summary' =>  $total_summary];
    }

    public function getPayment()
    {
        $sql = $this->db->get('payment');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }


    public function genTypet($res)
    {
        $text = '';
        $sortType = 1;
        if (!empty($res == 'RA')) {
            $text = 'RA - ยอด Invoice';
            $sortType  = 1;
        }
        if (!empty($res == 'RD')) {
            $text = 'RD - ยอดเพิ่มหนี้';
            $sortType  = 2;
        }
        if (!empty($res == 'DC')) {
            $text = 'DC - ยอด Rebate';
            $sortType  = 5;
        }
        if (!empty($res == 'RB')) {
            $text = 'RB - ยอดเงินเหลือ';
            $sortType  = 4;
        }
        if (!empty($res == 'RC')) {
            $text = 'RC - ยอดลดหนี้';
            $sortType  = 3;
        }
        if (!empty($res == 'RE')) {
            $text = 'RE - ยอดเงินเหลือในใบเสร็จ';
            $sortType  = 6;
        }


        return (object)['type' => $text, 'sortType' => $sortType];
    }


    function barcode($code)
    {
        require_once("vendor/autoload.php");
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $border = 2; //กำหนดความหน้าของเส้น Barcode
        $height = 1; //กำหนดความสูงของ Barcode

        return file_put_contents(FCPATH . 'assets/img/qrcode/barcode.jpg', $generator->getBarcode($code, $generator::TYPE_CODE_128, $border, $height));
    }

    function qrcode($code)
    {
        $this->load->library('ciqrcode');
        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']        = ''; //string, the default is application/cache/
        $config['errorlog']        = ''; //string, the default is application/logs/
        $config['quality']        = true; //boolean, the default is true
        $config['size']            = ''; //interger, the default is 1024
        $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
        $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        $params['data'] = $code;
        $params['level'] = 'H';
        $params['size'] = 5;
        $params['savename'] = FCPATH . 'assets/img/qrcode/qrcode.png';
        return  $this->ciqrcode->generate($params);
    }

    public function update($id, $data)
    {
        $sql = $this->db->where('uuid', $id)->update('report_notification', $data);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }


    public function createCfCall($params)
    {
        // var_dump($params);
        // exit;
        $checkFields = array_fill_keys($this->tableAllowFieldsCfCall, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('cf_call_report', $create);
        // var_dump($res);
        // exit;
        if (!empty($res)) {
            return $res;
        }
        // var_dump($res);
        // exit;
        return FALSE;
    }

    public function updateCfCall($id, $update)
    {
        $sql = $this->db->where('uuid', $id)->update('cf_call_report', $update);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }

    public function getCfCall()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->get('cf_call_report');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $res) {
            $lists[$res->report_uuid][$res->tel] = $res;
        }

        return $lists;
    }
}
