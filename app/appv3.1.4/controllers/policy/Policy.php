<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Policy extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('setting/Setting_model');
    }

    function policy()
    {
        $data = [];

        $header = [
            'title' => 'PRIVACY POLICY',
            'active_link' => '',
            'header_page_css_js' => ''
        ];

        
        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'policy/policy_view', $data);

        $this->_loadFooter();
    }

    function refund()
    {
        $data = [];

        $header = [
            'title' => 'Refund Policy',
            'active_link' => '',
            'header_page_css_js' => ''
        ];

        
        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'policy/refund_view', $data);

        $this->_loadFooter();
    }
    
    function terms()
    {
        $data = [];

        $header = [
            'title' => 'TERMS OF USE AGREEMENT',
            'active_link' => '',
            'header_page_css_js' => ''
        ];

        
        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'policy/terms_view', $data);

        $this->_loadFooter();
    }
}
