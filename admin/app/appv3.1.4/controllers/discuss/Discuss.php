<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Discuss extends MY_Controller
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
        $this->load->model('user/User_model');
    }

    function ajax_discuss_list()
    {

    }

    function ajax_discuss_add()
    {

    }

    function ajax_discuss_edit()
    {

    }

    function ajax_discuss_delete()
    {

    }
}