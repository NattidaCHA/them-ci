<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Setting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_setting');
    }


    public function index()
    {
        $result = $this->model_setting->getPage();
        $tab = !empty($this->input->get('tab', TRUE)) ? $this->input->get('tab', TRUE) : 'invoice';

        $this->data['page'] = 'Setting';
        $this->data['lists'] = $result;
        $this->data['tab'] = $tab;
        $this->data['page_header'] = 'Setting';
        $this->loadAsset(['dataTables', 'datepicker', 'select2', 'parsley']);
        $this->view('setting_form');
    }

    public function create()
    {
        $list = $this->config->item('page');

        foreach ($list as $page) {
            foreach ($page->colunm as $val) {
                $params = [
                    'uuid' => genRandomString(16),
                    'page_name' => $page->page,
                    'colunm' => $val->name,
                    'sort' => $val->id,
                    'page_sort' => $page->id,
                    'is_show' => 1
                ];
                $this->model_setting->create($params);
            }
        }
    }

    public function process($page)
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();
        $result = $this->model_setting->getPage();


        if (!empty($params)) {
            $formData = [];
            if ($page == 'invoice') {
                $formData = $result['invoice'];
            } else if ($page == 'report') {
                $formData = $result['report'];
            } else {
                $formData = $result['customer'];
            }
            // var_dump($formData);
            // exit;
            foreach ($formData as $val) {
                // var_dump($val);
                // var_dump($params['is_show']);
                // exit;
                if (in_array($val->uuid, $params['is_show'])) {
                    $is_show = 1;
                    $this->model_setting->updateSetting($val->uuid, $is_show);
                } else {
                    $is_show = 0;
                    $this->model_setting->updateSetting($val->uuid, $is_show);
                }
            }

            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }
}
