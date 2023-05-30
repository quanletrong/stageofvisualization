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

        $sid_room  = trim($this->input->get('sid_room'));
        $sid_style = trim($this->input->get('sid_style'));
        $sstatus   = trim($this->input->get('sstatus'));

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $id_room     = $this->input->post('id_room');
            $id_style    = $this->input->post('id_style');
            $status      = $this->input->post('status');
            $image       = $this->input->post('image');
            $id_library  = $this->input->post('id_library');
            $id_library  = is_numeric($id_library) && $id_library > 0 ? $id_library : 0;
            $create_time = date('Y-m-d H:i:s');

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                // $copy = copy_image_from_file_manager_to_public_upload($image, date('Y'), date('m')); TODO: copy ảnh
                // if ($copy['status']) {
                //     $exc = $this->Library_model->add($name, $sapo, $copy['basename'], $image, $status, $this->_session_uid(), $create_time);
                //     $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                // } else {
                //     $msg = $copy['error'];
                // }
                $image_arr = json_decode($image, true);

                if (empty($image_arr)) {
                    $this->session->set_flashdata('flsh_msg', 'Không lưu được ảnh');
                    redirect('library');
                }

                foreach ($image_arr as $key => $item) {
                    $exc = $this->Library_model->add($id_room, $id_style, $item['name'], $item['image'], $status, $this->_session_uid(), $create_time);
                }
                // $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!'; //TODO: tạm set mặc định
                $this->session->set_flashdata('flsh_msg', 'OK');
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

            //         $exc = $this->Library_model->edit($name, $sapo, $image_ok, $image, $status, $update_time, $id_library);
            //         $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
            //         redirect('library');
            //     }
            //     $this->session->set_flashdata('flsh_msg', $msg);
            //     redirect('library');
            // }
        }

        $list_library = $this->Library_model->get_list($sstatus, $sid_room, $sid_style);
        $list_style   = $this->Style_model->get_list(1);
        $list_room    = $this->Room_model->get_list(1);

        $data['list_library'] = $list_library;
        $data['list_style']   = $list_style;
        $data['list_room']    = $list_room;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'library/library_view', $data);
        $this->_loadFooter();
    }
}
