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
                $list_order = $this->Order_model->get_list_for_qc($uid); //lấy tất cả đơn
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
        // var_dump($order);die;

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
        $cur_uid = $this->_session_uid();

        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            resError('not_permit', 'Bạn không có quyền thực hiện.');
        }
        $curr_uinfo = $this->User_model->get_user_info_by_id($cur_uid);
        $curr_uinfo['status'] == '-1' ? resError('Tài khoản của bạn đang bị khóa') : '';

        $danh_sach_image_avaiable = $this->Order_model->danh_sach_image_avaiable();

        if (empty($danh_sach_image_avaiable)) {
            resError('not_result', 'Không tìm thấy đơn. Hãy thử lại bạn nhé.');
        } else {

            // lấy ra đơn đầu tiên 
            $first_image = array_key_first($danh_sach_image_avaiable);
            $first_order = $danh_sach_image_avaiable[$first_image]['id_order'];

            resSuccess('ok', $first_order);
        }
    }

    //TODO: check kỹ lại quyền
    function ajax_change_status_order($id_order, $new_status)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();

        $order                = $this->Order_model->get_info_order($id_order);
        $allow_status_by_role = button_status_order_by_role($role);

        empty($order)                               ? resError('Đơn không tồn tại') : '';
        !isset($allow_status_by_role[$new_status])  ? resError('Trạng thái chuyển không phù hợp') : '';

        $order['status'] == $new_status ? resSuccess('ok') : '';

        if ($new_status == ORDER_PENDING) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về PENDING') : '';
        }
        if ($new_status == ORDER_QC_CHECK) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về PENDING') : '';
        }
        if ($new_status == ORDER_AVAIABLE) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về AVAIABLE') : '';
        }
        if ($new_status == ORDER_DONE) {
        }
        if ($new_status == ORDER_DELIVERED ) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về DELIVERED') : '';
        }
        if ($new_status == ORDER_FIX) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về FIX') : '';
        }
        if ($new_status == ORDER_REWORK) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về REWORK') : '';
            $role == QC     ? resError('QC không có quyền đổi trạng thái về REWORK') : '';
        }
        if ($new_status == ORDER_CANCLE) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về CANCLE') : '';
            $role == QC     ? resError('QC không có quyền đổi trạng thái về CANCLE') : '';

        }
        if ($new_status == ORDER_COMPLETE) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về COMPLETE') : '';
            $role == QC     ? resError('QC không có quyền đổi trạng thái về COMPLETE') : '';
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
        if ($new_status == ORDER_DELIVERED || $new_status == ORDER_COMPLETE) {
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

        // WORKING_EDITOR
        if ($working_type == WORKING_EDITOR) {
            $role == EDITOR && $order['status'] == ORDER_PENDING    ? resError('ED không thể tham gia vào đơn hàng đang PENDING') : '';
            $role == EDITOR && $order['status'] == ORDER_QC_CHECK   ? resError('ED không thể tham gia vào đơn hàng đang QC CHECK') : '';
            !isset($order['job'][$id_job])                          ? resError('IMAGE không tồn tại') : '';
            !empty($order['job'][$id_job]['working_ed_active'])     ? resError('Đã có người nhận làm IMAGE này') : '';
        }
        // WORKING_QC_IN
        else if ($working_type == WORKING_QC_IN) {
            $role == EDITOR                                     ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $as_uinfo['role'] == EDITOR                         ? resError('Không được gán tài khoản ED vào đây.') : '';
            !isset($order['job'][$id_job])                      ? resError('IMAGE không tồn tại') : '';
            !empty($order['job'][$id_job]['working_qc_in_active']) ? resError('Đã có người nhận làm IMAGE này') : '';
        }
        // WORKING_QC_OUT
        else if ($working_type == WORKING_QC_OUT) {
            $role == EDITOR                                     ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $as_uinfo['role'] == EDITOR                         ? resError('Không được gán tài khoản ED vào đây.') : '';
            !isset($order['job'][$id_job])                      ? resError('IMAGE không tồn tại') : '';
            !empty($order['job'][$id_job]['working_qc_out_active']) ? resError('Đã có người nhận làm IMAGE này') : '';
        }
        // WORKING_CUSTOM
        else if ($working_type == WORKING_CUSTOM) {
            $id_job = 0; // mặc định
            $role == EDITOR ? resError('ED không có quyền thực hiện chức năng này.') : '';
        } else {
            resError('Lỗi dữ liệu truyền vào. Hãy thử lại!');
        }

        # CHECK SAVE
        $status = 1;
        $time_join = date('Y-m-d H:i:s');

        // WORKING_SALE, WORKING_QC_IN, WORKING_QC_OUT, WORKING_EDITOR => thay đổi tất cả user trong job thành status = 0
        if (in_array($working_type, [WORKING_SALE, WORKING_QC_IN, WORKING_QC_OUT, WORKING_EDITOR])) {
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


        // WORKING_QC_IN
        if ($working_type == WORKING_QC_IN) {
            $working_qc_in_active = $order['job'][$id_job]['working_qc_in_active'];

            $role == EDITOR                                         ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $role == QC && !isset($working_qc_in_active[$cur_uid])  ? resError('Bạn chưa được gán vào IMAGE này') : '';
        }
        // WORKING_QC_OUT
        else if ($working_type == WORKING_QC_OUT) {
            $working_qc_in_active = $order['job'][$id_job]['working_qc_out_active'];

            $role == EDITOR                                         ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $role == QC && !isset($working_qc_out_active[$cur_uid]) ? resError('Bạn chưa được gán vào IMAGE này') : '';
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
        if ($num_custom_used > $order['custom']) {
            resError('Đã vượt quá tổng custom');
        }

        $kq = $this->Order_model->update_custom_order_for_user($id_order, $custom, $id_user);

        // TODO: LOG
        resSuccess($kq);
    }

    function ajax_ed_join_order($id_order)
    {
        $role       = $this->_session_role();
        $cur_uid    = $this->_session_uid();
        $cur_uname  = $this->_session_uname();;
        $curr_uinfo = $this->User_model->get_user_info_by_id($cur_uid);
        $order      = $this->Order_model->get_info_order($id_order);

        $order == []                                ? resError('Đơn không tồn tại') : '';
        $curr_uinfo['status'] == '-1'               ? resError('Tài khoản của bạn đang bị khóa') : '';
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        // kiểm tra đơn có hợp lệ không
        $list_job_no_ed = [];
        foreach ($order['job'] as $id_job => $job) {
            if ($job['working_ed_active'] == []) {
                $list_job_no_ed[$id_job] = $id_job;
            }
        }

        if ($list_job_no_ed == []) {
            resError('Đơn không hợp lệ');
        }

        # SAVE
        // cập nhật vào custom
        $status = 1;
        $time_join = date('Y-m-d H:i:s');

        // add user vào custom
        $da_ton_tai_custom = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, 0, WORKING_CUSTOM, $cur_uid);
        if ($da_ton_tai_custom) {
            $this->Order_model->change_status_job_user($status, $id_order, 0, WORKING_CUSTOM, $cur_uid);
        } else {
            $this->Order_model->add_job_user($id_order, 0, $cur_uid, $cur_uname, '', WORKING_CUSTOM, $status, $time_join);
        }

        // add user vào job
        foreach ($list_job_no_ed as $id_job_no_ed) {
            $type_service = @$order['job'][$id_job]['type_service'];
            $this->Order_model->add_job_user($id_order, $id_job_no_ed, $cur_uid, $cur_uname, $type_service, WORKING_EDITOR, $status, $time_join);
        }

        // chuyển trạng thái đơn về đang xử lý sau khi add user xong
        $this->Order_model->update_status_order($id_order, ORDER_PROGRESS);

        resSuccess('Join thành công');
    }

    function ajax_change_code_order()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_order = $this->input->post('id_order');
        $code    = $this->input->post('code');
        $code = removeAllTags($code);
        $code = str_replace(' ', '_', $code);

        $order         = $this->Order_model->get_info_order($id_order);
        $infoOrderCode = $this->Order_model->get_order_info_by_code($code);

        $order == []            ? resError('Đơn hàng không tồn tại') : '';
        $infoOrderCode != []    ? resError('Code Order đã tồn tại') : '';

        $this->Order_model->update_code_order($id_order, $code);
        resSuccess('Thành công');
    }

    function ajax_change_custom_time()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $second   = $this->input->post('second');
        $id_order = $this->input->post('id_order');

        is_numeric($second) && $second >= 0 ? '' : resError('Thời gian không hợp lệ');

        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn hàng không tồn tại') : '';

        //TODO: THIẾU GHI LOG
        $this->Order_model->update_custom_time_order($id_order, $second);
        resSuccess('Thành công');
    }

    function ajax_edit_main_file()
    {
        $cur_uid = $this->_session_uid();
        $role    = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_job    = $this->input->post('id_job');

        !isIdNumber($id_job) ? resError('IMGAE không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_image_job($id_job, $copy['basename']);
        resSuccess('Thành công');
    }

    function ajax_edit_attach_file()
    {
        $cur_uid = $this->_session_uid();
        $role    = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_job    = $this->input->post('id_job');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_job)    ? resError('IMGAE không hợp lệ')    : '';
        !isIdNumber($id_attach) ? resError('ID ATTACH không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $attachs = json_decode($info['attach'], true);
        !isset($attachs[$id_attach]) ? resError('Attach không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $attachs[$id_attach] = $copy['basename'];
        $this->Job_model->update_attach_job($id_job, json_encode($attachs));
        resSuccess('Thành công');
    }

    function ajax_update_requirement()
    {
        $cur_uid = $this->_session_uid();
        $role    = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_job    = $this->input->post('id_job');
        $requirement = removeAllTags($this->input->post('requirement'));

        !isIdNumber($id_job) ? resError('IMGAE không hợp lệ') : '';
        !strlen($requirement) ? resError('Requirement không được bỏ trống') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_requirement_job($id_job, $requirement);
        resSuccess('Thành công');
    }

    function ajax_add_file_complete()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_job    = $this->input->post('id_job');

        !isIdNumber($id_job) ? resError('IMGAE không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $id_file_complete = time();
        $info['file_complete'][$id_file_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));
        resSuccess($id_file_complete);
    }

    function ajax_edit_file_complete()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image   = $this->input->post('url_image');
        $id_job      = $this->input->post('id_job');
        $id_complete = $this->input->post('id_complete');

        !isIdNumber($id_job)        ? resError('IMGAE không hợp lệ')      : '';
        !isIdNumber($id_complete)   ? resError('ID COMPLETE không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['file_complete'][$id_complete]) ? resError('ID COMPLETE không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $info['file_complete'][$id_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));
        resSuccess($id_complete);
    }

    function ajax_delete_file_complete()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_job    = $this->input->post('id_job');
        $id_complete = $this->input->post('id_complete');

        !isIdNumber($id_job)        ? resError('IMGAE không hợp lệ')           : '';
        !isIdNumber($id_complete)   ? resError('ID FILE COMPLETE không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('IMAGE không tồn tại') : '';

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['file_complete'][$id_complete]) ? resError('ID FILE COMPLETE không tồn tại') : '';

        unset($info['file_complete'][$id_complete]); // xóa

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));
        resSuccess($id_complete);
    }

    // REWORK
    function ajax_add_rework($id_job)
    {

        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $note   = $this->input->post('note');
        $attach = $this->input->post('attach');

        $note = removeAllTags($note);

        !isIdNumber($id_job)    ? resError('IMGAE không hợp lệ') : '';

        $job = $this->Job_model->get_info_job_by_id($id_job);
        $job == [] ? resError('IMAGE không tồn tại') : '';

        $note == ''             ? resError('Hãy nhập mô tả') : '';
        !is_array($attach)      ? resError('Attach không hợp lệ') : '';

        $db_attach = [];
        foreach ($attach as $i => $url_image) {
            $parse = parse_url($url_image);
            !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
            !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

            $copy = copy_image_from_file_manager_to_public_upload($url_image, $job['year'], $job['month']);
            !$copy['status'] ? resError($copy['error']) : '';
            $id_attach = time() + $i;
            $db_attach[$id_attach] = $copy['basename'];
        }

        $exc = $this->Job_model->add_rework($job['id_order'], $id_job, json_encode($db_attach), $note, $cur_uid);

        $exc ? resSuccess('ok') : resError('Không lưu được vào lúc này, vui lòng thử lại');
    }

    function ajax_add_file_attach_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_rework = $this->input->post('id_rework');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $id_attach = time();
        $info['attach'][$id_attach] = $copy['basename'];
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($info['attach']));
        resSuccess($id_attach);
    }

    function ajax_edit_file_attach_rework()
    {

        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_rework = $this->input->post('id_rework');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ')      : '';
        !isIdNumber($id_attach) ? resError('ID attach không hợp lệ') : '';

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($rework['attach'][$id_attach]) ? resError('ID attach không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $rework['year'], $rework['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $rework['attach'][$id_attach] = $copy['basename'];
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($rework['attach']));
        resSuccess($id_attach);
    }

    function ajax_delete_file_attach_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_rework    = $this->input->post('id_rework');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ')           : '';
        !isIdNumber($id_attach) ? resError('ID FILE COMPLETE không hợp lệ') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['attach'][$id_attach]) ? resError('ID FILE COMPLETE không tồn tại') : '';

        unset($info['attach'][$id_attach]); // xóa

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($info['attach']));
        resSuccess($id_attach);
    }

    // TODO: mới copy code
    function ajax_update_requirement_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_rework    = $this->input->post('id_rework');
        $requirement = removeAllTags($this->input->post('requirement'));

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';
        !strlen($requirement) ? resError('Requirement không được bỏ trống') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_requirement_rework($id_rework, $requirement);
        resSuccess('Thành công');
    }

    function ajax_add_file_complete_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_rework = $this->input->post('id_rework');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $info['year'], $info['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $id_file_complete = time();
        $info['file_complete'][$id_file_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_rework($id_rework, json_encode($info['file_complete']));
        resSuccess($id_file_complete);
    }

    function ajax_edit_file_complete_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image          = $this->input->post('url_image');
        $id_rework          = $this->input->post('id_rework');
        $id_complete_rework = $this->input->post('id_complete_rework');

        !isIdNumber($id_rework)             ? resError('Rework không hợp lệ')      : '';
        !isIdNumber($id_complete_rework)    ? resError('ID COMPLETE không hợp lệ') : '';

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($rework['file_complete'][$id_complete_rework]) ? resError('ID COMPLETE không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $copy = copy_image_from_file_manager_to_public_upload($url_image, $rework['year'], $rework['month']);
        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $rework['file_complete'][$id_complete_rework] = $copy['basename'];
        $this->Job_model->update_file_complete_rework($id_rework, json_encode($rework['file_complete']));
        resSuccess($id_complete_rework);
    }

    function ajax_delete_file_complete_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_rework    = $this->input->post('id_rework');
        $id_complete = $this->input->post('id_complete');

        !isIdNumber($id_rework)     ? resError('Rework không hợp lệ')           : '';
        !isIdNumber($id_complete)   ? resError('ID FILE COMPLETE không hợp lệ') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['file_complete'][$id_complete]) ? resError('ID FILE COMPLETE không tồn tại') : '';

        unset($info['file_complete'][$id_complete]); // xóa

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_file_complete_rework($id_rework, json_encode($info['file_complete']));
        resSuccess($id_complete);
    }
}
