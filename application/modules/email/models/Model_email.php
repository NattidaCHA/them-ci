<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_email extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sql = $this->db->get('cust_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }
}