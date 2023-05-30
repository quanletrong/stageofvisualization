<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Library extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('library/Library_model');
        $this->load->model('room/Room_model');
        $this->load->model('style/Style_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Library',
            'active_link' => 'home',
            'header_page_css_js' => 'library'
        ];

        $room = $this->Room_model->get_list(1);
        $style = $this->Style_model->get_list(1);
        $library = $this->Library_model->get_list(1);

        $data['room'] = $room;
        $data['style'] = $style;
        $data['library'] = $library;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'library/library_view', $data);

        $this->_loadFooter();
    }
}
