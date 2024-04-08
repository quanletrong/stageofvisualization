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
        $role = $this->_session_role();
        if($role == ADMIN) {
            include("index_admin.php");
        } else if ($role == SALE) {
            include("index_sale.php");
        } else {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
    }

    function ajax_get_list_voucher()
    {
        $cur_uid = $this->_session_uid();
        // lấy danh sách ID voucher mà user đó đc gán
        $list_voucher = $this->Voucher_model->voucher_user_get_list('', $cur_uid);

        // lấy thông tin voucher từ ID trên
        if (!empty($list_voucher)) {

            $arr_id = [];
            foreach ($list_voucher as $voucher) {
                $arr_id[] = $voucher['id_voucher'];
            }

            $id_voucher    = implode(',', $arr_id);
            $f_price       = '';                   // 
            $t_price       = '';
            $price_unit    = '';                   // 1 phần trăm;2 VND; 3 Đô la; 3 ...
            $code          = '';
            $f_expire      = date("Y-m-d H:s:i");  // lọc theo ngày hết hạn
            $t_expire      = '';                   // lọc theo ngày hết hạn
            $status        = 0;                    // lọc theo trạng thái
            $f_create_time = '';
            $t_create_time = '';
            $note          = '';
            $create_by     = '';
            $limit         = 10000;
            $offset        = 0;
            $list =  $this->Voucher_model->get_list2($id_voucher, $f_price, $t_price, $price_unit, $code, $f_expire, $t_expire, $status, $f_create_time, $t_create_time, $note, $create_by, $limit, $offset);
        }
    }

    function ajax_get_list_voucher_for_create_order_by_sale()
    {
        $cur_uid = $this->_session_uid();
        $id_sale = $cur_uid;
        $now = date("Y-m-d H:s:i");
        $list =  $this->Voucher_model->get_list_voucher_for_create_order_by_sale($id_sale, $now);
        resSuccess($list);
    }
}
