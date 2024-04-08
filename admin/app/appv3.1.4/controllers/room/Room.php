<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Room extends MY_Controller
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

        // model
        $this->load->model('room/Room_model');
    }

    function index()
    {
        $data = [];
        $header = [
            'title' => 'Quản lý phòng',
            'header_page_css_js' => 'room'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $id_room      = $this->input->post('id_room');
            $name         = $this->input->post('name');
            $status       = $this->input->post('status');
            $id_room   = is_numeric($id_room) && $id_room > 0 ? $id_room : 0;
            $create_time  = date('Y-m-d H:i:s');


            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                //END validate

                $exc = $this->Room_model->add($name, $status, $this->_session_uid(), $create_time);
                $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('room');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                // TODO: validate dữ liệu submit
                //END validate

                $info   = $this->Room_model->get_info($id_room);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    $exc = $this->Room_model->edit($name, $status, date('Y-m-d H:i:s'), $id_room);
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('room');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('room');
            }
        }

        $list =  $this->Room_model->get_list();
        $data['list'] = $list;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'room/room_view', $data);
        $this->_loadFooter();
    }
}
