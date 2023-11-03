<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_access extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function genToken()
    {
        $auth = 'Basic ' . base64_encode(CLIENT_ID . ":" . CLIENT_SECRET);
        $url = API . 'authapi/token';
        $response = $this->restAPI($url, 'grant_type=token&refresh_token=token', 'POST', TRUE,  $auth);
        return $response;
    }

    public function getUser($id, $token)
    {
        $auth = 'Bearer ' . $token;
        $url = API . 'userapi/getdatauser';
        $response = $this->restAPI($url, 'userid=' . $id, 'POST', TRUE,  $auth);
        return $response;
    }
}
