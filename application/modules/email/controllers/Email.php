<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Email extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['form', 'url']);
    }


    public function index()
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
        $result=  $this->email->send();
        $this->email->clear($pdfFilePath);
        // $this->email->clear($pdfFilePath);

        echo $this->email->print_debugger();

        if($result) {
            echo "Send";
            unlink($pdfFilePath); //for delete generated pdf file. 
        }
        // if ($this->email->send())
        //     $this->session->set_flashdata("email_sent", "Congragulation Email Send Successfully.");
        // else
        //     $this->session->set_flashdata("email_sent", "You have encountered an error");
    }


    public function send()
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.office365.com',
            'smtp_port' => '587',
            'smtp_crypto' => 'tls',
            'smtp_user' => 'nattidac@scg.com',
            'smtp_pass' => 'Year@2023',
            'mailtype' => 'html',
            'priority' => 3,
            'newline' => '\r\n',
            'crlf' => '\r\n',
            'charset' => 'utf-8',
            'smtp_timeout' => 7,
            'wordwrap' => TRUE
        );
        $from_email = "nattidac@scg.com";
        //Load email library
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from($from_email, 'From Name');
        $this->email->to('nattida.ncha@gmail.com');
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');
        $this->email->send();

        echo $this->email->print_debugger();
        // if ($this->email->send())
        //     $this->session->set_flashdata("email_sent", "Congragulation Email Send Successfully.");
        // else
        //     $this->session->set_flashdata("email_sent", "You have encountered an error");
    }

}
