<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Report extends MY_Controller
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
        $this->load->model('model_report');
        $this->load->model('model_system');
    }

    public function index()
    {
        $search = $this->config->item('fullSearch');
        $created_date = !empty($this->input->post('created_date')) ? $this->input->post('created_date') : '';
        $bill_no = !empty($this->input->post('bill_no')) ? $this->input->post('bill_no') : '';
        // $bill_no = NULL;
        $searchLists = !in_array($this->CURUSER->cus_no, $search) ? $this->model_system->findeCustomersearch($this->CURUSER->cus_no) : $this->model_system->getCustomerAll()->items;
        $cus_no = NULL;
        $table = $this->model_system->getPageIsShow()->items;

        if (in_array($this->CURUSER->cus_no, $search)) {
            if (!empty($this->input->post('customer'))) {
                $cus_no = implode(',', $this->input->post('customer'));
            }
        } else {
            $sendto = [];
            array_push($sendto, $this->CURUSER->cus_no);
            if ($this->CURUSER->type == 'main') {
                $isCheck = $this->model_system->checkSendtoMain($this->CURUSER->cus_no)->items;
                foreach ($isCheck as $val) {
                    if (!in_array($val->cus_no, $sendto)) {
                        array_push($sendto, $val->cus_no);
                    }
                }
            }

            $cus_no = implode(',', $sendto);
        }

        $billNos = $this->model_report->getBillNo()->items;

        $this->data['billNos'] = !empty($billNos) ? $billNos : [];
        $this->data['customers'] = $searchLists;
        $this->data['created_date'] = $created_date;
        $this->data['bill_no'] = $bill_no;
        $this->data['cus_no'] = $cus_no;
        $this->data['fullSearch'] = $search;
        $this->data['page_header'] = 'Report';
        $this->data['table'] = $table['report'];
        $this->data['info'] = $this->CURUSER;
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
        $email = $this->genEmail($params,'report');
        if ($email->status == 200) {
            $output['status'] = 200;
            $output['data'] = (object)['data' => $email->data, 'email' => $email->email];
        } else if ($email->status == 204) {
            $output['status'] = 204;
            $output['data'] = (object)['data' => false, 'msg' => 'No email'];
        } else {
            $output['status'] = 500;
            $output['msg'] = $email->msg;
            $output['error'] = $email->error;
        }
        // return $this->genEmail($params);
        //     require_once  './vendor/autoload.php';
        //     $mail = new PHPMailer(true);
        //     $from_email = "nan_zen0003@hotmail.com";

        //     $output = ['status' => 500, 'msg' => 'Can not send email !'];
        //     $params = $this->input->post();


        //     if (!empty($params)) {
        //         $cusType = $this->model_system->findCustomerById($params['cus_no'])->items;
        //         $emails =  $this->model_report->genEmail($params['cus_no'], $params['cus_main']);
        //         $reportChild = [];
        //         $content = $this->genPDF($params['uuid'], 'email');
        //         $data['data'] = (object)['end_date' => date('d/m/Y', strtotime($params['end_date']))];
        //         if ($cusType->type == 'main') {
        //             $childs = $this->model_report->checkChildSendto($params['cus_no'], $params['cus_main'])->items;
        //             foreach ($childs as $child) {
        //                 if ($params['cus_no'] != $child->cus_no) {
        //                     $res =  $this->model_report->getReportChildList($child->cus_no, $params['created_date'])->items;
        //                     if (!empty($res)) {
        //                         array_push($reportChild, $res);
        //                     }
        //                 }
        //             }
        //         }

        //         if (!empty($emails[$params['cus_no']])) {
        //             try {
        //                 $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        //                 $mail->isSMTP();
        //                 $mail->Host       = 'smtp.office365.com';
        //                 $mail->SMTPAuth   = true;
        //                 $mail->Username   = $from_email;
        //                 $mail->Password   = '!Ohsehun1228';
        //                 $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //                 $mail->Port       = 587;
        //                 $mail->SMTPSecure = 'tls';
        //                 $mail->Mailer = "smtp";
        //                 $mail->IsSMTP();
        //                 $mail->Debugoutput = 'error_log';
        //                 $mail->CharSet = 'utf-8';
        //                 $mail->SMTPOptions = array(
        //                     'ssl' => array(
        //                         'verify_peer' => false,
        //                         'verify_peer_name' => false,
        //                         'allow_self_signed' => true
        //                     )
        //                 );

        //                 $mesg = $this->load->view('email_tem', $data, TRUE);
        //                 // $from_email = "nattidac@scg.com";
        //                 $mail->setFrom($from_email, 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า');
        //                 foreach ($emails as $res) {
        //                     $mail->addAddress($res);
        //                 }


        //                 $mail->isHTML(true);
        //                 $mail->Subject = 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ ' . date('d/m/Y', strtotime($params['end_date']));
        //                 $mail->Body    = $mesg;
        //                 $mail->addStringAttachment($content, 'Report_' . $params['bill_no'] . '.pdf', 'base64', 'application/pdf');

        //                 if ($cusType->type == 'main') {
        //                     foreach ($reportChild as $res) {
        //                         $contentChild = $this->genPDF($res->uuid, 'email');
        //                         $mail->addStringAttachment($contentChild, 'Report_' . $res->bill_no . '.pdf', 'base64', 'application/pdf');
        //                     }
        //                 }
        //                 $mail->send();
        //                 $this->model_report->updateEmail($params['uuid']);
        //                 $output['status'] = 200;
        //                 $output['data'] = (object)['data' => $params, 'email' => $emails];
        //             } catch (Exception $e) {
        //                 $output['status'] = 500;
        //                 $output['msg'] = $mail->ErrorInfo;
        //                 $output['error'] = $mail->ErrorInfo;
        //             }
        //         } else {
        //             $output['status'] = 204;
        //             $output['msg'] = 'No email';
        //             $output['data'] = false;
        //         }
        //     }

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

            if (!empty($params['is_receive_bill'])) {
                $this->model_report->updateReceiveBill($params['is_receive_bill'], [1]);
            } else {
                $this->model_report->updateReceiveBill($params['report_uuid'][0], [0]);
            }

            if (empty($params['uuid'])) {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['cf_call'] = !empty($params['cf_call'][$key]) ? 1 : 0;
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';
                    $column = [genRandomString(16), $val, $params['cus_main'][$key], $params['tel'][$key], $data['cf_call'], $data['receive_call']];
                    $this->model_report->createCfCall($column);
                }
            } else {
                foreach ($params['report_uuid'] as $key => $val) {
                    $data['receive_call'] = !empty($params['receive_call'][$key]) ? $params['receive_call'][$key] : '';

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
                        $column = [$data['cf_call'], $data['receive_call']];
                        $this->model_report->updateCfCall($params['uuid'][$key],  $column);
                    } else {
                        $column = [genRandomString(16), $val, $params['cus_main'][$key], $params['tel'][$key], $data['cf_call'], $data['receive_call']];
                        $this->model_report->createCfCall($column);
                    }
                }
            }



            $output['status'] = 200;
            $output['data'] = $params;
        }

        $output['source'] = $params;
        $this->responseJSON($output);
    }

    // function genPDF($uuid, $type)
    // {

    //     require_once  './vendor/autoload.php';

    //     $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    //     $fontDirs = $defaultConfig['fontDir'];

    //     $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    //     $fontData = $defaultFontConfig['fontdata'];

    //     $mpdf = new \Mpdf\Mpdf([
    //         'fontDir' => array_merge($fontDirs, ['./assets/fonts']),
    //         'fontdata' => $fontData + [
    //             'sarabun' => [
    //                 'R' => 'THSarabunNew.ttf',
    //                 'I' => 'THSarabunNew Italic.ttf',
    //                 'B' =>  'THSarabunNew Bold.ttf',
    //             ]
    //         ],
    //         'default_font' => 'sarabun'
    //     ]);

    //     $result = $this->model_report->genPDF($uuid);


    //     foreach ($result->lists as $key => $res) {
    //         $result->lists = $res;
    //         $size = count($result->lists) > 1 ? 40 : 40;
    //         if ($key == 1) {
    //             $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size];
    //             $html = $this->load->view('report_pdf', $data, TRUE);
    //             $mpdf->WriteHTML($html);
    //         } else {
    //             $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size];
    //             $key = $this->load->view('table_pdf', $data, TRUE);
    //             $mpdf->WriteHTML($key);
    //         }
    //     }

    //     $title = 'Report_' . $result->bill_info->bill_no;
    //     $name = 'Report_' . $result->bill_info->bill_no;
    //     $mpdf->SetTitle($title);
    //     $footer = $this->load->view('footer_pdf', $data, TRUE);
    //     $mpdf->WriteHTML($footer);

    //     if ($type == 'email') {
    //         return $mpdf->Output($name, 'S');
    //     }

    //     return $mpdf->Output($name . '.pdf', 'I');
    // }

    public function listReport()
    {
        $result = [];
        $total_filter = 0;
        $this->setPagination();
        $this->setSearch();
        $this->setCondition();
        $this->queryCondition['page'] = $this->page;
        $this->queryCondition['limit'] = $this->limit;

        $params = $this->input->get();

        if (!empty($params)) {
            if (!empty($params['cus_no'])) {
                $this->queryCondition['cus_no'] = explode(',', $params['cus_no']);
            }

            if (!empty($params['created_date'])) {
                $this->queryCondition['created_date'] = $params['created_date'];
            }
            if (!empty($params['bill_no'])) {
                $this->queryCondition['bill_no'] = $params['bill_no'];
            }
        }

        $total = 0;

        if ($apiData = $this->model_report->getBillTb($this->queryCondition)) {
            if (empty($apiData)) {
                $this->responseJSON(['error' => 'Api Error']);
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


    public function genCfCall($uuid, $cus_no)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];


        if (!empty($uuid)) {
            $lists = $this->model_report->genCfCall($uuid, $cus_no);

            if (!empty($lists)) {
                $result['status'] = 200;
                $result['msg'] = 'OK';
                $result['data'] = $lists;
            } else {
                $result['status'] = 204;
                $result['msg'] = 'empty data';
                $result['data'] = false;
            }
        }

        $this->responseJSON($result);
    }


    public function genBill($uuid)
    {
        $result = ['status' => 500, 'msg' => 'Can not check data !'];

        if (!empty($uuid)) {
            $lists = $this->model_report->getBillById($uuid)->items;

            if (!empty($lists)) {
                $result['status'] = 200;
                $result['msg'] = 'OK';
                $result['data'] = $lists;
            } else {
                $result['status'] = 204;
                $result['msg'] = 'empty data';
                $result['data'] = false;
            }
        }

        $this->responseJSON($result);
    }
}
