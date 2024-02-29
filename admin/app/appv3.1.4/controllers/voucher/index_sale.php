<?php

$data = [];

$cur_uid = $this->_session_uid();

// lấy danh sách ID voucher mà user đó đc gán
$list_voucher = $this->Voucher_model->voucher_user_get_list('', $cur_uid);

// lấy thông tin voucher từ ID trên
if (!empty($list_voucher)) {

    $arr_id = [];
    foreach ($list_voucher as $voucher) {
        $arr_id[] = $voucher['id_voucher'];
    }

    $id_voucher    = implode(',', $arr_id);;
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
}

$data['list'] = $list;

$header = [
    'title' => 'Quản lý mã giảm giá',
    'header_page_css_js' => 'voucher'
];
$this->_loadHeader($header);
$this->load->view($this->_template_f . 'voucher/index_sale_view', $data);
$this->_loadFooter();