<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Access extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_access');
    }


    public function process($userid = FALSE)
    {

        if (empty($userid)) {
            redirect($this->url);
        }

        $genToken = $this->model_access->genToken();
        if (!empty($genToken)) {
            $this->setSiteCookie($genToken, $userid);
            $member = $this->model_access->getUser($userid, $genToken->access_token);
            if (empty($member->error)) {
                $this->CURUSER = $member;
                $this->CURUSER->user_cus = $this->findObjectUser($this->CURUSER->customer, $this->CURUSER->user[0]->cus_id);
                $this->CURUSER->session_expire = date('Y-m-d H:i:s', strtotime("+" . $genToken->expires_in . " sec"));
                $this->addSystemLog_Login([
                    genRandomString(16), $member->user[0]->userid, $member->user[0]->cus_id, $member->user[0]->username, $member->user[0]->userdisplay_th, $member->user[0]->user_type, $member->user[0]->dep_id, $member->user[0]->dep_code, $member->user[0]->user_status,
                    $member->user[0]->roleid, $member->user[0]->rolename, $member->user[0]->roletype, date('Y-m-d H:i:s')
                ]);

                if ($this->CURUSER->user[0]->user_type == 'Emp') {
                    if (checkPermission('การแจ้งเตือน', $this->CURUSER->user[0]->dep_id, $this->role)) {
                        redirect('invoice');
                    }

                    if (checkPermission('รายงาน', $this->CURUSER->user[0]->dep_id, $this->role)) {
                        redirect('report');
                    }
                } else {
                    redirect('invoice');
                }

                // redirect('invoice');

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
    }




    public function logout()
    {
        $this->clearSiteCookie();
        setcookie('userid', '', time() - 28800, $this->http . '/');
        redirect($this->url . '/signin/relogin/' . $_COOKIE['access_token']);
    }


    // public function info() {
    //     phpinfo();
    // }


}
