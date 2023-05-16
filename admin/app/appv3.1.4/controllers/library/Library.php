<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Library extends MY_Controller
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
        $this->load->model('library/Library_model');
        $this->load->model('style/Style_model');
        $this->load->model('room/Room_model');
    }

    function index()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $header = [
            'title' => 'Quản lý thư viện',
            'header_page_css_js' => 'library'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $name        = $this->input->post('name');
            $sapo        = $this->input->post('sapo');
            $status      = $this->input->post('status');
            $image       = $this->input->post('image');
            $slide        = $this->input->post('slide');
            $id_library  = $this->input->post('id_library');
            $id_library  = is_numeric($id_library) && $id_library > 0 ? $id_library : 0;
            $create_time = date('Y-m-d H:i:s');

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                $copy = copy_image_from_file_manager_to_public_upload($image, date('Y'), date('m'));
                if ($copy['status']) {
                    $exc = $this->Library_model->add($name, $sapo, $copy['basename'], $slide, $status, $this->_session_uid(), $create_time);
                    $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                } else {
                    $msg = $copy['error'];
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('library');
            }

            // CẬP NHẬT
            // if ($_POST['action'] == 'edit') {

            //     // TODO: validate dữ liệu submit
            //     $status = $status == 'on' ? 1 : 0;
            //     //END validate

            //     $info   = $this->Library_model->get_info($id_library);
            //     if (empty($info)) {
            //         $msg = 'Lưu không thành công vui lòng thử lại!';
            //     } else {

            //         $year   = date('Y', strtotime($info['create_time']));
            //         $monthe = date('m', strtotime($info['create_time']));
            //         $image_ok = $info['image'];
            //         $update_time = date('Y-m-d H:i:s');

            //         // copy anh truoc nếu upload mới
            //         if (basename($image) != $info['image']) {
            //             $copy = copy_image_from_file_manager_to_public_upload($image, $year, $monthe);
            //             if ($copy['status']) {
            //                 $image_ok = $copy['basename'];
            //             } else {
            //                 $this->session->set_flashdata('flsh_msg', $copy['error']);
            //                 redirect('library');
            //             }
            //         }

            //         $exc = $this->Library_model->edit($name, $sapo, $image_ok, $slide, $status, $update_time, $id_library);
            //         $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
            //         redirect('library');
            //     }
            //     $this->session->set_flashdata('flsh_msg', $msg);
            //     redirect('library');
            // }
        }

        $list_library = $this->Library_model->get_list();
        $list_style   = $this->Style_model->get_list();
        $list_room    = $this->Room_model->get_list();

        $data['list_library'] = $list_library;
        $data['list_style']   = $list_style;
        $data['list_room']    = $list_room;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'library/library_view', $data);
        $this->_loadFooter();
    }
}
