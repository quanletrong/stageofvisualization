<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct()
	{
		$this->_module = trim(strtolower(__CLASS__));
		parent::__construct();
		
        if (!$this->_isLogin())
        {
            if ($this->input->is_ajax_request())
            {
                echo 'unlogin';
                die();
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }
	}

	function index()
	{
        $data = [];
        if($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $header = [
            'title' => 'Trang chủ',
            'header_page_css_js' => 'home'
        ];
        
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'home/home_view', $data);
        $this->_loadFooter();
	}
}