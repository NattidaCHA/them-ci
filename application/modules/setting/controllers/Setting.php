<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Setting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_setting');
        $this->load->model('model_invoice');
    }


    public function index()
    {
        $result = !empty($this->model_setting->getPage()->items) ? $this->model_setting->getPage()->items : [];
        $tab = !empty($this->input->get('tab', TRUE)) ? $this->input->get('tab', TRUE) : 'invoice';
        $days = $this->config->item('day');

        $this->data['days'] = [];
        // $dateSelect = $this->input->get('dateSelect') ? $this->input->get('dateSelect') : '';
        $startDate =  date('Y-m-d');
        $endDate =  date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d'))));
        // $cus_no =  $this->input->get('customer') ? $this->input->get('endDate') : '';
        $typeSC =  '0281';
        // $is_bill = $this->input->get('is_bill') ? $this->input->get('is_bill') : '3';

        // $condition = (object)[
        //     'dateSelect' => $dateSelect,
        //     'startDate' => $startDate,
        //     'endDate' => $endDate,
        //     'cus_no' => $cus_no,
        //     'type' => $typeSC,
        //     'is_bill' => $is_bill
        // ];

        $o = $this->model_system->getTypeBusiness()->items;
        // var_dump($condition);
        // exit;

        foreach ($o as $v) {
            $this->data['types'][$v->msaleorg] = $v;
        }

        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        // $this->data['page'] = 'Setting';
        $this->data['lists'] = $result;
        $this->data['tab'] = $tab;
        $this->data['page_header'] = 'Setting';
        $this->data['selectDays'] = $this->model_system->getDateSelect()->items;
        $this->data['typeSC'] = $typeSC;
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->loadAsset(['dataTables', 'datepicker', 'select2', 'parsley']);
        $this->view('setting_form');
    }

    public function create()
    {
        $list = $this->config->item('page');

        foreach ($list as $page) {
            foreach ($page->colunm as $val) {
                $params = [
                    genRandomString(16),
                    $page->page,
                    $val->name,
                    $val->id,
                    $page->id,
                    1
                ];
                $this->model_setting->create($params);
            }
        }
    }

    public function process($page)
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();
        $result = $this->model_setting->getPage()->items;


        if (!empty($params)) {
            $formData = [];
            if ($page == 'invoice') {
                $formData = $result['invoice'];
            } else if ($page == 'report') {
                $formData = $result['report'];
            } else {
                $formData = $result['customer'];
            }

            foreach ($formData as $val) {
                if (in_array($val->uuid, $params['is_show'])) {
                    $this->model_setting->updateSetting($val->uuid, [1]);
                } else {
                    $this->model_setting->updateSetting($val->uuid, [0]);
                }
            }

            $output['status'] = 200;
            $output['data'] = $params;
            unset($output['error']);
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function repair()
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();

        if (!empty($params)) {
            if (!empty($params['is_bill']) && $params['is_bill'] == '2') {
                $checkReport = $this->model_setting->checkReport((object)$params);
                if (!empty($checkReport->key)) {
                    set_time_limit(0);
                    $checkMain = !empty($this->findObjectSendToRepair($checkReport->report)) ? true : false;
                    foreach ($checkReport->report as $key => $report) {
                        $email = $this->genEmail((array)$report, 'invoice', $checkMain);
                        if ($email->status == 204) {
                            $output['status'] = $email->status;
                            $output['data'] = $email->data;
                            $output['msg'] = $email->msg;
                            unset($output['error']);
                        } else if ($email->status == 500) {
                            $output['status'] = $email->status;
                            $output['msg'] = $email->msg;
                            $output['error'] = $email->error;
                        }
                    }

                    $output['status'] = $email->status;
                    $output['data'] = $email->data;
                    unset($output['error']);
                } else {
                    $result = $this->model_setting->getInvoice((object)$params)->items;
                    $process = $this->processJob($result, $params['startDate'], $params['endDate']);
                    if (!empty($process)) {
                        if ($process->status == 200) {
                            $output['status'] = $process->status;
                            $output['data'] = $process->data;
                            unset($output['error']);
                        } else if ($process->status == 204) {
                            $output['status'] = $process->status;
                            $output['msg'] = $process->msg;
                            $output['data'] = $process->data;
                            unset($output['error']);
                        } else {
                            $output['status'] = $process->status;
                            $output['msg'] = $process->msg;
                            $output['error'] = $process->error;
                        }
                    }
                }
            } else {
                $result = $this->model_setting->getInvoice((object)$params)->items;
                $process = $this->processJob($result, $params['startDate'], $params['endDate']);
                if (!empty($process)) {
                    if ($process->status == 200) {
                        $output['status'] = $process->status;
                        $output['data'] = $process->data;
                        unset($output['error']);
                    } else if ($process->status == 204) {
                        $output['status'] = $process->status;
                        $output['msg'] = $process->msg;
                        $output['data'] = $process->data;
                        unset($output['error']);
                    } else {
                        $output['status'] = $process->status;
                        $output['msg'] = $process->msg;
                        $output['error'] = $process->error;
                    }
                }
            }
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    function findObjectSendTo($data)
    {

        foreach ($data as $element) {
            foreach ($element as $res) {
                if (!empty($res->cus_main) && !empty($res->cus_no)) {
                    if ($res->cus_main == $res->cus_no) {
                        return true;
                        break;
                    }
                }
            }
        }

        return false;
    }

    function findObjectSendToRepair($data)
    {
        foreach ($data as $res) {
            if (!empty($res->cus_main) && !empty($res->cus_no)) {
                if ($res->cus_main == $res->cus_no) {
                    return true;
                    break;
                }
            }
        }

        return false;
    }


    public function genInvoiceDetail($report, $lists)
    {
        set_time_limit(0);
        if (!empty($report) && !empty($lists)) {
            foreach ($lists as $res) {
                $data = [
                    genRandomString(16),
                    $report->bill_no,
                    $report->uuid,
                    $res->macctdoc,
                    $res->mcustno,
                    $res->msendto,
                    $res->mdoctype,
                    $res->mbillno,
                    $res->mpostdate,
                    $res->mduedate,
                    $res->msaleorg,
                    $res->mpayterm,
                    $res->mnetamt,
                    $res->mtext,
                    $this->genType($res->mdoctype)
                ];

                $this->model_invoice->createDetailInvoice($data);
            }

            return true;
        }
        return false;
    }

    public function processJob($result, $start, $end)
    {
        if (!empty($result)) {
            foreach ($result as $key => $res) {
                $uuid = genRandomString(16);
                $data = [
                    $result[$key][0]->msendto,
                    $key,
                    $this->ramdomBillNo($result[$key][0]->msendto),
                    FALSE,
                    date("Y-m-d H:i:s"),
                    $uuid,
                    $start,
                    $end,
                    FALSE,
                    date("Y-m-d H:i:s"),
                    NULL,
                    NULL
                ];

                $this->model_invoice->createInvoice($data);
                $report = $this->model_invoice->getReportUuid($uuid)->items;
                $reportMain[$key] = $report;

                $genData = [];

                if (!empty($report)) {
                    $genData = $this->genInvoiceDetail($report, $result[$key]);
                    if (empty($genData)) {
                        return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
                    }
                } else {
                    return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
                }
            }

            $checkMain = !empty($this->findObjectSendTo($result)) ? true : false;
            foreach ($reportMain as $key => $report) {
                $email = $this->genEmail((array)$report, 'invoice', $checkMain);
                if ($email->status == 204) {
                    return (object)['status' => 204, 'data' => $email->data, 'msg' => $email->msg];
                } else if ($email->status == 500) {
                    return (object)['status' => 500, 'msg' => $email->msg, 'error' => $email->msg];
                }
            }

            return (object)['status' => 200, 'data' => (object)['data' => $reportMain]];
        } else {
            return (object)['status' => 500, 'msg' => 'ไม่พบข้อมูลค่าใช้จ่าย', 'error' => 'ไม่พบข้อมูลค่าใช้จ่าย'];
        }
    }


    public function cronJob()
    {
        $dateSelect = $this->model_system->getDateSelect()->items;
        $defaultSelectDate = [];
        foreach ($dateSelect as $date) {
            $defaultSelectDate[] = $date->mday;
        }

        $params = (object)[
            'dateSelect' => 'Thursday', //date('l'),
            'startDate' => '2023-09-11', //date('Y-m-d'),
            'endDate' => '2023-09-29', //date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d')))),
            'type' => '0281'
        ];

        if (in_array(date('l'), $defaultSelectDate) && !empty($params)) {
            echo '<pre>Start Job Success </pre>';
            echo '<pre>วันที่ต้องการแจ้ง ' . $params->dateSelect . ' ตั้งแต่ ' . $params->startDate  . '-' . $params->endDate . '</pre>';
            $result = $this->model_setting->getInvoice($params)->items;
            echo '<p>Total ' . count(array_keys($result)) . ' รหัสลูกค้า ' . implode(",\r\n", array_keys($result)) . '</p>';
            if (!empty($result)) {
                $process = $this->processJob($result, $params->startDate, $params->endDate);
                if (!empty($process)) {
                    if ($process->status == 200) {
                        echo '<pre>Run Job Success</pre>';
                        echo implode(',', array_keys($process->data->data));
                        return;
                    } else if ($process->status == 204) {
                        $noEmail = [];
                        array_push($noEmail, str_replace('ไม่พบอีเมลของรหัสลูกค้า ', '', $process->msg));
                        echo '<p>รหัสลูกค้าที่ไม่พบอีเมล ' . implode(',', $noEmail) . '</p>';
                    } else {
                        echo '<pre>Job Not Success</pre>';
                        return;
                    }
                }
            }
        }
    }
}
