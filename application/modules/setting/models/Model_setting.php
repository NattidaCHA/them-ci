<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_setting extends MY_Model
{
	private $tableAllowFieldsSetting = ('uuid,page_name, colunm, sort, page_sort, is_show');
	private $tableAllowFieldsTemPDF = ('uuid,page, company, address, tel, tel2,tax, account_no, account_name, image_name, bank_name, branch,comp_code, due_detail, cal, contact, type, payment_title, detail_1_1,detail_1_2, detail_2, detail_2_1, detail_2_2, detail_2_3, detail_2_4,detail_2_5, detail_2_6, detail_2_7, detail_2_8, detail_3,detail_4, detail_5, sort,tran_header,tran_detail_1,tran_detail_2,tran_detail_3');
	private $tableAllowFieldsDepartment = ('uuid,department_id, department_code, department_nameLC, department_nameEN, department_status,menu');
	private $tableAllowFieldsDocType = ('uuid,type, type_display_th, type_display_en, calculateSign, msort,mstatus,is_show,start_date,end_date');

	public function __construct()
	{
		parent::__construct();
	}

	public function create($params)
	{
		$sql = "INSERT INTO " . SETTING . ' (' . $this->tableAllowFieldsSetting . ") VALUES (?, ?, ?, ?, ?, ?)";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}

	public function create_tem_pdf($params)
	{
		$sql = "INSERT INTO " . PAYMENT . ' (' . $this->tableAllowFieldsTemPDF . ") VALUES (?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$res = sqlsrv_query($this->conn, $sql, $params);

		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}

	public function create_docType($params)
	{
		$sql = "INSERT INTO " . DOCTYPE . ' (' . $this->tableAllowFieldsDocType . ") VALUES (?, ?, ?, ?, ?, ?,?, ?, ?, ?)";
		$res = sqlsrv_query($this->conn, $sql, $params);

		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}

	public function getPage()
	{
		$result = [];
		$lists = [];
		$sql =  "SELECT MAX(uuid) as uuid,MAX(page_name) as page_name,MAX(CONVERT(int,page_sort)) as page_sort,MAX(CONVERT(int,sort)) as sort,MAX(colunm) as colunm,MAX(CONVERT(int,is_show)) as is_show FROM " . SETTING . " group by uuid order by page_sort asc,sort asc";

		$stmt = sqlsrv_query($this->conn, $sql);
		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($result, (object)$row);
			}

			foreach ($result as $val) {
				$lists[$val->page_name][$val->uuid] = $val;
			}

			$output = (object)[
				'status' => 200,
				'items'  => $lists,
				'msg'  => "success",
			];
		}
		return $output;
	}

	public function doctype()
	{
		$result = [];
		$sql =  "SELECT * FROM " . DOCTYPE . " order by msort asc";

		$stmt = sqlsrv_query($this->conn, $sql);
		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($result, (object)$row);
			}


			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}
		return $output;
	}

	public function updateSetting($id, $params)
	{
		$sql = "update " . SETTING . " set is_show=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}

	public function updateDocTypeShow($id, $params)
	{
		$sql = "update " . DOCTYPE . " set is_show=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}

	public function updateDocTypeDate($id, $params)
	{
		$sql = "update " . DOCTYPE . " set start_date=(?),end_date=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}

	public function getInvoice($val)
	{

		$result = [];
		$data = [];
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];
		$select =  "" . BILLPAY . ".mcustno," . BILLPAY . ".macctdoc," . BILLPAY . ".mdoctype," . BILLPAY . ".mnetamt," . BILLPAY . ".msaleorg," . BILLPAY . ".mduedate," . BILLPAY . ".mbillno," . VW_Customer . ".msendto," . CUSTOMER . ".send_date," . BILLPAY . ".mbillno," . BILLPAY . ".mpostdate," . BILLPAY . ".mduedate," . BILLPAY . ".mpayterm," . BILLPAY . ".mdocdate," . BILLPAY . ".mtext ";

		$join = " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . BILLPAY . ".mcustno left join " . VW_Customer . " on " . VW_Customer . ".mcustno = " . BILLPAY . ".mcustno";

		$sql =  "SELECT $select FROM " . BILLPAY . "$join" . " where " . CUSTOMER . ".send_date = '$val->dateSelect' AND " . BILLPAY . ".mduedate between '$val->startDate' and '$val->endDate' ";

		if (!empty($val->is_fax) && $val->is_fax != '1') {
			$fax = $val->is_fax == '2' ? 1 : 0;
			$sql = $sql . " AND " . CUSTOMER . ".is_fax = $fax";
		}

		if (!empty($val->is_email) && $val->is_email != '1') {
			$email = $val->is_email == '2' ? 1 : 0;
			$sql = $sql . " AND " . CUSTOMER . ".is_email = $email";
		}

		if (!empty($val->type) &&  $val->type != '1') {
			$sql = $sql . " AND " . BILLPAY . ".msaleorg = '$val->type'";
		}


		if (!empty($doctypeLists)) {
			$sql =  $sql . ' AND ' . BILLPAY . ".mdoctype in ('" . implode("','", array_keys($doctypeLists)) . "')";
		}

		// if (!empty($val->is_bill)) {
		//     $result2 = [];
		//     $sql2 = "SELECT cus_no FROM " . REPORT . " where start_date = '$val->startDate' AND  end_date ='$val->endDate' group by cus_no";
		//     $stmt2 = sqlsrv_query($this->conn, $sql2);

		//     while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
		//         array_push($result2, $row2["cus_no"]);
		//     }

		//     if ($val->is_bill == '3' && !empty($result2)) {
		//         $sendTo3 = [];
		//         foreach ($result2 as $cus_main) {
		//             $res = $this->sendTo($cus_main);
		//             foreach ($res as $_val) {
		//                 array_push($sendTo3, $_val);
		//             }
		//         }

		//         $sql = $sql . " AND " . BILLPAY . ".mcustno not in (" . implode(',', $sendTo3) . ")";
		//     }
		// }


		if (!empty($val->cus_no)) {
			$sendTo = $this->sendTo($val->cus_no);
			$sql = $sql . " AND " . BILLPAY . ".mcustno in ('" . implode("','", $sendTo) . "')";
		}

		if (!empty($val->action)) {
			if ($val->action == 'job') {
				$sql = $sql . " AND " . BILLPAY . ".mcustno not in ('0002000220','0002000111')";
			} else {
				if (!empty($val->cusNoreport)) {
					$sql = $sql . " AND " . BILLPAY . ".mcustno not in ('" . implode("','", $val->cusNoreport) . "')";
				} else {
					$sql = $sql . " AND " . BILLPAY . ".mcustno not in ('0002000220','0002000111')";
				}
			}
		}

		// var_dump($sql);
		// exit;
		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($result, (object)$row);
			}

			if (!empty($result)) {
				foreach ($result as $row) {
					$data[$row->mcustno][] = $row;
				}
			}

			// var_dump($data);
			// exit;

			$output = (object)[
				'status' => 200,
				'items'  => $data,
				'msg'  => "success",
			];
		}

		return $output;
	}


	public function sendTo($cus_no)
	{
		$result = [];
		$sql = "SELECT cus_no FROM " . SENTO_CUS . " where cus_main = '$cus_no' AND  is_check = 1 group by cus_no";
		$stmt = sqlsrv_query($this->conn, $sql);

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			array_push($result, $row["cus_no"]);
		}

		return $result;
	}

	public function checkReport($val)
	{
		$result = [];
		$key = [];
		$sql2 = "SELECT * FROM " . REPORT . " where send_date = '$val->dateSelect' AND start_date = '$val->startDate' AND end_date ='$val->endDate'";

		if (!empty($val->cus_no)) {
			$sendTo = $this->sendTo($val->cus_no);
			$sql2 = $sql2 . " AND " . REPORT . ".cus_no in (" . implode(',', $sendTo) . ")";
		}

		$stmt2 = sqlsrv_query($this->conn, $sql2);

		while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
			$key[$row2["cus_no"]] = $row2["cus_no"];
			$result[$row2["cus_no"]] = (object)$row2;
		}

		return (object)['key' => $key, 'report' => $result];
	}

	public function createDepartment($params)
	{
		$sql = "INSERT INTO " . DEPARTMENT . ' (' . $this->tableAllowFieldsDepartment . ") VALUES (?, ?, ?, ?, ?, ?,?)";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}
}
