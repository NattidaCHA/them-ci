<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Report extends MY_Controller
{

    private $page = 1;
    private $limit = 20;
    private $offset = 0;
    private $search = '';
    private $order = ['0', 'asc'];
    private $column = [];
    private $is_search = FALSE;
    private $condition = [];
    private $queryCondition = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_report');
        $this->load->model('model_system');
    }

    public function index()
    {
        $search = $this->config->item('fullSearch');
        $created_date = !empty($this->input->post('created_date')) ? $this->input->post('created_date') : '';
        $bill_no = !empty($this->input->post('bill_no')) ? $this->input->post('bill_no') : '';
        $searchLists = !in_array($this->CURUSER->cus_no, $search) ? $this->model_system->findeCustomersearch($this->CURUSER->cus_no) : $this->model_system->getCustomerAll();
        $cus_no = NULL;
        $table = $this->model_system->getPageIsShow();


        if (!in_array($this->CURUSER->cus_no, $search)) {
            if (!empty($this->input->post('customer'))) {
                $cus_no = implode(',', $this->input->post('customer'));
            } else {
                $sendto = [];
                array_push($sendto, $this->CURUSER->cus_no);
                if ($this->CURUSER->type == 'main') {
                    $isCheck = $this->model_system->checkSendtoMain($this->CURUSER->cus_no);
                    foreach ($isCheck as $val) {
                        if (!in_array($val->cus_no, $sendto)) {
                            array_push($sendto, $val->cus_no);
                        }
                    }
                }

                $cus_no = implode(',', $sendto);
            }
        }

        $billNos = $this->model_report->getBillNo();

        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $searchLists;
        $this->data['created_date'] = $created_date;
        $this->data['bill_no'] = $bill_no;
        $this->data['cus_no'] = $cus_no;
        $this->data['fullSearch'] = $search;
        $this->data['page_header'] = 'Report';
        $this->data['table'] = $table['report'];
        $this->data['info'] = $this->CURUSER;
        $this->loadAsset(['dataTables', 'datepicker', 'select2', 'parsley']);
        $this->view('report_lists');
    }

    public function pdf($uuid)
    {
        return $this->genPDF($uuid, 'report');
    }

    public function email()
    {
        $output = ['status' => 500, 'msg' => 'Can not send email !'];
        $params = $this->input->post();

        if (!empty($params)) {
            $cusType = $this->model_report->getCustomerByNo($params['cus_no']);
            $genEmail =  $this->model_report->getEmailById($params['cus_no'], $params['cus_main']);
            $emails = [];
            $reportChild = [];
            $content = $this->genPDF($params['uuid'], 'email');
            $data['data'] = (object)['end_date' => date('d/m/Y', strtotime($params['end_date']))];
            if ($cusType->type == 'main') {
                $childs = $this->model_report->checkChildSendto($params['cus_no'], $params['cus_main']);
                foreach ($childs as $child) {
                    if ($params['cus_no'] != $child->cus_no) {
                        $res =  $this->model_report->getReportChildList($child->cus_no, $params['created_date']);
                        if (!empty($res)) {
                            array_push($reportChild, $res);
                        }
                    }
                }

                // var_dump($reportChild);
                // exit;
            }


            if (!empty($genEmail[$params['cus_no']])) {
                foreach ($genEmail[$params['cus_no']] as $val) {
                    array_push($emails, $val->email);
                }


                $mesg = $this->load->view('email_tem', $data, TRUE);
                $from_email = "nan_zen0003@hotmail.com";
                // $from_email = "nattidac@scg.com";
                $this->load->library('email');
                $this->email->clear();
                $this->email->from($from_email, 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า');
                $this->email->to($emails);
                $this->email->subject('เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ ' . date('d/m/Y', strtotime($params['end_date'])));
                $this->email->message($mesg);
                $this->email->attach($content, 'attachment', 'Report_' . $params['bill_no'] . '.pdf', 'application/pdf');
                if ($cusType->type == 'main') {
                    foreach ($reportChild as $res) {
                        $contentChild = $this->genPDF($res->uuid, 'email');
                        $this->email->attach($contentChild, 'attachment', 'Report_' . $res->bill_no . '.pdf', 'application/pdf');
                    }
                }
                $result =  $this->email->send();


                if (!empty($result)) {
                    $this->model_report->updateEmail($params['uuid']);
                    $output['status'] = 200;
                    $output['data'] = (object)['data' => $params, 'email' => $emails];
                } else {
                    $output['status'] = 500;
                    $output['msg'] = $this->email->print_debugger();
                    $output['error'] = $this->email->print_debugger();
                }
            } else {
                $output['status'] = 204;
                $output['msg'] = 'No email';
                $output['data'] = false;
            }
        }

        $this->responseJSON($output);
    }

    public function genCustomerChild($id)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];


        if (!empty($id)) {
            $childLists = $this->model_invoice->getCustomerChain($id);

            if (!empty($childLists)) {
                $result['status'] = 200;
                $result['msg'] = 'OK';
                $result['data'] = $childLists;
            } else {
                $result['status'] = 204;
                $result['msg'] = 'empty data';
                $result['data'] = false;
            }
        }

        $this->responseJSON($result);
    }

    public function update()
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();
        $data = [];

        if (!empty($params)) {
            if (empty($params['uuid'])) {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['report_uuid'] = $val;
                    $data['uuid'] = genRandomString(16);
                    $data['cf_call'] = !empty($params['cf_call'][$key]) ? 1 : 0;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $data['cus_main'] = $params['cus_main'][$key];
                    $data['tel'] = $params['tel'][$key];
                    $this->model_report->createCfCall($data);
                }
            } else {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['report_uuid'] = $val;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $data['cus_main'] = $params['cus_main'][$key];
                    $data['tel'] = $params['tel'][$key];

                    if (!empty($params['cf_call'])) {
                        $find = in_array($params['tel'][$key], $params['cf_call']);
                        if (!empty($find)) {
                            $data['cf_call'] = 1;
                        } else {
                            $data['cf_call'] = 0;
                        }
                    } else {
                        $data['cf_call'] = 0;
                    }
                    if (!empty($params['uuid'][$key])) {
                        $data['uuid'] = $params['uuid'][$key];
                        $this->model_report->updateCfCall($params['uuid'][$key], $data);
                    } else {
                        $data['uuid'] = genRandomString(16);
                        $this->model_report->createCfCall($data);
                    }
                }
            }



            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    // function findObjectIsCall($data)
    // {

    //     foreach ($data as $element) {
    //         if ($element->is_call == 1) {
    //             return true;
    //             break;
    //         }
    //     }

    //     return false;
    // }

    function genPDF($uuid, $type)
    {

        require_once  './vendor/autoload.php';

        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, ['./assets/fonts']),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' =>  'THSarabunNew Bold.ttf',
                ]
            ],
            'default_font' => 'sarabun'
        ]);

        $result = $this->model_report->genPDF($uuid);


        foreach ($result->lists as $key => $res) {
            $result->lists = $res;
            $size = count($result->lists) > 1 ? 55 : 40;
            if ($key == 1) {
                $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size];
                $html = $this->load->view('report_pdf', $data, TRUE);
                $mpdf->WriteHTML($html);
            } else {
                $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size];
                $key = $this->load->view('table_pdf', $data, TRUE);
                $mpdf->WriteHTML($key);
            }
        }

        $title = 'Report_' . $result->bill_info->bill_no;
        $name = 'Report_' . $result->bill_info->bill_no;
        $mpdf->SetTitle($title);
        $footer = $this->load->view('footer_pdf', $data, TRUE);
        $mpdf->WriteHTML($footer);

        if ($type == 'email') {
            return $mpdf->Output($name, 'S');
        }

        return $mpdf->Output($name . '.pdf', 'I');
    }

    public function listReport()
    {
        $result = [];
        $total_filter = 0;
        $this->setPagination();
        $this->setSearch();
        $this->setCondition();
        $this->queryCondition['page'] = $this->page;
        $this->queryCondition['limit'] = $this->limit;

        $params = $this->input->get();

        if (!empty($params)) {
            if (!empty($params['cus_no'])) {
                $this->queryCondition['cus_no'] = explode(',', $params['cus_no']);
            }

            if (!empty($params['created_date'])) {
                $this->queryCondition['created_date'] = $params['created_date'];
            }
            if (!empty($params['bill_no'])) {
                $this->queryCondition['bill_no'] = $params['bill_no'];
            }
        }


        // if ($this->is_search && !empty($this->search)) {
        //     var_dump($this->search);
        //     exit;

        //     if (!empty($this->search)) {
        //         // $this->queryCondition['tag'] = $this->search;
        //         // $this->queryCondition['exactly_match'] = 'false';
        //     }
        // }

        $total = 0;

        if ($apiData = $this->model_report->getBillTb($this->queryCondition)) {
            if (empty($apiData)) {
                $this->responseJSON(['error' => 'Api Error']);
            } else {
                if (!empty($apiData)) {
                    $result = $apiData->lists;
                    $total = $apiData->totalRecord;
                    $total_filter = $apiData->totalRecord;
                }
            }
        }

        $this->responseDataTable($result, $total, $total_filter);
    }


    private function setPagination()
    {
        $limit = (int) $this->input->get('length', TRUE);
        $offset = (int) $this->input->get('start', TRUE);
        $search = $this->input->get('search', TRUE);
        $order = $this->input->get('order', TRUE);
        $this->column = $this->input->get('columns', TRUE);

        if (!empty($limit)) {
            $this->limit = $limit;
        }
        if (!empty($offset)) {
            $this->offset = $offset;
        }
        $this->page = floor($this->offset / $this->limit) + 1;
        if (!empty($search['value'])) {
            $this->search = $search['value'];
            $this->is_search = TRUE;
        }
        $field_name = $this->column[$order[0]['column']]['data'];
        $this->order = [$order[0]['column'], $order[0]['dir'], $field_name];

        return $this;
    }

    private function setSearch()
    {
        if (!empty($this->column)) {
            foreach ($this->column as $key => $val) {
                if (!empty($val['search']['value']) || $val['search']['value'] === '0') {
                    $this->is_search = TRUE;
                    $this->condition[] = [$val['data'], $val['search']['value'], $val['search']['regex']];
                }
            }
        }
        return $this;
    }

    private function setCondition()
    {
        $this->queryCondition = [];
        if (!empty($this->condition)) {
            foreach ($this->condition as $cond) {
                // $cond[0] = field name & $cond[1] = value
                $this->queryCondition[$cond[0]] = $cond[1];
            }
        }
        return $this;
    }


    public function genCfCall($uuid, $cus_no)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];


        if (!empty($uuid)) {
            $lists = $this->model_report->genCfCall($uuid, $cus_no);

            if (!empty($lists)) {
                $result['status'] = 200;
                $result['msg'] = 'OK';
                $result['data'] = $lists;
            } else {
                $result['status'] = 204;
                $result['msg'] = 'empty data';
                $result['data'] = false;
            }
        }

        $this->responseJSON($result);
    }


    public function genBill($uuid)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];

        if (!empty($uuid)) {
            $lists = $this->model_report->getBillById($uuid);

            if (!empty($lists)) {
                $result['status'] = 200;
                $result['msg'] = 'OK';
                $result['data'] = $lists;
            } else {
                $result['status'] = 204;
                $result['msg'] = 'empty data';
                $result['data'] = false;
            }
        }

        $this->responseJSON($result);
    }
}
