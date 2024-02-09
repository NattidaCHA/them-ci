<?php

use Mpdf\Tag\Em;

(defined('BASEPATH')) or exit('No direct script access allowed');

class Model_report extends MY_Model
{
	private $tableAllowFieldsCfCall = ('uuid, report_uuid,cus_main, tel,cf_call,receive_call');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_system');
		$this->load->model('customer/model_customer');
		$this->load->model('invoice/model_invoice');
	}

	public function getBillNo($cus_no = FALSE)
	{
		$result = [];
		$first_date = date('Y-m-d H:i:s', strtotime('-3 months'));
		$last_date = date('Y-m-d H:i:s');
		$sql =  "SELECT * FROM " . REPORT . " where created_date between '$first_date' and '$last_date'";

		if (!empty($cus_no)) {
			$sql = $sql . " AND cus_no in ($cus_no)";
		}

		$sql = $sql . ' order by bill_no asc';

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

	public function getEmailById($cus_no)
	{
		$result = [];
		$isCheck = [];
		$lists = [];
		$isCheck = $this->model_system->checkSendtoChild($cus_no)->items;
		foreach ($isCheck as $val) {
			$result = $this->model_customer->email($val->cus_main)->items;

			foreach ($result as $val) {
				array_push($lists, $val);
			}
		}
		return  $lists;
	}

	public function genEmail($cus_no)
	{
		$result = [];
		$isCheck = [];
		$lists = [];
		$isCheck = $this->model_system->checkSendtoChild($cus_no)->items;

		foreach ($isCheck as $val) {
			$result = $this->model_customer->email($val->cus_main)->items;
			foreach ($result as $val) {
				$lists[$val->cus_main][] = $val;
			}
		}
		return  $lists;
	}

	public function getTelById($cus_no)
	{
		$result = [];
		$isCheck = [];
		$lists = [];

		$isCheck = $this->model_system->checkSendtoChild($cus_no)->items;
		foreach ($isCheck as $val) {
			$result = $this->model_customer->tel($val->cus_main)->items;
			foreach ($result as $val) {
				array_push($lists, $val);
			}
		}
		return  $lists;
	}

	public function getFaxById($cus_no)
	{
		$result = [];
		$isCheckFax = (object)[];
		$lists = [];

		$isCheckFax = $this->model_system->findCustomerById($cus_no)->items;
		if (!empty($isCheckFax)) {
			$result = $this->model_customer->fax($cus_no)->items;
			foreach ($result as $val) {
				array_push($lists, $val);
			}
		}
		return  $lists;
	}

	public function checkChildSendto($cus_no)
	{
		$result = [];
		$sql =  "SELECT cus_no FROM " . SENTO_CUS . " where cus_main = '$cus_no' AND is_check = 1 group by cus_no";
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

	public function queryBills($params)
	{
		$first_date = date('Y-m-d H:i:s', strtotime('-3 months'));
		$last_date = date('Y-m-d H:i:s');
		$sql =  "SELECT " . REPORT . ".uuid," . REPORT . ".bill_no,MAX(CONVERT(int," . REPORT . ".is_email)) as is_email,MAX(" . REPORT . ".cus_main) as cus_main,MAX(CONVERT(int," . REPORT . ".is_receive_bill)) as is_receive_bill,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".mduedate) as mduedate,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . CUSTOMER . ".cus_name) as cus_name,MAX(" . REPORT . ".created_by) as created_by,MAX(" . REPORT . ".end_date) as end_date,MAX(CONVERT(int," . CUSTOMER . ".is_email)) as m_is_email,MAX(CONVERT(int," . CUSTOMER . ".is_fax)) as is_fax FROM " . REPORT . " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . REPORT . ".cus_no  where " . REPORT . " .created_date between '$first_date' and '$last_date'";
		// left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno
		//left join " . REPORT_DETAIL . " on " . REPORT . ".bill_no = " . REPORT_DETAIL . ".bill_no

		if (!empty($params->cus_no)) {
			$index = (string)array_search('all', $params->cus_no);
			if ($index == '0') {
				array_splice($params->cus_no, 0, 1);
			}
			$sql = $sql . " AND " . REPORT . ".cus_no in (" . implode(',', $params->cus_no) . ")";
		}



		if (!empty($params->bill_no)) {
			$sql = $sql . " AND " . REPORT . ".bill_no = '$params->bill_no'";
		}

		if (!empty($params->created_date)) {
			$startDate = $params->created_date . ' 00:00:00';
			$endDate = $params->created_date . ' 23:59:59';
			$sql = $sql . " AND " . REPORT . ".created_date >= '$startDate' AND " . REPORT . ".created_date <= '$endDate'";
		}


		if (!empty($params->is_contact) && $params->is_contact != '1') {
			$isContact = $this->model_invoice->checkIsContact($params->is_contact);
			if ($isContact->is_email != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_email in ($isContact->is_email)";
			}

			if ($isContact->is_fax != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_fax in ($isContact->is_fax)";
			}
		}

		$sql = $sql . "  group by " . REPORT . ".uuid," . REPORT . ".bill_no order by created_date desc";

		return $sql;
	}

	public function getBillTb($condition)
	{

		$conn = json_decode(json_encode($condition));
		$sql = $this->queryBills($conn);
		$result = [];
		$result2 = [];
		$offset = max($conn->page - 1, 0) * $conn->limit;
		$stmt = sqlsrv_query($this->conn, $sql);
		$stmt2 = sqlsrv_query($this->conn, $sql . " offset $offset rows fetch next $conn->limit rows only");

		if ($stmt == false || $stmt2 == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($result, (object)$row);
			}


			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
				$val = (object)$row2;
				array_push($result2, (object)['info' => $val, 'tels' => $this->getTelById($val->cus_no), 'emails' => $this->getEmailById($val->cus_no), 'cf_call' => $this->getCfCallByuuid($val->uuid)->items, 'faxs' => $this->getFaxById($val->cus_no)]);
			}

			$output = (object)[
				'status' => 200,
				'items'  => $result2,
				'totalRecord' => !empty($result) ? count($result) : 0,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function getListItem($bill_id)
	{
		$debit = [];
		$credit = [];
		$sql =  "SELECT * FROM " . REPORT_DETAIL . " where bill_id = '$bill_id' order by msort asc, mbillno asc,mpostdate asc,mduedate asc, mpayterm asc,mnetamt asc";
		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$res = (object)$row;
				if (in_array($res->msort, [1, 2, 7])) {
					$res->type = $this->genType($res->mdoctype)->type;
					$res->sortType = $this->genType($res->mdoctype)->sortType;
					array_push($debit, $res);
				} else {
					$res->type = $this->genType($res->mdoctype)->type;
					$res->sortType = $this->genType($res->mdoctype)->sortType;
					array_push($credit, $res);
				}
			}

			$output = (object)[
				'status' => 200,
				'debit'  => $debit,
				'credit'  => $credit,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function getReportUuid($uuid)
	{
		$result = (object)[];
		$sql =  "SELECT * FROM " . REPORT . " where uuid = '$uuid'";
		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			$result =  sqlsrv_fetch_object($stmt);
			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function getReportChildList($cus_no, $created_date)
	{

		$result = (object)[];
		$startDate = date('Y-m-d', strtotime($created_date)) . ' 00:00:00';
		$endDate = date('Y-m-d', strtotime($created_date)) . ' 23:59:59';
		$sql =  "SELECT uuid,end_date,bill_no FROM " . REPORT . " where cus_no = '$cus_no' AND created_date >='$startDate' AND created_date <='$endDate'";

		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			$result =  sqlsrv_fetch_object($stmt);
			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function genPDF($bill_id, $action)
	{
		$data = (object)[
			'info' => (object)[],
			'bill_info' => (object)[],
			'lists' => [],
			'total' => (object)[
				'total_debit' => 0,
				'total_credit' => 0,
				'total_summary' => 0,
				'total_RA' => 0,
				'total_RD' => 0,
				'total_RC' => 0,
				'total_RB' => 0,
				'total_DC' => 0,
				'total_RE' => 0,
				'total_DA' => 0,
				'total_DB' => 0,
				'total_DE' => 0,
			],
			'total_items' => 0,
			'total_page' => 0,
			'payment' => [],
			'barcode' => (object)[
				'code' => '',
				'image' => ''
			],
			'qrcode' => '',
			'last_count' => 0
		];

		if (!empty($bill_id)) {
			$bill_info = $this->getReportUuid($bill_id)->items;
			$debitLists = $this->getListItem($bill_id)->debit;
			$creditLists = $this->getListItem($bill_id)->credit;
			$data->payment = $this->getPayment()->items;
			$info = (object)[];

			if (!empty($bill_info)) {
				$data->bill_info = $bill_info;
				$info = $this->getCustomerInfo($bill_info->cus_no)->items;
				if (!empty($info)) {
					$data->info = $info;
				}
			}

			// var_dump($creditLists);
			// exit;
			if (!empty($debitLists)) {
				$itemLists = array_merge($debitLists, $creditLists);
				$data->total_items = count($itemLists);
				$data->total = $this->calculateTotallChild($itemLists);
				if ($action == 'pdf') {
					if (count($itemLists) <= 10) {
						$data->lists[1] = $itemLists;
						$data->lists[1]['total'] = $this->calculateTotallChild($data->lists[1])->total_summary;
						$data->lists[1]['size'] = count($itemLists);
						$data->total_page = 1;
					} else {
						$size = 38;
						$countDebit =  count($debitLists);
						if ($countDebit <= 28) {
							$data->lists[1] = $countDebit > 20 ? $debitLists :  $itemLists;
							$data->lists[1]['total'] = $this->calculateTotallChild($data->lists[1])->total_summary;
							$data->lists[1]['size'] = $countDebit > 20 ? $countDebit :  count($itemLists);
							$data->total_page = 1;
							if (!empty($creditLists) && $countDebit > 20) {
								$data->lists[2] = $creditLists;
								$data->lists[2]['total'] = $this->calculateTotallChild($data->lists[2])->total_summary;
								$data->lists[2]['size'] = count($creditLists);
								$data->total_page = 2;
							}
						} else {
							$data->lists[1] = array_slice($debitLists, 0, 29);
							$data->lists[1]['total'] = $this->calculateTotallChild($data->lists[1])->total_summary;
							$data->lists[1]['size'] = 29;
							$count = ceil(count(array_slice($itemLists, 29)) / $size) + 1;
							$data->total_page = $count;
							for ($i = 2; $i <= $count; $i++) {
								$sumList = $this->paginate(array_slice($debitLists, 29), $size, $i);
								if ($count == $i && !empty($creditLists)) {
									$sumList = array_merge($sumList, $creditLists);
								}

								$sumList['total'] = $this->calculateTotallChild($sumList)->total_summary;
								$sumList['size'] = count($data->lists[$i - 1]) - 2;
								$data->lists[$i] = $sumList;
							}
						}
					}
					$lastCount = count(array_slice(end($data->lists), 0, (count(end($data->lists)) - 2)));
					$data->last_count = $lastCount;
					$code = "|0273022069\r\n$info->mcustno\r\n$bill_info->bill_no\r\n" . str_replace('.', '', (str_replace('-', '', $data->total->total_summary)));
					$data->qrcode = $this->qrcode($code);
					$data->barcode->image = $this->barcode($code);
					$data->barcode->code = $code;
				} else {
					$data->lists = $itemLists;
				}
			}
		}
		// echo '<pre>';
		// var_dump($data->lists);
		// exit;
		// echo '</pre>';
		return $data;
	}


	public function paginate($array, $page_size, $page_number)
	{
		return array_slice($array, ($page_number - 2) * $page_size, $page_size);
	}

	public function getCustomerInfo($cus_no)
	{
		$result = (object)[];
		$sql =  "SELECT * FROM " . VW_Customer . " where mcustno = '$cus_no' and msaleorg = '0281'";

		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			$result =  sqlsrv_fetch_object($stmt);
			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function calculateTotallChild($result)
	{
		$total_summary = 0;
		$total_debit = 0;
		$total_credit = 0;
		$total_RA = 0;
		$total_RD = 0;
		$total_RC = 0;
		$total_RB = 0;
		$total_DC = 0;
		$total_RE = 0;
		$total_DA = 0;
		$total_DB = 0;
		$total_DE = 0;
		foreach ($result as $res) {
			// var_dump($res->mdoctype);
			// exit;
			if (!empty($res->mdoctype == 'RA')) {
				$total_summary = $total_summary + $res->mnetamt;
				$total_debit = $total_debit + $res->mnetamt;
				$total_RA += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RD')) {
				$total_summary = $total_summary + $res->mnetamt;
				$total_debit = $total_debit + $res->mnetamt;
				$total_RD += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DC')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_DC += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RB')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_RB += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RC')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_RC += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RE')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_RE += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DA')) {
				$total_summary = $total_summary + $res->mnetamt;
				$total_debit = $total_debit + $res->mnetamt;
				$total_DA += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DB')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_DB += $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DE')) {
				$total_summary = $total_summary - $res->mnetamt;
				$total_credit = $total_credit + $res->mnetamt;
				$total_DE += $res->mnetamt;
			}
		}

		return (object)['total_debit' => $total_debit, 'total_credit' => $total_credit, 'total_summary' =>  $total_summary, 'total_RA' => $total_RA, 'total_RD' => $total_RD, 'total_DC' => $total_DC, 'total_RB' => $total_RB, 'total_RC' => $total_RC, 'total_RE' => $total_RE, 'total_DA' => $total_DA, 'total_DB' => $total_DB, 'total_DE' => $total_DE];
	}

	public function getPayment()
	{
		$result = [];
		$sql =  "SELECT * FROM " . PAYMENT;
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


	public function genType($res)
	{
		$text = '';
		$sortType = 1;
		if (!empty($res == 'RA')) {
			//$text = 'RA - ยอด Invoice';
			$text = 'RA';
			$sortType  = 1;
		}
		if (!empty($res == 'RD')) {
			// $text = 'RD - ยอดเพิ่มหนี้';
			$text = 'RD';
			$sortType  = 2;
		}
		if (!empty($res == 'DA')) {
			//$text = 'DA: Customer Manual Inv.';
			$text = 'DA';
			$sortType  = 3;
		}
		if (!empty($res == 'DC')) {
			// $text = 'DC - ยอด Rebate';
			$text = 'DC';
			$sortType  = 6;
		}
		if (!empty($res == 'RB')) {
			//$text = 'RB - ยอดเงินเหลือ';
			$text = 'RB';
			$sortType  = 5;
		}
		if (!empty($res == 'RC')) {
			// $text = 'RC - ยอดลดหนี้';
			$text = 'RC';
			$sortType  = 4;
		}
		if (!empty($res == 'RE')) {
			// $text = 'RE - ยอดเงินเหลือในใบเสร็จ';
			$text = 'RE';
			$sortType  = 7;
		}
		if (!empty($res == 'DB')) {
			//$text = 'DB: Customer Adjustment';
			$text = 'DB';
			$sortType  = 8;
		}
		if (!empty($res == 'DE')) {
			// $text = 'DE: Customer Manual Payment';
			$text = 'DE';
			$sortType  = 9;
		}


		return (object)['type' => $text, 'sortType' => $sortType];
	}


	function barcode($code)
	{
		require_once("vendor/autoload.php");
		$generator = new Picqer\Barcode\BarcodeGeneratorJPG();
		$border = 2; //กำหนดความหน้าของเส้น Barcode
		$height = 1; //กำหนดความสูงของ Barcode

		// var_dump(FCPATH);
		// exit;
		return file_put_contents(FCPATH . 'assets/img/qrcode/barcode.jpg', $generator->getBarcode($code, $generator::TYPE_CODE_128, $border, $height));
	}

	function qrcode($code)
	{
		$this->load->library('ciqrcode');
		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']        = ''; //string, the default is application/cache/
		$config['errorlog']        = ''; //string, the default is application/logs/
		$config['quality']        = true; //boolean, the default is true
		$config['size']            = ''; //interger, the default is 1024
		$config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
		$config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);

		$params['data'] = $code;
		$params['level'] = 'H';
		$params['size'] = 5;
		$params['savename'] = FCPATH . 'assets/img/qrcode/qrcode.png';
		return  $this->ciqrcode->generate($params);
	}

	public function update($id, $data)
	{
		$sql = $this->db->where('uuid', $id)->update('report_notification', $data);
		if (!empty($sql)) {
			return $sql;
		}

		return FALSE;
	}


	public function createCfCall($params)
	{
		$sql = "INSERT INTO " . CFCALL . ' (' . $this->tableAllowFieldsCfCall . ") VALUES (?, ?, ?, ?, ?, ?)";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}

	public function updateCfCall($id, $params)
	{
		$sql = "update " . CFCALL . " set cf_call=(?),receive_call=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}


	public function updateReceiveBill($id, $params)
	{
		$sql = "update " . REPORT . " set is_receive_bill=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}

	public function getCfCall()
	{
		$result = [];
		$lists = [];
		$sql =  "SELECT * FROM " . CFCALL;
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

			foreach ($result as $res) {
				$lists[$res->report_uuid][$res->tel] = $res;
			}

			$output = (object)[
				'status' => 200,
				'items'  => $lists,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function updateEmail($id)
	{
		$data = [1, date("Y-m-d H:i:s")];
		$sql = "update " . REPORT . " set is_email=(?),updated_date=(?) where uuid = '$id'";
		$res = sqlsrv_query($this->conn, $sql, $data);
		if (!empty($res)) {
			return $res;
		}

		return FALSE;
	}

	public function getCfCallByuuid($report_uuid)
	{
		$result = [];
		$sql =  "SELECT * FROM " . CFCALL . " where report_uuid ='$report_uuid' AND receive_call !=''";
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


	public function genCfCallByuuid($report_uuid)
	{
		$result = [];
		$lists = [];

		$sql =  "SELECT * FROM " . CFCALL . " where report_uuid = '$report_uuid'";
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

			foreach ($result as $res) {
				$lists[$res->tel] = $res;
			}


			$output = (object)[
				'status' => 200,
				'items'  => $lists,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function genCfCall($report_uuid, $cus_no)
	{
		$genCfCall = $this->genCfCallByuuid($report_uuid)->items;
		$genTel = $this->getTelById($cus_no);
		return  (object)['tels' => $genTel, 'cf_call' => $genCfCall];
	}


	public function getBillById($report_uuid)
	{

		$result = (object)[];

		$select =  "MAX(" . REPORT . ".uuid) as uuid,MAX(" . REPORT . ".bill_no) as bill_no,MAX(CONVERT(int," . REPORT . ".is_email)) as is_email,MAX(" . REPORT . ".cus_main) as cus_main,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . REPORT . ".created_by) as created_by,MAX(" . REPORT . ".updated_date) as updated_date,MAX(CONVERT(int," . REPORT . ".is_receive_bill)) as is_receive_bill,MAX(" . REPORT . ".end_date) as end_date ";
		$join = " left join " . VW_Customer . " on " . REPORT . ".cus_no = " . VW_Customer . ".mcustno";

		$sql =  "SELECT $select FROM " . REPORT . "$join" . " where uuid ='$report_uuid'";
		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			$result =  sqlsrv_fetch_object($stmt);
			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}
}
