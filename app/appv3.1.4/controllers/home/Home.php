<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct()
	{
		$this->_module = trim(strtolower(__CLASS__));
		parent::__construct();
		
        
	}

	function index()
	{
        $data = [];
        
        $header = [
            'title' => 'Trang chủ',
            'active_link' => 'home',
            'header_page_css_js' => 'home'
        ];
        
        $this->_loadHeader($header);
        
        $this->load->view($this->_template_f . 'home/home_view', $data);
        
        $this->_loadFooter();
	}
}