<?php (defined('BASEPATH')) or exit('No direct script access allowed');
class Invoice extends MY_Controller
{
	private $page = 1;
	private $limit = 20;
	private $offset = 0;
	private $search = '';
	private $order = ['0', 'asc'];
	private $column = [];
	private $is_search = FALSE;
	private $condition = [];
	private $queryCondition = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_invoice');
		$this->load->model('model_system');
		$this->load->model('report/model_report');
		$this->load->model('setting/model_setting');
	}


	public function index()
	{
		$days = $this->config->item('day');
		$result = [];
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];
		$table = $this->model_system->getPageIsShow()->items;
		$keyTable = [];
		$this->data['days'] = []; // date('l')
		$dateSelect = $this->input->get('dateSelect') ? $this->input->get('dateSelect') : '';
		$startDate = $this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d', strtotime("monday this week"));
		$endDate = $this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d', strtotime("next sunday"));
		$cus_no = NULL;
		$typeSC = $this->input->get('type') ? $this->input->get('type') : '0281';
		$is_bill = $this->input->get('is_bill') ? $this->input->get('is_bill') : '3';
		$is_contact = $this->input->get('is_contact') ? $this->input->get('is_contact') : '1';
		$is_showType = $this->input->get('is_showType') ? $this->input->get('is_showType') : '1';
		$customer = NULL;

		if ($this->CURUSER->user[0]->user_type == 'Cus') {
			$customer = $this->CURUSER->user_cus->cus_code;
		} else {
			if (!empty($this->input->get('customer'))) {
				$customer = $this->input->get('customer');
			}
		}

		$cus_no =  !empty($customer) ? $this->model_system->findCustomerById($customer)->items : '';

		foreach ($table['invoice'] as $v) {
			array_push($keyTable, $v->sort);
		}

		$types = $this->model_system->getTypeBusiness()->items;

		foreach ($types as $v) {
			$this->data['types'][$v->msaleorg] = $v;
		}

		foreach ($days as $day) {
			$this->data['days'][$day->id] = $day;
		}

		$checkBill = $this->model_invoice->checkBill($startDate, $endDate)->items;

		$this->data['lists'] = $result;
		$this->data['selectDays'] = $this->model_system->getDateSelect()->items;
		$this->data['typeSC'] = $typeSC;
		$this->data['dateSelect'] = $dateSelect;
		$this->data['startDate'] = $startDate;
		$this->data['endDate'] = $endDate;
		$this->data['_customer'] = $cus_no;
		$this->data['cus_no'] = $customer;
		$this->data['table'] = $table['invoice'];
		$this->data['keyTable'] = $keyTable;
		$this->data['is_bill'] = $is_bill;
		$this->data['is_contact'] = $is_contact;
		$this->data['is_showType'] = $is_showType;
		$this->data['checkBill'] = $checkBill;
		$this->data['doctypeLists'] = $doctypeLists;
		$this->data['page_header'] = 'การแจ้งเตือน';
		$this->loadAsset(['dataTables', 'datepicker', 'select2']);
		$this->view('search_invoice');
	}

	public function detail($id)
	{
		$result = [];
		$start = $this->input->get('start');
		$end = $this->input->get('end');
		$send = $this->input->get('send');
		$type = $this->input->get('type');
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];

		// $is_showType = $this->input->get('is_showType');
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];
		$condition = (object)[
			'cus_no' => $id,
			'start_date' => $start,
			'end_date' => $end,
			'send_date' => $send,
			'type' => $type
		];

		if (!empty($id) && !empty($start) && !empty($end) && !empty($send)) {
			$result = $this->model_invoice->getDetailCustomer($condition);
		}

		$this->data['main_id'] = $id;
		$this->data['lists'] = $result;
		$this->data['start'] = $start;
		$this->data['end'] = $end;
		$this->data['send'] = $send;
		$this->data['type'] = $type;
		$this->data['mduedate'] = !empty($result->childs[explode(',', $result->cus_no)[0]]->bills[0]->mduedate) ? $result->childs[explode(',', $result->cus_no)[0]]->bills[0]->mduedate : '';
		$this->data['doctypeLists'] = $doctypeLists;
		$this->data['page_header'] = 'รายละเอียดการแจ้งเตือน';
		$this->view('invoice_detail');
	}


	public function create($cus_main, $start, $end, $sendDate)
	{

		$output = $this->apiDefaultOutput();
		$params = $this->input->post();

		if (!empty($params)) {
			array_walk_recursive($params, function (&$v) {
				$v = strip_tags(trim($v));
			});
		}

		// var_dump($params);
		// exit;
		$invoice =  $params['cf_invoice'];
		$getID = [];
		$reportMain = [];
		$mduedate = [];
		foreach ($invoice as $key => $val) {
			$spiltInvoice = explode('|', $val);
			$getID[$spiltInvoice[1]][] = $spiltInvoice[0];
			if ($key == 0) {
				$mduedate[$spiltInvoice[1]] = $spiltInvoice[2];
			}
		}

		if (!empty($getID)) {
			foreach ($getID as $key => $res) {
				$uuid = genRandomString(16);
				$data = [
					$cus_main,
					$key,
					$this->ramdomBillNo($cus_main),
					FALSE,
					date("Y-m-d H:i:s"),
					$uuid,
					$start,
					$end,
					FALSE,
					date("Y-m-d H:i:s"),
					$this->CURUSER->user[0]->userdisplay_th,
					NULL,
					$mduedate[$key],
					$sendDate
				];

				$this->model_invoice->createInvoice($data);
				$report = $this->model_invoice->getReportUuid($uuid)->items;
				$report->mduedate = $mduedate[$key];
				$reportMain[$key] = $report;

				$genData = [];

				if (!empty($report)) {
					$genData = $this->genInvoiceDetail($report, $res, $key, $cus_main);

					if (empty($genData)) {
						$output['status'] = 500;
						$output['msg'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
						$output['error'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
					}
				} else {
					$output['status'] = 500;
					$output['msg'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
					$output['error'] = 'ไม่สามารถสร้างใบแจ้งเตือนได้';
				}
			}

			// var_dump($reportMain);
			// exit;
			$checkMain = !empty($reportMain[$cus_main]) ? true : false;
			foreach ($reportMain as $key => $report) {
				$email = $this->genEmail((array)$report, 'invoice', $checkMain);
				if ($email->status == 204) {
					$output['status'] = 204;
					$output['data'] = (object)['data' => false, 'msg' => 'No email'];
				} else if ($email->status == 500) {
					$output['status'] = 500;
					$output['msg'] = $email->msg;
					$output['error'] = $email->error;
				}
			}

			$output['status'] = 200;
			$output['data'] = (object)['data' => $reportMain];
		}

		$output['source'] = $params;
		$this->responseJSON($output);
	}

	public function genInvoiceDetail($report, $res, $key, $cus_main)
	{
		set_time_limit(0);
		// foreach ($result as $res) {
		foreach ($res as $val) {
			$item = $this->model_invoice->getItem($val)->items;
			if (!empty($item)) {
				$data = [
					genRandomString(16),
					$report->bill_no,
					$report->uuid,
					$val,
					$key,
					$cus_main,
					$item->mdoctype,
					$item->mbillno,
					$item->mpostdate,
					$item->mduedate,
					$item->msaleorg,
					$item->mpayterm,
					$item->mnetamt,
					$item->mtext,
					$this->genType($item->mdoctype),
					$item->mdocdate
				];

				$this->model_invoice->createDetailInvoice($data);
			}
		}

		return true;
	}

	public function genCustomerChild($id)
	{
		$result = ['status' => 500, 'msg' => 'Can not check data !'];


		if (!empty($id)) {
			$childLists = $this->model_invoice->getCustomerChain($id);

			if (!empty($childLists)) {
				$result['status'] = 200;
				$result['msg'] = 'OK';
				$result['data'] = $childLists;
			} else {
				$result['status'] = 204;
				$result['msg'] = 'empty data';
				$result['data'] = false;
			}
		}

		$this->responseJSON($result);
	}


	public function genInvoiceListExcel()
	{
		$table = $this->model_system->getPageIsShow()->items;
		$params = $this->input->get();
		$keyTable = [];
		$types = [];
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];

		foreach ($table['invoice'] as $v) {
			array_push($keyTable, $v->sort);
		}

		$o = $this->model_system->getTypeBusiness()->items;
		foreach ($o as $v) {
			$types[$v->msaleorg] = $v;
		}

		$condition = [
			'dateSelect' =>  !empty($params['dateSelect']) ? $params['dateSelect'] : '',
			'startDate' => !empty($params['startDate']) ? $params['startDate'] : date('Y-m-d'),
			'endDate' => !empty($params['endDate']) ? $params['endDate'] :  date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d')))),
			'cus_no' => !empty($params['cus_no']) ? $params['cus_no'] : '',
			'type' => !empty($params['type']) ? $params['type'] : '0281',
			'is_bill' => !empty($params['is_bill']) ? $params['is_bill'] : '3',
			'is_contact' => !empty($params['is_contact']) ? $params['is_contact'] : '1',
			'is_showType' => !empty($params['is_showType']) ? $params['is_showType'] : '1',
			'action' => 'excel'
		];

		if (!empty($condition["dateSelect"]) && $params['startDate'] && $params['endDate']) {
			$result = !empty($this->model_invoice->getInvoice($condition)->items) ? $this->model_invoice->getInvoice($condition)->items  : [];
			$checkBill = $this->model_invoice->checkBill($params['startDate'], $params['endDate'])->items;
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="invoice.xls"');
			$data['result'] = (object)['data' => $result, 'header' => $table['invoice'], 'keyTable' => $keyTable, 'types' => $types, 'checkBill' => $checkBill, 'doctypeLists' => $doctypeLists];
			$this->load->view('export_invoice', $data);
		}
	}

	public function listInvoice()
	{
		$result = [];
		$total_filter = 0;
		$this->setPagination();
		$this->queryCondition['page'] = $this->page;
		$this->queryCondition['limit'] = $this->limit;
		$this->queryCondition['action'] = 'lists';


		// var_dump($this->input->get());
		// exit;
		$params = $this->input->get();
		if (!empty($params)) {
			if (!empty($params['cus_no'])) {
				$this->queryCondition['cus_no'] = $params['cus_no'];
			}

			if (!empty($params['dateSelect'])) {
				$this->queryCondition['dateSelect'] = $params['dateSelect'];
			}
			if (!empty($params['startDate'])) {
				$this->queryCondition['startDate'] = $params['startDate'];
			}

			if (!empty($params['endDate'])) {
				$this->queryCondition['endDate'] = $params['endDate'];
			}

			if (!empty($params['type'])) {
				$this->queryCondition['type'] = $params['type'];
			}

			if (!empty($params['is_bill'])) {
				$this->queryCondition['is_bill'] = $params['is_bill'];
			}

			if (!empty($params['is_contact'])) {
				$this->queryCondition['is_contact'] = $params['is_contact'];
			}

			if (!empty($params['is_showType'])) {
				$this->queryCondition['is_showType'] = $params['is_showType'];
			}
		}

		$total = 0;


		if ($apiData = $this->model_invoice->getInvoice($this->queryCondition)) {
			if (!empty($apiData->error)) {
				$this->responseJSON(['error' => $apiData->error]);
			} else {
				if (!empty($apiData->items)) {
					$result = $apiData->items;
					$total = $apiData->totalRecord;
					$total_filter = $apiData->totalRecord;
				}
			}
		}

		$this->responseDataTable($result, $total, $total_filter);
	}


	private function setPagination()
	{
		$limit = (int) $this->input->get('length', TRUE);
		$offset = (int) $this->input->get('start', TRUE);
		$search = $this->input->get('search', TRUE);
		// $order = $this->input->get('order', TRUE);
		$this->column = $this->input->get('columns', TRUE);

		if (!empty($limit)) {
			$this->limit = $limit;
		}
		if (!empty($offset)) {
			$this->offset = $offset;
		}
		$this->page = floor($this->offset / $this->limit) + 1;
		if (!empty($search['value'])) {
			$this->search = $search['value'];
			$this->is_search = TRUE;
		}
		// $field_name = $this->column[$order[0]['column']]['data'];
		// $this->order = [$order[0]['column'], $order[0]['dir'], $field_name];

		return $this;
	}

	public function genExcel($uuid)
	{
		$result = $this->model_report->genPDF($uuid, 'excel');
		$doctypeLists = !empty($this->model_system->getDoctypeShow()->items) ? $this->model_system->getDoctypeShow()->items : [];
		$tem = $this->model_system->getTemPDF()->items;
		$size = count($result->lists);
		$data['data'] = (object)['index' => 1, 'report' => $result, 'size' => $size, 'tem' => $tem, 'doctypeLists' => $doctypeLists];
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="Report_' . $result->bill_info->bill_no . '.xls"');
		$this->load->view('export_excel', $data);
	}
}
