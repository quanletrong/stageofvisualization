<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {
	
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
        
        $header = [
            'title' => 'Trang tin tức',
            'active_link' => 'news',
            'header_page_css_js' => 'news'
        ];
        
        $this->_loadHeader($header);
        
        $this->load->view($this->_template_f . 'news/news_view', $data);
        
        $this->_loadFooter();
	}
}