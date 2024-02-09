<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Setting extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_setting');
		$this->load->model('model_invoice');
		$this->load->model('model_system');
	}


	public function index()
	{
		$result = !empty($this->model_setting->getPage()->items) ? $this->model_setting->getPage()->items : [];
		$doctype = !empty($this->model_setting->doctype()->items) ? $this->model_setting->doctype()->items : [];
		$tab = (!empty($this->input->get('tab', TRUE)) ? $this->input->get('tab', TRUE) : ($this->CURUSER->user[0]->dep_id != 4 &&  $this->CURUSER->user[0]->user_type == 'Emp' ?  'invoice' : 'doctype'));
		$days = $this->config->item('day');
		$this->data['days'] = [];
		$startDate =  date('Y-m-d', strtotime("monday this week"));
		$endDate = date('Y-m-d', strtotime("next sunday"));
		$typeSC =  '0281';
		$is_contact =  '1';
		$o = $this->model_system->getTypeBusiness()->items;
		$startDefault = date('Y-m-d', strtotime("-6 month", strtotime(date('Y-m-d'))));
		$endDefault = date('Y-m-d', strtotime("+7 year", strtotime(date('Y-m-d'))));
		$bccEmail = !empty($this->model_system->getbccEmail()->items) ? $this->model_system->getbccEmail()->items : (object)[];

		foreach ($o as $v) {
			$this->data['types'][$v->msaleorg] = $v;
		}

		foreach ($days as $day) {
			$this->data['days'][$day->id] = $day;
		}

		$this->data['lists'] = $result;
		$this->data['doctype'] = $doctype;
		$this->data['tab'] = $tab;
		$this->data['page_header'] = 'แก้ไขคอลัมน์และซ่อมข้อมูล';
		$this->data['selectDays'] = $this->model_system->getDateSelect()->items;
		$this->data['typeSC'] = $typeSC;
		$this->data['startDate'] = $startDate;
		$this->data['endDate'] = $endDate;
		$this->data['is_contact'] = $is_contact;
		$this->data['startDefault'] = $startDefault;
		$this->data['endDefault'] = $endDefault;
		$this->data['bccEmail'] = $bccEmail->bcc_email;
		$this->loadAsset(['dataTables', 'datepicker', 'select2', 'parsley']);
		$this->view('setting_form');
	}

	public function create()
	{
		$list = $this->config->item('page');

		foreach ($list as $page) {
			foreach ($page->colunm as $val) {
				$params = [
					genRandomString(16),
					$page->page,
					$val->name,
					$val->id,
					$page->id,
					1
				];
				$this->model_setting->create($params);
			}
		}
	}

	public function create_paymentPDF()
	{
		$list = $this->config->item('pdf_tem');
		foreach ($list as $page) {
			$params = [
				genRandomString(16),
				$page->page,
				$page->company,
				$page->address,
				$page->tel,
				$page->tel2,
				$page->tax,
				$page->account_no,
				$page->account_name,
				$page->image_name,
				$page->bank_name,
				$page->branch,
				$page->comp_code,
				$page->due_detail,
				$page->cal,
				$page->contact,
				$page->type,
				$page->payment_title,
				$page->detail_1_1,
				$page->detail_1_2,
				$page->detail_2,
				$page->detail_2_1,
				$page->detail_2_2,
				$page->detail_2_3,
				$page->detail_2_4,
				$page->detail_2_5,
				$page->detail_2_6,
				$page->detail_2_7,
				$page->detail_2_8,
				$page->detail_3,
				$page->detail_4,
				$page->detail_5,
				$page->sort,
				$page->tran_header,
				$page->tran_detail_1,
				$page->tran_detail_2,
				$page->tran_detail_3,
			];
			$this->model_setting->create_tem_pdf($params);
		}
	}

	public function process($page)
	{
		$output = $this->apiDefaultOutput();
		$params = $this->input->post();
		$result = $this->model_setting->getPage()->items;


		if (!empty($params)) {
			$formData = [];
			if ($page == 'invoice') {
				$formData = $result['invoice'];
			} else if ($page == 'report') {
				$formData = $result['report'];
			} else {
				$formData = $result['customer'];
			}

			foreach ($formData as $val) {
				if (in_array($val->uuid, $params['is_show'])) {
					$this->model_setting->updateSetting($val->uuid, [1]);
				} else {
					$this->model_setting->updateSetting($val->uuid, [0]);
				}
			}

			$output['status'] = 200;
			$output['data'] = $params;
			unset($output['error']);
		}

		$output['source'] = $params;
		$this->responseJSON($output);
	}

	public function processDoctype()
	{
		$output = $this->apiDefaultOutput();
		$params = $this->input->post();
		$formData = $this->model_setting->doctype()->items;

		if (!empty($params)) {
			foreach ($formData as $key => $val) {
				if (in_array($val->uuid, $params['is_show'])) {
					$this->model_setting->updateDocTypeShow($val->uuid, [1]);
				} else {
					$this->model_setting->updateDocTypeShow($val->uuid, [0]);
				}

				$this->model_setting->updateDocTypeDate($val->uuid, [$params['show-startDate'][$key], $params['show-endDate'][$key]]);
			}

			$output['status'] = 200;
			$output['data'] = $params;
			unset($output['error']);
		}

		$output['source'] = $params;
		$this->responseJSON($output);
	}

	public function processBccEmail()
	{
		$output = $this->apiDefaultOutput();
		$params = $this->input->post();

		if (!empty($params)) {
			$this->model_setting->updateBcc([trim($params['bcc_email']),date('Y-m-d H:i')]);

			$output['status'] = 200;
			$output['data'] = $params;
			unset($output['error']);
		}

		$output['source'] = $params;
		$this->responseJSON($output);
	}

	public function repair()
	{
		$output = $this->apiDefaultOutput();
		$params = $this->input->post();

		if (!empty($params)) {
			$checkReport = $this->model_setting->checkReport((object)$params);
			$params['action'] = 'repair';
			if (!empty($checkReport->key)) {
				set_time_limit(0);
				$params['cusNoreport'] = array_merge($checkReport->key, ['0002000220', '0002000111']);
				$checkNoreport = $this->model_setting->getInvoice((object)$params)->items;

				if (!empty($checkNoreport)) {
					$process = $this->processJob($checkNoreport, $params['startDate'], $params['endDate'], $this->CURUSER->user[0]->userdisplay_th, $params['dateSelect']);
				}

				$checkMain = !empty($this->findObjectSendToRepair($checkReport->report)) ? true : false;
				foreach ($checkReport->report as $key => $report) {
					$email = $this->genEmail((array)$report, 'invoice', $checkMain);
					if ($email->status == 204) {
						$output['status'] = $email->status;
						$output['data'] = $email->data;
						$output['msg'] = $email->msg;
						unset($output['error']);
					} else if ($email->status == 500) {
						$output['status'] = $email->status;
						$output['msg'] = $email->msg;
						$output['error'] = $email->error;
					}
				}

				$output['status'] = $email->status;
				$output['data'] = $email->data;
				unset($output['error']);
			} else {
				$result = $this->model_setting->getInvoice((object)$params)->items;
				$process = $this->processJob($result, $params['startDate'], $params['endDate'], $this->CURUSER->user[0]->userdisplay_th, $params['dateSelect']);
				if (!empty($process)) {
					if ($process->status == 200) {
						$output['status'] = $process->status;
						$output['data'] = $process->data;
						unset($output['error']);
					} else if ($process->status == 204) {
						$output['status'] = $process->status;
						$output['msg'] = $process->msg;
						$output['data'] = $process->data;
						unset($output['error']);
					} else {
						$output['status'] = $process->status;
						$output['msg'] = $process->msg;
						$output['error'] = $process->error;
					}
				}
			}
		}

		$output['source'] = $params;
		$this->responseJSON($output);
	}

	function findObjectSendTo($data)
	{

		foreach ($data as $element) {
			foreach ($element as $res) {
				if (!empty($res->cus_main) && !empty($res->cus_no)) {
					if ($res->cus_main == $res->cus_no) {
						return true;
						break;
					}
				}
			}
		}

		return false;
	}

	function findObjectSendToRepair($data)
	{
		foreach ($data as $res) {
			if (!empty($res->cus_main) && !empty($res->cus_no)) {
				if ($res->cus_main == $res->cus_no) {
					return true;
					break;
				}
			}
		}

		return false;
	}


	public function genInvoiceDetail($report, $lists)
	{
		set_time_limit(0);
		if (!empty($report) && !empty($lists)) {
			foreach ($lists as $res) {
				$data = [
					genRandomString(16),
					$report->bill_no,
					$report->uuid,
					$res->macctdoc,
					$res->mcustno,
					$res->msendto,
					$res->mdoctype,
					$res->mbillno,
					$res->mpostdate,
					$res->mduedate,
					$res->msaleorg,
					$res->mpayterm,
					$res->mnetamt,
					$res->mtext,
					$this->genType($res->mdoctype),
					$res->mdocdate
				];

				$this->model_invoice->createDetailInvoice($data);
			}

			return true;
		}
		return false;
	}

	public function processJob($result, $start, $end, $created_by = FALSE, $sendDate)
	{
		if (!empty($result)) {
			foreach ($result as $key => $res) {
				$uuid = genRandomString(16);
				$data = [
					$result[$key][0]->msendto,
					$key,
					$this->ramdomBillNo($result[$key][0]->msendto),
					FALSE,
					date("Y-m-d H:i:s"),
					$uuid,
					$start,
					$end,
					FALSE,
					date("Y-m-d H:i:s"),
					!empty($created_by) ? $created_by : NULL,
					NULL,
					$res[0]->mduedate,
					$sendDate
				];

				$this->model_invoice->createInvoice($data);
				$report = $this->model_invoice->getReportUuid($uuid)->items;
				$report->mduedate = $res[0]->mduedate;
				$reportMain[$key] = $report;
				$genData = [];

				if (!empty($report)) {
					$genData = $this->genInvoiceDetail($report, $result[$key]);
					if (empty($genData)) {
						return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
					}
				} else {
					return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
				}
			}

			$checkMain = !empty($this->findObjectSendTo($result)) ? true : false;
			foreach ($reportMain as $key => $report) {
				$email = $this->genEmail((array)$report, 'invoice', $checkMain);
				if ($email->status == 204) {
					return (object)['status' => 204, 'data' => $email->data, 'msg' => $email->msg];
				} else if ($email->status == 500) {
					return (object)['status' => 500, 'msg' => $email->msg, 'error' => $email->msg];
				}
			}

			return (object)['status' => 200, 'data' => (object)['data' => $reportMain]];
		} else {
			return (object)['status' => 500, 'msg' => 'ไม่พบข้อมูลค่าใช้จ่าย', 'error' => 'ไม่พบข้อมูลค่าใช้จ่าย'];
		}
	}


	public function cronJob()
	{
		ini_set('memory_limit', '512M');

		$dateSelect = $this->model_system->getDateSelect()->items;
		$defaultSelectDate = [];
		foreach ($dateSelect as $date) {
			$defaultSelectDate[] = $date->mday;
		}
		//date('l', strtotime("+1 day", strtotime(date('l'))))
		$params = (object)[
			'dateSelect' => date('l'), //date('l') ,'Thursday'
			'startDate' => date('Y-m-d', strtotime("monday this week")), //date('Y-m-d'),date('Y-m-d', strtotime("monday this week"))
			'endDate' => date('Y-m-d', strtotime("next sunday")), //date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d')))),date('Y-m-d', strtotime("next sunday"))
			'type' => '0281',
			'action' => 'c'
		];

		//        if (in_array(date('l'), $defaultSelectDate) && !empty($params)) {
		if (in_array(date('l'), $defaultSelectDate) && !empty($params)) {
			echo '<pre>Start Job Success </pre>';
			echo '<pre>วันที่ต้องการแจ้ง ' . $params->dateSelect . ' ตั้งแต่ ' . $params->startDate  . '-' . $params->endDate . '</pre>';
			$result = $this->model_setting->getInvoice($params)->items;
			echo '<p>Total ' . count(array_keys($result)) . ' รหัสลูกค้า ' . implode(",\r\n", array_keys($result)) . '</p>';

			$success = [];
			$noSuccess = [];
			$noSendEmail = [];
			$fax = [];

			if (!empty($result)) {
				foreach ($result as $key => $lists) {
					$process = $this->runJob($lists, $params->startDate, $params->endDate, 'System', $params->dateSelect);
					if (!empty($process)) {
						if ($process->status == 200) {
							array_push($success, $key);
						} else if ($process->status == 204) {
							if (strpos($process->msg, 'Fax') > 0) {
								array_push($fax, $key);
							} else {
								array_push($noSendEmail, $key);
							}
						} else {
							array_push($noSuccess, $key);
						}
					}
				}

				echo '<p>สร้างใบแจ้งเตือนและส่งอีเมลเรียบร้อย ' . implode(",\r\n", $success) . '</p>';
				echo '<p>ส่งผ่าน Fax ' . implode(",\r\n", $fax) . '</p>';
				echo '<p>ไม่พบอีเมลของรหัสลูกค้า ' . implode(",\r\n", $noSendEmail) . '</p>';
				echo '<p>มีปัญหา ' . implode(",\r\n", $noSuccess) . '</p>';

				jobNotifyMessage("\r\n🔃 Start Job Success \nวันที่ต้องการแจ้ง : " . $params->dateSelect . "\nตั้งแต่ : " . $params->startDate . " - " . $params->endDate . "\nTotal : " . count(array_keys($result)) . " \nสร้างใบแจ้งเตือนและส่งอีเมลเรียบร้อย : " . count($success) . " จำนวน \nส่งผ่าน Fax : " . count($fax) . " จำนวน \nรหัสลูกค้า : " . implode(",", $fax) . "\nไม่พบอีเมลของรหัสลูกค้า : " . count($noSendEmail) . " จำนวน \nรหัสลูกค้า : "  . implode(",", $noSendEmail) . " \nมีปัญหา : " . count($noSuccess) . " จำนวน \nรหัสลูกค้า : " .  implode(",", $noSuccess));
			}
		}
	}

	public function createDepartment()
	{
		$list = $this->config->item('department');

		foreach ($list as $department) {
			// var_dump($department->department_id);
			$params = [
				genRandomString(16),
				$department->department_id,
				$department->department_code,
				$department->department_nameLC,
				$department->department_nameEN,
				$department->department_status,
				$department->menu
			];
			$this->model_setting->createDepartment($params);
		}
	}


	public function runJob($result, $start, $end, $created_by = FALSE, $send_date)
	{
		if (!empty($result)) {
			$uuid = genRandomString(16);
			$data = [
				$result[0]->msendto,
				$result[0]->mcustno,
				$this->ramdomBillNo($result[0]->msendto),
				FALSE,
				date("Y-m-d H:i:s"),
				$uuid,
				$start,
				$end,
				FALSE,
				date("Y-m-d H:i:s"),
				!empty($created_by) ? $created_by : NULL,
				NULL,
				$result[0]->mduedate,
				$send_date
			];

			$this->model_invoice->createInvoice($data);
			$report = $this->model_invoice->getReportUuid($uuid)->items;
			$report->mduedate = $result[0]->mduedate;
			$reportMain[$result[0]->msendto] = $report;
			$genData = [];
			if (!empty($report)) {
				$genData = $this->genInvoiceDetail($report, $result);
				if (empty($genData)) {
					return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
				}
			} else {
				return (object)['status' => 500, 'msg' => 'ไม่สามารถสร้างใบแจ้งเตือนได้', 'error' => 'ไม่สามารถสร้างใบแจ้งเตือนได้'];
			}
			// }

			$checkMain = !empty($this->findObjectSendTo($result)) ? true : false;
			foreach ($reportMain as $key => $report) {
				$email = $this->genEmail((array)$report, 'setting', $checkMain);
				if ($email->status == 204) {
					return (object)['status' => 204, 'data' => $email->data, 'msg' => $email->msg];
				} else if ($email->status == 500) {
					return (object)['status' => 500, 'msg' => $email->msg, 'error' => $email->msg];
				}
			}

			return (object)['status' => 200, 'data' => (object)['data' => $reportMain]];
		} else {
			return (object)['status' => 500, 'msg' => 'ไม่พบข้อมูลค่าใช้จ่าย', 'error' => 'ไม่พบข้อมูลค่าใช้จ่าย'];
		}

		// sleep(500);
	}

	public function createDocType()
	{
		$list = $this->config->item('docType');

		foreach ($list as $val) {
			$params = [
				genRandomString(16),
				$val->type,
				$val->type_display_th,
				$val->type_display_en,
				$val->calculateSign,
				$val->msort,
				$val->mstatus,
				$val->is_show,
				$val->start_date,
				$val->end_date
			];
			$this->model_setting->create_docType($params);
		}
	}
}
