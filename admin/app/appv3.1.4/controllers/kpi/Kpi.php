<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kpi extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        if (!$this->_isLogin()) {
            if ($this->input->is_ajax_request()) {
                echo 'unlogin';
                die();
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        // model
        $this->load->model('user/User_model');
        $this->load->model('service/Service_model');
        $this->load->model('withdraw/Withdraw_model');
    }

    function index()
    {
        $filter_id_user = $this->input->get('filter_id_user');
        $filter_role = $this->input->get('filter_role');

        //validate filter_id_user
        $all_user = $this->User_model->get_list_user_working('0,1', implode(",", [ADMIN, SALE, QC, EDITOR]));
        $filter_id_user = isIdNumber($filter_id_user) ? $filter_id_user : '';
        $filter_id_user = isset($all_user[$filter_id_user]) ? $filter_id_user : '';

        //validate role
        $filter_role = in_array($filter_role, [ADMIN, SALE, QC, EDITOR]) ? $filter_role : '';
        
        $filter['id_user'] = $filter_id_user;
        $filter['role']    = $filter_role;

        $list_kpi = $this->Withdraw_model->get_kpi_2($filter);
        // var_dump($list_kpi['user']);die;

        $data = [];
        $data['list_kpi']       = $list_kpi['user'];
        $data['list_service']   = $list_kpi['list_service'];
        $data['all_user']       = $all_user;

        $data['filter_id_user'] = $filter_id_user;
        $data['filter_role']    = $filter_role;

        $header = [
            'title' => "KPI",
            'header_page_css_js' => 'voucher'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'kpi/kpi_view', $data);
        $this->_loadFooter();
    }
}
