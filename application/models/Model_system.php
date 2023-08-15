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
        $sql = $this->db->select('msaleorg')
            ->group_by('msaleorg')
            ->get('vw_billpay_txt02');
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
}
