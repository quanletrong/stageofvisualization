<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('library/Library_model');
        $this->load->model('room/Room_model');
        $this->load->model('style/Style_model');
        $this->load->model('service/Service_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Order',
            'active_link' => 'home',
            'header_page_css_js' => 'order'
        ];

        $room = $this->Room_model->get_list(1);
        $room = $this->Service_model->get_list(1);
        $style = $this->Style_model->get_list(1);
        $library = $this->Library_model->get_list(1);
        $service = $this->Service_model->get_list(1);

        $data['list_room'] = $room;
        $data['list_service'] = $service;
        $data['list_style'] = $style;
        $data['list_library'] = $library;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/order_view', $data);

        $this->_loadFooter();
    }
}
