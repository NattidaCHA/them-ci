<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_customer extends MY_Model
{
    private $tableAllowFieldsCustomer = ('uuid, cus_no,cus_name, send_date,created_date, updated_date,type,created_by, updated_by');
    private $tableAllowFieldsTelContact = ('uuid, cus_main,  tel, created_date,updated_date, is_call, contact');
    private $tableAllowFieldsEmailContact = ('uuid, email, cus_main, created_date, updated_date');
    private $tableAllowFieldsSendTo = ('uuid, cus_no, cus_main,is_check');

    public function __construct()
    {
        parent::__construct();
    }

    public function getContact()
    {
        $result = [];
        $sql =  "SELECT * FROM " . CUST_NOTI;
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

    public function findMain($id)
    {
        $result = (object)[];
        $select =  "MAX(" . VW_Customer . ".mcustno) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . TBL_CUT . ".mcontact) as contact,MAX(" . VW_Customer . ".msendto) as msendto,MAX(" . VW_Customer . ".msaleorg) as msaleorg ";
        $join = " left join " . TBL_CUT . " on " . TBL_CUT . ".mcustno = " . VW_Customer . ".mcustno";
        $sql =  "SELECT $select FROM " . VW_Customer . "$join" . " where " . VW_Customer . ".msendto = '$id'";
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

    public function findChild($id)
    {
        $result = (object)[];
        $select =  "MAX(" . VW_Customer . ".mcustno) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . TBL_CUT . ".mcontact) as contact,MAX(" . VW_Customer . ".msendto) as msendto,MAX(" . VW_Customer . ".msaleorg) as msaleorg,MAX(" . TBL_CUT . ".mtel) as tel,MAX(" . TBL_CUT . ".memail) as email ";
        $join = " left join " . TBL_CUT . " on " . TBL_CUT . ".mcustno = " . VW_Customer . ".mcustno";
        $sql =  "SELECT $select FROM " . VW_Customer . "$join" . " where " . VW_Customer . ".mcustno = '$id' group by "  . VW_Customer . ".mcustno";
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


    public function findChildList($id)
    {
        $result = [];
        $sql =  "SELECT MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg FROM " . VW_Customer .  " where msendto = '$id' group by mcustno";

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

    public function createCustomer($params)
    {
        $sql = "INSERT INTO " . CUSTOMER . ' (' . $this->tableAllowFieldsCustomer . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return true;
        }

        return FALSE;
    }

    public function createTelContact($params)
    {
        $sql = "INSERT INTO " . TEL . ' (' . $this->tableAllowFieldsTelContact . ") VALUES (?, ?, ?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function createEmailContact($params)
    {
        $sql = "INSERT INTO " . EMAIL . ' (' . $this->tableAllowFieldsEmailContact . ") VALUES (?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function createSendto($params)
    {
        $sql = "INSERT INTO " . SENTO_CUS . ' (' . $this->tableAllowFieldsSendTo . ") VALUES (?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function customer($id)
    {
        $result = (object)[];
        $sql =  "SELECT * FROM " . CUSTOMER . " where cus_no = '$id'";
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

    public function email($id)
    {
        $result = [];
        $sql =  "SELECT * FROM " . EMAIL . " where cus_main = '$id'";

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

    public function tel($id)
    {
        $result = [];
        $sql =  "SELECT * FROM " . TEL . " where cus_main = '$id'";

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


    public function checkSendTo($cus_no, $cus_main)
    {
        $result = (object)[];
        $sql =  "SELECT * FROM " . SENTO_CUS . " where cus_no = '$cus_no' AND cus_main = '$cus_main'";
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

    public function checkSendToChild($cus_no)
    {
        $result = (object)[];
        $sql =  "SELECT * FROM " . SENTO_CUS . " where cus_no = '$cus_no' AND cus_main = '$cus_no'";
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

    public function getSendToId($id)
    {
        $result = (object)[];
        $sql =  "SELECT * FROM " . SENTO_CUS . " where uuid = '$id'";
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

    public function getSendTo($id)
    {
        $result = [];
        $lists = [];
        $sql =  "SELECT cus_no,MAX(cus_main) as cus_main,MAX(uuid) as uuid,MAX(CONVERT(int,is_check)) as is_check FROM " . SENTO_CUS . " where cus_main = '$id' group by cus_no";
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
                $sendto = $this->findChild($res->cus_no)->items;
                $sendto->is_check = $res->is_check;
                $sendto->uuid = $res->uuid;
                $lists[$sendto->cus_no] =  $sendto;
            }


            $output = (object)[
                'status' => 200,
                'items'  => $lists,
                'msg'  => "success",
            ];
        }

        return $output;
    }

    public function removeEmail($id)
    {
        $sql = "DELETE FROM " . EMAIL . " where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql);

        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function removeTel($id)
    {
        $sql = "DELETE FROM " . TEL . " where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }


    public function updateSendTo($id, $params)
    {
        $sql = "update " . SENTO_CUS . " set is_check=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }


    public function updateTelContact($id, $params)
    {
        $sql = "update " . TEL . " set tel=(?), updated_date=(?),is_call=(?),contact=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function updateEmailContact($id, $params)
    {
        $sql = "update " . EMAIL . " set email=(?), updated_date=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function updateInfo($id, $params)
    {
        $sql = "update " . CUSTOMER . " set send_date=(?), updated_date=(?), updated_by=(?) where uuid = '$id'";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function countCustomer($cus_no)
    {
        $result = [];
        $sql =  "SELECT * FROM " . CUSTOMER;

        if (!empty($cus_no)) {
            $sql = $sql . " where cus_no = '$cus_no'";
        }


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

    public function getCustomerTb($cus_no = FALSE, $limit, $page)
    {

        $totalRecord = !empty($this->countCustomer($cus_no)) ? count($this->countCustomer($cus_no)->items) : 0;
        $result = [];
        $lists = [];
        $offset = max($page - 1, 0) * $limit;

        $sql =  "SELECT * FROM " . CUSTOMER;

        if (!empty($cus_no)) {
            $sql = $sql . " where cus_no = '$cus_no' order by cus_no asc offset $offset rows fetch next $limit rows only";
        } else {
            $sql = $sql . " order by cus_no asc offset $offset rows fetch next $limit rows only";
        }

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
                foreach ($result as $key => $val) {
                    array_push($lists, (object)['info' => $val, 'tels' => $this->tel($val->cus_no)->items, 'emails' => $this->email($val->cus_no)->items]);
                }
            }

            $output = (object)[
                'status' => 200,
                'items'  => $lists,
                'msg'  => "success",
            ];
        }

        return (object)['lists' => $output, 'totalRecord' => $totalRecord];
    }
}
