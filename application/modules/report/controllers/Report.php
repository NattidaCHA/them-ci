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
        $this->load->model('customer/model_customer');
    }

    public function index()
    {
        $created_date = !empty($this->input->post('created_date')) ? $this->input->post('created_date') : '';
        $bill_no = !empty($this->input->post('bill_no')) ? $this->input->post('bill_no') : '';
        $first_date = date('Y-m-d', strtotime('-3 months'));
        $searchLists = $this->CURUSER->user[0]->user_type == 'Cus' ? $this->model_system->findeCustomersearch($this->CURUSER->user_cus->cus_code) : $this->model_system->getCustomerAll()->items;
        $cus_no = NULL;
        $table = $this->model_system->getPageIsShow()->items;
        $is_contact =  (!empty($this->input->post('is_contact')) ? $this->input->post('is_contact') : '1');

        if ($this->CURUSER->user[0]->user_type == 'Emp') {
            if (!empty($this->input->post('customer'))) {
                $cus_no = implode(',', $this->input->post('customer'));
            }
        } else {
            if (!empty($this->input->post('customer'))) {
                $cus_no = implode(',', $this->input->post('customer'));
            } else {
                $sendto = [];
                array_push($sendto, $this->CURUSER->user_cus->cus_code);
                if ($this->CURUSER->user_cus->cus_type == 'A') {
                    $isCheck = $this->model_system->checkSendtoMain($this->CURUSER->user_cus->cus_code)->items;
                    foreach ($isCheck as $val) {
                        if (!in_array($val->cus_no, $sendto)) {
                            array_push($sendto, $val->cus_no);
                        }
                    }
                }

                $cus_no = implode(',', $sendto);
            }
        }

        if (!empty($cus_no)) {
            $cus = explode(',', $cus_no);
            $index = (string)array_search('all', $cus);
            if ($index == '0') {
                array_splice($cus, 0, 1);
            }

            $billNos = $this->model_report->getBillNo(implode(',', $cus))->items;
        } else {
            $billNos = $this->model_report->getBillNo()->items;
        }

        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $searchLists;
        $this->data['created_date'] = $created_date;
        $this->data['bill_no'] = $bill_no;
        $this->data['cus_no'] = $cus_no;
        $this->data['page_header'] = 'รายงาน';
        $this->data['table'] = $table['report'];
        $this->data['info'] = $this->CURUSER;
        $this->data['first_date'] = $first_date;
        $this->data['is_contact'] = $is_contact;
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
        $email = $this->genEmail($params, 'report');
        if ($email->status == 200) {
            $output['status'] = 200;
            $output['data'] = (object)['data' => $email->data, 'email' => $email->email];
        } else if ($email->status == 204) {
            $output['status'] = 204;
            $output['data'] = (object)['data' => false, 'msg' => 'No email'];
        } else {
            $output['status'] = 500;
            $output['msg'] = $email->msg;
            $output['error'] = $email->error;
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

            if (!empty($params['is_receive_bill'])) {
                $this->model_report->updateReceiveBill($params['is_receive_bill'], [1]);
            } else {
                $this->model_report->updateReceiveBill($params['report_uuid'][0], [0]);
            }

            if (empty($params['uuid'])) {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['cf_call'] = !empty($params['cf_call'][$key]) ? 1 : 0;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $column = [genRandomString(16), $val, $params['cus_main'][$key], $params['tel'][$key], $data['cf_call'], $data['receive_call']];
                    $this->model_report->createCfCall($column);
                }
            } else {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';

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
                        $column = [$data['cf_call'], $data['receive_call']];
                        $this->model_report->updateCfCall($params['uuid'][$key],  $column);
                    } else {
                        $column = [genRandomString(16), $val, $params['cus_main'][$key], $params['tel'][$key], $data['cf_call'], $data['receive_call']];
                        $this->model_report->createCfCall($column);
                    }
                }
            }

            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function listReport()
    {
        $result = [];
        $total_filter = 0;
        $this->setPagination();
        // $this->setSearch();
        // $this->setCondition();
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

            if (!empty($params['is_contact'])) {
                $this->queryCondition['is_contact'] = $params['is_contact'];
            }
        }

        $total = 0;


        if ($apiData = $this->model_report->getBillTb($this->queryCondition)) {
            if (!empty($apiData->error)) {
                $this->responseJSON(['error' => $apiData->error]);
            } else {
                if (!empty($apiData->items)) {
                    $result = $apiData->items;
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
        // $order = $this->input->get('order', TRUE);
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
        // $field_name = $this->column[$order[0]['column']]['data'];
        // $this->order = [$order[0]['column'], $order[0]['dir'], $field_name];

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
            $lists = $this->model_report->getBillById($uuid)->items;

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

    public function genFax($cus_no)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];
        $lists = [];

        if (!empty($cus_no)) {
            $lists = $this->model_customer->fax($cus_no)->items;

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
