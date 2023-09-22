<?php
class Api extends MY_Controller
{

    public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_system');
        $this->load->model('model_report');
    }


    public function addMainLog($action = 'read')
    {
        $section = $this->input->post('section', TRUE);
        $page = $this->input->post('page', TRUE);
        $url = $this->input->post('url');
        $add_on = $this->input->post();
        unset($add_on['section'], $add_on['page'], $add_on['url']);

        $output = ['status' => 500, 'msg' => 'Something wrong !'];
        if ($result = $this->addSystemLog($section, $page, $url, $action, $add_on)) {
            $output = ['status' => 200, 'msg' => 'OK', 'data' => $result];
        }

        $this->responseJSON($output);
    }

    public function searchCustomer()
    {
        $output = $this->apiDefaultOutput();
        $keyword = $this->input->get('q');
        $keyword = trim($keyword);
        $result = [];

        if (strlen($keyword) <= 2) {
            if ($apiCustomer = $this->model_system->defaultCustomer()->items) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        } else {
            if ($apiCustomer = $this->model_system->searchCustomer($keyword)->items) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        }
        $this->responseJSON($output);
    }


    public function searchCustomerMain()
    {
        $output = $this->apiDefaultOutput();
        $keyword = $this->input->get('q');
        $keyword = trim($keyword);
        $result = [];

        if (strlen($keyword) <= 2) {
            if ($apiCustomer = $this->model_system->defaultCustomer()->items) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        } else {
            if ($apiCustomer = $this->model_system->searchCustomer($keyword)->items) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        }
        $this->responseJSON($output);
    }

    public function searchCustomerVW()
    {
        $output = $this->apiDefaultOutput();
        $keyword = $this->input->get('q');
        $keyword = trim($keyword);
        $result = [];

        if (strlen($keyword) <= 2) {
            if ($apiCustomer = $this->model_system->getCustomerDefaultVW()->items) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        } else {
            if ($apiCustomer = $this->model_system->searchCustomerVW($keyword)) {
                if (!empty($apiCustomer)) {
                    foreach ($apiCustomer as $val) {
                        $result[] = [
                            'id' => $val->cus_no,
                            'cus_no' => $val->cus_no,
                            'cus_name' => $val->cus_name
                        ];
                    }
                    $output = ['status' => 200, 'msg' => 'OK', 'results' => $result];
                } else if (empty($apiCustomer)) {
                    $output['msg'] = 'Empty';
                }
            } else {
                $output['msg'] = 'API Error';
            }
        }
        $this->responseJSON($output);
    }
}
