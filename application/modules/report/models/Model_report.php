<?php

use Mpdf\Tag\Em;

(defined('BASEPATH')) or exit('No direct script access allowed');

class Model_report extends MY_Model
{
    private $tableAllowFieldsCfCall = ('uuid, report_uuid,cus_main, tel,cf_call,receive_call');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_system');
        $this->load->model('customer/model_customer');
    }

    public function getBillNo($cus_no = FALSE)
    {
        $result = [];
        $sql =  "SELECT * FROM " . REPORT;

        if (!empty($cus_no)) {
            $sql = $sql . " where cus_no in ($cus_no)";
        }

        $sql = $sql . ' order by bill_no asc';

        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getEmailById($cus_no)
    {
        $result = [];
        $isCheck = [];
        $lists = [];
        $isCheck = $this->model_system->checkSendtoChild($cus_no)->items;
        foreach ($isCheck as $val) {
            $result = $this->model_customer->email($val->cus_main)->items;

            foreach ($result as $val) {
                array_push($lists, $val);
            }
        }
        return  $lists;
    }

    public function genEmail($cus_no)
    {
        $result = [];
        $isCheck = [];
        $lists = [];
        $isCheck = $this->model_system->checkSendtoChild($cus_no)->items;

        foreach ($isCheck as $val) {
            $result = $this->model_customer->email($val->cus_main)->items;

            foreach ($result as $val) {
                $lists[$val->cus_main] = $val;
            }
        }
        return  $lists;
    }

    public function getTelById($cus_no)
    {
        $result = [];
        $isCheck = [];
        $lists = [];

        $isCheck = $this->model_system->checkSendtoChild($cus_no)->items;
        foreach ($isCheck as $val) {
            $result = $this->model_customer->tel($val->cus_main)->items;
            foreach ($result as $val) {
                array_push($lists, $val);
            }
        }
        return  $lists;
    }

    public function getFaxById($cus_no)
    {
        $result = [];
        $isCheckFax = (object)[];
        $lists = [];

        $isCheckFax = $this->model_system->findCustomerById($cus_no)->items;
        // foreach ($isCheck as $val) {
        //     $result = $this->model_customer->tel($val->cus_main)->items;
        //     foreach ($result as $val) {
        //         array_push($lists, $val);
        //     }
        // }
        if (!empty($isCheckFax)) {
            $result = $this->model_customer->fax($cus_no)->items;
            foreach ($result as $val) {
                array_push($lists, $val);
            }
        }
        return  $lists;
    }

    public function checkChildSendto($cus_no)
    {
        $result = [];
        $sql =  "SELECT cus_no FROM " . SENTO_CUS . " where cus_main = '$cus_no' AND is_check = 1 group by cus_no";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function countBill($params)
    {
        $result = [];
        $sql =  "SELECT " . REPORT . ".uuid," . REPORT . ".bill_no,MAX(CONVERT(int," . REPORT . ".is_email)) as is_email,MAX(" . REPORT . ".cus_main) as cus_main,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . REPORT . ".created_by) as created_by,MAX(" . REPORT . ".end_date) as end_date FROM " . REPORT . " left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno";

        if (!empty($params->cus_no)) {
            $in_cusNo = [];
            array_push($in_cusNo, $this->CURUSER->cus_no);

            foreach ($params->cus_no as $cus_no) {
                if (!empty($cus_no) && $cus_no != 'all') {
                    $checkCustomer = $this->model_system->findCustomerById($cus_no)->items;
                    if ($checkCustomer->type == 'main') {
                        $findChild = $this->model_system->checkSendtoMain($cus_no)->items;
                        foreach ($findChild as $val) {
                            if (!in_array($val->cus_no, $in_cusNo)) {
                                array_push($in_cusNo, $val->cus_no);
                            }
                        }
                    }
                }
            }

            if (!empty($in_cusNo)) {
                $sql = $sql . " where " . REPORT . ".cus_no in (" . implode(',', $in_cusNo) . ")";
            }
        }

        if (!empty($params->bill_no) && !empty($params->cus_no)) {
            if (!empty($params->cus_no)) {
                $sql = $sql . " AND " . REPORT . ".bill_no = '$params->bill_no'";
            } else {
                $sql = $sql . " where " . REPORT . ".bill_no = '$params->bill_no'";
            }
        }

        if (!empty($params->created_date)) {
            $startDate = $params->created_date . ' 00:00:00';
            $endDate = $params->created_date . ' 23:59:59';
            if ((!empty($params->bill_no) || !empty($params->cus_no))) {
                $sql = $sql . " AND " . REPORT . ".created_date >= '$startDate' AND " . REPORT . ".created_date <= '$endDate'";
            } else {
                $sql = $sql . " where " . REPORT . ".created_date >= '$startDate' AND " . REPORT . ".created_date <= '$endDate'";
            }
        }

        $sql = $sql . "  group by " . REPORT . ".uuid," . REPORT . ".bill_no order by created_date desc";


        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => count($result),
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getBillTb($condition)
    {

        $conn = json_decode(json_encode($condition));
        $result = [];
        $lists = [];
        $offset = max($conn->page - 1, 0) * $conn->limit;
        $totalRecord = !empty($this->countBill($conn)->items) ? $this->countBill($conn)->items : 0;

        $result = [];
        $sql =  "SELECT " . REPORT . ".uuid," . REPORT . ".bill_no,MAX(CONVERT(int," . REPORT . ".is_email)) as is_email,MAX(" . REPORT . ".cus_main) as cus_main,MAX(CONVERT(int," . REPORT . ".is_receive_bill)) as is_receive_bill,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . REPORT . ".created_by) as created_by,MAX(" . REPORT . ".end_date) as end_date FROM " . REPORT . " left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno";


        if (!empty($conn->cus_no)) {
            $in_cusNo = [];
            array_push($in_cusNo, $this->CURUSER->cus_no);

            foreach ($conn->cus_no as $cus_no) {
                if (!empty($cus_no) && $cus_no != 'all') {
                    $checkCustomer = $this->model_system->findCustomerById($cus_no)->items;
                    if ($checkCustomer->type == 'main') {
                        $findChild = $this->model_system->checkSendtoMain($cus_no)->items;
                        foreach ($findChild as $val) {
                            if (!in_array($val->cus_no, $in_cusNo)) {
                                array_push($in_cusNo, $val->cus_no);
                            }
                        }
                    }
                }
            }

            if (!empty($in_cusNo)) {
                $sql = $sql . " where " . REPORT . ".cus_no in (" . implode(',', $in_cusNo) . ")";
            }
        }

        if (!empty($conn->bill_no)) {
            if (!empty($conn->cus_no)) {
                $sql = $sql . " AND " . REPORT . ".bill_no = '$conn->bill_no'";
            } else {
                $sql = $sql . " where " . REPORT . ".bill_no = '$conn->bill_no'";
            }
        }

        if (!empty($conn->created_date)) {
            $startDate = $conn->created_date . ' 00:00:00';
            $endDate = $conn->created_date . ' 23:59:59';
            if ((!empty($conn->bill_no) || !empty($conn->cus_no))) {
                $sql = $sql . " AND " . REPORT . ".created_date >= '$startDate' AND " . REPORT . ".created_date <= '$endDate'";
            } else {
                $sql = $sql . " where " . REPORT . ".created_date >= '$startDate' AND " . REPORT . ".created_date <= '$endDate'";
            }
        }

        $sql = $sql . "  group by " . REPORT . ".uuid," . REPORT . ".bill_no order by created_date desc offset $offset rows fetch next $conn->limit rows only";


        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }



        if (!empty($output->items)) {
            foreach ($result as $key => $val) {
                array_push($lists, (object)['info' => $val, 'tels' => $this->getTelById($val->cus_no), 'emails' => $this->getEmailById($val->cus_no), 'cf_call' => $this->getCfCallByuuid($val->uuid)->items, 'faxs' => $this->getFaxById($val->cus_no)]);
            }
        }

        return (object)['lists' => $lists, 'totalRecord' => $totalRecord];
    }

    public function getListItem($bill_id)
    {
        $result = [];
        $sql =  "SELECT * FROM " . REPORT_DETAIL . " where bill_id = '$bill_id' order by msort asc, mbillno asc,mpostdate asc,mduedate asc, mpayterm asc,mnetamt asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getReportUuid($uuid)
    {
        $result = (object)[];
        $sql =  "SELECT * FROM " . REPORT . " where uuid = '$uuid'";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            $result =  sqlsrv_fetch_object($stmt);
            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getReportChildList($cus_no, $created_date)
    {

        $result = (object)[];
        $startDate = date('Y-m-d', strtotime($created_date)) . ' 00:00:00';
        $endDate = date('Y-m-d', strtotime($created_date)) . ' 23:59:59';
        $sql =  "SELECT uuid,end_date,bill_no FROM " . REPORT . " where cus_no = '$cus_no' AND created_date >='$startDate' AND created_date <='$endDate'";

        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            $result =  sqlsrv_fetch_object($stmt);
            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
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
            $bill_info = $this->getReportUuid($bill_id)->items;
            $itemLists = $this->getListItem($bill_id)->items;
            $data->payment = $this->getPayment()->items;
            $info = (object)[];

            if (!empty($bill_info)) {
                $data->bill_info = $bill_info;
                $info = $this->getCustomerInfo($bill_info->cus_no)->items;
                if (!empty($info)) {
                    $data->info = $info;
                }
            }

            if (!empty($itemLists)) {
                foreach ($itemLists as $val) {
                    if (!empty($val)) {
                        $val->type = $this->genType($val->mdoctype)->type;
                        $val->sortType = $this->genType($val->mdoctype)->sortType;
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
                $size = count($itemLists) > 40 ? 40 : 40;
                $count = ceil(count($itemLists) / $size);
                $data->total_page = $count;
                $data->total = $this->calculateTotallChild($itemLists);
                //"|010556217035200\n . $info->mcustno .\n. str_replace('N', '8', $bill_info->bill_no) .\n0"
                //$code =  "|010556217035200\r\n" . $info->mcustno .  "\r\n" . str_replace('N', '8', $bill_info->bill_no) .   "\r\n" . str_replace('.', '', (str_replace('-', '', $data->total->total_summary)));0273022069
                $code = "|0105562170352\r\n$info->mcustno\r\n$bill_info->bill_no\r\n" . str_replace('.', '', (str_replace('-', '', $data->total->total_summary)));
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
        $result = (object)[];
        $sql =  "SELECT * FROM " . VW_Customer . " where mcustno = '$cus_no'";

        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            $result =  sqlsrv_fetch_object($stmt);
            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
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
        // $sql = $this->db->get('payment');
        // $result = $sql->result();
        // $sql->free_result();
        // return $result;

        $result = [];
        $sql =  "SELECT * FROM " . PAYMENT;
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }


    public function genType($res)
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
        $sql = "INSERT INTO " . CFCALL . ' (' . $this->tableAllowFieldsCfCall . ") VALUES (?, ?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return true;
        }

        return FALSE;
    }

    public function updateCfCall($id, $params)
    {
        $sql = "update " . CFCALL . " set cf_call=(?),receive_call=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }


    public function updateReceiveBill($id, $params)
    {
        $sql = "update " . REPORT . " set is_receive_bill=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function getCfCall()
    {
        $result = [];
        $lists = [];
        $sql =  "SELECT * FROM " . CFCALL;
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            foreach ($result as $res) {
                $lists[$res->report_uuid][$res->tel] = $res;
            }

            $output = (object)[
                'status' => 200,
                'items'  => $lists,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function updateEmail($id)
    {
        $data = [1, date("Y-m-d H:i:s")];
        $sql = "update " . REPORT . " set is_email=(?),updated_date=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $data);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    // public function getCustomerByNo($cus_no)
    // {
    //     $result = (object)[];
    //     $sql = $this->db->where('cus_no', $cus_no)->get('customer_notification');
    //     $result = $sql->row();
    //     $sql->free_result();
    //     return $result;
    // }

    public function getCfCallByuuid($report_uuid)
    {
        $result = [];
        $sql =  "SELECT * FROM " . CFCALL . " where report_uuid ='$report_uuid' AND receive_call !=''";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }


    public function genCfCallByuuid($report_uuid)
    {
        $result = [];
        $lists = [];

        $sql =  "SELECT * FROM " . CFCALL . " where report_uuid = '$report_uuid'";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                array_push($result, (object)$row);
            }

            foreach ($result as $res) {
                $lists[$res->tel] = $res;
            }


            $output = (object)[
                'status' => 200,
                'items'  => $lists,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function genCfCall($report_uuid, $cus_no)
    {
        $genCfCall = $this->genCfCallByuuid($report_uuid)->items;
        $genTel = $this->getTelById($cus_no);
        // var_dump($genCfCall);
        // exit;

        return  (object)['tels' => $genTel, 'cf_call' => $genCfCall];
    }


    public function getBillById($report_uuid)
    {

        $result = (object)[];

        $select =  "MAX(" . REPORT . ".uuid) as uuid,MAX(" . REPORT . ".bill_no) as bill_no,MAX(CONVERT(int," . REPORT . ".is_email)) as is_email,MAX(" . REPORT . ".cus_main) as cus_main,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . REPORT . ".created_by) as created_by,MAX(" . REPORT . ".updated_date) as updated_date,MAX(CONVERT(int," . REPORT . ".is_receive_bill)) as is_receive_bill,MAX(" . REPORT . ".end_date) as end_date ";
        $join = " left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno";

        $sql =  "SELECT $select FROM " . REPORT . "$join" . " where uuid ='$report_uuid'";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            $result =  sqlsrv_fetch_object($stmt);
            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }
}
