<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Invoice extends MY_Controller
{

    private $page = 1;
    private $limit = 10;
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
    }


    public function index()
    {
        $days = $this->config->item('day');
        $type = $this->config->item('type');
        $this->data['days'] = [];
        $condition = [
            'dateSelect' => $this->input->get('dateSelect'),
            'startDate' => $this->input->get('startDate'),
            'endDate' => $this->input->get('endDate'),
            'cus_no' => $this->input->get('customer'),
            'type' => $type
        ];
        foreach ($days as $day) {
            $this->data['days'][$day->id] = $day;
        }

        $result = $this->model_invoice->getInvoice($condition);

        if (!empty($result)) {
            $this->data['childLists'] = $this->model_invoice->getCustomerChain($result);
        }

        $this->data['lists'] = $result;
        $this->data['selectDays'] = $this->model_system->getDateSelect();
        $this->data['types'] = $this->model_system->getTypeBusiness();
        $this->data['customers'] = $this->model_system->getCustomer();
        $this->loadAsset(['dataTables', 'datepicker', 'select2']);
        $this->view('search_invoice');
    }

    public function searchInvoice()
    {
    }
}
