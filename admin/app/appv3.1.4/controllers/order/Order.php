<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
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

        $this->load->model('order/Order_model');
        $this->load->model('job/Job_model');
        $this->load->model('style/Style_model');
        $this->load->model('user/User_model');
    }

    function index()
    {

        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        $data = [];
        $role = $this->_session_role();
        $uid = $this->_session_uid();
        switch ($role) {
            case ADMIN:
                $list_order = $this->Order_model->get_list(); //lấy tất cả đơn
                break;
            case SALE:
                $list_order = $this->Order_model->get_list(); //lấy tất cả đơn
                break;
            case QC:
                $list_order = $this->Order_model->get_list_for_qc(); //lấy tất cả đơn
                break;
            case EDITOR:
                $list_order = $this->Order_model->get_list_order_by_id_user($uid);
                break;
            default:
                break;
        }

        $box = $this->Order_model->box_count($list_order);

        $data['box'] = $box;
        $data['list_order'] = $list_order;
        $header = [
            'title' => 'Quản lý đơn hàng',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'order/list/order_view', $data);
        $this->_loadFooter();
    }


    //TODO: check quyên truy cập id_order
    function detail($id_order)
    {
        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        $data = [];

        if (!isIdNumber($id_order)) {
            dbClose();
            redirect(site_url('order', $this->_langcode));
            die();
        }
        $role = $this->_session_role();
        $uid = $this->_session_uid();
        $all_user_working = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));
        $order = $this->Order_model->get_info_order($id_order);
        empty($order) ? redirect(site_url('order', $this->_langcode)) : '';

        ## check right access
        $status = $order['status'];
        switch ($role) {
            case ADMIN:
                break;
            case SALE:
                break;
            case QC:
                break;
            case EDITOR:
                if (!isset($order['team'][$uid]) && $status != ORDER_AVAIABLE) {
                    die('EDITOR này chưa tham gia đơn');
                }
                break;
            default:
                break;
        }


        ## DANH SÁCH JOB
        $list_job = $this->Job_model->get_list_job_by_order($id_order);
        empty($list_job) ? redirect(site_url('order', $this->_langcode)) : '';

        ## gán danh sách qc ed custom vào trong đơn
        $order['id_qc']       = [];
        $order['id_ed']       = [];
        $order['id_custom']   = [];
        $order['assign_user'] = [];
        $list_job_user = $this->Job_model->get_list_job_user_by_id_order($id_order);
        foreach ($list_job_user as $id_job => $job_user) {
            $id_user  = $job_user['id_user'];
            $username = $job_user['username'];
            $id_job   = $job_user['id_job'];
            $status   = $job_user['job_user_status'];

            if ($status) {
                if ($job_user['type_job_user'] == 2) {
                    $order['qc_user'][$id_user] = $username;
                }

                if ($job_user['type_job_user'] == 3) {
                    $order['ed_user'][$id_user] = $username;
                }

                if ($job_user['type_job_user'] == 4) {
                    $order['custom_user'][$id_user] = $username;
                }

                $order['assign_user'][$id_user] = $username;
            }
        }

        ## gắn id_qc, id_ed vào trong job
        foreach ($list_job as $id_job => $job) {
            $list_job[$id_job]['id_qc'] = '';
            $list_job[$id_job]['id_ed'] = '';
            foreach ($list_job_user as $job_user) {
                $id_user       = $job_user['id_user'];
                $status        = $job_user['status'];
                $type_job_user = $job_user['type_job_user'];
                // gán qc
                if ($id_job == $job_user['id_job'] && $type_job_user == 2 && $status) {
                    $list_job[$id_job]['id_qc'] = $id_user;
                }

                // gán ed
                if ($id_job == $job_user['id_job'] && $type_job_user == 3 && $status) {
                    $list_job[$id_job]['id_ed'] = $id_user;
                }
            }
        }


        ## danh sách VS VR 3D...
        $list_type_service = [];
        foreach ($list_job as $id_job => $job) {
            $list_type_service[$job['type_service']][] = $job['id_job'];
        }

        ## chung
        $data['order']              = $order;
        $data['list_job']           = $list_job;
        $data['role']               = $role;
        $data['curr_uid']           = $uid;
        $data['list_type_service']  = $list_type_service;
        $data['total_type_service'] = count($list_job);
        $data['all_user_working']   = $all_user_working;
        $data['list_job_user']      = $list_job_user;


        $header = [
            'title' => 'Chi tiết đơn hàng',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/detail/order_detail_view', $data);

        $this->_loadFooter();
    }

    function ajax_find_order()
    {
        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            // show_custom_error('Tài khoản không có quyền truy cập!');
            resError('not_permit', 'Bạn không có quyền thực hiện.');
        }

        // tìm ra 1 order QC_CHECK gần nhất
        if ($this->_session_role() == QC) {
            $kq = $this->Order_model->tim_don_gan_nhat(ORDER_QC_CHECK);
        } else if ($this->_session_role() == EDITOR) {
            $kq = $this->Order_model->tim_don_gan_nhat(ORDER_AVAIABLE);
        }

        if (empty($kq)) {
            resError('not_result', 'Không tìm thấy đơn. Hãy thử lại bạn nhé.');
        } else {
            resSuccess('ok', $kq['id_order']);
        }
    }

    function ajax_change_status_order($id_order, $new_status)
    {
        // TODO: check quyền thật cẩn thận
        $kq = $this->Order_model->update_status_order($id_order, $new_status);

        // lưu thời gian chuyển kiem tra don
        if($new_status == ORDER_QC_CHECK) {
            $thoi_gian_kiem_tra = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_kiem_tra_don($id_order, $thoi_gian_kiem_tra);
        }

        // lưu thời gian lam xong don
        if($new_status == ORDER_DONE) {
            $thoi_gian_lam_xong = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_lam_xong_don($id_order, $thoi_gian_lam_xong);
        }

        // lưu thời gian giao hàng
        if($new_status == ORDER_DELIVERED) {
            $thoi_gian_giao_hang = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_giao_hang($id_order, $thoi_gian_giao_hang);
        }

        // TODO: LOG
        resSuccess($kq);
    }

    function ajax_change_custom_order($id_order, $custom)
    {
        // TODO: check quyền thật cẩn thận   
        $kq = $this->Order_model->update_custom_order($id_order, $custom);

        // TODO: LOG
        resSuccess($kq);
    }

    function ajax_assign_custom($id_order, $id_user)
    {
        // TODO: check right
        // TODO: order tồn tại, order khác hoàn thành
        // TODO: user tồn tại, đang active, role [admin sale qc ed]
        // TODO: ADMIN SALE QC thêm dc tất cả tài khoàn. ED ko có quyền chức năng này

        $id_job = 0; // vì assign custom
        $type_job_user = 4; // vì assign custom
        $status = 1;
        $time_join = date('Y-m-d H:i:s');

        // user đã tồn tại thì UPDATE status = 1
        $da_ton_tai = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, $id_job, $type_job_user, $id_user);
        if ($da_ton_tai) {
            $kq = $this->Order_model->change_status_custom_to_job($status, $id_order, $id_job, $type_job_user, $id_user);
            // TODO: LOG
            resSuccess($kq);
        }
        // user chưa tồn tại thì INSERT bản ghi mới
        else {
            $kq = $this->Order_model->assign_custom_to_job($id_order, $id_job, $id_user, $type_job_user, $status, $time_join);
            // TODO: LOG
            resSuccess($kq);
        }
    }

    // Bản chất xóa custom là đổi `status = 0`
    function ajax_remove_custom($id_order, $id_user)
    {
        // TODO: check right
        // TODO: order tồn tại, order khác hoàn thành
        // TODO: ADMIN xóa all, SALE xóa QC ED, QC xóa ED, ED xóa chính ED

        $status = 0; // remove
        $id_job = 0;
        $type_job_user = 4;
        $kq = $this->Order_model->change_status_custom_to_job($status, $id_order, $id_job, $type_job_user, $id_user);

        // TODO: LOG
        resSuccess($kq);
    }
}
