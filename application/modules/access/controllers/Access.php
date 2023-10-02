<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Access extends MY_Controller {


    public function __construct() {
        parent::__construct();
    }

    // password_hash('secret|test1234', PASSWORD_BCRYPT);


    public function index() {
        if (!empty($this->CURUSER)) {
            redirect('dashboard');
        }
        $invalid_token = FALSE;
        $backUrl = $this->input->get('back_url', TRUE);
        $check_token = (!empty($_COOKIE['_csrftoken'])) ? $_COOKIE['_csrftoken'] : '';
        $remember = ((!empty($_COOKIE['rememail'])) ? htmlspecialchars($_COOKIE['rememail']) : '');
        $token = md5(microtime());
        setcookie('_csrftoken', $token, time()+86400, '/access');

        if ($this->input->method() == 'post') {
            $this->data['error'] = 'email หรือ password ไม่ถูกต้อง !';
            $params = $this->input->post(NULL, TRUE);

            if ($params['token'] === $check_token && !empty($check_token)) {
                if (!empty($params['email']) && !empty($params['password'])) {
                    $email = trim($params['email']);
                    $remember = (!empty($email)) ? $email : $remember;
                    $this->load->model('member/model_member');
                    if ($member = $this->model_member->getUser($email, 'email')) {
                        if (empty($member->status)) {
                            $this->data['error'] = 'ไม่สามารถเข้าใช้งานระบบได้ !';
                        } else {
                            if ($verify = password_verify($member->secret.'|'.$params['password'], $member->passhash)) {
                                // Set cookie
                                $this->setSiteCookie($member);
                                // Set remember user
                                if (!empty($params['remember'])) {
                                    setcookie('rememail', $email, time()+(86400*90), '/access');
                                }

                                $this->CURUSER = $this->model_member->getMember($member->member_id);
                                $this->model_member->updateUser($member->member_id, ['last_login' => date('Y-m-d H:i:s'), 'last_ip' => get_ip()], FALSE);
                                $this->addSystemLog('update', site_url('access'), 'Login');
                                redirect(((!empty($params['redirect_url']))) ? $params['redirect_url'] : 'dashboard');
                            }
                        }
                    }
                }
            } else {
                $this->data['error'] = 'รูปแบบการเข้าใช้งานไม่ถูกต้อง ! กรุณาลองใหม่อีกครั้ง';
            }
        }

        $this->data['back_url'] = rawurldecode($backUrl);
        $this->data['login_token'] = $token;
        $this->data['remember'] = $remember;
        $this->layout = 'blank';
        $this->view('login');
    }


    public function logout() {
        $this->clearSiteCookie();
        redirect('access');
    }


    public function info() {
        phpinfo();
    }


}