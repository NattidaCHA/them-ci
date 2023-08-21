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
        $type = $this->config->item('type');
        $result = [];
        $this->data['days'] = [];
        $condition = [
            'dateSelect' => $this->input->get('dateSelect'),
            'startDate' => $this->input->get('startDate'),
            'endDate' => $this->input->get('endDate'),
            'cus_no' => $this->input->get('customer'),
            'type' => $type
        ];

        setcookie("dateSelect", $this->input->get('dateSelect'), time() + 3600, "/");
        setcookie("startDate", $this->input->get('startDate'), time() + 3600, "/");
        setcookie("endDate", $this->input->get('endDate'), time() + 3600, "/");
        setcookie("cus_no", $this->input->get('customer'), time() + 3600, "/");
        setcookie("type", $this->input->get('type'), time() + 3600, "/");


        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        $result = $this->model_invoice->getInvoice($condition);

        $this->data['lists'] = $result;
        $this->data['childLists'] = !empty($result) ? $this->model_invoice->getCustomerChain($result) : [];
        $this->data['selectDays'] = $this->model_system->getDateSelect();
        $this->data['types'] = $this->model_system->getTypeBusiness();
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->loadAsset(['dataTables', 'datepicker', 'select2']);
        $this->view('search_invoice');
    }

    public function detail($id)
    {
        $result = [];
        if (!empty($id)) {
            $result = $this->model_invoice->getDetailCustomer($id);
        }

        $this->data['main_id'] = $id;
        $this->data['lists'] = $result;
        $this->view('invoice_detail');
    }


    public function create($cus_main)
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
                    'created_date' => date("Y-m-d")
                ];

                $this->model_invoice->createInvoice($data);
                $report = $this->model_invoice->getReportUuid($uuid);

                if (!empty($report)) {
                    foreach ($res as $val) {
                        $data = [
                            'uuid' => genRandomString(16),
                            'bill_no' => $report->bill_no,
                            'bill_id' => $report->uuid,
                            'macctdoc' => $val,
                            'cus_no' => $key,
                            'cus_main' => $cus_main,
                        ];

                        $this->model_invoice->createDetailInvoice($data);
                    }

                    $output['status'] = 200;
                    $output['data'] = $report;
                }
            }
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    public function ramdomBillNo($main_id)
    {

        $random = $this->runNumber($main_id);
        $num = 'N' . substr($main_id, 6) . substr(date('Ymd'), 2) . $random;
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


    public function report()
    {
        $condition = [
            'created_date' => $this->input->get('created_date'),
            'bill_no' => $this->input->get('bill_no'),
            'cus_no' => $this->input->get('customer')
        ];

        $result = $this->model_invoice->getBill($condition);

        $billNos = $this->model_invoice->getBillNo();
        // var_dump($billNos);
        $this->data['lists'] = $result;
        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->loadAsset(['dataTables', 'datepicker', 'select2']);
        $this->view('report_lists');
    }

    public function pdf()
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

        $html = $this->load->view('report_pdf', 55555, TRUE);
        $title = 'Invoice YouTube Revenue ';
        $name = 'Invoice_YouTube_Revenue_';

        $mpdf->SetTitle($title);
        $mpdf->WriteHTML($html);
        $mpdf->Output($name.'.pdf', 'I');
    }

}
