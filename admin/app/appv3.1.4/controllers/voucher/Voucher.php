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
            $code               = removeAllTags($this->input->post('code'));
            $note               = removeAllTags($this->input->post('note'));
            $voucher_user_sale  = $this->input->post('voucher_user_sale[]');
            $voucher_user_khach = $this->input->post('voucher_user_khach[]');
            $price              = removeAllTags($this->input->post('price'));
            $price_unit         = removeAllTags($this->input->post('price_unit'));
            $limit              = removeAllTags($this->input->post('limit'));
            $expire_date        = removeAllTags($this->input->post('expire_date'));
            $status             = removeAllTags($this->input->post('status'));
            $id_voucher         = removeAllTags($this->input->post('id_voucher'));
            $id_voucher         = is_numeric($id_voucher) && $id_voucher > 0 ? $id_voucher : 0;

            $create_time        = date('Y-m-d H:i:s');

            // TODO: CHƯA VALIDATE

            $voucher_user_sale  = $voucher_user_sale  == null ? [] : $voucher_user_sale;
            $voucher_user_khach = $voucher_user_khach == null ? [] : $voucher_user_khach;

            $status = $status == 'on' ? 0 : 1;
            //END validate

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {


                // // copy and validate room
                // $arr_room = json_decode($room, true);
                // $room_ok = [];
                // foreach ($arr_room as $id => $it) {
                //     $img_room = $it['image'];
                //     $copy = copy_image_to_public_upload($img_room, FOLDER_SERVICES);
                //     if ($copy['status']) {
                //         $room_ok[$id]['name'] = $it['name'];
                //         $room_ok[$id]['image'] = $copy['basename'];
                //     }
                // }
                // // end copy and validate room

                // $copy = copy_image_to_public_upload($image, FOLDER_SERVICES);
                // if ($copy['status']) {

                //     $exc = $this->Service_model->add($name, $type_service, $sapo, $copy['basename'], json_encode($room_ok, JSON_FORCE_OBJECT), $price, $status, $this->_session_uid(), $create_time);
                //     $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                // } else {
                //     $msg = $copy['error'];
                // }
                // $this->session->set_flashdata('flsh_msg', $msg);
                // redirect('service');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                $info =  $this->Voucher_model->get_info($id_voucher);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    // update voucher
                    $exc = $this->Voucher_model->edit($code, $note, $price, $price_unit, $status, $limit, $expire_date, $id_voucher);

                    // thêm xóa user khỏi voucher
                    $voucher_user_new = array_merge($voucher_user_khach, $voucher_user_sale);
                    $voucher_user_old = [];
                    foreach ($info['voucher_user'] as $id_user => $item) {
                        $voucher_user_old[] = $id_user;
                    }
                    $new = array_diff($voucher_user_new, $voucher_user_old);
                    $del = array_diff($voucher_user_old, $voucher_user_new);

                    if(count($new)) {
                        $exc = $this->Voucher_model->add_multiple_voucher_user($new, $id_voucher, $create_time);
                    }

                    if(count($del)) {
                        $exc = $this->Voucher_model->delete_multiple_voucher_user($del, $id_voucher);
                    }

                    //error
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('voucher');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('voucher');
            }
        }

        $id_voucher    = '';
        $f_price       = '';     // 
        $t_price       = '';
        $price_unit    = '';     // 1 phần trăm;2 VND; 3 Đô la; 3 ...
        $code          = '';
        $f_expire      = '';     // lọc theo ngày hết hạn
        $t_expire      = '';     // lọc theo ngày hết hạn
        $status        = '';     // lọc theo trạng thái
        $f_create_time = '';
        $t_create_time = '';
        $note          = '';
        $create_by     = '';
        $limit         = 10000;
        $offset        = 0;

        $list =  $this->Voucher_model->get_list2($id_voucher, $f_price, $t_price, $price_unit, $code, $f_expire, $t_expire, $status, $f_create_time, $t_create_time, $note, $create_by, $limit, $offset);

        $data['list'] = $list;
        $data['list_sale'] = $list_sale;
        $data['list_khach'] = $list_khach;


        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'voucher/voucher_view', $data);
        $this->_loadFooter();
    }
}
