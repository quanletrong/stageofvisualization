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

        $list_style = $this->Style_model->get_list(1);
        $list_room  = $this->Room_model->get_list(1);

        //validate search
        $sid_room  = trim($this->input->get('sid_room'));
        $sid_style = trim($this->input->get('sid_style'));
        $sstatus   = trim($this->input->get('sstatus'));

        $sid_room  = isIdNumber($sid_room) ? $sid_room : '';
        $sid_style = isIdNumber($sid_style) ? $sid_style : '';
        $sstatus   = in_array($sstatus, ['0', '1']) ? $sstatus : '';
        // end validate search

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $id_room     = $this->input->post('id_room');
            $id_style    = $this->input->post('id_style');
            $status      = $this->input->post('status');
            $image       = $this->input->post('image');
            $id_library  = $this->input->post('id_library');
            $id_library  = is_numeric($id_library) && $id_library > 0 ? $id_library : 0;
            $create_time = date('Y-m-d H:i:s');
            
            //validate
            $status = $status == 'on' ? 1 : 0;

            if(!isset($list_room[$id_room])) {
                $this->session->set_flashdata('flsh_msg', 'Loại phòng không tồn tại!');
                redirect('library');
            }
            $id_style = $id_style == null ? 0 : $id_style;
            if($id_style != 0 && !isset($list_style[$id_style])) {
                $this->session->set_flashdata('flsh_msg', 'Phong cách thiết kế không tồn tại!');
                redirect('library');
            }

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // copy and validate image
                $arr_image = json_decode($image, true);
                foreach ($arr_image as $id => $it) {
                    $copy = copy_image_to_public_upload($it['image'], FOLDER_LIBRARY);

                    copy_image_to_thumb(url_image($copy['basename'], FOLDER_LIBRARY), FOLDER_LIBRARY_THUMB, 300, 300);

                    if ($copy['status']) {
                        $this->Library_model->add($id_room, $id_style, $it['name'], $copy['basename'], $status, $this->_session_uid(), $create_time);
                    }
                }
                $this->session->set_flashdata('flsh_msg', 'OK');
                redirect('library');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {
                $info   = $this->Library_model->get_info($id_library);
                if (empty($info)) {
                    $this->session->set_flashdata('flsh_msg', 'Lưu không thành công vui lòng thử lại!');
                    redirect('library');
                } else {

                    $update_time = date('Y-m-d H:i:s');
                    $arr_image = json_decode($image, true);
                    foreach ($arr_image as $id => $it) {

                        $image_ok = $info['image'];
                        // copy and validate image
                        if (strpos($it['image'], 'uploads/tmp') !== false) {
                            $copy = copy_image_to_public_upload($it['image'], FOLDER_LIBRARY);
                            copy_image_to_thumb(url_image($copy['basename'], FOLDER_LIBRARY), FOLDER_LIBRARY_THUMB, 300, 300);

                            $image_ok = $copy['basename'];
                        }

                        $exc = $this->Library_model->edit($id_room, $id_style, $it['name'], $image_ok, $status, $update_time, $id_library);
                        $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                        redirect('library');
                    }
                }
            }
        }

        $list_library = $this->Library_model->get_list($sstatus, $sid_room, $sid_style);

        $data['list_library'] = $list_library;
        $data['list_style']   = $list_style;
        $data['list_room']    = $list_room;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'library/library_view', $data);
        $this->_loadFooter();
    }
    function ajax_delete()
    {
        if ($this->_session_role() != ADMIN) {
            resError('Tài khoản không có quyền truy cập!');
        }

        $id_library  = $this->input->post('id_library');
        $id_library  = is_numeric($id_library) && $id_library > 0 ? $id_library : 0;
        $info = $this->Library_model->get_info($id_library);
        if (empty($info)) {
            resError('Không tồn tại trong thư viện');
        }

        // xóa bản ghi
        $exc = $this->Library_model->delete($id_library);

        if ($exc) {
            //xóa file ảnh
            $path_image = $_SERVER["DOCUMENT_ROOT"] . '/' . FOLDER_LIBRARY . $info['image'];
            $path_image_thumb = $_SERVER["DOCUMENT_ROOT"] . '/' . FOLDER_LIBRARY_THUMB . $info['image'];
            if (file_exists($path_image)) {
                @unlink($path_image);
            }

            if (file_exists($path_image_thumb)) {
                @unlink($path_image_thumb);
            }
            resSuccess('Xóa thành công');
        } else {
            resError('Xóa không thành công');
        }
    }
}
