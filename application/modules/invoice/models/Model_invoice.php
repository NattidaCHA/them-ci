<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_invoice extends MY_Model
{

    private $tableAllowFieldsInvoice = ('cus_main, cus_no, bill_no, is_email, created_date,uuid,start_date,end_date,is_sms,updated_date, created_by, is_receive_bill');
    private $tableAllowFieldsInvoiceDatail = ('uuid, bill_no, bill_id, macctdoc, cus_no, cus_main, mdoctype, mbillno, mpostdate, mduedate, msaleorg, mpayterm, mnetamt, mtext, msort');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_system');
    }


    public function getInvoice($condition = [])
    {
        $val = json_decode(json_encode($condition));
        $result = [];
        $data = [];
        $select =  "" . BILLPAY . ".mcustno," . BILLPAY . ".mdoctype,SUM(" . BILLPAY . ".mnetamt) as total_mnetamt,MAX(" . BILLPAY . ".mcustname) as cus_name,MAX(" . BILLPAY . ".msaleorg) as msaleorg,MAX(" . BILLPAY . ".mduedate) as end_date,MAX(" . BILLPAY . ".mpostdate) as start_date,MAX(" . CUST_NOTI . ".mday) as send_date ";

        $join = " left join " . CUST_NOTI . " on " . CUST_NOTI . ".mcustno = " . BILLPAY . ".mcustno ";

        $sql =  "SELECT $select FROM " . BILLPAY . "$join" . " where " . CUST_NOTI . ".mday = '$val->dateSelect' AND " . BILLPAY . ".mpostdate >='$val->startDate' AND " . BILLPAY . ".mduedate <='$val->endDate'";

        if (!empty($val->is_bill)) {
            $result2 = [];
            $sql2 = "SELECT cus_main FROM " . REPORT . " where start_date >= '$val->startDate' AND  end_date <='$val->endDate' group by cus_main";
            $stmt2 = sqlsrv_query($this->conn, $sql2);

            while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                array_push($result2, $row2["cus_main"]);
            }


            if ($val->is_bill == '2') {
                $sql = $sql . " AND " . BILLPAY . ".mcustno in (" . implode(',', $result2) . ")";
            }


            if ($val->is_bill == '3' && !empty($result2)) {
                $sql = $sql . " AND " . BILLPAY . ".mcustno not in (" . implode(',', $result2) . ")";
            }
        }



        if (!empty($val->cus_no)) {
            $sql = $sql . " AND " . BILLPAY . ".mcustno = '$val->cus_no'";
        }

        if (!empty($val->type)) {
            $sql = $sql . " AND " . BILLPAY . ".msaleorg = '$val->type'";
        }

        $sql = $sql . " group by "  . BILLPAY . ".mdoctype,"  . BILLPAY . ".mcustno";

        // var_dump($sql);
        // exit;

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

            if (!empty($result)) {
                foreach ($result as $row) {
                    $data[$row->mcustno][] = $row;
                }
            }

            $output = (object)[
                'status' => 200,
                'items'  => $this->processData($data),
                'msg'  => "success",
            ];
        }

        return $output;
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
        $lists = $this->findChildCustomer((object)['cus_no' => $id])->items;
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
        $sql =  "SELECT MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg FROM " . VW_Customer . " where msendto = '$condition->cus_no' group by mcustno";

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

    public function findCustomerDefault($condition)
    {
        $result = [];
        $sql =  "SELECT MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg FROM " . VW_Customer . " where mcustno = '$condition->cus_no' group by mcustno";

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

    public function getBillChild($condition)
    {
        $result = [];

        $sql =  "SELECT " . BILLPAY . ".macctdoc," . BILLPAY . ".mdoctype," . BILLPAY . ".mnetamt," . BILLPAY . ".msaleorg," . BILLPAY . ".mduedate," . BILLPAY . ".mbillno FROM " . BILLPAY . " left join " . CUST_NOTI . " on " . CUST_NOTI . ".mcustno = " . BILLPAY . ".mcustno  where " . BILLPAY . ".mcustno = '$condition->cus_no' AND " . BILLPAY . ".mpostdate >='$condition->start_date' AND " . BILLPAY . ".mduedate <='$condition->end_date' AND " . CUST_NOTI . ".mday = '$condition->send_date'";

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

    public function getDetailCustomer($res)
    {
        $childs = [];
        $val = json_decode(json_encode($res));
        $customer = (object)[];
        if (!empty($val)) {
            // $sql =  "SELECT mcustname FROM " . TBL_CUT . " where mcustno = '$val->cus_no'";
            // $stmt = sqlsrv_query($this->conn, $sql);

            // if (!empty($stmt)) {
            //     $customer =  sqlsrv_fetch_object($stmt);
            // }
            $customer = $this->model_system->findCustomerById($val->cus_no)->items;
            $res = $customer->type == 'main' && !empty($customer) ? $this->findChildCustomer($val)->items : $this->findCustomerDefault($val)->items;
            foreach ($res as $bill) {
                $val->cus_no = $bill->cus_no;
                $bills = $this->getBillChild($val)->items;
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

        $lists = (object) [
            'cus_no' => $val->cus_no,
            'cus_name' => !empty($customer) && !empty($customer->mcustname)  ? $customer->mcustname : '-',
            'childs' => $childs,
        ];
        return $lists;
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

        $sql = "INSERT INTO " . REPORT . ' (' . $this->tableAllowFieldsInvoice . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return true;
        }

        return FALSE;
    }

    public function createDetailInvoice($params)
    {
        $sql = "INSERT INTO " . REPORT_DETAIL . ' (' . $this->tableAllowFieldsInvoiceDatail . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?,?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return true;
        }

        return FALSE;
    }

    public function getReportId($main_id)
    {
        $result = (object)[];
        $sql =  "SELECT MAX(bill_no) as bill_no,MAX(created_date) as created_date,MAX(cus_main) as cus_main FROM " . REPORT . " where cus_main = '$main_id' group by " . REPORT . ".bill_no order by " . REPORT . ".bill_no desc";

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


    public function getReportUuid($uuid)
    {
        $result = (object)[];
        // $sql = $this->db->select('*')
        //     ->where("uuid", $uuid)
        //     ->get('report_notification');
        // $result = $sql->row();
        // $sql->free_result();
        // return $result;

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

    public function getReport()
    {
        // $result = [];
        // $sql = $this->db->select('*')
        //     ->order_by('report_notification.created_date', 'desc')
        //     ->get('report_notification');
        // $result = $sql->result();
        // $sql->free_result();
        // return $result;
        $sql =  "SELECT * FROM " . REPORT . " order by " . REPORT . ".created_date desc";
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

    // public function getBillNo()
    // {
    //     $result = [];
    //     $sql = $this->db->select('*')->get('report_notification');
    //     $result = $sql->result();
    //     $sql->free_result();
    //     return $result;
    // }

    // public function getBill($condition)
    // {
    //     $val = json_decode(json_encode($condition));
    //     $result = [];

    //     if (!empty($val->cus_no)) {
    //         $this->db->where('T1.cus_no', $val->cus_no);
    //     }

    //     if (!empty($val->bill_no)) {
    //         $this->db->where('T1.bill_no', $val->bill_no);
    //     }

    //     if (!empty($val->created_date)) {
    //         $this->db->where('T1.created_date', $val->created_date);
    //     }

    //     $sql = $this->db->select('T1.uuid,T1.bill_no,MAX(CONVERT(int,T1.is_email)) as is_email,MAX(T1.cus_main) as cus_main,MAX(T1.created_date) as created_date,MAX(T1.cus_no) as cus_no,MAX(T2.memail) as memail,MAX(T2.mfax) as mfax,MAX(T2.mcustname) as mcustname')
    //         ->join('tbl_custtel T2', 'T2.mcustno = T1.cus_no', 'left')
    //         ->order_by('bill_no', 'desc')
    //         ->group_by('T1.uuid')
    //         ->group_by('T1.bill_no')
    //         ->get('report_notification T1');
    //     $result = $sql->result();
    //     $sql->free_result();
    //     return $result;
    // }

    public function getItem($macctdoc)
    {
        $sql =  "SELECT * FROM " . BILLPAY . " where macctdoc = '$macctdoc'";
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
