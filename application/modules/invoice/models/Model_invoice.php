<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_invoice extends MY_Model
{

	private $tableAllowFieldsInvoice = ('cus_main, cus_no, bill_no, is_email, created_date,uuid,start_date,end_date,is_sms,updated_date, created_by, is_receive_bill,mduedate,send_date');
	private $tableAllowFieldsInvoiceDatail = ('uuid, bill_no, bill_id, macctdoc, cus_no, cus_main, mdoctype, mbillno, mpostdate, mduedate, msaleorg, mpayterm, mnetamt, mtext, msort,mdocdate');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_system');
	}

	public function queryInvoice($val, $idLists = [])
	{
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];

		$select =  "" . BILLPAY . ".mcustno," . BILLPAY . ".mdoctype,SUM(" . BILLPAY . ".mnetamt) as total_mnetamt,MAX(" . BILLPAY . ".mcustname) as cus_name,MAX(" . BILLPAY . ".msaleorg) as msaleorg,MAX(" . BILLPAY . ".mduedate) as end_date,MAX(" . BILLPAY . ".mpostdate) as start_date,MAX(" . CUSTOMER . ".send_date) as mday ,MAX(" . CUSTOMER . ".type) as type ";

		$join = " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . BILLPAY . ".mcustno ";

		$sql =  "SELECT $select FROM " . BILLPAY . "$join" . " where " . CUSTOMER . ".send_date = '$val->dateSelect' AND " . BILLPAY . ".mduedate between '$val->startDate' and '$val->endDate'";

		if (!empty($val->is_bill)) {
			$result2 = [];
			$sql2 = "SELECT cus_no FROM " . REPORT . " where send_date = '$val->dateSelect' AND start_date = '$val->startDate' AND  end_date = '$val->endDate' group by cus_no";
			$stmt2 = sqlsrv_query($this->conn, $sql2);

			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
				array_push($result2, $row2["cus_no"]);
			}

			if ($val->is_bill != '3') {
				$join = $join . " left join " . REPORT . " on " . REPORT . ".cus_no = " . BILLPAY . ".mcustno ";
			}


			if ($val->is_bill == '2') {
				if (!empty($result2)) {
					$sql = $sql . " AND " . BILLPAY . ".mcustno in (" . implode(',', $result2) . ")";
				} else {
					$sql = $sql . " AND " . BILLPAY . ".mcustno in ('')";
				}
			}


			if ($val->is_bill == '3' && !empty($result2)) {
				$sql = $sql . " AND " . BILLPAY . ".mcustno not in (" . implode(',', $result2) . ")";
			}

		}

		if (!empty($val->type) && $val->type !== '1') {
			$sql = $sql . " AND " . BILLPAY . ".msaleorg = '$val->type'";
		}

		if (!empty($val->is_contact) && $val->is_contact != '1') {
			$isContact = $this->checkIsContact($val->is_contact);
			if ($isContact->is_email != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_email in ($isContact->is_email)";
			}

			if ($isContact->is_fax != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_fax in ($isContact->is_fax)";
			}
		}

		if (!empty($doctypeLists)) {
			$sql =  $sql . ' AND ' . BILLPAY . ".mdoctype in ('" . implode("','", array_keys($doctypeLists)) . "')";
		}

		if (!empty($val->cus_no)) {
			$sql = $sql . " AND " . BILLPAY . ".mcustno = '$val->cus_no'";
		} else {
			if (!empty($idLists)) {
				$sql = $sql . " AND " . BILLPAY . ".mcustno in  ('" . implode("','", $idLists) . "')";
			}
		}

		$sql = $sql . " group by "  . BILLPAY . ".mdoctype,"  . BILLPAY . ".mcustno order by " . BILLPAY . ".mcustno asc";
		// var_dump($sql);
		// exit;
		return $sql;
	}

	public function queryInvoiceFindId($val)
	{

		$allLists = [];
		$result = [];
		$cus_main = [];
		$result2 = [];

		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];

		$select =  "" . BILLPAY . ".mcustno,MAX(" . CUSTOMER . ".type) as type ";

		$join = " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . BILLPAY . ".mcustno ";

		$sql =  "SELECT $select FROM " . BILLPAY . "$join" . " where " . CUSTOMER . ".send_date = '$val->dateSelect' AND " . BILLPAY . ".mduedate between '$val->startDate' and '$val->endDate'";

		if (!empty($val->is_bill)) {
			$sql2 = "SELECT " . REPORT . ".cus_no,MAX(" . CUSTOMER . ".type) as type FROM " . REPORT . " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . REPORT . ".cus_no where " . REPORT . ".send_date = '$val->dateSelect' AND " . REPORT . ".start_date = '$val->startDate' AND  " . REPORT . ".end_date = '$val->endDate' group by " . REPORT . ".cus_no";

			$stmt2 = sqlsrv_query($this->conn, $sql2);

			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
				// array_push($result2, $row2["cus_no"]);
				$result2[$row2["cus_no"]] = (object)$row2;
			}

			// var_dump($sql2);
			// exit;
			// if ($val->is_bill != '3') {
			// 	$join = $join . " left join " . REPORT . " on " . REPORT . ".cus_no = " . BILLPAY . ".mcustno ";
			// }

			if ($val->is_bill == '2') {
				if (!empty($result2)) {
					$sql = $sql . " AND " . BILLPAY . ".mcustno in (" . implode(',', array_keys($result2)) . ")";
				} else {
					$sql = $sql . " AND " . BILLPAY . ".mcustno in ('')";
				}
			}


			if ($val->is_bill == '3' && !empty(array_keys($result2))) {
				$sql = $sql . " AND " . BILLPAY . ".mcustno not in (" . implode(',', array_keys($result2)) . ")";
			}
		}

		if (!empty($val->type) && $val->type !== '1') {
			$sql = $sql . " AND " . BILLPAY . ".msaleorg = '$val->type'";
		}

		if (!empty($val->is_contact) && $val->is_contact != '1') {
			$isContact = $this->checkIsContact($val->is_contact);
			if ($isContact->is_email != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_email in ($isContact->is_email)";
			}

			if ($isContact->is_fax != 2) {
				$sql = $sql . " AND " . CUSTOMER . ".is_fax in ($isContact->is_fax)";
			}
		}

		if (!empty($doctypeLists)) {
			$sql =  $sql . ' AND ' . BILLPAY . ".mdoctype in ('" . implode("','", array_keys($doctypeLists)) . "')";
		}

		if (!empty($val->cus_no)) {
			$sql = $sql . " AND " . BILLPAY . ".mcustno = '$val->cus_no'";
		}


		$sql = $sql . " group by "  . BILLPAY . ".mcustno order by " . BILLPAY . ".mcustno asc";

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

			while ($res = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				// array_push($result, (object)$res);
				$result[$res["mcustno"]] = (object)$res;
				// if (!in_array($res["mcustno"], $result2)) {
				// }
			}

			if (!empty($result)) {
				// if ($val->is_bill != '3') {
				// 	foreach ($result2 as $dataRes) {
				// 		if (!in_array($dataRes->cus_no, array_keys($result))) {
				// 			$dataRes->mcustno = $dataRes->cus_no;
				// 			$result[$dataRes->cus_no] = $dataRes;
				// 		}
				// 	}
				// }

				usort($result, function ($a, $b) {
					return $a->type < $b->type;
				});

				// var_dump(count($result));
				// exit;
				foreach ($result as $row) {
					if (!empty($row->mcustno)) {
						if ($row->type == 'main') {
							if (!in_array($row->mcustno, $allLists)) {
								array_push($allLists, $row->mcustno);
								array_push($cus_main, $row->mcustno);
							}
						} else {
							if (!in_array($row->mcustno, $allLists)) {
								$main = $this->checkMainBill($row->mcustno)->items;
								if (!empty($main->mcustno)) {
									if (!in_array($main->mcustno, $cus_main)) {
										array_push($allLists,  $row->mcustno);
										array_push($cus_main,  $main->mcustno);
									}
								} else {
									array_push($allLists,  $row->mcustno);
									array_push($cus_main,  $row->mcustno);
								}
							}
						}
					}
				}
			}


			$output = (object)[
				'status' => 200,
				'items'  => $allLists,
				'mainItems'  => $cus_main,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function getInvoice($condition = [])
	{
		$conn = json_decode(json_encode($condition));
		$result = [];
		$result2 = [];
		$cus_main = [];
		$sqlFindId = [];

		if (!empty($conn->dateSelect) && !empty($conn->startDate) && !empty($conn->endDate)) {
			$sqlFindId = !empty($this->queryInvoiceFindId($conn)->items) ? $this->queryInvoiceFindId($conn)->items : [];

			$sql = $this->queryInvoice($conn, $sqlFindId);

			if ($conn->action != 'excel') {
				$offset = max($conn->page - 1, 0) * $conn->limit;
			}

			$stmt2 = sqlsrv_query($this->conn, $sql);

			if ($conn->action != 'excel') {
				$stmt = sqlsrv_query($this->conn, $sql . "  offset $offset rows fetch next $conn->limit rows only");
			}

			// var_dump($sqlFindId);
			// //var_dump($sql . "  offset $offset rows fetch next $conn->limit rows only");
			// exit;

			if (($conn->action != 'excel' && $stmt == false) || $stmt2 == false) {
				$output = (object)[
					'status' => 500,
					'error'  => sqlsrv_errors(),
					'msg'  => "Database error",
				];
			} else {
				while ($res = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
					$row = (object)$res;
					$result2[$row->mcustno][] = $row;
					// array_push($result2, (object)$res);
				}

				if ($conn->action != 'excel') {
					while ($res = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$row = (object)$res;
						var_dump($row);
						exit;

						if (!empty($row->mcustno)) {
							if ($row->type == 'main') {
								// array_push($result, $row);
								$result[$row->mcustno][] = $row;
								array_push($cus_main, $row->mcustno);
							} else {
								if ($this->CURUSER->user[0]->user_type == 'Cus') {
									// array_push($result, $row);
									$result[$row->mcustno][] = $row;
								} else {
									$main = $this->checkMainBill($row->mcustno)->items;
									if (!empty($main->mcustno)) {
										if (!in_array($main->mcustno, $cus_main)) {
											// $row->mcustno = $main->mcustno;
											// $row->cus_name = $main->cus_name;
											// $row->send_date = $main->send_date;
											// $row->type = $main->type;
											// $row->msaleorg = $main->msaleorg;
											// array_push($result, $main);
											// var_dump($row);
											// exit;
											$result[$row->mcustno][] = $main;
											array_push($cus_main, $main->mcustno);
										}
									} else {
										$result[$row->mcustno][] = $row;
										array_push($cus_main, $row->mcustno);
									}
								}
							}
						}
					}
				}

				$lists = $conn->action != 'excel' ? $this->processData($result) : $this->processData($result2);


				$output = (object)[
					'status' => 200,
					'items'  => $lists,
					'totalRecord' => !empty($result2) ? count($result2) : 0,
					'msg'  => "success",
				];
			}
		} else {
			$output = (object)[
				'status' => 200,
				'items'  => [],
				'totalRecord' => 0,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function checkIsContact($isContact)
	{
		// 2 => all emaill
		// 3 => all fax
		// 4 => Email & Fax
		// 5 => No Fax
		// 6 => No Email
		// 7 => No Fax & No Email
		// 8 => Email & No Fax
		// 9 => No Email & Fax

		switch ($isContact) {
			case '2':
				return (object) ['is_email' => 1, 'is_fax' => 2];
				break;
			case '3':
				return (object) ['is_email' => 2, 'is_fax' => 1];
				break;
			case '4':
				return (object) ['is_email' => 1, 'is_fax' => 1];
				break;
			case '5':
				return (object) ['is_email' => 2, 'is_fax' => 0];
				break;
			case '6':
				return (object) ['is_email' => 0, 'is_fax' => 2];
				break;
			case '7':
				return (object) ['is_email' => 0, 'is_fax' => 0];
				break;
			case '8':
				return (object) ['is_email' => 1, 'is_fax' => 0];
				break;
			case '9':
				return (object) ['is_email' => 'NULL,0', 'is_fax' => 1];
				break;
		}

		return 2;
	}


	public function processData($result)
	{
		$lists = [];
		foreach ($result as $key => $rows) {
			$data = (object)[
				'cus_no' => $this->CURUSER->user[0]->user_type == 'Cus' ?  $key :  $result[$key][0]->mcustno
				// 'cus_no' => $result[$key][0]->mcustno
			];

			foreach ($rows as $val) {
				$data->cus_name = !empty($val->cus_name) || $val->cus_name != '-' ? $val->cus_name : '-';
				$data->msaleorg = !empty($val->msaleorg) ? $val->msaleorg : '-';
				$data->start_date = !empty($val->start_date) ? $val->start_date : '-';
				$data->end_date = !empty($val->end_date) ? $val->end_date : '-';
				$data->send_date = !empty($val->send_date) ? $val->send_date : '-';

				if (!empty($val->mdoctype)) {
					if ($val->mdoctype == 'DC') {
						$data->DC = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'RA') {
						$data->RA = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'RB') {
						$data->RB = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'RC') {
						$data->RC = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'RD') {
						$data->RD = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'RE') {
						$data->RE = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'DA') {
						$data->DA = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'DB') {
						$data->DB = floatval($val->total_mnetamt);
					}
					if ($val->mdoctype == 'DE') {
						$data->DE = floatval($val->total_mnetamt);
					}
				}
			}
			$lists[$key] = $data;
		}

		// var_dump($this->calculateTotall($lists));
		// exit;
		return  $this->calculateTotall($lists);
	}

	public function getCustomerChain($id)
	{

		$result = [];
		$lists = $this->checkSendtoMain($id)->items;
		if (!empty($lists)) {
			foreach ($lists as $val) {
				$customer = $this->model_system->findCustomerById($val)->items;
				array_push($result, $customer);
			}
		}

		return $result;
	}

	public function calculateTotall($result)
	{
		$data = [];
		foreach ($result as $res) {
			$total = 0;
			if (!empty($res->RA)) {
				$total = $total + $res->RA;
			}
			if (!empty($res->RD)) {
				$total = $total + $res->RD;
			}
			if (!empty($res->DC)) {
				$total = $total - $res->DC;
			}
			if (!empty($res->RB)) {
				$total = $total - $res->RB;
			}
			if (!empty($res->RC)) {
				$total = $total - $res->RC;
			}
			if (!empty($res->DA)) {
				$total = $total + $res->DA;
			}
			if (!empty($res->DB)) {
				$total = $total - $res->DB;
			}
			if (!empty($res->DE)) {
				$total = $total - $res->DE;
			}

			$res->balance =  $total;

			array_push($data, $res);
		}


		return $data;
	}

	public function findChildCustomer($condition)
	{
		$result = [];
		// $join = " left join " . TBL_CUT . " on " . TBL_CUT . ".mcustno = " . VW_Customer . ".mcustno";
		$sql =  "SELECT MAX(" . VW_Customer . ".mcustno) as cus_no,MAX(" . VW_Customer . ".mcustname) as cus_name,MAX(" . VW_Customer . ".msaleorg) as msaleorg FROM " . VW_Customer . " left join " . SENTO_CUS . " on " . SENTO_CUS . ".cus_main = " . VW_Customer . ".mcustno where " . SENTO_CUS . ".cus_main = '$condition->cus_no' AND " . SENTO_CUS . ".is_check = 1 group by mcustno";

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

	public function findCustomerDefault($condition)
	{
		$result = [];
		$sql =  "SELECT MAX(mcustno) as cus_no,MAX(mcustname) as cus_name,MAX(msaleorg) as msaleorg FROM " . VW_Customer . " where mcustno = '$condition->cus_no' group by mcustno";

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

	public function getBillChild($condition)
	{
		$result = [];
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];

		$sql =  "SELECT " . BILLPAY . ".macctdoc," . BILLPAY . ".mdoctype," . BILLPAY . ".mnetamt," . BILLPAY . ".msaleorg," . BILLPAY . ".mduedate," . BILLPAY . ".mbillno," . BILLPAY . ".mdocdate FROM " . BILLPAY . " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . BILLPAY . ".mcustno  where " . BILLPAY . ".mcustno = '$condition->cus_no' AND " . BILLPAY . ".mduedate between '$condition->start_date' and '$condition->end_date' AND " . CUSTOMER . ".send_date = '$condition->send_date'";

		if (!empty($condition->type) && $condition->type !== '1') {
			$sql =  $sql . ' AND ' . BILLPAY . ".msaleorg = " . "$condition->type";
		}

		if (!empty($doctypeLists)) {
			$sql =  $sql . ' AND ' . BILLPAY . ".mdoctype in ('" . implode("','", array_keys($doctypeLists)) . "')";
		}

		// echo '<pre>';
		// var_dump($sql);
		// exit;
		// echo '</pre>';

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

			usort($result, function ($a, $b) {
				return $this->checkType($a->mdoctype) > $this->checkType($b->mdoctype);
			});

			// echo '<pre>';
			// var_dump($result);
			// exit;
			// echo '</pre>';

			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function summaryBills($condition)
	{
		$result = [];
		$sql =  "SELECT "  . BILLPAY . ".mdoctype,SUM(" . BILLPAY . ".mnetamt) as mnetamt FROM " . BILLPAY . " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . BILLPAY . ".mcustno  where " . BILLPAY . ".mcustno in ($condition->cus_no) AND " . BILLPAY . ".mduedate between '$condition->start_date' and '$condition->end_date' AND " . CUSTOMER . ".send_date = '$condition->send_date' group by "  . BILLPAY . ".mdoctype ";

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

			usort($result, function ($a, $b) {
				return $a->mnetamt < $b->mnetamt;
			});

			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function getDetailCustomer($params)
	{
		$childs = [];
		$summary = [];
		$val = json_decode(json_encode($params));
		$customer = (object)[];
		if (!empty($val)) {
			$customer = $this->model_system->findCustomerById($val->cus_no)->items;
			$res = $customer->type == 'main' && !empty($customer) ? $this->getCustomerChain($val->cus_no) : $this->findCustomerDefault($val)->items;
			$child_cus = [];

			foreach ($res as $bill) {
				$val->cus_no = $bill->cus_no;
				$bills = $this->getBillChild($val)->items;
				$data = (object)[
					'info' => (object)[
						'cus_no' => $bill->cus_no,
						'cus_name' => $bill->cus_name,
						'saleorg' =>  !empty($bill->saleorg) ? $bill->saleorg : $bill->msaleorg
					],
					'bills' => [],
					'balance' => (object)[
						'total_balance' => 0,
						'total_RA' => 0,
						'total_RD' =>  0,
						'total_DC' => 0,
						'total_RB' => 0,
						'total_RC' => 0,
						'total_RE' => 0,
						'total_DA' => 0,
						'total_DB' => 0,
						'total_DE' => 0
					],
				];
				if (!empty($bills)) {
					$data->bills = $bills;
					$sum_balance = $this->calculateTotallChild($bills);
					$data->balance->total_balance = $sum_balance->total_balance;
					$data->balance->total_RA = $sum_balance->total_RA;
					$data->balance->total_RD = $sum_balance->total_RD;
					$data->balance->total_DC = $sum_balance->total_DC;
					$data->balance->total_RB = $sum_balance->total_RB;
					$data->balance->total_RC = $sum_balance->total_RC;
					$data->balance->total_RE = $sum_balance->total_RE;
					$data->balance->total_DA = $sum_balance->total_DA;
					$data->balance->total_DB = $sum_balance->total_DB;
					$data->balance->total_DE = $sum_balance->total_DE;
					$childs[$bill->cus_no] = $data;
				}

				array_push($child_cus, $bill->cus_no);
			}

			if (!empty($child_cus)) {
				$val->cus_no = implode(',', $child_cus);
				$summary = $this->summaryBills($val)->items;
			}
		}

		$lists = (object) [
			'cus_no' => $val->cus_no,
			'cus_name' => !empty($customer) && !empty($customer->mcustname)  ? $customer->mcustname : '-',
			'childs' => $childs,
			'total_summary' => $this->calculateTotallChild($summary)
		];
		return $lists;
	}

	public function calculateTotallChild($result)
	{
		$total_balance = 0;
		$total_RA = 0;
		$total_RD =  0;
		$total_DC = 0;
		$total_RB = 0;
		$total_RC = 0;
		$total_RE = 0;
		$total_DB = 0;
		$total_DA = 0;
		$total_DE = 0;

		foreach ($result as $res) {
			if (!empty($res->mdoctype == 'RA')) {
				$total_balance = $total_balance + $res->mnetamt;
				$total_RA = $total_RA + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RD')) {
				$total_balance = $total_balance + $res->mnetamt;
				$total_RD = $total_RD + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DC')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_DC = $total_DC + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RB')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_RB = $total_RB + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RC')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_RC = $total_RC + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'RE')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_RE = $total_RE + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DA')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_DA = $total_DA + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DB')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_DB = $total_DB + $res->mnetamt;
			}
			if (!empty($res->mdoctype == 'DE')) {
				// $total_balance = $total_balance - $res->mnetamt;
				$total_DE = $total_DE + $res->mnetamt;
			}
		}

		return (object)['total_balance' => $total_balance, 'total_RA' => $total_RA, 'total_RD' => $total_RD, 'total_DC' => $total_DC, 'total_RB' => $total_RB, 'total_RC' => $total_RC, 'total_RE' => $total_RE, 'total_DB' => $total_DB, 'total_DA' => $total_DA, 'total_DE' => $total_DE];
	}


	public function createInvoice($params)
	{
		$sql = "INSERT INTO " . REPORT . ' (' . $this->tableAllowFieldsInvoice . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return true;
		}
		// var_dump(sqlsrv_errors());
		// exit;
		return FALSE;
	}

	public function createDetailInvoice($params)
	{
		$sql = "INSERT INTO " . REPORT_DETAIL . ' (' . $this->tableAllowFieldsInvoiceDatail . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?,?, ?, ?, ?)";
		$res = sqlsrv_query($this->conn, $sql, $params);
		if (!empty($res)) {
			return true;
		}

		return FALSE;
	}

	public function getReportId($main_id)
	{
		$result = (object)[];
		$sql =  "SELECT MAX(bill_no) as bill_no,MAX(created_date) as created_date,MAX(cus_main) as cus_main FROM " . REPORT . " where cus_main = '$main_id' group by " . REPORT . ".bill_no order by " . REPORT . ".bill_no desc";

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


	public function getReportUuid($uuid)
	{
		$result = (object)[];
		// $sql =  "SELECT MAX(" . REPORT . ".bill_no) as bill_no, MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . REPORT . ".uuid) as uuid,MAX(" . CUSTOMER . ".cus_name) as cus_name,MAX(" . CUSTOMER . ".is_email) as is_email,MAX(" . CUSTOMER . ".is_fax) as is_fax FROM " . REPORT . "left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . REPORT . ".cus_no  where uuid = '$uuid' ";


		$select =  "MAX(" . REPORT . ".bill_no) as bill_no,MAX(" . REPORT . ".cus_no) as cus_no,MAX(" . REPORT . ".cus_main) as cus_main,MAX(" . REPORT . ".start_date) as start_date,MAX(" . REPORT . ".end_date) as end_date,MAX(" . REPORT . ".created_date) as created_date,MAX(" . REPORT . ".uuid) as uuid,MAX(" . CUSTOMER . ".cus_name) as cus_name,MAX(CONVERT(int," . CUSTOMER . ".is_email)) as is_email,MAX(CONVERT(int," . CUSTOMER . ".is_fax)) as is_fax ";

		$join = " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . REPORT . ".cus_no ";

		$sql =  "SELECT $select FROM " . REPORT . "$join" . " where " . REPORT . ".uuid = '$uuid' ";


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
		// var_dump(sqlsrv_errors());
		// var_dump($result);
		// exit;

		return $output;
	}

	public function getReport()
	{
		$result = [];
		$sql =  "SELECT * FROM " . REPORT . " order by " . REPORT . ".created_date desc";
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

	public function getItem($macctdoc)
	{
		$sql =  "SELECT * FROM " . BILLPAY . " where macctdoc = '$macctdoc'";
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


	function findObject($customer, $cus_id, $page = FALSE)
	{

		foreach ($customer as $key => $element) {
			if ($cus_id == $element) {
				return $key;
			}
		}

		return false;
	}

	public function checkSendtoMain($cus_no)
	{
		$result = [];
		$sql =  "SELECT cus_no FROM " . SENTO_CUS . " where cus_main = '$cus_no' OR cus_no = '$cus_no' AND is_check = 1 group by cus_no";
		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				// $res = $row;
				// $result[$res->cus_no] = $res;
				array_push($result, $row['cus_no']);
			}

			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function checkBill($startDate, $endDate)
	{
		$result = [];
		$sql = "SELECT cus_no FROM " . REPORT . " where start_date = '$startDate' AND  end_date = '$endDate' group by cus_no";

		$stmt = sqlsrv_query($this->conn, $sql);

		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				array_push($result, $row["cus_no"]);
			}

			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}

	public function checkMainBill($cus_no)
	{
		$result = (object)[];
		$sql =  "SELECT MAX(" . CUSTOMER . ".cus_no) as mcustno,MAX(CONVERT(int," . SENTO_CUS . ".is_check)) as is_check,MAX(" . CUSTOMER . ".cus_name) as cus_name,MAX(" . CUSTOMER . ".send_date) as send_date,MAX(" . CUSTOMER . ".type) as type,MAX(" . CUSTOMER . ".saleorg) as msaleorg FROM " . SENTO_CUS . " left join " . CUSTOMER . " on " . CUSTOMER . ".cus_no = " . SENTO_CUS . ".cus_main where " . SENTO_CUS . ".cus_no = '$cus_no' AND " . SENTO_CUS . ".is_check = 1 group by " . CUSTOMER . ".cus_no";
		$stmt = sqlsrv_query($this->conn, $sql);


		if ($stmt == false) {
			$output = (object)[
				'status' => 500,
				'error'  => sqlsrv_errors(),
				'msg'  => "Database error",
			];
		} else {
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$result = (object)$row;
				// $result[$res->cus_no] = $res;
				// array_push($result, $res);
			}


			$output = (object)[
				'status' => 200,
				'items'  => $result,
				'msg'  => "success",
			];
		}

		return $output;
	}


	public function checkType($res)
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

	function findObjectReport($customer, $cus_id)
	{

		foreach ($customer as $element) {
			if ($cus_id != $element->cus_no) {
				return $element;
			}
		}

		return false;
	}
}
