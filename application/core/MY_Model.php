<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public $timeOut = 15;

    public function __construct() {
        parent::__construct();
    }


    private function _prefixDoc($type) {
        $prefix = '';
        switch($type) {
            case 'sponsor': $prefix = 'MD'; break;
            case 'youtube': $prefix = 'YT'; break;
            case 'wht': $prefix = 'WHT'; break;
        }

        return $prefix;
    }


    public function getRunningNo($type, $year, $month = FALSE, $length = 5, $yearLength = 2) {
        $no = NULL;
        if (!empty($month)) {
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            $this->db->where('month_data', $month);
        }
        $sql = $this->db->where('type', $type)->where('year_data', $year)->get('_running');
        $result = $sql->row();

        $runningYear = ($yearLength === 2) ? substr($year,2,2) : substr($year,0,4);
        $runningPrefix = self::_prefixDoc($type);

        if (!empty($result)) {
            $no = $result->prefix . $runningYear . ((!empty($month)) ? $result->month_data : '') . '-' . str_pad($result->next_no, $length, '0', STR_PAD_LEFT);
            // Increasing
            $this->db->set('next_no', 'next_no+1', FALSE)->where('id', $result->id)->update('_running');
        } else {
            $new = ['type' => $type, 'year_data' => $year, 'prefix' => $runningPrefix, 'next_no' => 2];
            if (!empty($month)) {
                $new['month_data'] = $month;
            }
            $this->db->insert('_running', $new);
            $no = $runningPrefix . $runningYear . ((!empty($month)) ? $month : '') . '-' . str_pad(1, $length, '0', STR_PAD_LEFT);
        }

        return $no;
    }


    protected function restAPI($url, $param = NULL, $method = 'POST', $json = TRUE) {
        $data_pack = $this->curlRemote($url, $param, $method, $json);
        $data_decode = json_decode($data_pack);

        if (json_last_error() == JSON_ERROR_NONE && !empty($data_decode)) {
            return $data_decode;
        }

        return FALSE;
    }


    private function curlRemote($url, $param = NULL, $method = 'POST', $json = FALSE) {
        $refer = site_url('');
        $method = strtoupper($method);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $contentLength = 0;
        if (!empty($param)) {
            if ($json === TRUE) {
                $json_string = json_encode($param);
                $contentLength = strlen($json_string);
                curl_setopt(
                    $curl,
                    CURLOPT_HTTPHEADER,
                    ['Content-Type: application/json', 'Content-Length: ' . $contentLength]
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

        if (($contentLength * 8) > 921600) {
            $data = json_encode([
                "response" => [
                    "result" => "false",
                    "remark" => "PayloadTooLargeError: request entity too large (over 900kb)",
                    "code" => 400
                ],
                "error" => [
                    "code" => "",
                    "message" => "Your total payload data is too large than the system limit."
                ]
            ]);
        } else {
            curl_setopt($curl, CURLOPT_REFERER, $refer);
            $data = @curl_exec($curl);
            curl_close($curl);
        }

        return $data;
    }



} // End of class
