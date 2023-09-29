<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_system extends MY_Model
{
    // private $tableAllowLog = ('uuid, page,action,detail,created_date,created_by,updated_date,updated_by,url');

    public function __construct()
    {
        parent::__construct();
    }

    public function getDateSelect()
    {
        $result = [];
        $sql =  "SELECT mday FROM " . CUST_NOTI . " where mday != 'NO FAX' GROUP BY mday";
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

    public function getTypeBusiness()
    {
        $result = [];
        $sql =  "SELECT * FROM " . SALEORG . " ORDER BY msort";
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

    public function getCustomer()
    {
        $result = [];
        $select = VW_Customer . '.mcustno,MAX(' . VW_Customer . '.mcustname) as cus_name,MAX(' . TBL_CUT . '.maddress1) as maddress1,MAX(' . TBL_CUT . '.maddress2) as maddress2,MAX(' . TBL_CUT . '.mfax) as mfax,MAX(' . TBL_CUT . '.mtel) as mtel,MAX(' . TBL_CUT . '.mmobile) as mobile,MAX(' . TBL_CUT . '.memail) as memail,MAX(' . TBL_CUT . '.mcontact) as contact,MAX(' . TBL_CUT . '.mremarks) as mremarks ';
        $join = " left join " . TBL_CUT . " on " . TBL_CUT . ".mcustno = " . VW_Customer . ".mcustno";
        $group_by = ' GROUP BY ' . VW_Customer . '.mcustno';

        $sql =  "SELECT $select FROM " . VW_Customer . $join .  $group_by;
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function findCustomer()
    {
        $result = [];
        $sql =  "SELECT mcustno FROM " . VW_Customer . " group by mcustno";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getCustomerAll()
    {
        $result = [];

        $sql =  "SELECT cus_no,cus_name FROM " . CUSTOMER . " order by cus_no asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getCustomerNew()
    {
        $result = [];
        $sql =  "SELECT cus_no,cus_name FROM " . CUSTOMER . " order by cus_no asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = $row['cus_no'];
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function checkSendtoMain($cus_no)
    {
        $result = [];
        $sql =  "SELECT cus_no,MAX(CONVERT(int,is_check)) as is_check FROM " . SENTO_CUS . " where cus_main = '$cus_no' OR cus_no = '$cus_no' AND is_check = 1 group by cus_no";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function checkSendtoChild($cus_no)
    {
        $result = [];
        $sql =  "SELECT cus_main,MAX(CONVERT(int,is_check)) as is_check FROM " . SENTO_CUS . " where cus_main = '$cus_no' OR cus_no = '$cus_no' AND is_check = 1 group by cus_main";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function findeCustomersearch($cus_no)
    {
        $result2 = (object)[];
        $result = (object)[];
        $lists = [];
        $isCheck = [];
        $checkCustomer = $this->model_system->findCustomerById($cus_no)->items;

        $sql =  "SELECT cus_no,cus_name FROM " . CUSTOMER . " where cus_no = '$cus_no'";
        $stmt = sqlsrv_query($this->conn, $sql);
        $result =  sqlsrv_fetch_object($stmt);
        $lists[$cus_no] = $result;

        if ($checkCustomer->type == 'main') {
            $isCheck = $this->checkSendtoMain($cus_no)->items;
            foreach ($isCheck as $val) {
                if ($val->cus_no != $cus_no) {
                    $sql1 =  "SELECT cus_no,cus_name FROM " . CUSTOMER . " where cus_no = '$val->cus_no'";
                    $stmt1 = sqlsrv_query($this->conn, $sql1);
                    $result2 =  sqlsrv_fetch_object($stmt1);
                    $lists[$val->cus_no] = $result2;
                }
            }
        }

        return  $lists;
    }

    public function searchCustomer($keywork)
    {

        $result = [];
        $sql =  "SELECT uuid,cus_no,cus_name FROM " . CUSTOMER;
        $order_by = ' order by cus_no asc';
        if (!empty($keywork)) {
            $sql = $sql . " where cus_no like '%$keywork%' OR cus_name like '%$keywork%'";
        }
        $sql = $sql . $order_by;
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function defaultCustomer()
    {
        $result = [];
        $sql =  "SELECT top 50 uuid,cus_no,cus_name FROM " . CUSTOMER . " order by cus_no asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }
        return $output;
    }

    public function getSendDate($id)
    {
        $result = (object)[];
        $sql =  "SELECT mday FROM " . CUST_NOTI . " where mcustno = $id AND mday != 'NO FAX' group by mday";
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

    public function findCustomerById($cus_no)
    {
        $output = (object)['status' => 500];
        $result = (object)[];
        $sql =  "SELECT * FROM " . CUSTOMER . " where cus_no = $cus_no";
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

    public function getCustomerMainDefault()
    {
        $result = [];
        $sql =  "SELECT top 50 cus_no,cus_name FROM " . CUSTOMER . " where type = 'main' order by cus_no asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }
        return $output;
    }

    public function searchCustomerMain($keywork)
    {
        $result = [];
        $sql =  "SELECT cus_no,cus_name FROM " . CUSTOMER . " where type = 'main'";
        $order_by = ' order by cus_no asc';
        if (!empty($keywork)) {
            $sql = $sql . " AND cus_no like '%$keywork%' OR cus_name like '%$keywork%'";
        }
        $sql = $sql . $order_by;

        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getPageIsShow()
    {
        $result = [];
        $sql =  "SELECT MAX(uuid) as uuid,MAX(page_name) as page_name,MAX(CONVERT(int,page_sort)) as page_sort,MAX(CONVERT(int,sort)) as sort,MAX(colunm) as colunm,MAX(CONVERT(int,is_show)) as is_show FROM " . SETTING . " where is_show = '1' group by uuid order by page_sort asc,sort asc";
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = (object)$row;
                $result[$res->page_name][] = $res;
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function getTemPDF()
    {
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
                $result[$row['page']][] = (object)$row;
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }
}
