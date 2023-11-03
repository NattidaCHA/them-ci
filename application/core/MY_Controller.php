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
    public $conn = NULL;
    public $http = ((ENVIRONMENT == 'development') ? '' : '/invoicenotification');
    public $url = ((ENVIRONMENT == 'production') ? 'https://npismo.scg.com' : 'https://npismodev.scg.com');
    public $tableAllowLog = ('uuid, page,action,detail,created_date,created_by,updated_date,updated_by,url');
    public $tableAllowLogin = ('uuid, userid,cus_id,username,userdisplay_th,user_type,dep_id,dep_code,user_status,roleid,rolename,roletype,created_date');
    public $CURUSER = NULL;
    public $role = array();


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('model_system');
        $this->load->model('report/model_report');
        $this->load->model('invoice/model_invoice');
        $this->load->model('access/model_access');


        $memberLogon = self::getSiteCookie();
        if (!empty($memberLogon)) {
            if ($memberLogon->expires_in < date('Y-m-d H:i:s')) {
                self::clearSiteCookie();
                redirect($this->url);
            } else {
                $this->connect();
                if (empty($role)) {
                    $this->role = $this->model_system->getDepartment()->items;
                }

                if (empty($this->CURUSER)) {
                    $logon = $this->model_access->getUser($memberLogon->userid, $memberLogon->access_token);

                    if (empty($logon->error)) {
                        $this->CURUSER = $logon;
                        $this->CURUSER->user_cus = $this->findObjectUser($this->CURUSER->customer, $this->CURUSER->user[0]->cus_id);
                        $this->CURUSER->session_expire = $memberLogon->expires_in;

                        if (checkPermission('system', $this->CURUSER->user[0]->dep_id, $this->role)) {
                            echo '<script type="text/javascript">';
                            echo 'alert("ท่านไม่สามารถเข้าใช้ระบบได้");';
                            echo "window.location.href = '" . $this->url . "';";
                            echo '</script>';
                            self::clearSiteCookie();
                            setcookie('userid', '', time() - 28800, $this->http . '/');
                        }
                    } else {
                        echo '<script type="text/javascript">';
                        echo 'alert("ข้อมูลผู้ใช้งานไม่ถูกต้อง กรุณาตรวจสอบใหม่อีกครั้ง");';
                        echo "window.location.href = '" . $this->url . "';";
                        echo '</script>';
                        self::clearSiteCookie();
                        setcookie('userid', '', time() - 28800, $this->http . '/');
                    }
                }
            }
        } else if ($this->router->fetch_class() == 'access' && $this->input->method() == 'get') {
            $this->connect();
        }

        if ($this->router->fetch_class() != 'access' && empty($this->CURUSER)) {
            if (!empty($_COOKIE['userid'])) {
                $this->connect();
                if (empty($role)) {
                    $this->role = $this->model_system->getDepartment()->items;
                }
                $genToken = $this->model_access->genToken();
                if (!empty($genToken)) {
                    $this->setSiteCookie($genToken, rawurldecode($_COOKIE['userid']));
                    $member = $this->model_access->getUser(rawurldecode($_COOKIE['userid']), $genToken->access_token);

                    if (empty($member->error)) {
                        $this->CURUSER = $member;
                        $this->CURUSER->user_cus = $this->findObjectUser($this->CURUSER->customer, $this->CURUSER->user[0]->cus_id);
                        $this->CURUSER->session_expire = date('Y-m-d H:i:s', strtotime("+" . $genToken->expires_in . " sec"));
                        if (checkPermission('system', $this->CURUSER->user[0]->dep_id, $this->role)) {
                            echo '<script type="text/javascript">';
                            echo 'alert("ท่านไม่สามารถเข้าใช้ระบบได้");';
                            echo "window.location.href = '" . $this->url . "';";
                            echo '</script>';
                            self::clearSiteCookie();
                            setcookie('userid', '', time() - 28800, $this->http . '/');
                        }
                    } else {
                        echo '<script type="text/javascript">';
                        echo 'alert("ข้อมูลผู้ใช้งานไม่ถูกต้อง กรุณาตรวจสอบใหม่อีกครั้ง");';
                        echo "window.location.href = '" . $this->url . "';";
                        echo '</script>';
                        self::clearSiteCookie();
                        setcookie('userid', '', time() - 28800, $this->http . '/');
                    }
                } else {
                    redirect($this->url);
                }
            } else {
                redirect($this->url);
            }
        }

        // var_dump($this->CURUSER);
        // exit;
        // Default Site Config
        $this->data = [
            'site_name' => $this->config->item('site_name'),
            'site_thumb' => $this->config->item('site_thumb'),
            'site_author' => $this->config->item('site_author'),
            'theme' => $this->theme,
            'http' => $this->http,
            'CURUSER' => $this->CURUSER,
            'role' => $this->role
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

    protected function addSystemLog_Login($params)
    {

        $sql = "INSERT INTO " . LOG_IN . ' (' . $this->tableAllowLogin . ") VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?)";
        $res = sqlsrv_query($this->conn, $sql, $params);
        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    protected function setSiteCookie($genToken, $userid)
    {
        setcookie('access_token', $genToken->access_token, time() + $genToken->expires_in,  '/');
        setcookie('userid', $userid, time() + $genToken->expires_in, $this->http . '/');
        setcookie('refresh_token', $genToken->refresh_token, time() + $genToken->expires_in, '/');
        setcookie('app_id', $genToken->app_id, time() + $genToken->expires_in, '/');
        setcookie('expires_in', $genToken->expires_in, time() + $genToken->expires_in, '/');
        setcookie('token_type', $genToken->token_type, time() + $genToken->expires_in,  '/');
    }


    protected function getSiteCookie()
    {
        $result = NULL;
        if (!empty($_COOKIE['access_token']) && !empty($_COOKIE['refresh_token']) && !empty($_COOKIE['app_id']) && !empty($_COOKIE['expires_in'])) {
            $result = new stdClass();
            $result->access_token = $_COOKIE['access_token'];
            $result->userid = $_COOKIE['userid'];
            $result->refresh_token = $_COOKIE['access_token'];
            $result->app_id = $_COOKIE['app_id'];
            $result->expires_in = date('Y-m-d H:i:s', strtotime("+" . $_COOKIE['expires_in'] . " sec"));
            $result->token_type = $_COOKIE['token_type'];
        }
        return $result;
    }


    protected function clearSiteCookie()
    {
        setcookie('access_token', '', time() - 28800, $this->http . '/');
        setcookie('refresh_token', '', time() - 28800, $this->http . '/');
        setcookie('app_id', '', time() - 28800, $this->http . '/');
        setcookie('expires_in', '', time() - 28800, $this->http . '/');
        setcookie('token_type', '', time() - 28800, $this->http . '/');
    }


    // protected function checkPermission($page)
    // {
    //     $dep_id = 5;
    //     $result = false;
    //     switch ($page) {
    //         case 'การแจ้งเตือน':
    //             if (in_array($dep_id, $role['all'])) {
    //                 $result = true;
    //             }
    //             break;
    //         case 'รายงาน':
    //             if (in_array($dep_id, $role['all']) || in_array($dep_id,  $role['report'])) {
    //                 $result = true;
    //             }
    //             break;
    //         case 'ตั้งค่า':
    //             if (in_array($dep_id, $role['all'])) {
    //                 $result = true;
    //             }
    //             break;
    //     }
    //     return $result;
    // }

    protected function connect()
    {
        require(realpath($_SERVER["DOCUMENT_ROOT"]) . $this->http . '/pdo/db.php');
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

            $data['data'] = (object)['end_date' => date('d/m/Y', strtotime($params['mduedate'])), 'uuid' => $params['uuid'], 'http' => $this->http];
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
                        $mail->isSMTP();
                        $mail->Mailer = "smtp";
                        $mail->Host       =  '10.101.97.25'; //$pdo->email->host; //'10.101.97.25';
                        $mail->SMTPAuth   = false;
                        // $mail->Username   = $pdo->email->username;
                        // $mail->Password   = $pdo->email->password;
                        $mail->Port       = $pdo->email->port;
                        // $mail->SMTPSecure = $pdo->email->SMTPSecure;
                        // $mail->SMTPSecure = 'ssl';
                        // $mail->SMTPSecure = $pdo->email->SMTPSecure;SMTP::DEBUG_SERVER
                        $mail->Debugoutput = 'error_log';
                        $mail->SMTPDebug = 0;
                        $mail->CharSet = 'utf-8';
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );

                        $mesg = $this->load->view('report/email_tem', $data, TRUE);
                        $mail->setFrom($pdo->email->username, '(ทดสอบระบบ) เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า');

                        //'phrueksp@scg.com', 'nan_zen0003@hotmail.com', 'sakchasa@scg.com', 'npibs_pipess01@scg.com'
                        // foreach ($emails as $res) {
                        //     $mail->addAddress($res->email);SMTP::DEBUG_SERVER
                        //'npibs_pipess01@scg.com', 'phrueksp@scg.com', 'sakchasa@scg.com', 'npibs_pipess01@scg.com',, 'npibs_pipess01@scg.com'
                        // }, 'nattidac@scg.com'$pdo->email->username$pdo->email->username
                        //, 'phrueksp@scg.com', 'nan_zen0003@hotmail.com', 'sakchasa@scg.com', 'npibs_pipess01@scg.com'
                        foreach (['nattida.ncha@gmail.com', 'phrueksp@scg.com', 'nan_zen0003@hotmail.com', 'sakchasa@scg.com', 'npibs_pipess01@scg.com'] as $res) {
                            $mail->addAddress($res);
                        }

                        $mail->isHTML(true);
                        $mail->Subject = '(ทดสอบระบบ) เอกสารใบแจ้งเตือนครบกำหนดชำระค่าสินค้า Due วันที่ ' . date('d/m/Y', strtotime($params['mduedate']));
                        $mail->Body    = $mesg;
                        $mail->addStringAttachment($content, 'Report_' . $params['bill_no'] . '.pdf', 'base64', 'application/pdf');
                        if ($cusType->type == 'main') {
                            foreach ($reportChild as $res) {
                                $contentChild = $this->genPDF($res->uuid, 'email');
                                $mail->addStringAttachment($contentChild, 'Report_' . $res->bill_no . '.pdf', 'base64', 'application/pdf');
                            }
                        }
                        $mail->send();
                        // var_dump($mail->send());
                        $this->model_report->updateEmail($params['uuid']);
                        return (object)['status' => 200, 'data' => $params, 'email' => $emails];
                    } catch (Exception $e) {
                        // var_dump($mail->ErrorInfo);
                        return (object)['status' => 500, 'msg' =>  $mail->ErrorInfo, 'error' => $mail->ErrorInfo];
                    }
                } else {
                    return (object)['status' => 204, 'data' => false, 'msg' => 'ไม่พบอีเมลของรหัสลูกค้า ' . $cusType->cus_no];
                }
            } else {
                return (object)['status' => 204, 'data' => $cusType->cus_no, 'msg' => 'ส่ง Fax'];
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

    function findObjectUser($customer, $cus_id)
    {

        foreach ($customer as $element) {
            if ($cus_id == $element->cus_id) {
                return $element;
            }
        }

        return false;
    }


    // protected function checkPermission($page, $dep_id) 
    // {
    //     $dep_id = 5;
    //     // $role = $this->config->item('day');master_department
    //     $role =  $this->model_system->getDepartment();
    //     $result = false;
    //     switch ($page) {
    //         case 'การแจ้งเตือน':
    //             $result = in_array($dep_id, [3, 5]);
    //             break;
    //         case 'รายงาน':
    //             $result = in_array($dep_id, [1, 2, 3, 5]);
    //             break;
    //         case 'ตั้งค่า':
    //             $result = in_array($dep_id, [3, 5]);
    //             break;
    //     }
    //     return $result;
    // }
} // End of class
