<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Customer extends MY_Controller
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
        $this->load->model('model_customer');
        $this->load->model('model_system');
        $this->load->model('model_api');
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

    public function index()
    {
        $cus_no = $this->input->get('customer') ? $this->input->get('customer') : '';
        $table = $this->model_system->getPageIsShow();
        $this->data['page_header'] = 'ลูกค้า';
        $this->data['cus_no'] = $cus_no;
        $this->data['table'] = $table['customer'];
        $this->loadAsset(['dataTables', 'datepicker', 'select2']);
        $this->view('customer_lists');
    }

    public function process($action)
    {
        $days = $this->config->item('day');
        $this->data['days'] = [];
        $cus_no = $this->input->get('customer') ? $this->input->get('customer') : '';
        $customer = (object) [];
        $customerChild = [];
        $sendDate = (object) [];
        $type = 'main';
        $isCheck = [];
        $tels = [];
        $emails = [];
        $loading = !empty($cus_no) && $action == 'create' ? true :  false;

        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        if ($action == 'create') {
            $main = $this->model_customer->findMain($cus_no);
            if (!empty($main->cus_no)) {
                $customerChild = $this->model_customer->findChildList($cus_no);
                $sendDate =  !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : '';
                $customer = $this->model_customer->findChild($main->msendto);
                $loading = false;

                if (!empty($customer->tel)) {
                    if (strpos($customer->tel, ",") > 0) {
                        $mobile = explode(',', $customer->tel);
                        foreach ($mobile as $tel) {
                            array_push($tels, (object)['tel' => $tel, 'contact' => $customer->contact]);
                        }
                    } else {
                        array_push($tels, (object)['tel' => $customer->tel, 'contact' => $customer->contact]);
                    }
                }

                if (!empty($customer->email)) {
                    if (strpos($customer->email, ";") > 0) {
                        $res = explode(';', $customer->email);
                        foreach ($res as $email) {
                            array_push($emails, (object)['email' => $email]);
                        }
                    } else {
                        array_push($emails, (object)['email' => $customer->email]);
                    }
                }
            } else {
                $child = $this->model_customer->findChild($cus_no);
                if (!empty($child->cus_no)) {
                    $customer = $this->model_customer->findChild($child->msendto);
                    $type = 'child';
                    $customer = $child;
                    $customerMain = $this->model_customer->findChild($child->msendto);
                    array_push($customerChild, (object)['cus_no' => $customerMain->cus_no, 'cus_name' => $customerMain->cus_name, 'type' => 'main'], (object)['cus_no' => $child->cus_no, 'cus_name' => $child->cus_name]);
                    $sendDate = !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : '';
                    $loading = false;

                    if (!empty($customer->tel)) {
                        if (strpos($customer->tel, ",") > 0) {
                            $mobile = explode(',', $customer->tel);
                            foreach ($mobile as $tel) {
                                array_push($tels, (object)['tel' => $tel, 'contact' => $customer->contact]);
                            }
                        } else {
                            array_push($tels, (object)['tel' => $customer->tel, 'contact' => $customer->contact]);
                        }
                    }

                    if (!empty($customer->email)) {
                        if (strpos($customer->email, ";") > 0) {
                            $res = explode(';', $customer->email);
                            foreach ($res as $email) {
                                array_push($emails, (object)['email' => $email]);
                            }
                        } else {
                            array_push($emails, (object)['email' => $customer->email]);
                        }
                    }
                }
            }
        } else {
            $customer = $this->model_customer->customer($cus_no);
            if ($customer->type == 'main') {
                $customerChild = $this->model_customer->findChildList($customer->cus_no);
            } else {
                $findMiain = $this->model_customer->findChild($customer->cus_no);
                $customerMain = $this->model_customer->findChild($findMiain->msendto);
                array_push($customerChild, (object)['cus_no' => $customerMain->cus_no, 'cus_name' => $customerMain->cus_name, 'type' => 'main'], (object)['cus_no' => $customer->cus_no, 'cus_name' => $customer->cus_name]);
            }

            $isCheck = $this->model_customer->getSendTo($cus_no);
            $sendDate = $customer->send_date;
            $type = $customer->type;
            $tels = $this->model_customer->tel($cus_no);
            $emails = $this->model_customer->email($cus_no);
        }

        // var_dump($tels);
        // exit;
        $this->data['page_header'] = ($action == 'create') ? 'สร้างข้อมูลลูกค้า' : 'แก้ไขข้อมูลลูกค้า';
        $this->data['customers'] = ($action == 'create') ? $this->model_system->getCustomer() : '';
        $this->data['info'] = $customer;
        $this->data['select_customer'] = $customerChild;
        $this->data['send_date'] = !empty($sendDate) ? $sendDate : '';
        $this->data['type'] = $type;
        $this->data['isCheck'] = $isCheck;
        $this->data['tels'] = $tels;
        $this->data['emails'] = $emails;
        $this->data['loading'] = $loading;
        $this->data['action'] = $action;
        $this->loadAsset(['parsley', 'sweetalert', 'select2']);
        $this->view('customer_form');
    }

    public function action($action)
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();


        if (!empty($params)) {
            array_walk_recursive($params, function (&$v) {
                $v = strip_tags(trim($v));
            });
        }
        if (!empty($params)) {
            $customer = [
                'send_date' => $params['send_date'],
            ];

            $sendto = [];

            if ($action == 'create') {
                $checkCustomer = $this->model_system->findCustomerById($params['cus_no']);
                if (empty($checkCustomer)) {
                    $customer['uuid'] =  genRandomString(16);
                    $customer['cus_no'] = $params['cus_no'];
                    $customer['cus_name'] = $params['cus_name'];
                    $customer['type'] = $params['type'];
                    $customer['created_date'] =  date("Y-m-d H:i:s");
                    $create = $this->model_customer->createCustomer($customer);

                    if (!empty($create) && !empty($params['sendto'])) {
                        foreach ($params['sendto'] as $key => $val) {
                            $checkChild = $this->model_customer->checkSendTo($val, $params['cus_no']);
                            $checkToChild = $this->model_customer->checkSendToChild($val);

                            if (empty($checkChild) || empty($checkToChild)) {
                                $sendto['uuid'] = genRandomString(16);
                                $sendto['cus_main'] = $params['cus_no'];
                                $sendto['cus_no'] = $val;
                                $sendto['is_check'] = 1;
                                $this->model_customer->createSendto($sendto);
                            }
                        }
                    }

                    if (!empty($create) &&  (!empty($params['tel']))) {
                        $tel = $params['tel'];
                        foreach ($tel as $key => $val) {
                            if (!empty($val)) {
                                $conatct = [
                                    'uuid' => genRandomString(16),
                                    'cus_main' => $params['cus_no'],
                                    'tel' => !empty($val) ? $val : '',
                                    'is_call' => !empty($params['is_call'][$key]) && !empty($params['is_call']) ? 1 : 0,
                                    'contact' => !empty($params['contact'][$key]) && !empty($params['contact']) ? $params['contact'][$key] : NULL,
                                    'created_date' => date("Y-m-d H:i:s")
                                ];

                                $this->model_customer->createTelContact($conatct);
                            }
                        }
                    }

                    if (!empty($create) &&  !empty($params['email'])) {
                        foreach ($params['email'] as $key => $val) {
                            if (!empty($val)) {
                                $conatct = [
                                    'uuid' => genRandomString(16),
                                    'cus_main' => $params['cus_no'],
                                    'email' => !empty($params['email']) ? $val : '',
                                    'created_date' => date("Y-m-d H:i:s")
                                ];

                                $this->model_customer->createEmailContact($conatct);
                            }
                        }
                    }
                    // var_dump($checkCustomer);
                    // exit;

                    $output['status'] = 200;
                    $output['data'] = $params;
                } else {
                    $output['status'] = 500;
                    $output['error'] = 'ข้อมูลลูกค้ามีการสร้างเรียบร้อยแล้ว';
                    $output['msg'] = 'ข้อมูลลูกค้ามีการสร้างเรียบร้อยแล้ว';
                }
            } else {
                $id = $this->input->get('id');
                $customer['updated_date'] =  date("Y-m-d H:i:s");
                $this->model_customer->updateInfo($params['id'], $customer);

                if (!empty($params['noCheckChild'])) {
                    foreach ($params['noCheckChild'] as $val) {
                        $checkChild = $this->model_customer->getSendToId($val);
                        if (!empty($checkChild)) {
                            $this->model_customer->updateSendTo($val, 0);
                        }
                    }
                }

                if (!empty($params['sendto'])) {
                    foreach ($params['sendto'] as $val) {
                        $customerMain = $this->model_customer->findChild($val);
                        $res = $this->model_customer->checkSendTo($val, $customerMain->msendto);
                        $checkToChild = $this->model_customer->checkSendToChild($val);
                        $sendto['is_check'] = 1;

                        if (!empty($params['noCheckChildId'])) {
                            $uuid = !empty($res) ? $res->uuid : $checkToChild->uuid;
                            if ((!empty($res) || !empty($checkToChild)) && (!in_array($val, $params['noCheckChildId']))) {
                                $this->model_customer->updateSendTo($uuid, 1);
                            }
                        } else {
                            if ($params['type'] == 'main') {
                                if (!empty($res)) {
                                    $this->model_customer->updateSendTo($res->uuid, 1);
                                } else {
                                    $sendto['uuid'] = genRandomString(16);
                                    $sendto['cus_no'] = $val;
                                    $sendto['cus_main'] = $params['cus_no'];
                                    $this->model_customer->createSendto($sendto);
                                }
                            } else {
                                if (!empty($checkToChild)) {
                                    $this->model_customer->updateSendTo($checkToChild->uuid, 1);
                                } else {
                                    $sendto['uuid'] = genRandomString(16);
                                    $sendto['cus_no'] = $val;
                                    $sendto['cus_main'] = $params['cus_no'];
                                    $this->model_customer->createSendto($sendto);
                                }
                            }
                        }
                    }
                }

                if (!empty($params['tel'])) {
                    $id = $params['uuid_tel'];
                    $tel = $params['tel'];

                    foreach ($id as $key => $val) {
                        if (!empty($tel[$key])) {
                            $conatct = [
                                'tel' => $tel[$key]
                            ];
                            if ($val == '1') {
                                $conatct['uuid'] =  genRandomString(16);
                                $conatct['cus_main'] = $params['cus_no'];
                                $conatct['is_call'] = !empty($params['is_call'][$key]) && !empty($params['is_call']) ? 1 : 0;
                                $conatct['contact'] = !empty($params['contact'][$key]) && !empty($params['contact']) ? $params['contact'][$key] : NULL;
                                $conatct['created_date'] =  date("Y-m-d H:i:s");
                                $this->model_customer->createTelContact($conatct);
                            } else {
                                $conatct['updated_date'] =  date("Y-m-d H:i:s");
                                $conatct['is_call'] = !empty($params['is_call'][$key]) && !empty($params['is_call']) ? 1 : 0;
                                $conatct['contact'] = !empty($params['contact'][$key]) && !empty($params['contact']) ? $params['contact'][$key] : NULL;
                                $this->model_customer->updateTelContact($val, $conatct);
                            }
                        }
                    }
                }

                if (!empty($params['email'])) {
                    $id = $params['uuid_email'];
                    $email = $params['email'];
                    foreach ($id as $key => $val) {
                        if (!empty($email[$key])) {
                            $conatct = [
                                'email' =>  $email[$key]
                            ];

                            if ($val == '1') {
                                $conatct['uuid'] =  genRandomString(16);
                                $conatct['cus_main'] = $params['cus_no'];
                                $conatct['created_date'] =  date("Y-m-d H:i:s");
                                $this->model_customer->createEmailContact($conatct);
                            } else {
                                $conatct['updated_date'] =  date("Y-m-d H:i:s");
                                $this->model_customer->updateEmailContact($val, $conatct);
                            }
                        }
                    }
                }

                // var_dump($output);
                // exit;
                $output['status'] = 200;
                $output['data'] = $params;
            }
        }


        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function removeEmail($id)
    {
        $output = $this->apiDefaultOutput();

        if (!empty($id)) {
            $this->model_customer->removeEmail($id);

            $output['status'] = 200;
            $output['data'] = $id;
        }


        $output['source'] = $id;
        $this->responseJSON($output);
    }

    public function removeTel($id)
    {
        $output = $this->apiDefaultOutput();

        if (!empty($id)) {
            $this->model_customer->removeTel($id);

            $output['status'] = 200;
            $output['data'] = $id;
        }


        $output['source'] = $id;
        $this->responseJSON($output);
    }

    public function genCustomer()
    {
        $lists = $this->model_system->findCustomer();

        $current = $this->model_system->getCustomerNew();

        $res = [];
        // var_dump($val);
        // exit;
        foreach ($lists as $k => $val) {
            // var_dump($val);
            // exit;
            if (!in_array($val->mcustno, $current)) {
                array_push($res, $val->mcustno);
            }
            // break;
            // $this->transformCustomer($val->mcustno);
            // echo $k . 'success';

            // if (count($lists) - 1 == $k) {
            //     break;
            //     return true;
            // }
            // return true;
        }

        foreach ($res as $k => $val) {

            // break;
            $this->transformCustomer($val);
            echo $k . 'success';

            if (count($res) - 1 == $k) {
                break;
                return true;
            }
        }

        // var_dump($res);
        // exit;

        echo 'No success';
        return false;
    }

    public function transformCustomer($cus_no)
    {
        set_time_limit(0);
        $customer = (object) [];
        $customerChild = [];
        $sendDate = (object) [];
        $type = 'main';
        $tels = [];
        $emails = [];

        $main = $this->model_customer->findMain($cus_no);
        if (!empty($main->cus_no)) {
            $customerChild = $this->model_customer->findChildList($cus_no);
            $sendDate =  !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : NULL;
            $customer = $this->model_customer->findChild($main->msendto);

            if (!empty($customer->tel)) {
                if (strpos($customer->tel, ",") > 0) {
                    $mobile = explode(',', $customer->tel);
                    foreach ($mobile as $tel) {
                        array_push($tels, (object)['tel' => $tel, 'contact' => $customer->contact]);
                    }
                } else {
                    array_push($tels, (object)['tel' => $customer->tel, 'contact' => $customer->contact]);
                }
            }

            if (!empty($customer->email)) {
                if (strpos($customer->email, ";") > 0) {
                    $res = explode(';', $customer->email);
                    foreach ($res as $email) {
                        array_push($emails, (object)['email' => $email]);
                    }
                } else {
                    array_push($emails, (object)['email' => $customer->email]);
                }
            }
        } else {
            $child = $this->model_customer->findChild($cus_no);
            if (!empty($child->cus_no)) {
                $customer = $this->model_customer->findChild($child->msendto);
                $type = 'child';
                $customer = $child;
                $customerMain = $this->model_customer->findChild($child->msendto);
                array_push($customerChild, (object)['cus_no' => $customerMain->cus_no, 'cus_name' => $customerMain->cus_name, 'type' => 'main'], (object)['cus_no' => $child->cus_no, 'cus_name' => $child->cus_name]);
                $sendDate = !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : NULL;

                if (!empty($customer->tel)) {
                    if (strpos($customer->tel, ",") > 0) {
                        $mobile = explode(',', $customer->tel);
                        foreach ($mobile as $tel) {
                            array_push($tels, (object)['tel' => $tel, 'contact' => $customer->contact]);
                        }
                    } else {
                        array_push($tels, (object)['tel' => $customer->tel, 'contact' => $customer->contact]);
                    }
                }

                if (!empty($customer->email)) {
                    if (strpos($customer->email, ";") > 0) {
                        $res = explode(';', $customer->email);
                        foreach ($res as $email) {
                            array_push($emails, (object)['email' => $email]);
                        }
                    } else {
                        array_push($emails, (object)['email' => $customer->email]);
                    }
                }
            }
        }


        if (!empty($customer)) {
            $create = $this->transformCreateCustomer($customer, $sendDate, $type, $tels, $emails);

            if (!empty($create)) {
                return $create;
            }
        }

        return false;
    }


    public function transformCreateCustomer($info, $sendDate, $type, $tels, $emails)
    {
        set_time_limit(0);
        $customer = [
            'uuid' => genRandomString(16),
            'cus_no' => $info->cus_no,
            'cus_name' => $info->cus_name,
            'type' => $type,
            'send_date' => $sendDate,
            'created_date' =>  date("Y-m-d H:i:s")
        ];
        $create = $this->model_customer->createCustomer($customer);


        if ((!empty($tels))) {
            foreach ($tels as $key => $val) {
                if (!empty($val)) {
                    $conatct = [
                        'uuid' => genRandomString(16),
                        'cus_main' => $info->cus_no,
                        'tel' => !empty($val->tel) ? $val->tel : '',
                        'is_call' => 0,
                        'contact' => !empty($val->contact) && trim($val->contact) != '-' ? $val->contact : NULL,
                        'created_date' => date("Y-m-d H:i:s")
                    ];

                    $this->model_customer->createTelContact($conatct);
                }
            }
        }

        if (!empty($emails)) {
            foreach ($emails as $key => $val) {
                if (!empty($val)) {
                    $conatct = [
                        'uuid' => genRandomString(16),
                        'cus_main' => $info->cus_no,
                        'email' => !empty($val->email) ? $val->email : NULL,
                        'created_date' => date("Y-m-d H:i:s")
                    ];

                    $this->model_customer->createEmailContact($conatct);
                }
            }
        }

        return $create;
    }

    public function listCustomer($cus_no = FALSE)
    {
        $result = [];
        $total_filter = 0;
        $this->setPagination();
        $this->setSearch();
        $this->setCondition();
        $this->queryCondition['page'] = $this->page;
        $this->queryCondition['limit'] = $this->limit;


        $total = 0;

        if ($apiData = $this->model_customer->getCustomerTb($cus_no, $this->limit, $this->page)) {
            if (empty($apiData)) {
                $this->responseJSON(['error' => $apiData->error]);
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


    public function test()
    {
        var_dump($this->model_api->getInvoice());
    }
}
