<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Dashboard extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('model_dashboard');
    }


    public function index2() {
        // $res = $this->model_dashboard->getDateSelect();
        // var_dump('text');
        // var_dump($res);
        // exit;
        $this->view('dashboard');
    }

}