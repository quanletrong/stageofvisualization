<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
{

    // private $_status_working = [ORDER_PENDING, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE, ORDER_FIX, ORDER_REWORK];

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
        $this->load->model('library/Library_model');
        $this->load->model('room/Room_model');
        $this->load->model('service/Service_model');
        $this->load->model('setting/Setting_model');
        $this->load->model('log/Log_model');
        $this->load->model('voucher/Voucher_model');
        $this->load->model('discuss/Discuss_model');
    }

    function index()
    {

        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        $data = [];
        $role = $this->_session_role();
        $uid = $this->_session_uid();
        $uinfo = $this->User_model->get_user_info_by_id($uid);

        ### DU LIEU LAM FILTER
        $all_service    = $this->Service_model->get_list();
        $all_user       = $this->User_model->get_list_user_working('0,1', implode(",", [ADMIN, SALE, QC, EDITOR]));
        $all_ed_type    = [ED_NOI_BO => 'Editor nội bộ', ED_CTV => 'Editor cộng tác viên'];
        $all_order_type = [DON_KHACH_TAO => 'Đơn khách tạo', DON_NOI_BO => 'Đơn nội bộ', DON_TAO_HO => 'Đơn tạo hộ'];

        $all_status[ORDER_PENDING]   = status_order(ORDER_PENDING);    // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_QC_CHECK]  = status_order(ORDER_QC_CHECK);   // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_AVAIABLE]  = status_order(ORDER_AVAIABLE);   // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_PROGRESS]  = status_order(ORDER_PROGRESS);   // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_DONE]      = status_order(ORDER_DONE);       // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_FIX]       = status_order(ORDER_FIX);        // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_REWORK]    = status_order(ORDER_REWORK);     // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_DELIVERED] = status_order(ORDER_DELIVERED);  // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_COMPLETE]  = status_order(ORDER_COMPLETE);   // bỏ sung thêm trạng thái đang xử lý
        $all_status[ORDER_CANCLE]    = status_order(ORDER_CANCLE);     // bỏ sung thêm trạng thái đang xử lý

        if ($role == ADMIN || $role == SALE) {
            $all_status[ORDER_PAY_WAITING]    = status_order(ORDER_PAY_WAITING);     // bỏ sung thêm trạng thái đang xử lý
        }
        # 

        ### FORM FILTER
        $filter_code_order    = removeAllTags($this->input->get('filter_code_order'));
        $filter_user_code     = removeAllTags($this->input->get('filter_user_code'));
        $filter_order_ed_type = $this->input->get('filter_order_ed_type');
        $filter_status        = $this->input->get('filter_status');
        $filter_service       = $this->input->get('filter_service');
        $filter_order_type    = $this->input->get('filter_order_type');
        $filter_fdate         = $this->input->get('filter_fdate');
        $filter_tdate         = $this->input->get('filter_tdate');
        $filter_id_user       = $this->input->get('filter_id_user');
        $filter_custom        = $this->input->get('filter_custom');

        ### DU LIEU MAC DINH
        if ($role == ADMIN || $role == SALE) {
            $status_filter_default = [ORDER_PENDING, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_REWORK, ORDER_DELIVERED];
        } else {
            $status_filter_default = [ORDER_PENDING, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE, ORDER_FIX, ORDER_REWORK];
        }

        if ($role == EDITOR) {
            $filter_id_user = [$uid];
        }

        // QC chi duoc xem DON cua` minh 

        //validate filter_order_ed_type
        $filter_order_ed_type = is_array($filter_order_ed_type) ? $filter_order_ed_type : [];
        foreach ($filter_order_ed_type as $ed_type) {
            if (!isset($all_ed_type[$ed_type])) {
                $filter_order_ed_type = [];
                break;
            }
        }

        //validate filter_status
        $filter_status = is_array($filter_status) ? $filter_status : $status_filter_default;
        foreach ($filter_status as $status) {
            if (!isset($all_status[$status])) {
                $filter_status = [];
                break;
            }
        }

        //validate filter_service
        $filter_service = is_array($filter_service) ? $filter_service : [];
        foreach ($filter_service as $service) {
            if (!isset($all_service[$service])) {
                $filter_service = [];
                break;
            }
        }

        //validate filter_order_type
        $filter_order_type = is_array($filter_order_type) ? $filter_order_type : [];
        foreach ($filter_order_type as $order_type) {
            if (!isset($all_order_type[$order_type])) {
                $filter_order_type = [];
                break;
            }
        }

        //validate filter_id_user
        $filter_id_user = is_array($filter_id_user) ? $filter_id_user : [];
        foreach ($filter_id_user as $id_user) {
            if (!isset($all_user[$id_user])) {
                $filter_id_user = [];
                break;
            }
        }

        //validate filter date
        $ngay_hien_tai = date("Y-m-d H:i:s");
        $ba_muoi_ngay_truoc = date("Y-m-d H:i:s", strtotime('today - 29 days'));
        $filter_fdate = !is_date($filter_fdate) ? $ba_muoi_ngay_truoc : $filter_fdate;
        $filter_tdate = !is_date($filter_tdate) ? $ngay_hien_tai : $filter_tdate;


        //validate filter_id_user
        $filter_custom = in_array($filter_custom, ['>=', '>', '=']) ? $filter_custom : '>=';
        if ($role == EDITOR) {
            $filter_custom = '>=';
        }

        # END FORM FILTER

        ### CALL DATABASE
        $filter['code_order']   = $filter_code_order;
        $filter['user_code']    = $filter_user_code;
        $filter['ed_type']      = implode(',', $filter_order_ed_type);
        $filter['status']       = implode(',', $filter_status);
        $filter['type_service'] = implode(',', $filter_service);
        $filter['order_type']   = implode(',', $filter_order_type);
        $filter['id_user']      = implode(',', $filter_id_user);
        $filter['custom']       = $filter_custom;

        $filter['fdate']   = date("Y-m-d", strtotime($filter_fdate)) . ' 00:00:00';
        if ($filter_tdate < $ngay_hien_tai) {
            $filter['tdate']   = date("Y-m-d", strtotime($filter_tdate)) . ' 23:59:59';
        } else {
            $filter['tdate']   = date("Y-m-d", strtotime($filter_tdate)) . ' ' . date("H:i:s");
        }

        $list_order = $this->Order_model->get_list_v2($filter, $role);       //lấy tất cả đơn

        // sô liệu total
        $box = $this->Order_model->box_count($list_order);

        // $ed_type dùng để làm tính total image_avaiable
        $ed_type = $role == EDITOR ? $uinfo['type'] : ED_NOI_BO . "," . ED_CTV;

        $all_service_ids = implode(',', array_keys($all_service));

        $list_image_avaiable = $this->Order_model->danh_sach_image_avaiable($ed_type, $all_service_ids);
        $box['image_avaiable'] = count($list_image_avaiable);

        # END CALL DATABASE

        ### DATA
        $data['box']                  = $box;
        $data['list_order']           = $list_order;
        $data['filter_code_order']    = $filter_code_order;
        $data['filter_user_code']     = $filter_user_code;
        $data['filter_order_ed_type'] = $filter_order_ed_type;
        $data['filter_service']       = $filter_service;
        $data['filter_status']        = $filter_status;
        $data['filter_order_type']    = $filter_order_type;
        $data['filter_fdate']         = $filter_fdate;
        $data['filter_tdate']         = $filter_tdate;
        $data['filter_id_user']       = $filter_id_user;
        $data['filter_custom']        = $filter_custom;

        $data['all_service']    = $all_service;
        $data['all_status']     = $all_status;
        $data['all_user']       = $all_user;
        $data['all_ed_type']    = $all_ed_type;
        $data['all_order_type'] = $all_order_type;
        # END DATA

        $header = [
            'title' => 'Quản lý đơn hàng',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'order/list/order_view_v2', $data);
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
        $all_user_working = $this->User_model->get_list_user_working('0,1', implode(",", [ADMIN, SALE, QC, EDITOR]));
        $order = $this->Order_model->get_info_order($id_order);
        // var_dump($order);die;
        empty($order) ? redirect(site_url('order', $this->_langcode)) : '';

        ## check right access
        $status = $order['status'];
        switch ($role) {
            case ADMIN:
                break;
            case SALE:
                break;
            case QC:
                // QC chỉ được xem những đơn khác pending hoặc những đơn QC đã active
                if (isset($order['team'][$uid]) || $status != ORDER_PENDING) {
                } else {
                    redirect(site_url('order', $this->_langcode));
                }
                break;
            case EDITOR:
                if (!isset($order['team'][$uid]) && $status != ORDER_AVAIABLE) {
                    // die('Bạn không phải thành viên trong đơn hàng này.');
                    redirect(site_url('order', $this->_langcode));
                }
                break;
            default:
                break;
        }

        ## chung
        $data['order']            = $order;
        $data['role']             = $role;
        $data['curr_uid']         = $uid;
        $data['all_user_working'] = $all_user_working;
        $data['FDR_ORDER']        = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';

        $header = [
            'title' => 'Chi tiết đơn hàng',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/detail/order_detail_view', $data);

        $this->_loadFooter();
    }

    function add_private()
    {
        $cur_uid = $this->_session_uid();
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            dbClose();
            redirect(site_url('order', $this->_langcode));
            die();
        }

        $room          = $this->Room_model->get_list(1);
        $style         = $this->Style_model->get_list(1);
        $library       = $this->Library_model->get_list(1);
        $service       = $this->Service_model->get_list(1);

        $data = [];
        $data['list_room']     = $room;
        $data['list_service']  = $service;
        $data['list_style']    = $style;
        $data['list_library']  = $library;

        $header = [
            'title' => 'Tạo đơn hàng nội bộ',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'order/add_private/order_view', $data);
        $this->_loadFooter();
    }

    function add_customer()
    {
        $cur_uid = $this->_session_uid();
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            dbClose();
            redirect(site_url('order', $this->_langcode));
            die();
        }

        $room          = $this->Room_model->get_list(1);
        $style         = $this->Style_model->get_list(1);
        $library       = $this->Library_model->get_list(1);
        $service       = $this->Service_model->get_list(1);
        $list_customer = $this->User_model->get_list_user_working(1, implode(",", [CUSTOMER]));

        $data = [];
        $data['list_room']     = $room;
        $data['list_service']  = $service;
        $data['list_style']    = $style;
        $data['list_library']  = $library;
        $data['list_customer'] = $list_customer;

        $header = [
            'title' => 'Tạo đơn hàng cho khách',
            'header_page_css_js' => 'order'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'order/add_customer/order_view', $data);
        $this->_loadFooter();
    }

    function submit_add($type)
    {
        $cur_uid     = $this->_session_uid();
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            resError('not_permit', 'Bạn không có quyền thực hiện.');
        }

        $all_room    = $this->Room_model->get_list(1);
        $all_service = $this->Service_model->get_list(1);
        $all_style   = $this->Style_model->get_list(1);

        $order       = $this->input->post('order');
        $jid         = removeAllTags($order['jid']);
        $style       = isset($order['style']) ? $order['style'] : resError('Không tìm thấy Design Style');
        $for_user    = isset($order['for_user']) ? $order['for_user'] : '';
        $create_time = date('Y-m-d H:i:s');
        $list_job    = isset($order['job']) ? $order['job'] : resError('Không tìm thấy Photo (job)');
        $id_voucher  = isset($order['voucher']) ? $order['voucher'] : 0;

        // VALIDATE

        # check private
        if ($type == 'private') {
            $create_id_user = $cur_uid;  //mặc định
            $for_user       = $cur_uid;  //mặc định
            $info_user      = $this->User_model->get_user_info_by_id($cur_uid);
            $FDR_ORDER      = FOLDER_ORDER . strtotime($create_time) . '@' . $info_user['username'];
        }
        # check customer
        else if ($type == 'customer') {
            $info_user = $this->User_model->get_user_info_by_id($for_user);

            empty($info_user)               ? resError('User được chọn không tồn tại') : '';
            $info_user['role'] != CUSTOMER  ? resError('User được chọn không phải là khách hàng') : '';
            $info_user['status'] == 0       ? resError('User được chọn đã bị khóa') : '';

            $create_id_user = $cur_uid;
            $FDR_ORDER      = FOLDER_ORDER . strtotime($create_time) . '@' . $info_user['username'];
        }
        # không hợp lệ
        else {
            resError('type', 'Dữ liệu không hợp lệ');
        }

        # check style
        if (isIdNumber($style)) {
            isset($all_style[$style]) ? '' : resError('error_style');
        }

        # check voucher (ko bắt buộc nhập voucher)
        $info_voucher = [];
        if (isIdNumber($id_voucher)) {
            $lst_voucher =  $this->Voucher_model->get_list_voucher_for_create_order_by_sale($cur_uid, $create_time);
            isset($lst_voucher[$id_voucher]) ? '' : resError('Mã giảm giá không hợp lệ');
            $info_voucher = $lst_voucher[$id_voucher];
        }

        # check file attach
        foreach ($list_job as $id_job => $job) {
            $room        = $job['room'];
            $service     = $job['service'];
            $image       = $job['image'];
            $requirement = isset($job['requirement']) ? $job['requirement'] : '';
            $attach      = isset($job['attach']) ? $job['attach'] : [];      // k bat buoc nhap attach nen de @

            # check room, service
            isset($all_room[$room])         ? '' : resError('error_room');
            isset($all_service[$service])   ? '' : resError('error_service');

            # lưu ảnh image
            $copy_image = copy_image_to_public_upload($image, $FDR_ORDER);
            if ($copy_image['status'] == false) {
                deleteDirectory($FDR_ORDER);
                resError('error_image');
            }
            $list_job[$id_job]['image_ok'] = $copy_image['basename'];

            # lưu ảnh attachments
            $attach_ok = [];
            foreach ($attach as $id_attach => $image_attach) {
                $copy_attach = copy_image_to_public_upload($image_attach, $FDR_ORDER);
                if ($copy_attach['status']) {
                    $attach_ok[$id_attach] = $copy_attach['basename'];
                } else {
                    deleteDirectory($FDR_ORDER);
                    resError('error_attach');
                }
            }
            $list_job[$id_job]['attach_ok'] = $attach_ok;
        }
        // END VALIDATE

        // LƯU ĐƠN HÀNG
        $type_order = $type == 'private' ? DON_NOI_BO : DON_TAO_HO;
        $new_order = $this->Order_model->add_order($style, $create_time, $for_user, ORDER_PAY_WAITING, $type_order, $create_id_user, ED_NOI_BO, $jid);

        if ($new_order == false) {
            deleteDirectory($FDR_ORDER);
            resError('Tạo đơn thất bại. Hãy thử lại lần nữa.');
        }

        // LƯU JOB SAU KHI TẠO XONG ORDER
        $total_price = 0;
        $exc_add_job = true;
        foreach ($list_job as $job) {
            $room           = $job['room'];
            $service        = $job['service'];
            $type_service   = $all_service[$service]['type_service'];
            $price          = $all_service[$service]['price'];
            $price_unit     = '2'; //TODO: 1 VND, 2 Đô, ...
            $image_ok       = $job['image_ok'];
            $json_attach_ok = json_encode($job['attach_ok'], JSON_FORCE_OBJECT);
            $requirement    = $job['requirement'];

            $exc_add_job = $this->Order_model->add_order_job($new_order, $service, $type_service, $price, $price_unit, $room, $style, $image_ok, $json_attach_ok, $requirement, $create_time);

            if (!$exc_add_job) break;

            $total_price += $price;
        }

        // LƯU LICH SU THANH TOAN ORDER
        $price_vou = isset($info_voucher['price']) ? $info_voucher['price'] : 0;
        $code_vou  = isset($info_voucher['code']) ? $info_voucher['code'] : '';
        $amount    = (float) ($total_price > $price_vou ? ($total_price - $price_vou) : 0);
        $don_khong_can_thanh_toan = $amount == 0 || $type_order == DON_NOI_BO;
        $don_can_thanh_toan       = $amount > 0 && $type_order == DON_TAO_HO;

        // LƯU LỊCH SỬ THANH TOÁN ĐƠN
        $exc_add_payment_order = true;

        # đơn không cần thanh toán
        if ($don_khong_can_thanh_toan) {
            $exc_add_payment_order = $this->Order_model->add_payment_order($new_order, $id_voucher, $code_vou, $total_price, $price_vou, $cur_uid, PAY_HOAN_THANH, 0, $create_time);
        }

        # đơn cần thanh toán
        if ($don_can_thanh_toan) {
            $type_payment = PAYPAL;  //TODO: mặc định thanh toán bằng PAYPAL
            if ($type_payment == PAYPAL) {
                $exc_add_payment_order =  $this->Order_model->add_payment_order($new_order, $id_voucher, $code_vou, $total_price, $price_vou, $cur_uid, PAY_DANG_CHO, PAYPAL, $create_time);
            }
        }

        // UPDATE ĐƠN VÊ PENDING
        $exc_update_status_order = true;
        if ($don_khong_can_thanh_toan) {
            $exc_update_status_order = $this->Order_model->update_status_order($new_order, ORDER_PENDING);
        }

        // GUI TIN NHAN DEN KHACH (NẾU CÓ)
        $exc_discuss_add = true;
        if ($don_can_thanh_toan) {
            $nguoi_tao = $this->User_model->get_user_info_by_id($cur_uid);
            $temp = [
                'id_order'      => $new_order,
                'title'         => 'BẠN CÓ ĐƠN HÀNG CẦN THANH TOÁN',
                'create_time'   => $create_time,
                'nguoi_tao'     => $nguoi_tao,
                'total'         => count($list_job),
                'price'         => $price,
                'price_vouchor' => $price_vou,
                'amount'        => $amount
            ];
            $content = $this->load->view($this->_template_f . 'order/add_customer/temp_order_request', $temp, true);
            $exc_discuss_add = $this->Discuss_model->discuss_add($cur_uid, $new_order, $content, '{}', $create_time, 1, CHAT_KHACH);
        }

        // LƯU LOG
        $log['type']     = LOG_CREATE_ORDER;
        $log['id_order'] = $new_order;
        $order           = $this->Order_model->get_info_order($new_order);
        $exc_log_add = $this->Log_model->log_add($log, $order);


        // HOÀN THÀNH QUÁ TRÌNH LƯU ĐƠN HÀNG
        if (
            !$exc_add_job ||
            !$exc_add_payment_order ||
            !$exc_update_status_order ||
            !$exc_discuss_add ||
            !$exc_log_add
        ) {
            $this->Order_model->delete_order_and_job($new_order);
            deleteDirectory($FDR_ORDER);
            // [3] xóa payment order TODO:
            // [4] xóa log order TODO:
            // [5] xóa discuss (nếu có) TODO:
            resError('Có lỗi xảy ra trong quá trình lưu đơn. Vui lòng thử lại sau.');
        } else {
            resSuccess([
                'price'          => (float) $total_price,
                'price_vou'      => (float) $price_vou,
                'price_payment'  => (float) $amount,
                'new_id_order'   => (int) $new_order,
                'new_id_payment' => (int) $exc_add_payment_order
            ]);
        }
    }

    function ajax_find_order()
    {
        $cur_uid = $this->_session_uid();
        $role    = $this->_session_role();

        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            resError('not_permit', 'Bạn không có quyền thực hiện.');
        }
        $curr_uinfo = $this->User_model->get_user_info_by_id($cur_uid);
        $curr_uinfo['status'] == '-1' ? resError('Tài khoản của bạn đang bị khóa') : '';


        $user_service = implode(',', array_keys($curr_uinfo['user_service']));

        if ($user_service == '') {
            resError('not_result', 'Không tìm thấy đơn. Hãy thử lại bạn nhé.');
        }

        // lọc theo ed là nội bộ hoặc ctv
        $ed_type = $role == EDITOR ? $curr_uinfo['type'] : ED_NOI_BO . "," . ED_CTV;
        $danh_sach_image_avaiable = $this->Order_model->danh_sach_image_avaiable($ed_type, $user_service);

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

        // Khánh bảo bỏ
        // if ($new_status == ORDER_QC_CHECK) {
        //     $role == EDITOR ? resError('ED không có quyền đổi trạng thái về QC_CHECK') : '';
        // }

        if ($new_status == ORDER_PROGRESS) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về IN-PROGRESS') : '';
        }

        if ($new_status == ORDER_AVAIABLE) {
            $role == EDITOR ? resError('ED không có quyền đổi trạng thái về AVAILABLE') : '';
        }
        if ($new_status == ORDER_DONE) {
        }
        if ($new_status == ORDER_DELIVERED) {
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
        if ($new_status == ORDER_DELIVERED) {
            $thoi_gian_giao_hang = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_giao_hang($id_order, $thoi_gian_giao_hang);
        }

        if ($new_status == ORDER_COMPLETE && $order['done_qc_time'] == '') {
            $thoi_gian_giao_hang = date('Y-m-d H:i:s');
            $this->Order_model->luu_thoi_gian_giao_hang($id_order, $thoi_gian_giao_hang);
        }
        // end lưu thời gian giao hàng



        // tính tiền cho user đang active trong đơn
        if ($new_status == ORDER_DELIVERED || $new_status == ORDER_COMPLETE) {
            $thoi_gian_tinh_tien = date('Y-m-d H:i:s');
            $this->Order_model->tinh_tien_cho_cac_user_dang_active($id_order, $thoi_gian_tinh_tien);
        }

        //LOG
        $log['type']      = LOG_STATUS;
        $log['id_order']  = $order['id_order'];
        $log['old']       = status_order($order['status'])['text'];
        $log['new']       = status_order($new_status)['text'];
        $this->Log_model->log_add($log, $order);

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

        // gán type_service để lưu vào db
        $db_type_service = @$order['job'][$id_job]['type_service'];

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
            $db_type_service = SERVICES_CUSTOM;
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

        // cập nhật vào custom (TODO: bỏ)
        // $da_ton_tai_custom = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, 0, WORKING_CUSTOM, $id_user);
        // if ($da_ton_tai_custom) {
        //     $this->Order_model->change_status_job_user($status, $id_order, 0, WORKING_CUSTOM, $id_user);
        // } else {
        //     $this->Order_model->add_job_user($id_order, 0, $id_user, $as_uinfo['username'], SERVICES_CUSTOM, WORKING_CUSTOM, $status, $time_join, 0);
        // }

        // user gán đã tồn tại thì UPDATE status = 1
        $da_ton_tai = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, $id_job, $working_type, $id_user);
        if ($da_ton_tai) {
            $kq = $this->Order_model->change_status_job_user($status, $id_order, $id_job, $working_type, $id_user);
        }
        // user gán chưa tồn tại thì INSERT bản ghi mới
        else {
            $kq = $this->Order_model->add_job_user($id_order, $id_job, $id_user, $as_uinfo['username'], $db_type_service, $working_type, $status, $time_join, 1);
        }

        //LOG
        if ($working_type == WORKING_EDITOR) {
            $log_type = LOG_ED_ADD;
        } else if ($working_type == WORKING_QC_IN) {
            $log_type = LOG_QC_IN_ADD;
        } else if ($working_type == WORKING_QC_OUT) {
            $log_type = LOG_QC_OUT_ADD;
        } else if ($working_type == WORKING_CUSTOM) {
            $log_type = LOG_CUSTOM_ADD;
        }

        $log['type']      = $log_type;
        $log['id_order']  = $order['id_order'];
        $log['id_job']    = $id_job;
        $log['new']       = $as_uinfo['username'];

        $order['team'][$id_user] = $as_uinfo; // gán người vừa được thêm vào team, để gửi email
        $this->Log_model->log_add($log, $order);

        resSuccess($kq);
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
            $working_qc_in_active[$id_user]['withdraw']             ? resError('Xóa không thành công vì đơn hàng đã được tính tiền cho người dùng này') : '';
        }
        // WORKING_QC_OUT
        else if ($working_type == WORKING_QC_OUT) {
            $working_qc_out_active = $order['job'][$id_job]['working_qc_out_active'];

            $role == EDITOR                                         ? resError('ED không có quyền thực hiện chức năng này.') : '';
            $role == QC && !isset($working_qc_out_active[$cur_uid]) ? resError('Bạn chưa được gán vào IMAGE này') : '';
            $working_qc_out_active[$id_user]['withdraw']            ? resError('Xóa không thành công vì đơn hàng đã được tính tiền cho người dùng này') : '';
        }
        // WORKING_EDITOR
        else if ($working_type == WORKING_EDITOR) {
            $working_ed_active = $order['job'][$id_job]['working_ed_active'];
            $role == EDITOR && !isset($working_ed_active[$cur_uid]) ? resError('Bạn chưa được gán vào IMAGE này') : '';
            $working_ed_active[$id_user]['withdraw']                ? resError('Xóa không thành công vì đơn hàng đã được tính tiền cho người dùng này') : '';
        }
        // WORKING_CUSTOM
        else if ($working_type == WORKING_CUSTOM) {
            $role == EDITOR  ? resError('ED không có quyền thực hiện chức năng này.') : '';

            $working_custom_active = $order['working_custom_active'];
            $working_custom_active[$id_user]['withdraw'] ? resError('Xóa không thành công vì đơn hàng đã được tính tiền cho người dùng này') : '';

            $id_job = 0;
        } else {
            resError('Lỗi dữ liệu truyền vào. Hãy thử lại!');
        }

        // KHÔNG ĐƯỢC XÓA USER ĐÃ RÚT TIỀN TODO: check lại chỗ này
        // if()

        // cấp nhật giá custom về 0 (nếu có)
        if ($working_type == WORKING_CUSTOM) {
            $custom = 0;
            $this->Order_model->update_custom_order_for_user($id_order, $custom, $id_user);
        }

        $status = 0;
        $kq = $this->Order_model->change_status_job_user($status, $id_order, $id_job, $working_type, $id_user);

        //LOG
        if ($working_type == WORKING_EDITOR) {
            $log_type = LOG_ED_REMOVE;
        } else if ($working_type == WORKING_QC_IN) {
            $log_type = LOG_QC_IN_REMOVE;
        } else if ($working_type == WORKING_QC_OUT) {
            $log_type = LOG_QC_OUT_REMOVE;
        } else if ($working_type == WORKING_CUSTOM) {
            $log_type = LOG_CUSTOM_REMOVE;
        }

        $log['type']      = $log_type;
        $log['id_order']  = $order['id_order'];
        $log['id_job']    = $id_job;
        $log['new']       = $as_uinfo['username'];
        $this->Log_model->log_add($log, $order);

        resSuccess($kq);
    }

    function ajax_change_custom_order($id_order, $custom)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();
        $order   = $this->Order_model->get_info_order($id_order);

        $order == []                        ? resError('Đơn không tồn tại') : '';
        !in_array($role, [ADMIN, SALE])     ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        !is_numeric($custom) || $custom < 0 ? resError('Tổng custom không hợp lệ') : '';
        $custom == $order['custom']         ? resSuccess('ok') : ''; // giá mới = giá cũ

        $kq = $this->Order_model->update_custom_order($id_order, $custom);

        // LOG
        $log['type']      = LOG_CUSTOM_TOTAL_PRICE_EDIT;
        $log['id_order']  = $order['id_order'];
        $log['old']       = $order['custom'];
        $log['new']       = $custom;
        $this->Log_model->log_add($log, $order);

        resSuccess($kq);
    }

    function ajax_change_custom_order_for_user($id_order, $custom, $id_user)
    {
        $role    = $this->_session_role();
        $cur_uid = $this->_session_uid();
        $order   = $this->Order_model->get_info_order($id_order);
        $uinfo   = $this->User_model->get_user_info_by_id($id_user);

        $uinfo == []                                    ? resError('User không tồn tại') : '';
        $order == []                                    ? resError('Đơn không tồn tại') : '';
        !in_array($role, [ADMIN, SALE, QC, EDITOR])     ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        !is_numeric($custom) || $custom < 0             ? resError('Tổng custom không hợp lệ') : '';

        // QC ED không có quyền thay đổi custom của người khác
        // QC ED không có quyền thay custom khi đơn hàng đã giao hoặc đã hoàn thành hoặc đã hủy
        if (in_array($role, [QC, EDITOR])) {

            $cur_uid != $id_user ? resError('QC ED không có quyền thay đổi custom của người khác') : '';

            $DON_GIAO_HUY_XONG = [ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE];
            if (in_array($order['status'], $DON_GIAO_HUY_XONG)) {
                resError('QC ED không có quyền thay custom khi đơn hàng đã giao hoặc đã hoàn thành hoặc đã hủy');
            }
        }

        // price custom cũ
        $old_custom = $order['working_custom_active'][$id_user]['custom'];

        // kiểm tra đã vượt quá tổng custom hay chưa
        $order['working_custom_active'][$id_user]['custom'] = $custom;
        $num_custom_used = 0;
        foreach ($order['working_custom_active'] as $u) {
            $num_custom_used += $u['custom'];
        }
        if ($num_custom_used > $order['custom']) {
            resError('Đã vượt quá tổng custom');
        }
        // end kiểm tra đã vượt quá tổng custom hay chưa

        $kq = $this->Order_model->update_custom_order_for_user($id_order, $custom, $id_user);

        $log['type']           = LOG_CUSTOM_USER_PRICE_EDIT;
        $log['id_order']       = $order['id_order'];
        $log['price_id_user']  = $id_user;
        $log['price_username'] = $uinfo['username'];
        $log['old']            = $old_custom;
        $log['new']            = $custom;
        $this->Log_model->log_add($log, $order);

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

        // kiểm tra tài khoản có quyền làm đơn?
        // lấy danh sách service của đơn - danh sách service của user
        $user_service = $curr_uinfo['user_service'];
        $list_type_service = [];
        foreach ($order['list_type_service'] as $type_service => $id_job) {
            $list_type_service[] = $type_service;
        }
        $ds_service_khong_duoc_lam = array_diff($list_type_service, $user_service);
        count($ds_service_khong_duoc_lam) ? resError('Bạn chưa được cấp quyền làm đơn ' . implode(', ', $ds_service_khong_duoc_lam)) : '';

        // kiểm tra số lượng đơn đang làm có vượt quá max_working_order trong setting không?
        $total_order_working = $this->Order_model->get_total_order_working_by_id_user($cur_uid);
        $get_setting = $this->Setting_model->get_setting();
        $max_order_working = (int) $get_setting['max_order_working'];

        if ($total_order_working >= $max_order_working) {
            resError('Bạn đang có ' . $total_order_working . ' đơn chưa hoàn thành. <br/> Số đơn chưa hoàn thành phải ít hơn ' . $max_order_working . ' đơn.');
        }

        # SAVE
        // cập nhật vào custom
        $status = 1;
        $time_join = date('Y-m-d H:i:s');

        // add user vào custom
        // $da_ton_tai_custom = $this->Order_model->kiem_tra_user_da_ton_tai_trong_job_chua($id_order, 0, WORKING_CUSTOM, $cur_uid);
        // if ($da_ton_tai_custom) {
        //     $this->Order_model->change_status_job_user($status, $id_order, 0, WORKING_CUSTOM, $cur_uid);
        // } else {
        //     $this->Order_model->add_job_user($id_order, 0, $cur_uid, $cur_uname, SERVICES_CUSTOM, WORKING_CUSTOM, $status, $time_join, 0);
        // }

        // add user vào job
        foreach ($list_job_no_ed as $id_job_no_ed) {
            $type_service = @$order['job'][$id_job_no_ed]['type_service'];
            $this->Order_model->add_job_user($id_order, $id_job_no_ed, $cur_uid, $cur_uname, $type_service, WORKING_EDITOR, $status, $time_join, 1);
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
        $code     = $this->input->post('code');
        $code     = removeAllTags($code);

        $order         = $this->Order_model->get_info_order($id_order);
        // $infoOrderCode = $this->Order_model->get_order_info_by_code($code);

        $order == []            ? resError('Đơn hàng không tồn tại') : '';
        // $infoOrderCode != []    ? resError('Code Order đã tồn tại') : '';

        $this->Order_model->update_code_order($id_order, $code);

        //LOG
        $log['type']      = LOG_ORDER_CODE;
        $log['id_order']  = $order['id_order'];
        $log['old']       = $order['code_order'];
        $log['new']       = $code;
        $this->Log_model->log_add($log, $order);

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

        // thời gian mới = thời gian cũ
        $order['custom_time'] == $second ? resSuccess('Thành công') : '';

        $this->Order_model->update_custom_time_order($id_order, $second);

        //LOG
        $log['type']      = LOG_TIME_CUSTOM;
        $log['id_order']  = $order['id_order'];
        $log['old']       = sec2time($order['custom_time']);
        $log['new']       = sec2time($second);
        $this->Log_model->log_add($log, $order);

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

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);
        !$copy['status'] ? resError($copy['error']) : '';

        $this->Job_model->update_image_job($id_job, $copy['basename']);

        //LOG
        $log['type']     = LOG_FILE_MAIN_EDIT;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['old']      = $log['db_old'] = $order['job'][$id_job]['image'];
        $log['new']      = $log['db_new'] = $copy['basename'];
        $this->Log_model->log_add($log, $order);

        resSuccess('Thành công');
    }

    function ajax_add_attach_file()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $url_image = $this->input->post('url_image');
        $id_job = $this->input->post('id_job');

        !isIdNumber($id_job) ? resError('id_job không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('id_job không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $attachs = json_decode($info['attach'], true);

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $id_attach = generateRandomNumber();
        $attachs[$id_attach] = $copy['basename'];
        $this->Job_model->update_attach_job($id_job, json_encode($attachs));

        //LOG
        $log['type']     = LOG_REF_ADD;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['new']      = $copy['basename'];
        $this->Log_model->log_add($log, $order);

        resSuccess($id_attach);
    }

    function ajax_delete_attach_file()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_job    = $this->input->post('id_job');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_job) ? resError('id_job không hợp lệ')           : '';
        !isIdNumber($id_attach) ? resError('id_attach không hợp lệ') : '';

        $info = $this->Job_model->get_info_job_by_id($id_job);
        $info == [] ? resError('id_job không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $attachs = json_decode($info['attach'], true);
        !isset($attachs[$id_attach]) ? resError('id_attach không tồn tại') : '';

        $log['new'] = $attachs[$id_attach]; // log file cũ

        unset($attachs[$id_attach]); // xóa

        $this->Job_model->update_attach_job($id_job, json_encode($attachs));

        //LOG
        $log['type']     = LOG_REF_REMOVE;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $this->Log_model->log_add($log, $order);

        resSuccess($id_attach);
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

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);
        !$copy['status'] ? resError($copy['error']) : '';

        $old_attach = $attachs[$id_attach]; // log file cũ
        $attachs[$id_attach] = $copy['basename'];
        $this->Job_model->update_attach_job($id_job, json_encode($attachs));

        //LOG
        $log['type']     = LOG_REF_EDIT;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['old'] = $old_attach;
        $log['new'] = $copy['basename'];
        $this->Log_model->log_add($log, $order);
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

        // nếu ko thay đổi gì thì bỏ qua
        if ($order['job'][$id_job]['requirement'] == $requirement) {
            resSuccess('Thành công');
        }

        $this->Job_model->update_requirement_job($id_job, $requirement);

        //LOG
        $log['type']     = LOG_NOTE_EDIT;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['old']      = $order['job'][$id_job]['requirement'];
        $log['new']      = $requirement;
        $this->Log_model->log_add($log, $order);
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

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $id_file_complete = generateRandomNumber();
        $info['file_complete'][$id_file_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));

        //LOG
        $log['type']     = LOG_COMPLETE_ADD;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['new']      = $copy['basename'];
        $this->Log_model->log_add($log, $order);

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

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['file_complete'][$id_complete]) ? resError('ID COMPLETE không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';
        $old_file = $info['file_complete'][$id_complete];
        $info['file_complete'][$id_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));

        //LOG
        $log['type']     = LOG_COMPLETE_EDIT;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['old']      = $old_file;
        $log['new']      = $copy['basename'];
        $this->Log_model->log_add($log, $order);
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

        $order = $this->Order_model->get_info_order($info['id_order']);
        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($info['file_complete'][$id_complete]) ? resError('ID FILE COMPLETE không tồn tại') : '';

        $old_file = $info['file_complete'][$id_complete]; // để ghi vào log
        unset($info['file_complete'][$id_complete]); // xóa

        $this->Job_model->update_file_complete_job($id_job, json_encode($info['file_complete']));

        //LOG
        $log['type']     = LOG_COMPLETE_REMOVE;
        $log['id_order'] = $order['id_order'];
        $log['id_job']   = $id_job;
        $log['new']      = $old_file;
        $this->Log_model->log_add($log, $order);
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
        $attach = !is_array($attach) ? [] : $attach;

        $note == '' && empty($attach) ? resError('Hãy nhập mô tả hoặc đính kèm file khi tạo rework') : '';
        !isIdNumber($id_job) ? resError('IMGAE không hợp lệ') : '';

        $job = $this->Job_model->get_info_job_by_id($id_job);
        $job == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($job['id_order']);

        $db_attach = [];
        foreach ($attach as $i => $url_image) {
            $parse = parse_url($url_image);
            !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
            !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

            $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
            $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

            !$copy['status'] ? resError($copy['error']) : '';
            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        $newid = $this->Job_model->add_rework($job['id_order'], $id_job, json_encode($db_attach), $note, $cur_uid);

        if ($newid) {
            //LOG
            $log['type']      = LOG_RW_ADD;
            $log['id_order']  = $order['id_order'];
            $log['id_job']    = $id_job;
            $log['id_rework'] = $newid;
            $this->Log_model->log_add($log, $order);

            resSuccess('ok');
        } else {
            resError('Không lưu được vào lúc này, vui lòng thử lại');
        }
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

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $id_attach = generateRandomNumber(10);
        $info['attach'][$id_attach] = $copy['basename'];
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($info['attach']));

        //LOG
        $log['type']      = LOG_RW_REF_ADD;
        $log['id_order']  = $order['id_order'];
        $log['id_job']    = $info['id_job'];
        $log['id_rework'] = $id_rework;
        $log['new']       = $copy['basename'];
        $this->Log_model->log_add($log, $order);

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

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $old_file = $rework['attach'][$id_attach]; // file cu
        $rework['attach'][$id_attach] = $copy['basename'];
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($rework['attach']));

        //LOG
        $log['type']      = LOG_RW_REF_EDIT;
        $log['id_order']  = $rework['id_order'];
        $log['id_job']    = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['old']       = $old_file;
        $log['new']       = $copy['basename'];
        $this->Log_model->log_add($log, $order);
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
        !isIdNumber($id_attach) ? resError('ID FILE REWORK không hợp lệ') : '';

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($rework['attach'][$id_attach]) ? resError('ID FILE REWORK không tồn tại') : '';

        $old_file = $rework['attach'][$id_attach]; // file cu
        unset($rework['attach'][$id_attach]); // xóa

        $this->Job_model->update_file_attach_rework($id_rework, json_encode($rework['attach']));

        //LOG
        $log['type']      = LOG_RW_REF_REMOVE;
        $log['id_order']  = $rework['id_order'];
        $log['id_job']    = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['new']       = $old_file;
        $this->Log_model->log_add($log, $order);
        resSuccess($id_attach);
    }

    function ajax_update_requirement_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_rework    = $this->input->post('id_rework');
        $requirement = removeAllTags($this->input->post('requirement'));

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';
        !strlen($requirement) ? resError('Requirement không được bỏ trống') : '';

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        // du lieu moi = du lieu cu
        $rework['note'] == $requirement ? resSuccess('Thành công') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        if ($role == QC) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $this->Job_model->update_requirement_rework($id_rework, $requirement);

        //LOG
        $log['type']     = LOG_RW_NOTE_EDIT;
        $log['id_order'] = $rework['id_order'];
        $log['id_job']   = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['old']      = $rework['note'];
        $log['new']      = $requirement;
        $this->Log_model->log_add($log, $order);

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

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $id_file_complete = generateRandomNumber();
        $rework['file_complete'][$id_file_complete] = $copy['basename'];
        $this->Job_model->update_file_complete_rework($id_rework, json_encode($rework['file_complete']));

        //LOG
        $log['type']     = LOG_RW_FILE_COMPLETE_ADD;
        $log['id_order'] = $rework['id_order'];
        $log['id_job']   = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['new']      = $copy['basename'];
        $this->Log_model->log_add($log, $order);

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

        $old_file = $rework['file_complete'][$id_complete_rework]; // file cu

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        $rework['file_complete'][$id_complete_rework] = $copy['basename'];
        $this->Job_model->update_file_complete_rework($id_rework, json_encode($rework['file_complete']));

        //LOG
        $log['type']     = LOG_RW_FILE_COMPLETE_EDIT;
        $log['id_order'] = $rework['id_order'];
        $log['id_job']   = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['old']      = $old_file;
        $log['new']      = $copy['basename'];
        $this->Log_model->log_add($log, $order);

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

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);
        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        !isset($rework['file_complete'][$id_complete]) ? resError('ID FILE COMPLETE không tồn tại') : '';

        $old_file = $rework['file_complete'][$id_complete]; //file cu

        unset($rework['file_complete'][$id_complete]); // xóa

        $this->Job_model->update_file_complete_rework($id_rework, json_encode($rework['file_complete']));

        //LOG
        $log['type']     = LOG_RW_FILE_COMPLETE_REMOVE;
        $log['id_order'] = $rework['id_order'];
        $log['id_job']   = $rework['id_job'];
        $log['id_rework'] = $id_rework;
        $log['new']      = $old_file;
        $this->Log_model->log_add($log, $order);

        resSuccess($id_complete);
    }

    /**
     * Chức năng thay đổi đơn cho ED nội bộ hoặc ED cộng tác viên
     */
    function ajax_update_ed_type()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $ed_type   = $this->input->post('ed_type');
        $id_order = $this->input->post('id_order');

        in_array($ed_type, [ED_NOI_BO, ED_CTV]) ? '' : resError('Giá trị không hợp lệ');

        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn hàng không tồn tại') : '';

        $this->Order_model->edit_ed_type($id_order, $ed_type);
        resSuccess('Thành công');
    }

    /**
     * Chức năng zip files attach
     */
    function ajax_zip_attach($id_job)
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        // check right
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? redirect(site_url('order', $this->_langcode)) : '';

        isIdNumber($id_job) ? $id_job : 0;

        $job = $this->Job_model->get_info_job_by_id($id_job);
        $job == [] ? redirect(site_url('order', $this->_langcode)) : '';

        $order = $this->Order_model->get_info_order($job['id_order']);
        $order == [] ? redirect(site_url('order', $this->_langcode)) : '';

        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? redirect(site_url('order', $this->_langcode)) : '';
        }
        // end check right

        // danh sach file attach
        $attach = json_decode($job['attach'], true);
        $FDR_ORDER =  $_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        foreach ($attach as $id => $at) {
            $attach[$id] = $FDR_ORDER . $at;
        }
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/' . "attach_files" . time() . ".zip";
        $error_text = handle_zip_files($filename, $attach);

        if ($error_text != '') {
            redirect(site_url('order/detail/' . $order['id_order'] . '?zip=' . $error_text, $this->_langcode));
        }
    }

    /**
     * Chức năng zip files attach
     */
    function ajax_zip_attach_rework($id_rework)
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        // check right
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        isIdNumber($id_rework) ? $id_rework : 0;

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);
        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        // end check right

        // danh sach file attach
        $attach = $rework['attach'];
        $FDR_ORDER =  $_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        foreach ($attach as $id => $at) {
            $attach[$id] = $FDR_ORDER . $at;
        }

        $filename = $_SERVER['DOCUMENT_ROOT'] . '/' . "attach_rework_files" . time() . ".zip";
        $error_text = handle_zip_files($filename, $attach);

        if ($error_text != '') {
            redirect(site_url('order/detail/' . $order['id_order'] . '?zip=' . $error_text, $this->_langcode));
        }
    }

    /**
     * Chức năng lấy danh sách log
     */

    function ajax_log_order($id_order)
    {
    }


    function export()
    {
        $cur_uname = $this->_session_uname();
        $role = $this->_session_role();

        // check right
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $hdData = $this->input->post('hdData');
        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $todate = date('Y-m-d', strtotime($this->input->post('todate')));

        $list_order = json_decode($hdData, true);

        $all_service    = $this->Service_model->get_list();
        $all_user       = $this->User_model->get_list_user_working('0,1', implode(",", [ADMIN, SALE, QC, EDITOR]));

        $data['list_order']  = $list_order;
        $data['all_service'] = $all_service;
        $data['all_user']    = $all_user;
        $data['fromdate']    = $fromdate;
        $data['todate']      = $todate;
        $data['cur_uname']   = $cur_uname;

        $this->load->view($this->_template_f . 'order/list/excel.php', $data);
    }
    function ajax_log_list($id_order)
    {

        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        // check right
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        isIdNumber($id_order) ? $id_order : 0;

        $order = $this->Order_model->get_info_order($id_order);
        if ($role == QC || $role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        // end check right


        ## log
        $dk['id_order']    = $id_order;
        $logs              = $this->Log_model->log_list($dk, $order);
        $data['logs']      = $logs;
        $data['order']     = $order;
        $data['FDR_ORDER'] = $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';

        $this->load->view($this->_template_f . 'order/detail/ajax_log_list_view.php', $data);
    }
}
