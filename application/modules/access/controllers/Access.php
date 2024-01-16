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

    public function lineOA()
    {
        
        $datas = file_get_contents('php://input');

        $deCode = json_decode($datas, true);

        file_put_contents('line_msg.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

        $replyToken = $deCode['events'][0]['replyToken'];
        $userId = $deCode['events'][0]['source']['userId'];
        $text = $deCode['events'][0]['message']['text'];

        $messages = [];
        $messages['replyToken'] = $replyToken;
        $messages['messages'][0] = $this->getFormatTextMessage("เอ้ย ถามอะไรก็ตอบได้");

        $encodeJson = json_encode($messages);

        $LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
        $LINEDatas['token'] = 'HiXpIrzBi0fp8WbiilSZQTvPJJnERPB/OFOole2ryDRHmeft1M0vxoqH8YvPbf82Yy+i08ieC9imVXZHL/IZ/mQG1AhiWBlsZ4PK1CF9ox789u89+HxdTvnJkVuyK+VW6WhoMxQvWbU/YPN7scVjTwdB04t89/1O/w1cDnyilFU=';

        $results = $this->sentMessage($encodeJson, $LINEDatas);
        // var_dump($results);
        // exit;
        /*Return HTTP Request 200*/
        http_response_code(200);
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

    public function getFormatTextMessage($text)
    {
        $datas = [];
        $datas['type'] = 'text';
        $datas['text'] = $text;

        return $datas;
    }

    public function sentMessage($encodeJson, $datas)
    {
        $datasReturn = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $datas['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $encodeJson,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $datas['token'],
                "cache-control: no-cache",
                "content-type: application/json; charset=UTF-8",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
        } else {
            if ($response == "{}") {
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
            } else {
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
            }
        }

        return $datasReturn;
    }
}
