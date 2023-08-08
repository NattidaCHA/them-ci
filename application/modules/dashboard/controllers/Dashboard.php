<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Dashboard extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('model_dashboard');
    }


    public function index() {
        $this->data['lists'] = $this->model_dashboard->getContact();
        $this->view('dashboard');
    }

}