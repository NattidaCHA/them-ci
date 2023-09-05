<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_customer extends MY_Model
{
    private $tableAllowFieldsCustomer = ['uuid', 'cus_name', 'cus_no', 'send_date', 'type', 'created_date', 'updated_date'];
    private $tableAllowFieldsTelContact = ['uuid', 'cus_main',  'tel', 'contact', 'is_call', 'created_date', 'updated_date'];
    private $tableAllowFieldsEmailContact = ['uuid', 'cus_main', 'email', 'created_date', 'updated_date'];
    private $tableAllowFieldsSendTo = ['uuid', 'cus_no', 'cus_main', 'is_check'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getContact()
    {
        $sql = $this->db->get('cust_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function findMain($id)
    {
        $result = (object)[];
        $sql = $this->db->select('MAX(T1.mcustno) as cus_no,MAX(T1.mcustname) as cus_name,MAX(T2.mcontact) as contact,MAX(T1.msendto) as msendto,MAX(T1.msaleorg) as msaleorg')
            ->where('T1.msendto', $id)
            ->join('tbl_custtel T2', 'T1.mcustno = T2.mcustno', 'left')
            ->get('vw_Customer_DWH T1');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    public function findChild($id)
    {
        $result = (object)[];
        $sql = $this->db->select('MAX(T1.mcustno) as cus_no,MAX(T1.mcustname) as cus_name,MAX(T2.mcontact) as contact,MAX(T1.msendto) as msendto,MAX(T1.msaleorg) as msaleorgม,MAX(T2.mtel) as tel,MAX(T2.memail) as email')
            ->where('T1.mcustno', $id)
            ->join('tbl_custtel T2', 'T1.mcustno = T2.mcustno', 'left')
            ->group_by('T1.mcustno')
            ->get('vw_Customer_DWH T1');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }


    public function findChildList($id)
    {
        $result = [];
        $sql = $this->db->select('MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg')
            ->where('msendto', $id)
            ->group_by('mcustno')
            ->get('vw_Customer_DWH');
        $result = $sql->result();
        $sql->free_result();

        return  $result;
    }

    public function createCustomer($params)
    {
        $checkFields = array_fill_keys($this->tableAllowFieldsCustomer, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('customer_notification', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function createTelContact($params)
    {
        $checkFields = array_fill_keys($this->tableAllowFieldsTelContact, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('tel_customer', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function createEmailContact($params)
    {
        $checkFields = array_fill_keys($this->tableAllowFieldsEmailContact, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('email_customer', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }


    public function createSendto($params)
    {

        $checkFields = array_fill_keys($this->tableAllowFieldsSendTo, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('sendto_customer', $create);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function customer($id)
    {
        $result = (object)[];
        $sql = $this->db->where('cus_no', $id)->get('customer_notification');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    public function email($id)
    {

        $result = [];
        $sql = $this->db->where('cus_main', $id)->get('email_customer');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function tel($id)
    {

        $result = [];
        $sql = $this->db->where('cus_main', $id)->get('tel_customer');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }


    public function checkSendTo($cus_no, $cus_main)
    {
        $result = (object)[];
        $sql = $this->db->where("(cus_no = '$cus_no' AND cus_main = '$cus_main')")->get('sendto_customer');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    public function checkSendToChild($cus_no)
    {
        $result = (object)[];
        $sql = $this->db->where("(cus_no = '$cus_no' AND cus_main = '$cus_no')")->get('sendto_customer');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    // public function checkSendToChild($cus_no)
    // {
    //     $result = (object)[];
    //     $sql = $this->db->where("(cus_no = '$cus_no' AND cus_main = '$cus_no')")->get('sendto_customer');
    //     $result = $sql->row();
    //     $sql->free_result();
    //     return  $result;
    // }


    public function getSendToId($id)
    {
        $result = (object)[];
        $sql = $this->db->where('uuid', $id)->get('sendto_customer');
        $result = $sql->row();
        $sql->free_result();
        return  $result;
    }

    public function getSendTo($id)
    {
        $result = [];
        $lists = [];
        $sql = $this->db->select('cus_no,MAX(cus_main) as cus_main,MAX(uuid) as uuid,MAX(CONVERT(int,is_check)) as is_check')
            ->where('cus_main', $id)
            ->group_by('cus_no')
            ->get('sendto_customer');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $res) {
            $sendto = $this->findChild($res->cus_no);
            $sendto->is_check = $res->is_check;
            $sendto->uuid = $res->uuid;
            $lists[$sendto->cus_no] =  $sendto;
        }

        return  $lists;
    }

    public function removeEmail($id)
    {
        $sql = $this->db->where('uuid', $id)->delete('email_customer');
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }

    public function removeTel($id)
    {
        $sql = $this->db->where('uuid', $id)->delete('tel_customer');
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }


    public function updateSendTo($id, $sendTo)
    {
        // var_dump($id, $sendTo);
        // exit;
        $sql = $this->db->where('uuid', $id)->update('sendto_customer', $sendTo);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }


    public function updateTelContact($id, $contact)
    {
        $sql = $this->db->where('uuid', $id)->update('tel_customer', $contact);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }

    public function updateEmailContact($id, $contact)
    {
        $sql = $this->db->where('uuid', $id)->update('email_customer', $contact);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }

    public function updateInfo($id, $contact)
    {
        $sql = $this->db->where('uuid', $id)->update('customer_notification', $contact);
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }

    public function findCustomer($id)
    {
        $sql = $this->db->where('cus_no', $id)->get('customer_notification');
        $result = $sql->row();
        $sql->free_result();
        return $result;
    }

    public function getCustomerList()
    {

        $result = [];
        $sql = $this->db->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getCustomerTb($cus_no)
    {

        if (!empty($cus_no)) {
            $this->db->where('cus_no', $cus_no);
        }

        $result = [];
        $sql = $this->db->get('customer_notification');
        $result = $sql->result();
        $sql->free_result();
        return  $result;
    }

    public function getEmail()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->get('email_customer');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            $lists[$val->cus_main][] = $val;
        }
        return  $lists;
    }

    public function getTel()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->get('tel_customer');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            $lists[$val->cus_main][] = $val;
        }
        return  $lists;
    }
}
