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
        $condition = [
            'created_date' => $this->input->get('created_date'),
            'bill_no' => $this->input->get('bill_no'),
            'cus_no' => $this->input->get('customer')
        ];

        $result = $this->model_report->getBill($condition);

        $billNos = $this->model_report->getBillNo();
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
}
