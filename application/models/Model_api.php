<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_api extends MY_Model
{
    private $apiDefaultOutput = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function getContact()
    {
        $sql = $this->db->get('cust_notification');
        $result = $sql->result();
        $sql->free_result();
        return $result;
    }

    public function getInvoice()
    {
        $result = [];
        $output = (object)[];
        $select = REPORT . '.uuid,' . REPORT . '.bill_no,MAX(CONVERT(int,' . REPORT . '.is_email)) as is_email,MAX(' . REPORT . '.cus_main) as cus_main,MAX(' . REPORT . '.created_date) as created_date,MAX(' . REPORT . '.cus_no) as cus_no,MAX(' . REPORT . '.created_by) as created_by,MAX(' . REPORT . '.end_date) as end_date,MAX(' . VW_Customer . '.mcustname) as cus_name ';
        $group_by = REPORT . '.uuid,' . REPORT . '.bill_no';
        $sql =  "SELECT $select FROM " . REPORT . " left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno GROUP BY $group_by  ORDER BY created_date desc";

        
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt == false) {
            $output = (object)[
                'status' => 500,
                'error'  => sqlsrv_errors(),
                'msg'  => "Database error",
            ];
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $res = $row;
                array_push($result, $res);
            }

            $output = (object)[
                'status' => 200,
                'items'  => $result,
                'msg'  => "success",
            ];
        }

        return $output;
    }
}
