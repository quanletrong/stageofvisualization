<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
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

        $this->load->model('order/Order_model');
        $this->load->model('job/Job_model');
        $this->load->model('style/Style_model');
    }

    function index()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        $list_order = $this->Order_model->get_list();
        $header = [
            'title' => 'Quản lý đơn hàng',
            'header_page_css_js' => 'order'
        ];

        $data['list_order'] = $list_order;
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'order/order_view', $data);
        $this->_loadFooter();
    }


    function detail($id_order)
    {
        $data = [];



        if (!isIdNumber($id_order)) {
            dbClose();
            redirect(site_url('order', $this->_langcode));
            die();
        }

        $list_job = $this->Job_model->get_list_job_by_order($id_order);
        if (empty($list_job)) {
            dbClose();
            redirect(site_url('order', $this->_langcode));
            die();
        }

        $data['list_job'] = $list_job;

        $header = [
            'title' => 'Chi tiết đơn hàng',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);
        
        $this->load->view($this->_template_f . 'order/order_detail_view', $data);

        $this->_loadFooter();
    }
}
