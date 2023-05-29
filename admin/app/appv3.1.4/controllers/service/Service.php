<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service extends MY_Controller
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
        $this->load->model('service/Service_model');
    }

    function index()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $header = [
            'title' => 'Quản lý dịch vụ',
            'header_page_css_js' => 'service'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $name        = $this->input->post('name');
            $sapo        = $this->input->post('sapo');
            $price       = $this->input->post('price');
            $status      = $this->input->post('status');
            $image       = $this->input->post('image');
            $room        = $this->input->post('room');
            $id_service  = $this->input->post('id_service');
            $id_service  = is_numeric($id_service) && $id_service > 0 ? $id_service : 0;
            $create_time = date('Y-m-d H:i:s');

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                // copy and validate room
                $arr_room = json_decode($room, true);
                $room_ok = [];
                foreach ($arr_room as $id => $it) {
                    $img_room = $it['image'];
                    $copy = copy_image_from_file_manager_to_public_upload($img_room, date('Y'), date('m'));
                    if ($copy['status']) {
                        $room_ok[$id]['name'] = $it['name'];
                        $room_ok[$id]['image'] = $copy['basename'];
                    }
                }
                // end copy and validate room

                $copy = copy_image_from_file_manager_to_public_upload($image, date('Y'), date('m'));
                if ($copy['status']) {

                    $exc = $this->Service_model->add($name, $sapo, $copy['basename'], json_encode($room_ok, JSON_FORCE_OBJECT), $price, $status, $this->_session_uid(), $create_time);
                    $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                } else {
                    $msg = $copy['error'];
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('service');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                $info   = $this->Service_model->get_info($id_service);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    $year   = date('Y', strtotime($info['create_time']));
                    $monthe = date('m', strtotime($info['create_time']));
                    $image_ok = $info['image'];
                    $update_time = date('Y-m-d H:i:s');

                    // copy anh truoc nếu upload mới
                    if (strpos($image, 'uploads/tmp') !== false) {
                        $copy = copy_image_from_file_manager_to_public_upload($image, $year, $monthe);
                        if ($copy['status']) {
                            $image_ok = $copy['basename'];
                        } else {
                            $this->session->set_flashdata('flsh_msg', $copy['error']);
                            redirect('service');
                        }
                    }

                    // copy and validate room
                    $room_old = json_decode($info['room'], true);
                    $room_ok = [];
                    $arr_room = json_decode($room, true);
                    foreach ($arr_room as $id => $it) {

                        // room cũ
                        if (isset($room_old[$id])) {
                            $room_ok[$id]['name'] = $it['name'];

                            // room cũ thay đổi ảnh
                            $img_room = $it['image'];
                            if (strpos($it['image'], 'uploads/tmp') !== false) {
                                $copy = copy_image_from_file_manager_to_public_upload($img_room, $year, $monthe);
                                if ($copy['status']) {
                                    $room_ok[$id]['image'] = $copy['basename'];
                                }
                            } else {
                                $room_ok[$id]['image'] = $room_old[$id]['image'];
                            }
                        }
                        // thêm room mới
                        else {
                            $img_room = $it['image'];
                            $copy = copy_image_from_file_manager_to_public_upload($img_room, $year, $monthe);
                            if ($copy['status']) {
                                $room_ok[$id]['name'] = $it['name'];
                                $room_ok[$id]['image'] = $copy['basename'];
                            }
                        }
                    }
                    // end copy and validate room

                    $exc = $this->Service_model->edit($name, $sapo, $price, $image_ok, json_encode($room_ok, JSON_FORCE_OBJECT), $status, $update_time, $id_service);
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('service');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('service');
            }
        }

        $list =  $this->Service_model->get_list();
        $data['list'] = $list;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'service/service_view', $data);
        $this->_loadFooter();
    }
}
