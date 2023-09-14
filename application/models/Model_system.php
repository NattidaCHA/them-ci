<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_system extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDateSelect()
    {
        $result = [];
        $sql = $this->db->select('mday')
            ->group_by('mday')
            ->where('mday !=', 'NO FAX', 'left')
            ->get('cust_notification');

        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getTypeBusiness()
    {
        $result = [];
        $sql = $this->db
            ->order_by('msort')
            ->get('saleorg');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getCustomer()
    {
        $result = [];
        $sql = $this->db->select('T1.mcustno, MAX(T1.mcustname) as cus_name, MAX(T2.maddress1) as address1, MAX(T2.maddress2) as address2, MAX(T2.mfax) as fax, MAX(T2.mtel) as tel, MAX(T2.mmobile) as mobile, MAX(T2.memail) as email, MAX(T2.mcontact) as contact, MAX(T2.mremarks) as remarks')
            ->join('tbl_custtel T2', 'T2.mcustno = T1.mcustno', 'left')
            ->group_by('T1.mcustno')
            ->get('vw_Customer_DWH T1');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function findCustomer()
    {
        $result = [];
        $sql = $this->db->select('mcustno')
            ->group_by('mcustno')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getCustomerAll()
    {
        $result = [];
        $sql = $this->db->select('cus_no,cus_name')
            ->order_by('cus_no', 'asc')
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getCustomerNew()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->select('cus_no,cus_name')
            ->order_by('cus_no', 'asc')
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            array_push($lists, $val->cus_no);
        }
        return  $lists;
    }

    public function checkSendtoMain($cus_no)
    {
        $result = [];
        $sql = $this->db->select("cus_no,MAX(CONVERT(int,is_check)) as is_check")
            ->where("is_check =", 1)
            ->where("(cus_main = '$cus_no' OR cus_no = '$cus_no')")
            ->group_by('cus_no')
            ->get('sendto_customer');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function checkSendtoChild($cus_no)
    {
        $result = [];
        $sql = $this->db->select("cus_main,MAX(CONVERT(int,is_check)) as is_check")
            ->where("is_check =", 1)
            ->where("(cus_main = '$cus_no' OR cus_no = '$cus_no')")
            ->group_by('cus_main')
            ->get('sendto_customer');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function findeCustomersearch($cus_no)
    {
        $result2 = (object)[];
        $result = (object)[];
        $lists = [];
        $isCheck = [];
        $checkCustomer = $this->model_system->findCustomerById($cus_no);

        $sql = $this->db->select('cus_no,cus_name')->where('cus_no', $cus_no)->get('customer_notification');
        $result = $sql->row();
        $sql->free_result();
        $lists[$cus_no] = $result;

        if ($checkCustomer->type == 'main') {
            $isCheck = $this->checkSendtoMain($cus_no);
            foreach ($isCheck as $val) {
                if ($val->cus_no != $cus_no) {
                    $sql1 = $this->db->select('cus_no,cus_name')->where('cus_no', $val->cus_no)->get('customer_notification');
                    $result2 = $sql1->row();
                    $sql1->free_result();
                    $lists[$val->cus_no] = $result2;
                }
            }
        }

        return  $lists;
    }

    public function searchCustomer($keywork)
    {

        if (!empty($keywork)) {
            $this->db->like('cus_no', $keywork, 'both');
        }

        if (!empty($keywork)) {
            $this->db->or_like('cus_name', $keywork, 'both');
        }

        $result = [];
        $sql = $this->db->select('uuid,cus_no,cus_name')
            ->order_by('cus_no', 'asc')
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function defaultCustomer()
    {
        $result = [];
        $sql = $this->db->select('uuid,cus_no,cus_name')
            ->order_by('cus_no', 'asc')
            ->limit(50)
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getCustomerSelect()
    {
        $result = [];
        $sql = $this->db->select('msendto,MAX(mcustno) as mcustno,MAX(mcustname) as cus_name')
            ->group_by('msendto')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }


    public function getSendDate($id)
    {
        $result = [];
        $sql = $this->db->select('mday')
            ->where("(mcustno ='$id' AND mday != 'NO FAX')")
            ->group_by('mday')
            ->get('cust_notification');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    public function findCustomerById($id)
    {
        // var_dump($this->db->query("SELECT * FROM customer_notification;"));
        $result = (object)[];
        $sql = $this->db->where('cus_no', $id)->get('customer_notification');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }

    public function getCustomerMainDefault()
    {
        $result = [];
        $sql = $this->db->select('cus_no,cus_name')
            ->where('type', 'main')
            ->order_by('cus_no', 'asc')
            ->limit(50)
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function searchCustomerMain($keywork)
    {

        if (!empty($keywork)) {
            $this->db->like('cus_no', $keywork, 'both');
        }

        if (!empty($keywork)) {
            $this->db->or_like('cus_name', $keywork, 'both');
        }

        $result = [];
        $sql = $this->db->select('cus_no,cus_name')
            ->where('type', 'main')
            ->order_by('cus_no', 'asc')
            ->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }


    public function getCustomerDefaultVW()
    {
        $result = [];
        $sql = $this->db->select('MAX(mcustno) as cus_no, MAX(mcustname) as cus_name')
            ->group_by('mcustno')
            ->limit(50)
            ->order_by('mcustno', 'asc')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function searchCustomerVW($keywork)
    {

        if (!empty($keywork)) {
            $this->db->like('mcustno', $keywork, 'both');
        }

        if (!empty($keywork)) {
            $this->db->or_like('mcustname', $keywork, 'both');
        }

        $result = [];
        $sql = $this->db->select('MAX(mcustno) as cus_no, MAX(mcustname) as cus_name')
            ->group_by('mcustno')
            ->order_by('mcustno', 'asc')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getPageIsShow()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->select('MAX(uuid) as uuid,MAX(page_name) as page_name,MAX(CONVERT(int,page_sort)) as page_sort,MAX(CONVERT(int,sort)) as sort,MAX(colunm) as colunm,MAX(CONVERT(int,is_show)) as is_show')
            ->where('is_show', 1)
            ->group_by('uuid') 
            ->order_by('page_sort asc,sort asc')
            ->get('setting');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            $lists[$val->page_name][] = $val;
        }
        return $lists;
    }


    public function test()
    {
        $serverName = "10.51.249.87";
        $connectionInfo = array(
            "Database" => "NpiNotification_Dev", "UID" => "NpiNoti_usr01", "PWD" => "NpiNoti01@2022",  "ReturnDatesAsStrings" => true, // Optional, can be used to ensure date handling is as strings
            "CharacterSet" => "UTF-8"
        );
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $sql = "select * from tel_customer";

        // $stmt = sqlsrv_query($conn, $sql);

        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt == false) {
            echo '555';
            die(print_r(sqlsrv_errors(), true));
        } else {
            $locations = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $location = $row;
                array_push($locations, $location);
            }
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);
            return $locations;
        }
    }
}
