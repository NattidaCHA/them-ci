<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Invoice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_invoice');
        $this->load->model('model_system');
    }


    public function index()
    {
        $days = $this->config->item('day');
        $search = $this->config->item('fullSearch');
        $result = [];
        $table = $this->model_system->getPageIsShow();
        $keyTable = [];
        $this->data['days'] = [];
        $dateSelect = $this->input->get('dateSelect') ? $this->input->get('dateSelect') : '';
        $startDate = $this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d');
        $endDate = $this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d'))));
        $cus_no = NULL;
        $typeSC = $this->input->get('type') ? $this->input->get('type') : '0281';

        // var_dump($table['invoice']);
        // exit;

        foreach ($table['invoice'] as $v) {
            array_push($keyTable, $v->sort);
        }

        if (!in_array($this->CURUSER->cus_no, $search)) {
            if (!empty($this->input->get('customer'))) {
                $cus_no = $this->input->get('customer');
            } else {
                $cus_no = $this->CURUSER->cus_no;
            }
        } else {
            if (!empty($this->input->get('customer'))) {
                $cus_no = $this->input->get('customer');
            }
        }

        $condition = [
            'dateSelect' => $dateSelect,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'cus_no' => $cus_no,
            'type' => $typeSC
        ];

        $o = $this->model_system->getTypeBusiness();

        foreach ($o as $v) {
            $this->data['types'][$v->msaleorg] = $v;
        }

        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        $result = $this->model_invoice->getInvoice($condition);

        // var_dump($condition);
        // exit;

        $this->data['lists'] = $result;
        $this->data['selectDays'] = $this->model_system->getDateSelect();
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->data['typeSC'] = $typeSC;
        $this->data['dateSelect'] = $dateSelect;
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->data['cus_no'] = $cus_no;
        $this->data['search'] = $search;
        $this->data['table'] = $table['invoice'];
        $this->data['keyTable'] = $keyTable;
        $this->data['page_header'] = 'Invoice';
        $this->loadAsset(['dataTables', 'datepicker', 'select2']);
        $this->view('search_invoice');
    }

    public function detail($id)
    {
        $result = [];
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $send = $this->input->get('send');

        $condition = (object)[
            'cus_no' => $id,
            'start_date' => $start,
            'end_date' => $end,
            'send_date' => $send,
        ];

        if (!empty($id) && !empty($start) && !empty($end) && !empty($send)) {
            $result = $this->model_invoice->getDetailCustomer($condition);
        }

        $this->data['main_id'] = $id;
        $this->data['lists'] = $result;
        $this->data['start'] = $start;
        $this->data['end'] = $end;
        $this->data['page_header'] = 'รายละเอียด';
        $this->view('invoice_detail');
    }


    public function create($cus_main, $start, $end)
    {

        $output = $this->apiDefaultOutput();
        $params = $this->input->post();


        if (!empty($params)) {
            array_walk_recursive($params, function (&$v) {
                $v = strip_tags(trim($v));
            });
        }

        $invoice =  $params['cf_invoice'];
        $getID = [];
        foreach ($invoice as $val) {
            $spiltInvoice = explode('|', $val);
            $getID[$spiltInvoice[1]][] = $spiltInvoice[0];
        }

        if (!empty($getID)) {
            foreach ($getID as $key => $res) {
                $uuid = genRandomString(16);
                $data = [
                    'uuid' => $uuid,
                    'bill_no' => $this->ramdomBillNo($cus_main),
                    'cus_main' => $cus_main,
                    'cus_no' => $key,
                    'is_email' => FALSE,
                    'is_sms' => FALSE,
                    'start_date' => $start,
                    'end_date' => $end,
                    'created_by' => '',
                    'created_date' => date("Y-m-d H:i:s")
                ];

                $this->model_invoice->createInvoice($data);
                $report = $this->model_invoice->getReportUuid($uuid);
                $genData = [];

                if (!empty($report)) {
                    $genData = $this->genInvoiceDetail($report, $res, $key, $cus_main);

                    if (!empty($genData)) {
                        $output['status'] = 200;
                        $output['data'] = $report;
                    } else {
                        $result['status'] = 500;
                        $result['msg'] = 'Data Timeout';
                        $result['error'] = 'Data Timeout';
                    }
                }
            }
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function genInvoiceDetail($report, $res, $key, $cus_main)
    {
        set_time_limit(10000);
        // foreach ($result as $res) {
        foreach ($res as $val) {
            $item = $this->model_invoice->getItem($val);
            if (!empty($item)) {
                $data = [
                    'uuid' => genRandomString(16),
                    'bill_no' => $report->bill_no,
                    'bill_id' => $report->uuid,
                    'macctdoc' => $val,
                    'cus_no' => $key,
                    'cus_main' => $cus_main,
                    'mdoctype' => $item->mdoctype,
                    'mbillno' => $item->mbillno,
                    'mpostdate' => $item->mpostdate,
                    'mduedate' => $item->mduedate,
                    'msaleorg' => $item->msaleorg,
                    'mpayterm' => $item->mpayterm,
                    'mnetamt' => $item->mnetamt,
                    'mtext' => $item->mtext,
                    'msort' => $this->genType($item->mdoctype)
                ];

                $this->model_invoice->createDetailInvoice($data);
            }
        }

        return true;
        // }
    }

    public function ramdomBillNo($main_id)
    {

        $random = $this->runNumber($main_id);
        $num = '8' . substr($main_id, 6) . substr(date('Ymd'), 2) . $random;
        return $num;
    }


    public function runNumber($main_id)
    {
        $number = '00001';
        $report = $this->model_invoice->getReportId($main_id);
        if (!empty($report)) {
            if ($main_id == $report->cus_main && date('Y') == date('Y', strtotime($report->created_date))) {
                $number = $this->checkNum(substr($report->bill_no, 13));
            }
        }

        return $number;
    }

    public function checkNum($no)
    {
        $calculate = $this->switchCheck($no);
        $length = strlen($calculate);
        $num = $calculate;

        if ($length == 1) {
            $num = '0000' . strval($calculate);
        }
        if ($length == 2) {
            $num = '000' . strval($calculate);
        }
        if ($length == 3) {
            $num = '00' . strval($calculate);
        }
        if ($length == 4) {
            $num = '0' . strval($calculate);
        }

        return $num;
    }

    public function switchCheck($no)
    {

        $num = (int)substr($no, 3) + 1;
        if (substr($no, 0) == '0' && substr($no, 1) == '0') {
            $num = (int)substr($no, 2) + 1;
        }

        if (substr($no, 0) == '0' && substr($no, 1) != '0') {
            $num = (int)substr($no, 1) + 1;
        }

        if (substr($no, 0) != '0') {
            $num = (int)$no + 1;
        }

        return $num;
    }

    public function genType($res)
    {
        $sortType = 0;
        if (!empty($res == 'RA')) {
            $sortType  = 1;
        }
        if (!empty($res == 'RD')) {
            $sortType  = 2;
        }
        if (!empty($res == 'DC')) {
            $sortType  = 5;
        }
        if (!empty($res == 'RB')) {
            $sortType  = 4;
        }
        if (!empty($res == 'RC')) {
            $sortType  = 3;
        }
        if (!empty($res == 'RE')) {
            $sortType  = 6;
        }
        return  $sortType;
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
}
