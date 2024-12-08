<?php

$data = [];

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

    // VALIDATE DATA

    $code        = $code                   != '' ? $code : 0;
    $note        = $note                   != '' ? $note : 0;
    $price       = is_numeric($price) || $price > 0 ? $price : 0;
    $price_unit  = in_array($price_unit, [VOUCHER_PERCENT, VOUCHER_VND, VOUCHER_USD, VOUCHER_EUR]) ? $price_unit : 0;
    $limit       = is_numeric($limit) && $limit > 0 ? $limit : 0;
    $expire_date = strtotime($expire_date) == false ? 0 : $expire_date;
    $status      = $status                 == 'on' ? 0 : 1;

    // dữ liệu không hợp lệ => báo lỗi
    if (!$code || !$note || !$price || !$price_unit || !$limit || !$expire_date) {
        $this->session->set_flashdata('flsh_msg', 'Dữ liệu không hợp lệ!');
        redirect('voucher');
    }

    // tài khoản được gán mã không tồn tại => báo lỗi
    $voucher_user_sale  = $voucher_user_sale  == null ? [] : $voucher_user_sale;
    $voucher_user_khach = $voucher_user_khach == null ? [] : $voucher_user_khach;
    $all_sale_khach = $list_sale + $list_khach;
    $voucher_user_new = array_merge($voucher_user_khach, $voucher_user_sale);

    foreach ($voucher_user_new as $id_user) {
        if (isset($all_sale_khach[$id_user]) == false) {
            $this->session->set_flashdata('flsh_msg', 'Tài khoản được cấp mã không tồn tại!');
            redirect('voucher');
        }
    }

    // giảm giá theo phần trăm lớn hơn 100% => báo lỗi
    if ($price_unit == VOUCHER_PERCENT && $price > 100) {
        $this->session->set_flashdata('flsh_msg', 'Giảm giá không được quá 100%!');
        redirect('voucher');
    }

    //END VALIDATE

    // TẠO MỚI 
    if ($_POST['action'] == 'add') {

        // update voucher
        $newid = $this->Voucher_model->add($code, $note, $price, $price_unit, $status, $limit, $expire_date, $create_time);

        // thêm user voucher

        if (count($voucher_user_new)) {
            $exc = $this->Voucher_model->add_multiple_voucher_user($voucher_user_new, $newid, $create_time);
        }

        //error
        $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
        redirect('voucher');
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
            foreach ($info['voucher_user'] as $user) {
                $voucher_user_old[] = $user['id_user'];
            }
            $new = array_diff($voucher_user_new, $voucher_user_old);
            $del = array_diff($voucher_user_old, $voucher_user_new);

            if (count($new)) {
                $exc = $this->Voucher_model->add_multiple_voucher_user($new, $id_voucher, $create_time);
            }

            if (count($del)) {
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
$this->load->view($this->_template_f . 'voucher/index_admin_view', $data);
$this->_loadFooter();