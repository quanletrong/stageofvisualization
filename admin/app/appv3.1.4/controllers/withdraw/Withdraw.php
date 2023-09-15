<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw extends MY_Controller {
	
	function __construct()
	{
		$this->_module = trim(strtolower(__CLASS__));
		parent::__construct();
		
        if (!$this->_isLogin())
        {
            if ($this->input->is_ajax_request())
            {
                resError('unlogin');
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        $this->load->model('order/Order_model');
	}
    
    function ajax_get_rut_tien() {
        $cur_uid = $this->_session_uid();
        $rut_tien = $this->Order_model->danh_sach_chua_rut_tien($cur_uid);
        resSuccess($rut_tien);
    }

    function ajax_set_rut_tien() {
        $cur_uid = $this->_session_uid();
        $exc = $this->Order_model->tao_yeu_cau_rut_tien($cur_uid);
        resSuccess($exc);
    }
}