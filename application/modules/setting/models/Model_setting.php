<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Model_setting extends MY_Model
{
    private $tableAllowFieldsSetting = ['uuid', 'page_name', 'colunm', 'sort', 'page_sort', 'is_show'];

    public function __construct()
    {
        parent::__construct();
    }

    public function create($params)
    {
        $checkFields = array_fill_keys($this->tableAllowFieldsSetting, 0);
        $create = array_intersect_key($params, $checkFields);
        $res = $this->db->insert('setting', $create);

        if (!empty($res)) {
            return $res;
        }

        return FALSE;
    }

    public function getPage()
    {
        $result = [];
        $lists = [];
        $sql = $this->db->select('MAX(uuid) as uuid,MAX(page_name) as page_name,MAX(CONVERT(int,page_sort)) as page_sort,MAX(CONVERT(int,sort)) as sort,MAX(colunm) as colunm,MAX(CONVERT(int,is_show)) as is_show')
            ->group_by('uuid')
            ->order_by('page_sort asc,sort asc')
            ->get('setting');
        $result = $sql->result();
        $sql->free_result();

        foreach ($result as $val) {
            $lists[$val->page_name][$val->uuid] = $val;
        }
        return $lists;
    }

    public function updateSetting($id, $data)
    {
        $sql = $this->db->set('is_show', $data)->where('uuid', $id)->update('setting');
        if (!empty($sql)) {
            return $sql;
        }

        return FALSE;
    }
}
