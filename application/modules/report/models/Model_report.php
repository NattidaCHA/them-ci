<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_report extends MY_Model
{

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
}