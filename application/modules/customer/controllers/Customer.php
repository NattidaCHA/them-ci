<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Customer extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_customer');
        $this->load->model('model_system');
    }


    public function index()
    {
        $result = [];
        $lists = [];
        $tels = [];
        $emails = [];
        $cus_no = $this->input->get('customer') ? $this->input->get('customer') : '';
        $result = $this->model_customer->getCustomerList();
        $lists = $this->model_customer->getCustomerTb($cus_no);

        if (!empty($lists)) {
            foreach ($lists as $key => $val) {
                $tel  = $this->model_customer->getTelById($val->cus_no);
                $email  = $this->model_customer->getEmailById($val->cus_no);
                foreach ($tel as $k => $res) {
                    $tels[$res->cus_main][] = $res;
                }

                foreach ($email as $k => $res) {
                    $emails[$res->cus_main][] = $res;
                }
            }
        }

        // echo '<pre>';
        // var_dump($contacts);
        // echo '</pre>';

        $this->data['page_header'] = 'Customer';
        $this->data['customers'] = $result;
        $this->data['lists'] = $lists;
        $this->data['tels'] = $tels;
        $this->data['emails'] = $emails;
        $this->data['cus_no'] = $cus_no;
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
        $loading = false;

        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        if ($action == 'create') {
            $loading = true;
            $main = $this->model_customer->findMain($cus_no);
            if (!empty($main->cus_no)) {
                $loading = false;
                $customerChild = $this->model_customer->findChildList($cus_no);
                $sendDate =  !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : '';
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
                    $loading = false;
                    $customer = $this->model_customer->findChild($child->msendto);
                    $type = 'child';
                    $customer = $child;
                    $customerMain = $this->model_customer->findChild($child->msendto);
                    array_push($customerChild, (object)['cus_no' => $customerMain->cus_no, 'cus_name' => $customerMain->cus_name, 'type' => 'main'], (object)['cus_no' => $child->cus_no, 'cus_name' => $child->cus_name]);
                    $sendDate = !empty($this->model_system->getSendDate($cus_no)) ? $this->model_system->getSendDate($cus_no)->mday : '';
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
                $checkCustomer = $this->model_customer->findCustomer($params['cus_no']);
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
                            // var_dump($checkChild);
                            // exit;
                            if (empty($res) || empty($checkToChild)) {
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
                            $sendto['is_check'] = 0;
                            $this->model_customer->updateSendTo($val, $sendto);
                        }
                    }
                }

                if (!empty($params['sendto'])) {
                    foreach ($params['sendto'] as $val) {
                        $customerMain = $this->model_customer->findChild($val);
                        $res = $this->model_customer->checkSendTo($val, $customerMain->msendto);
                        $checkToChild = $this->model_customer->checkSendToChild($val);
                        // var_dump($res);
                        // exit;
                        $sendto['is_check'] = 1;
                        if (!empty($params['noCheckChildId'])) {
                            if ((!empty($res) || !empty($checkToChild)) && (!in_array($val, $params['noCheckChildId']))) {
                                $this->model_customer->updateSendTo($res->uuid, $sendto);
                            }
                        } else {
                            if (empty($res) || empty($checkToChild)) {
                                $sendto['uuid'] = genRandomString(16);
                                $sendto['cus_no'] = $val;
                                $sendto['cus_main'] = $params['cus_no'];
                                $this->model_customer->createSendto($sendto);
                            } else {
                                $this->model_customer->updateSendTo($res->uuid, $sendto);
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
}
