<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_dashboard extends MY_Model
{

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
}