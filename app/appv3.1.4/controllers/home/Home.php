<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('service/Service_model');
        $this->load->model('style/Style_model');
        $this->load->model('setting/Setting_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Home',
            'active_link' => 'home',
            'header_page_css_js' => 'home'
        ];

        $service = $this->Service_model->get_list(1);
        $style = $this->Style_model->get_list(1);

        $setting = $this->Setting_model->get_setting();
        //full path ảnh slide
        $home_slide = json_decode($setting['home_slide'], true);
        foreach ($home_slide as $id => $it) {
            $home_slide[$id]['image'] = url_image($it['image'], FOLDER_SLIDE);
        }
        $setting['home_slide'] = $home_slide;

        //full path ảnh đối tác
        $partner = json_decode($setting['partner'], true);
        foreach ($partner['images'] as $id => $it) {
            $partner['images'][$id]['image'] = url_image($it['image'], FOLDER_PARTNER);
        }
        // path happy_guaranteed
        $happy_guaranteed = json_decode($setting['happy_guaranteed'], true);
        $happy_guaranteed['image_path'] = url_image(@$happy_guaranteed['image'], FOLDER_LOGO); ;
        

        $data['service'] = $service;
        $data['style'] = $style;
        $data['setting'] = $setting;
        $data['partner'] = $partner;
        $data['happy_guaranteed'] = $happy_guaranteed;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'home/home_view', $data);

        $this->_loadFooter();
    }

    function hiw()
    {
        $data = [];

        $header = [
            'title' => 'How it works',
            'active_link' => 'hiw',
            'header_page_css_js' => 'home'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'home/hiw_view', $data);
        $this->_loadFooter();
    }
}
