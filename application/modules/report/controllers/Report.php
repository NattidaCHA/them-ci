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
        $tels = [];
        $emails = [];
        $receiveCall = [];
        $isCall = [];

        $condition = [
            'created_date' => $created_date,
            'bill_no' =>  $bill_no,
            'cus_no' => $cus_no
        ];

        $result = $this->model_report->getBill($condition);
        foreach ($result as $res) {
            $lists[$res->uuid] = $res;
            $tels[$res->uuid] = !empty($this->model_report->getTelById($res->cus_no, $res->cus_main)) ? $this->model_report->getTelById($res->cus_no, $res->cus_main)[$res->cus_no] : [];
            $isCall[$res->uuid] = $this->findObjectIsCall($tels[$res->uuid]);
            $emails[$res->uuid] = !empty($this->model_report->getEmailById($res->cus_no, $res->cus_main)) ? $this->model_report->getEmailById($res->cus_no, $res->cus_main)[$res->cus_no] : [];
            $receiveCall = $this->model_report->getCfCall($res->uuid);
        }

        // echo '<pre>';
        // var_dump($tels);
        // echo '</pre>';
        $billNos = $this->model_report->getBillNo();
        $cfCall = $this->model_report->getCfCall();
        $this->data['lists'] = $lists;
        $this->data['tels'] = $tels;
        $this->data['emails'] = $emails;
        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->data['created_date'] = $created_date;
        $this->data['bill_no'] = $bill_no;
        $this->data['receives'] = $receiveCall;
        $this->data['is_call'] = $isCall;
        $this->data['cus_no'] = $cus_no;
        $this->data['cf_call'] = !empty($cfCall) ? $cfCall : [];
        $this->data['page_header'] = 'Report';
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
        $content = $this->genPDF($params['uuid'], 'email');
        $data['data'] = (object)['end_date' => date('d/m/Y', strtotime($params['end_date']))];
        $genEmail =  $this->model_report->getEmailById($params['cus_no'], $params['cus_main']);
        $emails = [];


        if (!empty($genEmail[$params['cus_no']])) {

            foreach ($genEmail[$params['cus_no']] as $val) {
                array_push($emails, $val->email);
            }


            $mesg = $this->load->view('email_tem', $data, TRUE);
            $from_email = "nan_zen0003@hotmail.com";
            // $from_email = "nattidac@scg.com";
            $this->load->library('email');
            $this->email->clear();
            $this->email->from($from_email, 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า');
            $this->email->to($emails);
            $this->email->subject('เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ ' . date('d/m/Y', strtotime($params['end_date'])));
            $this->email->message($mesg);
            $this->email->attach($content, 'attachment', 'Report_' . $params['bill_no'] . '.pdf', 'application/pdf');
            $result =  $this->email->send();


            if ($result) {
                $output['status'] = 200;
                $output['data'] = (object)['data' => $params, 'email' => $emails];
            } else {
                $output['status'] = 500;
                $output['msg'] = $this->email->print_debugger();
                $output['error'] = $this->email->print_debugger();
            }
        } else {
            $output['status'] = 204;
            $output['msg'] = 'No email';
            $output['data'] = false;
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
            if (empty($params['uuid'])) {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['report_uuid'] = $val;
                    $data['uuid'] = genRandomString(16);
                    $data['cf_call'] = !empty($params['cf_call'][$key]) ? 1 : 0;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $data['cus_main'] = $params['cus_main'][$key];
                    $data['tel'] = $params['tel'][$key];
                    $this->model_report->createCfCall($data);
                }
            } else {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['report_uuid'] = $val;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $data['cus_main'] = $params['cus_main'][$key];
                    $data['tel'] = $params['tel'][$key];

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
                        $data['uuid'] = $params['uuid'][$key];
                        $this->model_report->updateCfCall($params['uuid'][$key], $data);
                    } else {
                        $data['uuid'] = genRandomString(16);
                        $this->model_report->createCfCall($data);
                    }
                }
            }



            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    function findObjectIsCall($data)
    {

        foreach ($data as $element) {
            if ($element->is_call == 1) {
                return true;
                break;
            }
        }

        return false;
    }

    function genPDF($uuid, $type)
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
        }

        $title = 'Report_' . $result->bill_info->bill_no;
        $name = 'Report_' . $result->bill_info->bill_no;
        $mpdf->SetTitle($title);
        $footer = $this->load->view('footer_pdf', $data, TRUE);
        $mpdf->WriteHTML($footer);

        if ($type == 'email') {
            return $mpdf->Output($name, 'S');
        }

        return $mpdf->Output($name . '.pdf', 'I');
    }
}
