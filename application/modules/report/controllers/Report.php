<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_report');
        $this->load->model('model_system');
    }

    public function index()
    {
        $created_date = !empty($this->input->get('created_date')) ? $this->input->get('created_date') : '';
        $bill_no = !empty($this->input->get('bill_no')) ? $this->input->get('bill_no') : '';
        $cus_no = !empty($this->input->get('customer')) ? $this->input->get('customer') : '';
        $lists = [];

        $condition = [
            'created_date' => $created_date,
            'bill_no' =>  $bill_no,
            'cus_no' => $cus_no
        ];

        $result = $this->model_report->getBill($condition);
        foreach ($result as $res) {
            $lists[$res->uuid] = $res;
        }

        $billNos = $this->model_report->getBillNo();
        // var_dump($billNos);
        $this->data['lists'] = $lists;
        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->data['created_date'] = $created_date;
        $this->data['bill_no'] = $bill_no;
        $this->data['cus_no'] = $cus_no;
        $this->data['page_header'] = 'Report';
        $this->loadAsset(['dataTables', 'datepicker', 'select2', 'parsley']);
        $this->view('report_lists');
    }

    public function pdf($uuid)
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
            // }
        }

        // echo '<pre>';
        // var_dump($result->lists['total']);
        // echo '</pre>';
        // exit;
        $title = 'Report_' . $result->bill_info->bill_no;
        $name = 'Report_' . $result->bill_info->bill_no;
        $mpdf->SetTitle($title);
        $footer = $this->load->view('footer_pdf', $data, TRUE);
        $mpdf->WriteHTML($footer);
        $mpdf->Output($name . '.pdf', 'I');
    }

    public function email()
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
        $content = $mpdf->Output('', 'S');


        $from_email = "nan_zen0003@hotmail.com";
        // $from_email = "nattidac@scg.com";
        $this->load->library('email');
        $this->email->set_newline("\r\n");


        // $this->email->initialize($config);"http://notification.com/report/pdf";
        // $this->email->set_newline("\r\n");APPPATH.
        $pdfFilePath = 'http://notification.com/report/pdf';
        $this->email->from($from_email, 'Test email');
        $this->email->to('nattida.ncha@gmail.com');
        $this->email->subject('เทสอีเมล');
        $this->email->message('เทสส่งอีเมลงับๆ');
        $this->email->attach($content, 'attachment', 'report.pdf', 'application/pdf');
        $result =  $this->email->send();
        $this->email->clear($pdfFilePath);
        // $this->email->clear($pdfFilePath);

        echo $this->email->print_debugger();

        if ($result) {
            echo "Send";
        }
        // if ($this->email->send())
        //     $this->session->set_flashdata("email_ sent", "Congragulation Email Send Successfully.");
        // else
        //     $this->session->set_flashdata("email_sent", "You have encountered an error");
    }

    public function update()
    {
        $output = $this->apiDefaultOutput();
        $params = $this->input->post();

        if (!empty($params)) {
            $params['is_call'] = !empty($params['is_call']) ? 1 : 0;
            $this->model_report->update($params['uuid'], $params);

            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }
}
