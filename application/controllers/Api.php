<?php
class Api extends MY_Controller
{

    public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_system');
        $this->load->model('model_report');
        $this->load->model('model_invoice');
    }


    public function addMainLog($action = 'read')
    {
        $page = $this->input->post('page', TRUE);
        $detail = $this->input->post('detail', TRUE);
        $created_by = NULL;
        $updated_by = NULL;
        $url = $this->input->post('url');

        $output = ['status' => 500, 'msg' => 'Something wrong !'];
        $result = $this->addSystemLog([
            genRandomString(16), $page, $action, $detail, date('Y-m-d H:i'), $created_by,
            date('Y-m-d H:i'), $updated_by, $url
        ]);

        // var_dump($result);

        if (!empty($result)) {
            $output = ['status' => 200, 'msg' => 'OK', 'data' => $result];
        }

        // var_dump($this->responseJSON($output));
        // exit;
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
        $search = $this->config->item('fullSearch');
        $keyword = !in_array($this->CURUSER->cus_no, $search) ? $this->CURUSER->cus_no : $this->input->get('q');
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

                    // var_dump($result);
                    // exit;
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
