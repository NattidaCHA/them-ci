<?php (defined('BASEPATH')) or exit('No direct script access allowed');
class Invoice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_invoice');
        $this->load->model('model_system');
        $this->load->model('report/model_report');
    }


    public function index()
    {
        $days = $this->config->item('day');
        $result = [];
        $table = $this->model_system->getPageIsShow()->items;
        $keyTable = [];
        $this->data['days'] = [];
        $dateSelect = $this->input->get('dateSelect') ? $this->input->get('dateSelect') : '';
        $startDate = $this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d');
        $endDate = $this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d'))));
        $cus_no = NULL;
        $typeSC = $this->input->get('type') ? $this->input->get('type') : '0281';
        $is_bill = $this->input->get('is_bill') ? $this->input->get('is_bill') : '3';
        $is_contact = $this->input->get('is_contact') ? $this->input->get('is_contact') : '1';
        
        foreach ($table['invoice'] as $v) {
            array_push($keyTable, $v->sort);
        }

        if ($this->CURUSER->user[0]->user_type == 'Cus') {
            if (!empty($this->input->get('customer'))) {
                $cus_no = $this->input->get('customer');
            } else {
                $cus_no = $this->CURUSER->user_cus->cus_code;
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
            'type' => $typeSC,
            'is_bill' => $is_bill,
            'is_contact' => $is_contact
        ];

        $o = $this->model_system->getTypeBusiness()->items;

        foreach ($o as $v) {
            $this->data['types'][$v->msaleorg] = $v;
        }

        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        $result = !empty($this->model_invoice->getInvoice($condition)->items) ? $this->model_invoice->getInvoice($condition)->items  : [];

        $this->data['lists'] = $result;
        $this->data['selectDays'] = $this->model_system->getDateSelect()->items;
        $this->data['typeSC'] = $typeSC;
        $this->data['dateSelect'] = $dateSelect;
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->data['cus_no'] = $cus_no;
        $this->data['table'] = $table['invoice'];
        $this->data['keyTable'] = $keyTable;
        $this->data['is_bill'] = $is_bill;
        $this->data['is_contact'] = $is_contact;
        $this->data['page_header'] = 'การแจ้งเตือน';
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
        $this->data['page_header'] = 'รายละเอียดการแจ้งเตือน';
        $this->view('invoice_detail');
    }


    public function create($cus_main, $start, $end)
    {

        $output = $this->apiDefaultOutput();
        $params = $this->input->post();

        // var_dump($params);

        if (!empty($params)) {
            array_walk_recursive($params, function (&$v) {
                $v = strip_tags(trim($v));
            });
        }

        $invoice =  $params['cf_invoice'];
        $getID = [];
        $reportMain = [];
        foreach ($invoice as $val) {
            $spiltInvoice = explode('|', $val);
            $getID[$spiltInvoice[1]][] = $spiltInvoice[0];
        }

        // var_dump($getID);
        // exit;
        if (!empty($getID)) {
            foreach ($getID as $key => $res) {
                $uuid = genRandomString(16);
                $data = [
                    $cus_main,
                    $key,
                    $this->ramdomBillNo($cus_main),
                    FALSE,
                    date("Y-m-d H:i:s"),
                    $uuid,
                    $start,
                    $end,
                    FALSE,
                    date("Y-m-d H:i:s"),
                    $this->CURUSER->user[0]->userdisplay_th,
                    NULL
                ];

                $this->model_invoice->createInvoice($data);
                $report = $this->model_invoice->getReportUuid($uuid)->items;
                $report->mduedate = $params['mduedate'];
                $reportMain[$key] = $report;

                $genData = [];

                if (!empty($report)) {
                    $genData = $this->genInvoiceDetail($report, $res, $key, $cus_main);

                    if (empty($genData)) {
                        $output['status'] = 500;
                        $output['msg'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
                        $output['error'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
                    }
                } else {
                    $output['status'] = 500;
                    $output['msg'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
                    $output['error'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
                }
            }

            // var_dump($report);
            // exit;
            $checkMain = !empty($reportMain[$cus_main]) ? true : false;
            foreach ($reportMain as $key => $report) {
                $email = $this->genEmail((array)$report, 'invoice', $checkMain);
                if ($email->status == 204) {
                    $output['status'] = 204;
                    $output['data'] = (object)['data' => false, 'msg' => 'No email'];
                } else if ($email->status == 500) {
                    $output['status'] = 500;
                    $output['msg'] = $email->msg;
                    $output['error'] = $email->error;
                }
            }

            $output['status'] = 200;
            $output['data'] = (object)['data' => $reportMain];
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function genInvoiceDetail($report, $res, $key, $cus_main)
    {
        set_time_limit(0);
        // foreach ($result as $res) {
        foreach ($res as $val) {
            $item = $this->model_invoice->getItem($val)->items;
            if (!empty($item)) {
                $data = [
                    genRandomString(16),
                    $report->bill_no,
                    $report->uuid,
                    $val,
                    $key,
                    $cus_main,
                    $item->mdoctype,
                    $item->mbillno,
                    $item->mpostdate,
                    $item->mduedate,
                    $item->msaleorg,
                    $item->mpayterm,
                    $item->mnetamt,
                    $item->mtext,
                    $this->genType($item->mdoctype)
                ];

                $this->model_invoice->createDetailInvoice($data);
            }
        }

        return true;
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


    public function genInvoiceListExcel()
    {
        $table = $this->model_system->getPageIsShow()->items;
        $params = $this->input->get();
        $keyTable = [];
        $types = [];
        foreach ($table['invoice'] as $v) {
            array_push($keyTable, $v->sort);
        }

        $o = $this->model_system->getTypeBusiness()->items;
        foreach ($o as $v) {
            $types[$v->msaleorg] = $v;
        }

        $condition = [
            'dateSelect' =>  !empty($params['dateSelect']) ? $params['dateSelect'] : '',
            'startDate' => !empty($params['startDate']) ? $params['startDate'] : date('Y-m-d'),
            'endDate' => !empty($params['endDate']) ? $params['endDate'] :  date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d')))),
            'cus_no' => !empty($params['cus_no']) ? $params['cus_no'] : '',
            'type' => !empty($params['type']) ? $params['type'] : '0281',
            'is_bill' => !empty($params['is_bill']) ? $params['is_bill'] : '3',
            'is_contact' => !empty($params['is_contact']) ? $params['is_contact'] : '1'
        ];


        if (!empty($condition["dateSelect"])) {
            $result = !empty($this->model_invoice->getInvoice($condition)->items) ? $this->model_invoice->getInvoice($condition)->items  : [];
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="invoice.xls"');
            $data['result'] = (object)['data' => $result, 'header' => $table['invoice'], 'keyTable' => $keyTable, 'types' => $types];
            $this->load->view('export_invoice', $data);
        }
    }
    public function genExcel($uuid)
    {
        $result = $this->model_report->excel($uuid);
        $tem = $this->model_system->getTemPDF()->items;
        $size = count($result->lists);
        $data['data'] = (object)['index' => 1, 'report' => $result, 'size' => $size, 'tem' => $tem];
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Report_' . $result->bill_info->bill_no . '.xls"');
        $this->load->view('export_excel', $data);
    }
}
