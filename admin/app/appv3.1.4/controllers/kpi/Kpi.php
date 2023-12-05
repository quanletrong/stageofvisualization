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
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? redirect(site_url('', $this->_langcode)) : '';

        $filter_fdate   = $this->input->get('filter_fdate');
        $filter_tdate   = $this->input->get('filter_tdate');
        $filter_id_user = $this->input->get('filter_id_user');
        $filter_role = $this->input->get('filter_role');

        //validate filter_id_user
        $all_user = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));
        $filter_id_user = isIdNumber($filter_id_user) ? $filter_id_user : '';
        $filter_id_user = isset($all_user[$filter_id_user]) ? $filter_id_user : '';

        //validate role
        $filter_role = in_array($filter_role, [ADMIN, SALE, QC, EDITOR]) ? $filter_role : '';

        //validate filter date
        $ngay_hien_tai = date("Y-m-d H:i:s");
        $ba_muoi_ngay_truoc = date("Y-m-d H:i:s", strtotime('today - 29 days'));
        $filter_fdate = !is_date($filter_fdate) ? $ba_muoi_ngay_truoc : $filter_fdate;
        $filter_tdate = !is_date($filter_tdate) ? $ngay_hien_tai : $filter_tdate;

        $filter['fdate']   = date("Y-m-d H:i:s", strtotime($filter_fdate));
        $filter['tdate']   = date("Y-m-d H:i:s", strtotime($filter_tdate));
        $filter['id_user'] = $filter_id_user;
        $filter['role']    = $filter_role;

        $list_kpi = $this->Withdraw_model->get_kpi($filter);

        $data = [];
        $data['list_kpi']       = $list_kpi['user'];
        $data['list_service']   = $list_kpi['list_service'];
        $data['all_user']       = $all_user;

        $data['filter_fdate']   = $filter_fdate;
        $data['filter_tdate']   = $filter_tdate;
        $data['filter_id_user'] = $filter_id_user;
        $data['filter_role']    = $filter_role;

        $header = [
            'title' => "KPI từ $filter_fdate đến $filter_tdate",
            'header_page_css_js' => 'voucher'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'kpi/kpi_view', $data);
        $this->_loadFooter();
    }
}
