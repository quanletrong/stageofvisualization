<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pricing extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('setting/Setting_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => '',
            'active_link' => '',
            'header_page_css_js' => ''
        ];

        
        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'pricing/pricing_view', $data);

        $this->_loadFooter();
    }
}
