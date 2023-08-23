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
                    die('Bạn không phải thành viên trong đơn hàng này.');
                }
                break;
            case EDITOR:
                if (!isset($order['team'][$uid]) && $status != ORDER_AVAIABLE) {
                    die('Bạn không phải thành viên trong đơn hàng này.');
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
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $order                = $this->Order_model->get_info_order($id_order);
        $allow_status_by_role = button_status_order_by_role($role);

        empty($order)                               ? resError('Đơn không tồn tại') : '';
        !isset($allow_status_by_role[$new_status])  ? resError('Trạng thái chuyển không phù hợp') : '';

        $cur_status            = $order['status'];
        $num_working_qc_active = count($order['working_qc_active']);
        $num_working_ed_active = count($order['working_ed_active']);

        if ($new_status == ORDER_PENDING) {
        }
        if ($new_status == ORDER_QC_CHECK) {
        }
        if ($new_status == ORDER_AVAIABLE) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái AVAIABLE') : '';
        }
        if ($new_status == ORDER_DONE) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái DONE') : '';
            $num_working_ed_active == 0 ? resError('Hãy nhập WORKING ED trước khi chuyển trạng thái DONE') : '';
        }
        if ($new_status == ORDER_DELIVERED) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái DELIVERED') : '';
            $num_working_ed_active == 0 ? resError('Hãy nhập WORKING ED trước khi chuyển trạng thái DELIVERED') : '';
        }
        if ($new_status == ORDER_FIX) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái FIX') : '';
            $num_working_ed_active == 0 ? resError('Hãy nhập WORKING ED trước khi chuyển trạng thái FIX') : '';
        }
        if ($new_status == ORDER_REWORK) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái REWORK') : '';
            $num_working_ed_active == 0 ? resError('Hãy nhập WORKING ED trước khi chuyển trạng thái REWORK') : '';
        }
        if ($new_status == ORDER_CANCLE) {
        }
        if ($new_status == ORDER_COMPLETE) {
            $num_working_qc_active == 0 ? resError('Hãy nhập WORKING QC trước khi chuyển trạng thái COMPLETE') : '';
            $num_working_ed_active == 0 ? resError('Hãy nhập WORKING ED trước khi chuyển trạng thái COMPLETE') : '';
        }

        // save
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

    function ajax_assign_job_user($working_type, $id_order, $id_job, $id_user)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $curr_uinfo = $this->User_model->get_user_info_by_id($cur_uid);
        $as_uinfo   = $this->User_model->get_user_info_by_id($id_user);
        $order      = $this->Order_model->get_info_order($id_order);

        # CHECK RIGHT
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        $curr_uinfo['status'] == 0  ? resError('Tài khoản đang bị khóa') : '';
        $as_uinfo             == [] ? resError('User được gán không tồn tại') : '';
        $as_uinfo['status']  == 0  ? resError('User được gán đang bị khóa') : '';
        $order                == [] ? resError('Đơn không tồn tại') : '';

        // không được gán người khi đơn đã giao, đã hoàn thành, đã hủy
        $order['status'] == ORDER_DELIVERED    ? resError('Đơn hàng đã giao không được thay đổi người làm') : '';
        $order['status'] == ORDER_COMPLETE     ? resError('Đơn hàng hoàn thành không được thay đổi người làm') : '';
        $order['status'] == ORDER_CANCLE       ? resError('Đơn hàng đã hủy không được thay đổi người làm') : '';


        // không được gán người đồng cấp (hack)
        $role == ADMIN && $as_uinfo['role']  == ADMIN && $cur_uid  != $id_user   ? resError('ADMIN không có quyền gán người cùng cấp') : '';
        $role == SALE && $as_uinfo['role']   == SALE && $cur_uid   != $id_user   ? resError('SALE không có quyền gán người cùng cấp') : '';
        $role == QC && $as_uinfo['role']     == QC && $cur_uid     != $id_user   ? resError('QC không có quyền gán người cùng cấp') : '';
        $role == EDITOR && $as_uinfo['role'] == EDITOR && $cur_uid != $id_user   ? resError('EDITOR không có quyền gán người cùng cấp') : '';

        // không được gán người cấp cao hơn (hack)
        $role == SALE && $as_uinfo['role']   == ADMIN    ? resError('SALE không có quyền gán người cấp ADMIN') : '';
        $role == QC && $as_uinfo['role']     == ADMIN    ? resError('QC không có quyền gán người cấp ADMIN') : '';
        $role == QC && $as_uinfo['role']     == SALE     ? resError('QC không có quyền gán người cấp SALE') : '';
        $role == EDITOR && $as_uinfo['role'] == ADMIN    ? resError('ED không có quyền gán người cấp ADMIN') : '';
        $role == EDITOR && $as_uinfo['role'] == SALE     ? resError('ED không có quyền gán người cấp SALE') : '';
        $role == EDITOR && $as_uinfo['role'] == QC       ? resError('ED không có quyền gán người cấp QC') : '';

        // working_type
        if ($working_type == WORKING_EDITOR) {
            $role == EDITOR && $order['status'] == ORDER_PENDING    ? resError('ED không thể tham gia vào đơn hàng đang PENDING') : '';
            $role == EDITOR && $order['status'] == ORDER_QC_CHECK   ? resError('ED không thể tham gia vào đơn hàng đang QC CHECK') : '';
            !isset($order['job'][$id_job])                          ? resError('IMAGE không tồn tại') : '';
            !empty($order['job'][$id_job]['working_ed_active'])     ? resError('Đã có người nhận làm IMAGE này') : '';
        } else if ($working_type == WORKING_QC) {
            $role == EDITOR                                     ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $as_uinfo['role'] == EDITOR                         ? resError('Không được gán tài khoản ED vào đây.') : '';
            !isset($order['job'][$id_job])                      ? resError('IMAGE không tồn tại') : '';
            !empty($order['job'][$id_job]['working_qc_active']) ? resError('Đã có người nhận làm IMAGE này') : '';
        } else if ($working_type == WORKING_CUSTOM) {
            $id_job = 0; // mặc định
            $role == EDITOR ? resError('ED không có quyền thực hiện chức năng này.') : '';
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

        // chuyển đơn về ORDER_PROGRESS
        if ($working_type == WORKING_EDITOR) {
            $this->Order_model->update_status_order($id_order, ORDER_PROGRESS);
        }

        // cập nhật vào custom
        $da_ton_tai_custom = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, 0, WORKING_CUSTOM, $id_user);
        if ($da_ton_tai_custom) {
            $this->Order_model->change_status_job_user($status, $id_order, 0, WORKING_CUSTOM, $id_user);
        } else {
            $this->Order_model->add_job_user($id_order, 0, $id_user, $as_uinfo['username'], '', WORKING_CUSTOM, $status, $time_join);
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
            $kq = $this->Order_model->add_job_user($id_order, $id_job, $id_user, $as_uinfo['username'], $type_service, $working_type, $status, $time_join);

            // TODO: LOG
            resSuccess($kq);
        }
    }

    // Bản chất xóa custom là đổi `status = 0`
    function ajax_remove_job_user($working_type, $id_order, $id_job, $id_user)
    {

        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $curr_uinfo = $this->User_model->get_user_info_by_id($cur_uid);
        $as_uinfo   = $this->User_model->get_user_info_by_id($id_user);
        $order      = $this->Order_model->get_info_order($id_order);

        // chỉ admin, sale, qc, ed mới được vào đây
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản của bạn không có quyền thực hiện chức năng này')        : '';
        $curr_uinfo['status'] == 0                  ? resError('Tài khoản của bạn đang bị khóa') : '';
        $as_uinfo             == []                 ? resError('User được xóa không tồn tại') : '';
        $order                == []                 ? resError('Đơn không tồn tại') : '';

        // không được xóa người khi đơn đã giao, đã hoàn thành, đã hủy
        $order['status'] == ORDER_DELIVERED    ? resError('Đơn hàng đã giao không được thay đổi người làm') : '';
        $order['status'] == ORDER_COMPLETE     ? resError('Đơn hàng hoàn thành không được thay đổi người làm') : '';
        $order['status'] == ORDER_CANCLE       ? resError('Đơn hàng đã hủy không thay được đổi người làm') : '';

        // không được xóa người đồng cấp (hack)
        $role == ADMIN && $as_uinfo['role']  == ADMIN && $cur_uid  != $id_user   ? resError('ADMIN không có quyền xóa người cùng cấp') : '';
        $role == SALE && $as_uinfo['role']   == SALE && $cur_uid   != $id_user   ? resError('SALE không có quyền xóa người cùng cấp') : '';
        $role == QC && $as_uinfo['role']     == QC && $cur_uid     != $id_user   ? resError('QC không có quyền xóa người cùng cấp') : '';
        $role == EDITOR && $as_uinfo['role'] == EDITOR && $cur_uid != $id_user   ? resError('EDITOR không có quyền xóa người cùng cấp') : '';

        // không được xóa người cấp cao hơn (hack)
        $role == SALE && $as_uinfo['role']   == ADMIN    ? resError('SALE không có quyền xóa người cấp ADMIN') : '';
        $role == QC && $as_uinfo['role']     == ADMIN    ? resError('QC không có quyền xóa người cấp ADMIN') : '';
        $role == QC && $as_uinfo['role']     == SALE     ? resError('QC không có quyền xóa người cấp SALE') : '';
        $role == EDITOR && $as_uinfo['role'] == ADMIN    ? resError('ED không có quyền xóa người cấp ADMIN') : '';
        $role == EDITOR && $as_uinfo['role'] == SALE     ? resError('ED không có quyền xóa người cấp SALE') : '';
        $role == EDITOR && $as_uinfo['role'] == QC       ? resError('ED không có quyền xóa người cấp QC') : '';


        // WORKING_QC
        if ($working_type == WORKING_QC) {
            $working_qc_active = $order['job'][$id_job]['working_qc_active'];

            $role == EDITOR                                     ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $role == QC && !isset($working_qc_active[$cur_uid]) ? resError('Bạn chưa được gán vào IMAGE này') : '';
        }
        // WORKING_EDITOR
        else if ($working_type == WORKING_EDITOR) {
            $working_ed_active = $order['job'][$id_job]['working_ed_active'];
            $role == EDITOR && !isset($working_ed_active[$cur_uid])  ? resError('Bạn chưa được gán vào IMAGE này') : '';
        }
        // WORKING_CUSTOM
        else if ($working_type == WORKING_CUSTOM) {
            $role == EDITOR  ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $id_job = 0;
        } else {
            resError('Lỗi dữ liệu truyền vào. Hãy thử lại!');
        }

        // cấp nhật giá custom về 0 (nếu có)
        if ($working_type == WORKING_CUSTOM) {
            $custom = 0;
            $this->Order_model->update_custom_order_for_user($id_order, $custom, $id_user);
        }

        $status = 0;
        $kq = $this->Order_model->change_status_job_user($status, $id_order, $id_job, $working_type, $id_user);

        // TODO: LOG
        resSuccess($kq);
    }

    function ajax_change_custom_order($id_order, $custom)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();
        $order   = $this->Order_model->get_info_order($id_order);

        $order == []                        ? resError('Đơn không tồn tại') : '';
        !in_array($role, [ADMIN, SALE])    ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        !is_numeric($custom) || $custom < 0 ? resError('Tổng custom không hợp lệ') : '';

        $kq = $this->Order_model->update_custom_order($id_order, $custom);

        // TODO: LOG
        resSuccess($kq);
    }

    function ajax_change_custom_order_for_user($id_order, $custom, $id_user)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();
        $order   = $this->Order_model->get_info_order($id_order);

        $order == []                        ? resError('Đơn không tồn tại') : '';
        !in_array($role, [ADMIN, SALE])    ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        !is_numeric($custom) || $custom < 0 ? resError('Tổng custom không hợp lệ') : '';


        $order['working_custom_active'][$id_user]['custom'] = $custom;
        $num_custom_used = 0;
        foreach ($order['working_custom_active'] as $u) {
            $num_custom_used += $u['custom'];
        }
        if($num_custom_used > $order['custom']) {
            resError('Đã vượt quá tổng custom');
        }

        $kq = $this->Order_model->update_custom_order_for_user($id_order, $custom, $id_user);

        // TODO: LOG
        resSuccess($kq);
    }
}
