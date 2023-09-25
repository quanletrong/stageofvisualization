<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
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
    }

    function order()
    {
        $data = [];
        $header = [
            'title' => 'Order',
            'active_link' => 'user',
            'header_page_css_js' => 'user'
        ];
        $uid = $this->_session_uid();
        $list_order = $this->Order_model->get_list_order_by_id_user($uid);
        $data['list_order'] = $list_order;
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'user/order_view', $data);
        $this->_loadFooter();
    }

    //TODO: check right
    function orderdetail($id_order)
    {
        $data = [];
        $header = [
            'title' => 'Order',
            'active_link' => 'user',
            'header_page_css_js' => 'user'
        ];

        $id_order = isIdNumber($id_order) ? $id_order : 0;

        $order = $this->Order_model->get_info_order($id_order);
        if (empty($order)) {
            dbClose();
            redirect(site_url('user', $this->_langcode));
            die();
        }

        $data['list_type_service']  =  $order['list_type_service'];
        $data['list_job'] = $order['job'];
        $data['order'] = $order;
        $data['FDR_ORDER'] = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'user/order_detail/order_detail_view', $data);
        $this->_loadFooter();
    }
}
