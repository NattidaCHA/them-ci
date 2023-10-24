<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MY_Controller extends CI_Controller
{

    /* Global Variable */
    public $theme = 'default';
    public $layout = 'global';
    public $partials = array('header', 'sidebar', 'topbar', 'footer');
    public $data = array();
    public $CURUSER = NULL;
    public $conn = NULL;
    public $http = ((ENVIRONMENT == 'development') ? '' : '/invoicenotification');
    public $tableAllowLog = ('uuid, page,action,detail,created_date,created_by,updated_date,updated_by,url');


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('model_system');
        $this->load->model('report/model_report');
        $this->load->model('invoice/model_invoice');


        // var_dump($pdo->db->DATABASE);
        // exit;
        // var_dump(self::getSiteCookie());
        // $memberLogon = self::getSiteCookie();
        // if (!empty($memberLogon)) {
        //     if ($memberLogon->expire < date('Y-m-d H:i:s')) {
        //         self::clearSiteCookie();
        //     } else {
        //         $this->load->database();
        //         $this->load->model('member/model_member');
        //         if ($logon = $this->model_member->getUser($memberLogon->member_id)) {
        //             if ($logon->status == 1) {
        //                 $this->CURUSER = $this->model_member->getMember($logon->member_id);
        //                 $this->CURUSER->session_expire = $memberLogon->expire;
        //             } else {
        //                 self::clearSiteCookie();
        //             }
        //         } else {
        //             $this->CURUSER = NULL;
        //         }
        //     }
        // } else if ($this->router->fetch_class() == 'access' && $this->input->method() == 'post') {
        //     $this->load->database();
        // }

        // if ($this->router->fetch_class() != 'access' && empty($this->CURUSER)) {
        //     $lastPath = str_replace(site_url(), '', current_url());
        //     if ($this->input->is_ajax_request()) {
        //         $this->responseJSON([
        //             'status' => 401,
        //             'error' => 'Not Authorized',
        //             'msg' => 'เซสชั่นระบบของท่านหมดอายุ  กรุณาเข้าสู่ระบบใหม่อีกครั้ง'
        //         ]);
        //     }
        //     redirect('access?back_url=' . rawurlencode($lastPath));
        // }
        // $this->load->database();

        // var_dump(ENVIRONMENT);

        $this->connect();
        // $this->load->database();

        //getDateSelec
        // var_dump($this->conn);
        //  var_dump($this->model_system->checkSendtoChild('0002000080')->items);
        $this->CURUSER = $this->model_system->findCustomerById('0000000281')->items;
        // var_dump($this->CURUSER);

        // var_dump($this->CURUSER);
        // $this->CURUSER->cus_name = 'บจ.ป.กวิน';0002000082
        //0002000080
        //0000000281
        // Default Site Config
        $this->data = [
            'site_name' => $this->config->item('site_name'),
            'site_thumb' => $this->config->item('site_thumb'),
            'site_author' => $this->config->item('site_author'),
            'theme' => $this->theme,
            'http' => $this->http
        ];
    }


    public final function view($view_file, $data = NULL, $layout = NULL)
    {
        if (empty($layout)) {
            $layout = $this->layout;
        }

        $data =  (empty($data)) ? $this->data : array_merge($this->data, $data);

        if (!empty($this->partials)) {
            foreach ($this->partials as $p) {
                $this->template->set_partial($p);
            }
        }

        $this->template->set_layout($layout, $view_file, $data);
        $this->template->build();
    }


    protected function apiDefaultOutput()
    {
        return ['status' => 500, 'error' => 'Default Error', 'msg' => 'Something went wrong !'];
    }


    protected function responseJSON($result, $JSONP = FALSE)
    {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0');
        header("Pragma: no-cache");

        if (!empty($result['status'])) {
            if ($result['status'] == 200) {
                unset($result['error'], $result['msg']);
            }
        }

        $state = $this->input->get('_');
        $callback =  $this->input->get('callback');

        if ($JSONP === TRUE || (!empty($callback) && !empty($state))) {
            header("Content-type: application/javascript");
            $callback = (empty($callback)) ? '_' : $callback;
            echo $callback . '(' . json_encode($result) . ')';
        } else {
            header('Content-Type: application/json');
            echo json_encode($result);
        }
        exit;
    }


    protected function responseDataTable($result, $total, $total_filer, $add_on = [], $JSONP = FALSE)
    {
        $draw = (int) $this->input->get('draw');
        $output = [
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total_filer,
            "data" => $result,
            "addon" => $add_on
        ];
        $this->responseJSON($output, $JSONP);
    }


    protected function loadAsset($module)
    {
        if (is_array($module)) {
            foreach ($module as $m) {
                $this->asset_module($m);
            }
        } else {
            $this->asset_module($module);
        }
    }


    private function asset_module($module)
    {
        switch ($module) {
            case 'parsley':
                $this->data['js'][] = js_asset('parsleyjs/parsley.min.js', TRUE, 'assets/vendor');
                break;
            case 'ajaxBrowseUpload':
                $this->data['js'][] = js_asset('ajaxBrowseUpload.js', TRUE);
                break;
            case 'vanilla_datepicker':
                $this->data['css'][] = css_asset('vanillajs-datepicker/css/datepicker.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('vanillajs-datepicker/css/datepicker-bs4.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('vanillajs-datepicker/css/datepicker-bs5.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('vanillajs-datepicker/css/datepicker-bulma.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('vanillajs-datepicker/css/datepicker-foundation.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('vanillajs-datepicker/js/datepicker.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('vanillajs-datepicker/js/datepicker-full.min.js', TRUE, 'assets/vendor');
                break;
            case 'dataTables':
                $this->data['css'][] = css_asset('datatables/dataTables.bootstrap5.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('datatables/responsive.dataTables.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('datatables/buttons.dataTables.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('datatables/buttons.bootstrap5.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/jquery.dataTables.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/dataTables.bootstrap5.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/dataTables.responsive.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/dataTables.fixedHeader.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/dataTables.buttons.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datatables/buttons.bootstrap5.min.js', TRUE, 'assets/vendor');
                break;
            case 'select2':
                // $this->data['css'][] = css_asset('select2/bootstrap.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('select2/select2.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('select2/select2-bootstrap-5-theme.rtl.min.css', TRUE, 'assets/vendor');
                $this->data['css'][] = css_asset('select2/select2-bootstrap-5-theme.min.css', TRUE, 'assets/vendor');
                // $this->data['js'][] = js_asset('select2/jquery.slim.min.js', TRUE, 'assets/vendor');
                // $this->data['js'][] = js_asset('select2/bootstrap.bundle.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('select2/select2.min.js', TRUE, 'assets/vendor');
                break;
            case 'mask':
                $this->data['js'][] = js_asset('jquery-mask-plugin/jquery.mask.min.js', TRUE, 'assets/vendor');
                break;
            case 'datepicker':
                $this->data['css'][] = css_asset('datepicker/bootstrap-datepicker.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('datepicker/bootstrap-datepicker.min.js', TRUE, 'assets/vendor');
                break;
            case 'moment':
                $this->data['js'][] = js_asset('moment/moment.min.js', TRUE, 'assets/vendor');
                break;
        }
        return $this;
    }

    protected function curlRemote($url, $param = NULL, $method = 'POST', $json = FALSE)
    {
        $refer = site_url('');
        $method = strtoupper($method);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        if (!empty($param)) {
            if ($json === TRUE) {
                $json_string = json_encode($param);
                curl_setopt(
                    $curl,
                    CURLOPT_HTTPHEADER,
                    ['Content-Type: application/json', 'Content-Length: ' . strlen($json_string)]
                );
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
            }
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        } else {
            if (in_array($method, ['DELETE', 'PATCH', 'PUT'])) {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            }
        }
        curl_setopt($curl, CURLOPT_REFERER, $refer);
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    protected function addSystemLog($params)
    {
        $sql = "INSERT INTO " . LOG . ' (' . $this->tableAllowLog . ") VALUES (?, ?, ?, ?, ?,?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    protected function setSiteCookie($member)
    {
        if (is_array($member)) {
            $member = (object) $member;
        }
        $member_data = [
            'id' => $member->member_id,
            'display' => $member->display_name,
            'email' => $member->email
        ];
        $expire = strtotime(date('Y-m-d ' . COOKIE_EXPIRE));
        setcookie('edmuid', _encodeData($member->email, PRIVATE_KEY), $expire, '/');
        setcookie('edmus', _encodeData(json_encode($member_data), PRIVATE_KEY), $expire, '/');
        setcookie('edmck', _encodeData(time(), PRIVATE_KEY), $expire, '/');
    }


    protected function getSiteCookie()
    {
        $result = NULL;
        if (!empty($_COOKIE['edmuid']) && !empty($_COOKIE['edmus']) && !empty($_COOKIE['edmck'])) {
            $email = _decodeData($_COOKIE['edmuid'], PRIVATE_KEY);
            $member = json_decode(_decodeData($_COOKIE['edmus'], PRIVATE_KEY));
            $added = _decodeData($_COOKIE['edmck'], PRIVATE_KEY);
            if (json_last_error() === JSON_ERROR_NONE && !empty($member)) {
                if ($email != $member->email) {
                    self::clearSiteCookie();
                    return $result;
                }
                $result = new stdClass();
                $result->member_id = $member->id;
                $result->display_name = $member->display;
                $result->email = $member->email;
                $result->added = date('Y-m-d H:i:s', ((int) $added));
                $result->expire = date('Y-m-d ' . COOKIE_EXPIRE, strtotime($result->added));
            }
        }
        return $result;
    }


    protected function clearSiteCookie()
    {
        setcookie('edmuid', '', time() - 3600, '/');
        setcookie('edmus', '', time() - 3600, '/');
        setcookie('edmck', '', time() - 3600, '/');
    }


    protected function checkPermission($check_slug, $level = 'view', $redirect = FALSE)
    {
        $result = checkPermission($this->CURUSER->permission, $check_slug, $level);
        if ($redirect && !$result) {
            redirect('/dashboard?permission=' . rawurlencode('/' . str_replace(base_url(), '', current_url())));
        }
        return $result;
    }

    protected function connect()
    {
        require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . $this->http . '/pdo/db.php');
        $config = [];
        $config = ["Database" => $pdo->db->DATABASE, "UID" => $pdo->db->UID, "PWD" => $pdo->db->PWD, "ReturnDatesAsStrings" => true, "CharacterSet" => "UTF-8"];

        $this->conn = sqlsrv_connect($pdo->db->SERVERNAME,  $config);

        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        return $this->conn;
    }

    protected function genPDF($uuid, $type)
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

        $tem = $this->model_system->getTemPDF()->items;

        foreach ($result->lists as $key => $res) {
            $result->lists = $res;
            $size = count($result->lists) > 1 ? 50 : 40;
            if ($key == 1) {
                $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size, 'tem' => $tem];
                $html = $this->load->view('report/report_pdf', $data, TRUE);
                $mpdf->WriteHTML($html);
            } else {
                $data['data'] = (object)['index' => $key, 'report' => $result, 'size' => $size, 'tem' => $tem];
                $key = $this->load->view('report/table_pdf', $data, TRUE);
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

        if ($type == 'excel') {
            return $mpdf->Output($name . '.pdf', 'F');
        }

        return $mpdf->Output($name . '.pdf', 'I');
    }


    protected function genEmail($params, $page, $checkMain = FALSE)
    {
        require_once  './vendor/autoload.php';
        require($_SERVER['DOCUMENT_ROOT'] . $this->http . '/pdo/email.php');

        $mail = new PHPMailer(true);
        if (!empty($params)) {
            $cusType = $this->model_system->findCustomerById($params['cus_no'])->items;
            $emails =  $this->model_report->genEmail($params['cus_no'], $cusType->is_email);
            if ($page == 'invoice' && !empty($checkMain) && $cusType->type == 'child' && !empty($emails[$params['cus_no']])) {
                $k = array_search($params['cus_main'], array_keys($emails));
                $emails = array_slice($emails, $k + 1);
            }

            $reportChild = [];
            $content = $this->genPDF($params['uuid'], 'email');

            $data['data'] = (object)['end_date' => date('d/m/Y', strtotime($params['end_date'])), 'uuid' => $params['uuid'], 'http' => $this->http];
            if ($cusType->type == 'main') {
                $childs = $this->model_report->checkChildSendto($params['cus_no'], $params['cus_main'])->items;
                foreach ($childs as $child) {
                    if ($params['cus_no'] != $child->cus_no) {
                        $res =  $this->model_report->getReportChildList($child->cus_no, $params['created_date'])->items;
                        if (!empty($res)) {
                            array_push($reportChild, $res);
                        }
                    }
                }
            }

            if ($page == 'invoice' && empty($emails[$params['cus_no']]) && $cusType->type == 'child' &&  !empty($checkMain) && !empty($emails[$params['cus_main']])) {
                $this->model_report->updateEmail($params['uuid']);
                return (object)['status' => 200, 'data' => $params, 'email' => $emails];
            }

            if (!empty($cusType->is_email)) {
                if (!empty($emails[$params['cus_no']]) || !empty($emails[$params['cus_main']])) {
                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        $mail->isSMTP();
                        $mail->Host       = $pdo->email->host;
                        // $mail->Host       = 'soms.scg.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = $pdo->email->username;
                        $mail->Password   = $pdo->email->password;
                        // $mail->Password   = 'Year@2023';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = $pdo->email->port;
                        // $mail->Port       = 25;
                        // $mail->SMTPSecure = 'ssl';
                        $mail->SMTPSecure = $pdo->email->SMTPSecure;
                        $mail->Mailer = "smtp";
                        $mail->IsSMTP();
                        $mail->Debugoutput = 'error_log';
                        $mail->CharSet = 'utf-8';
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );

                        $mesg = $this->load->view('report/email_tem', $data, TRUE);
                        $mail->setFrom($pdo->email->username, 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า');

                        // foreach ($emails as $res) {
                        //     $mail->addAddress($res->email);
                        //'npibs_pipess01@scg.com', 'phrueksp@scg.com', 'sakchasa@scg.com', 'npibs_pipess01@scg.com',, 'npibs_pipess01@scg.com'
                        // }
                        foreach (['nattida.ncha@gmail.com', 'nattidac@scg.com'] as $res) {
                            $mail->addAddress($res);
                        }

                        $mail->isHTML(true);
                        $mail->Subject = 'เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ ' . date('d/m/Y', strtotime($params['end_date']));
                        $mail->Body    = $mesg;
                        $mail->addStringAttachment($content, 'Report_' . $params['bill_no'] . '.pdf', 'base64', 'application/pdf');
                        if ($cusType->type == 'main') {
                            foreach ($reportChild as $res) {
                                $contentChild = $this->genPDF($res->uuid, 'email');
                                $mail->addStringAttachment($contentChild, 'Report_' . $res->bill_no . '.pdf', 'base64', 'application/pdf');
                            }
                        }
                        $mail->send();
                        $this->model_report->updateEmail($params['uuid']);
                        return (object)['status' => 200, 'data' => $params, 'email' => $emails];
                    } catch (Exception $e) {
                        return (object)['status' => 500, 'msg' =>  $mail->ErrorInfo, 'error' => $mail->ErrorInfo];
                    }
                } else {
                    return (object)['status' => 204, 'data' => false, 'msg' => 'ไม่พบอีเมลของรหัสลูกค้า ' . $cusType->cus_no];
                }
            } else {
                return (object)['status' => 200, 'data' => 'ไม่ต้องส่งใบแจ้งเตือนผ่านอีเมล'];
            }
        }

        return (object)['status' => 500, 'msg' => 'API Error', 'error' => 'API Error'];
    }

    protected function ramdomBillNo($main_id)
    {

        $random = $this->runNumber($main_id);
        $num = '8' . substr($main_id, 6) . substr(date('Ymd'), 2) . $random;
        return $num;
    }


    protected function runNumber($main_id)
    {
        $number = '00001';
        $report = $this->model_invoice->getReportId($main_id)->items;
        if (!empty($report)) {
            if ($main_id == $report->cus_main && date('Y') == date('Y', strtotime($report->created_date))) {
                $number = $this->checkNum(substr($report->bill_no, 13));
            }
        }

        return $number;
    }

    protected function checkNum($no)
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

    protected function switchCheck($no)
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

    protected function genType($res)
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
} // End of class
