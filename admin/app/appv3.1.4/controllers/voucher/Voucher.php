<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends MY_Controller
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
        $this->load->model('voucher/Voucher_model');
        $this->load->model('user/User_model');
    }

    function index()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        $list_sale = $this->User_model->get_list_user_working(1, implode(",", [SALE]));
        $list_khach = $this->User_model->get_list_user_working(1, implode(",", [CUSTOMER]));

        $header = [
            'title' => 'Quản lý mã giảm giá',
            'header_page_css_js' => 'voucher'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $name         = removeAllTags($this->input->post('name'));
            $type_service = removeAllTags($this->input->post('type_service'));
            $sapo         = removeAllTags($this->input->post('sapo'));
            $price        = removeAllTags($this->input->post('price'));
            $status       = removeAllTags($this->input->post('status'));
            $image        = removeAllTags($this->input->post('image'));
            $room         = removeAllTags($this->input->post('room'));
            $id_service   = removeAllTags($this->input->post('id_service'));
            $id_service   = is_numeric($id_service) && $id_service > 0 ? $id_service : 0;
            $create_time  = date('Y-m-d H:i:s');

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
                    $copy = copy_image_to_public_upload($img_room, FOLDER_SERVICES);
                    if ($copy['status']) {
                        $room_ok[$id]['name'] = $it['name'];
                        $room_ok[$id]['image'] = $copy['basename'];
                    }
                }
                // end copy and validate room

                $copy = copy_image_to_public_upload($image, FOLDER_SERVICES);
                if ($copy['status']) {

                    $exc = $this->Service_model->add($name, $type_service, $sapo, $copy['basename'], json_encode($room_ok, JSON_FORCE_OBJECT), $price, $status, $this->_session_uid(), $create_time);
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

                    $image_ok = $info['image'];
                    $update_time = date('Y-m-d H:i:s');

                    // copy anh truoc nếu upload mới
                    if (strpos($image, 'uploads/tmp') !== false) {
                        $copy = copy_image_to_public_upload($image, FOLDER_SERVICES);
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
                                $copy = copy_image_to_public_upload($img_room, FOLDER_SERVICES);
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
                            $copy = copy_image_to_public_upload($img_room, FOLDER_SERVICES);
                            if ($copy['status']) {
                                $room_ok[$id]['name'] = $it['name'];
                                $room_ok[$id]['image'] = $copy['basename'];
                            }
                        }
                    }
                    // end copy and validate room

                    $exc = $this->Service_model->edit($name, $type_service, $sapo, $price, $image_ok, json_encode($room_ok, JSON_FORCE_OBJECT), $status, $update_time, $id_service);
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('service');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('service');
            }
        }

        $id_voucher    = '';
        $value_type    = '';     // 1 giảm theo phần trăm đơn; 2 giảm theo số tiền
        $f_price       = '';     // 
        $t_price       = '';
        $price_unit    = '';     // 1 phần trăm;2 VND; 3 Đô la; 3 ...
        $code          = '';
        $f_expire      = '';     // lọc theo ngày hết hạn
        $t_expire      = '';     // lọc theo ngày hết hạn
        $type_assign   = '';     // 1 cho sale; 2 cho customer
        $id_assign     = '';     // lọc theo người được gán 
        $id_used       = '';     // lọc theo người đã dùng
        $id_order      = '';     // lọc theo đơn đã dùng
        $status        = '';     // lọc theo trạng thái
        $f_create_time = '';
        $t_create_time = '';
        $note          = '';
        $create_by     = '1';
        $limit         = 10000;
        $offset        = 0;

        // $list =  $this->Voucher_model->get_list($id_voucher, $value_type, $f_price, $t_price, $price_unit, $code, $f_expire, $t_expire, $type_assign, $id_assign, $id_used, $id_order, $status, $f_create_time, $t_create_time, $note, $limit, $offset);


        $list =  $this->Voucher_model->get_list2($id_voucher, $f_price, $t_price, $price_unit, $code, $f_expire, $t_expire, $status, $f_create_time, $t_create_time, $note, $create_by, $limit, $offset);

        $data['list'] = $list;
        $data['list_sale'] = $list_sale;
        $data['list_khach'] = $list_khach;
        

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'voucher/voucher_view', $data);
        $this->_loadFooter();
    }
}
