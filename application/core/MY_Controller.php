<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /* Global Variable */
    public $theme = 'default';
    public $layout = 'global';
    public $partials = array('header', 'sidebar', 'topbar', 'footer');
    public $data = array();
    public $CURUSER = NULL;


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");

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

        // Default Site Config
        $this->data = [
            'site_name' => $this->config->item('site_name'),
            'site_thumb' => $this->config->item('site_thumb'),
            'site_author' => $this->config->item('site_author'),
            'google_verify' => $this->config->item('google_verify'),
            'fb_app_id' => $this->config->item('fb_app_id'),
            'fb_app_secrete' => $this->config->item('fb_app_secrete'),
            'theme' => $this->theme,
            'm_active' => ''
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
            case 'dropzone':
                $this->data['css'][] = css_asset('dropzone/dropzone.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('dropzone/dropzone.min.js', TRUE, 'assets/vendor');
                break;
            case 'tagsinput':
                $this->data['css'][] = css_asset('tagsinput/bootstrap-tagsinput.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('tagsinput/bootstrap-tagsinput.min.js', TRUE, 'assets/vendor');
                break;
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
            case 'touchspin':
                $this->data['js'][] = js_asset('touchspin/bootstrap-input-spinner.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('touchspin/custom-editors.js', TRUE, 'assets/vendor');
                break;
            case 'selectpicker':
                $this->data['css'][] = css_asset('selectpicker/bootstrap-select.min.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('selectpicker/bootstrap-select.min.js', TRUE, 'assets/vendor');
                break;
            case 'chartjs':
                $this->data['js'][] = js_asset('chartjs/chart.min.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('chartjs/chartjs-plugin-datalabels.js', TRUE, 'assets/vendor');
                break;
            case 'highcharts':
                //$this->data['css'][] = css_asset('highcharts/css/highcharts.css', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('highcharts/highcharts.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('highcharts/highcharts-3d.js', TRUE, 'assets/vendor');
                $this->data['js'][] = js_asset('highcharts/modules/accessibility.js', TRUE, 'assets/vendor');
                break;
            case 'moment':
                $this->data['js'][] = js_asset('moment/moment.min.js', TRUE, 'assets/vendor');
                break;
        }
        return $this;
    }



    protected function addSystemLog($action, $url, $page, $section='', $add_on=[]) {
        $this->load->library('mongo_db', NULL, 'mdb');
        $result = $this->mdb->insert('system_log', [
            'url' => $url,
            'action' => $action,
            'page' => $page,
            'section' => $section,
            'memberInfo' => [
                'member_id' => $this->CURUSER->member_id,
                'display_name' => $this->CURUSER->display_name,
            ],
            'addOn' => (!empty($add_on)) ? $add_on : NULL,
            'createdDate' => $this->mdb->date()
        ]);

        return $result;
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
} // End of class
