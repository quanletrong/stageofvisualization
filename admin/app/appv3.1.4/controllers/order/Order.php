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
                if (!isset($order['team'][$uid]) && $status != ORDER_QC_CHECK) {
                    die('Đơn này đang chờ SALE duyệt, QC chưa thể xem vào lúc này.');
                }
                break;
            case EDITOR:
                if (!isset($order['team'][$uid]) && $status != ORDER_AVAIABLE) {
                    die('Đơn này đang chờ QC duyệt, ED chưa thể xem vào lúc này.');
                }
                break;
            default:
                break;
        }

        ## chung
        $data['order']              = $order;
        $data['role']               = $role;
        $data['curr_uid']           = $uid;
        $data['all_user_working']   = $all_user_working;

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
        if ($new_status == ORDER_QC_CHECK) {
            $thoi_gian_kiem_tra = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_kiem_tra_don($id_order, $thoi_gian_kiem_tra);
        }

        // lưu thời gian lam xong don
        if ($new_status == ORDER_DONE) {
            $thoi_gian_lam_xong = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_lam_xong_don($id_order, $thoi_gian_lam_xong);
        }

        // lưu thời gian giao hàng
        if ($new_status == ORDER_DELIVERED) {
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

    function ajax_assign_job_user($working_type, $id_order, $id_job, $id_user)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $curr_user_info = $this->User_model->get_user_info_by_id($cur_uid);
        $assign_info    = $this->User_model->get_user_info_by_id($id_user);
        $order     = $this->Order_model->get_info_order($id_order);

        # CHECK RIGHT
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        $curr_user_info['status'] == 0              ? resError('Tài khoản đang bị khóa') : '';
        empty($assign_info)                         ? resError('User được gán không tồn tại') : '';
        $assign_info['status'] == 0                 ? resError('User được gán đang bị khóa') : '';
        empty($order)                          ? resError('Đơn không tồn tại') : '';
        $order['status'] == ORDER_DELIVERED    ? resError('Đơn hàng đã giao không được thay đổi người làm') : '';
        $order['status'] == ORDER_COMPLETE     ? resError('Đơn hàng hoàn thành không được thay đổi người làm') : '';
        $order['status'] == ORDER_CANCLE       ? resError('Đơn hàng đã hủy không được thay đổi người làm') : '';


        // không được gán người đồng cấp (hack)
        $role == ADMIN && $assign_info['role'] == ADMIN && $cur_uid != $id_user     ? resError('ADMIN không có quyền gán người cùng cấp') : '';
        $role == SALE && $assign_info['role'] == SALE && $cur_uid != $id_user       ? resError('SALE không có quyền gán người cùng cấp') : '';
        $role == QC && $assign_info['role'] == QC && $cur_uid != $id_user           ? resError('SALE không có quyền gán người cùng cấp') : '';
        $role == EDITOR && $assign_info['role'] == EDITOR && $cur_uid != $id_user   ? resError('EDITOR không có quyền gán người cùng cấp') : '';

        // không được gán người cấp cao hơn (hack)
        $role == SALE && $assign_info['role']   == ADMIN    ? resError('SALE không có quyền gán người cấp ADMIN') : '';
        $role == QC && $assign_info['role']     == ADMIN    ? resError('QC không có quyền gán người cấp ADMIN') : '';
        $role == QC && $assign_info['role']     == SALE     ? resError('QC không có quyền gán người cấp SALE') : '';
        $role == EDITOR && $assign_info['role'] == ADMIN    ? resError('ED không có quyền gán người cấp ADMIN') : '';
        $role == EDITOR && $assign_info['role'] == SALE     ? resError('ED không có quyền gán người cấp SALE') : '';
        $role == EDITOR && $assign_info['role'] == QC       ? resError('ED không có quyền gán người cấp QC') : '';

        if ($working_type == WORKING_EDITOR) {
            !isset($order['job'][$id_job])                          ? resError('IMAGE không tồn tại') : '';
            $role == EDITOR && $order['status'] == ORDER_PENDING    ? resError('Không thể thêm ED khi đơn hàng đang PENDING') : '';
            $role == EDITOR && $order['status'] == ORDER_QC_CHECK   ? resError('Không thể thêm ED khi đơn hàng đang QC CHECK') : '';
            !empty($order['job'][$id_job]['working_ed_active'])     ? resError('Đã có người nhận làm IMAGE này') : '';

        } else if ($working_type == WORKING_QC) {
            !isset($order['job'][$id_job]) ? resError('IMAGE không tồn tại') : '';
            $role == EDITOR                                         ? resError('ED không có quyền thực chức năng này.') : '';
            $assign_info['role'] == EDITOR                          ? resError('Không được gán tài khoản ED vào đây.') : '';
            !empty($order['job'][$id_job]['working_qc_active'])     ? resError('Đã có người nhận làm IMAGE này') : '';


        } else if ($working_type == WORKING_CUSTOM) {
            $id_job = 0; // mặc định
            $role == EDITOR ? resError('ED không có quyền thực chức năng này.') : '';
        } else {
            resError('Lỗi dữ liệu truyền vào. Hãy thử lại!');
        }

        # CHECK SAVE
        $status = 1;
        $time_join = date('Y-m-d H:i:s');

        // WORKING_SALE, WORKING_QC, WORKING_EDITOR => thay đổi tất cả user trong job thành status = 0
        if (in_array($working_type, [WORKING_SALE, WORKING_QC, WORKING_EDITOR])) {
            $this->Order_model->thay_doi_status_tat_ca_job_user(0, $id_order, $id_job, $working_type);
        }

        // user gán đã tồn tại thì UPDATE status = 1
        $da_ton_tai = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, $id_job, $working_type, $id_user);
        if ($da_ton_tai) {
            $kq = $this->Order_model->change_status_job_user($status, $id_order, $id_job, $working_type, $id_user);
            // TODO: LOG

            resSuccess($kq);
        }
        // user gán chưa tồn tại thì INSERT bản ghi mới
        else {
            $type_service = @$order['job'][$id_job]['type_service'];
            $kq = $this->Order_model->add_job_user($id_order, $id_job, $id_user, $assign_info['username'], $type_service, $working_type, $status, $time_join);
            // TODO: LOG
            resSuccess($kq);
        }
    }

    // Bản chất xóa custom là đổi `status = 0`
    function ajax_remove_job_user($working_type, $id_order, $id_job, $id_user)
    {

        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $curr_user_info = $this->User_model->get_user_info_by_id($cur_uid);
        $assign_info    = $this->User_model->get_user_info_by_id($id_user);
        $info_order     = $this->Order_model->get_info_order($id_order);

        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        $info_order['status'] == ORDER_DELIVERED    ? resError('Đơn hàng đã giao không được thay đổi người làm') : '';
        $info_order['status'] == ORDER_COMPLETE     ? resError('Đơn hàng hoàn thành không được thay đổi người làm') : '';
        $info_order['status'] == ORDER_CANCLE       ? resError('Đơn hàng đã hủy không thay được đổi người làm') : '';

        if ($working_type == WORKING_EDITOR) {
            $role == EDITOR && $cur_uid != $id_user     ? resError('Editor không có quyền xóa editor khác.') : '';
        }


        $status = 0; // remove
        $id_job = 0;
        $type_job_user = 4;
        $kq = $this->Order_model->change_status_job_user($status, $id_order, $id_job, $working_type, $id_user);

        // TODO: LOG
        resSuccess($kq);
    }
}
