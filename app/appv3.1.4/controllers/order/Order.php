<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('library/Library_model');
        $this->load->model('room/Room_model');
        $this->load->model('style/Style_model');
        $this->load->model('service/Service_model');
        $this->load->model('order/Order_model');
        $this->load->model('job/Job_model');
        $this->load->model('login/Login_model');
        $this->load->model('account/Account_model');
        $this->load->model('payment/Payment_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Order',
            'active_link' => 'home',
            'header_page_css_js' => 'order'
        ];

        $room = $this->Room_model->get_list(1);
        $style = $this->Style_model->get_list(1);
        $library = $this->Library_model->get_list(1);
        $service = $this->Service_model->get_list(1);

        $data['list_room'] = $room;
        $data['list_service'] = $service;
        $data['list_style'] = $style;
        $data['list_library'] = $library;

        $user_info = [];
        if ($this->_session_uname() != '') {
            $user_info = $this->Login_model->get_user_info_by_username($this->_session_uname());
        }
        $data['user_info'] = $user_info;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/order_view', $data);

        $this->_loadFooter();
    }

    function submit()
    {
        // TODO: sale admin qc ed muốn tạo đơn có được không?

        $this->_islogin() ? "" : resError('error_attach');

        $all_room    = $this->Room_model->get_list(1);
        $all_service = $this->Service_model->get_list(1);
        $all_style   = $this->Style_model->get_list(1);

        $order = $this->input->post('order');
        $style       = $order['style'];
        $id_user     = $this->_session_uid();
        $create_time = date('Y-m-d H:i:s');
        $coupon      = $order['coupon'];
        $list_job  = $order['job'];

        // VALIDATE
        # check style
        if (isIdNumber($style)) {
            isset($all_style[$style]) ? '' : resError('error_style');
        }

        $info_user = $this->Account_model->get_user_info_by_uid($id_user);
        $FDR_ORDER = FOLDER_ORDER . strtotime($create_time) . '@' . $info_user['username'];

        foreach ($list_job as $id_job => $job) {
            $room        = $job['room'];
            $service     = $job['service'];
            $image       = $job['image'];
            $requirement = $job['requirement'];
            $attach      = @$job['attach'];      // k bat buoc nhap attach nen de @

            # check room, service
            isset($all_room[$room])         ? '' : resError('error_room');
            isset($all_service[$service])   ? '' : resError('error_service');

            # lưu ảnh image
            $copy_image = copy_image_to_public_upload($image, $FDR_ORDER);
            $copy_image['status'] ? '' : resError('error_image');
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

        // Tạo đơn vào tbl_order
        $create_id_user = $id_user;
        $new_order = $this->Order_model->add_order($style, $create_time, $id_user, ORDER_PAY_WAITING, DON_KHACH_TAO, $create_id_user, ED_NOI_BO);

        $flag_error = false;
        if ($new_order) {
            // số lượng job của đơn lưu vào tbl_job
            foreach ($list_job as $job) {

                $room           = $job['room'];
                $service        = $job['service'];
                $type_service   = $all_service[$service]['type_service'];
                $price          = $all_service[$service]['price'];
                $price_unit     = '2'; //TODO: 1 VND, 2 Đô, ...
                $image_ok       = $job['image_ok'];
                $json_attach_ok = json_encode($job['attach_ok'], JSON_FORCE_OBJECT);
                $requirement    = $job['requirement'];

                $new_order_job_service = $this->Order_model->add_order_job($new_order, $service, $type_service, $price, $price_unit, $room, $style, $image_ok, $json_attach_ok, $requirement, $create_time);

                if (!$new_order_job_service) {
                    $flag_error = true;
                    break;
                }
            }

            if ($flag_error) {
                $this->Order_model->delete_order_and_job($new_order);
                // Xóa ảnh của job, ảnh attach...TODO:
                resError('Loi luu job');
            } else {
                resSuccess('ok');
            }
        } else {
            resError('Loi luu don');
        }
    }

    // REWORK
    function ajax_add_rework($id_job)
    {
        $cur_uid = $this->_session_uid();
        $note   = $this->input->post('note');
        $attach = $this->input->post('attach');

        $note = removeAllTags($note);

        !isIdNumber($id_job)    ? resError('IMGAE không hợp lệ') : '';

        $job = $this->Job_model->get_info_job_by_id($id_job);
        $job == [] ? resError('IMAGE không tồn tại') : '';

        $order = $this->Order_model->get_info_order($job['id_order']);
        empty($order) ? resError('Đơn hàng không tồn tại') : '';

        $edit_action = $order['id_user'] == $cur_uid || $order['create_id_user'] != $cur_uid;
        !$edit_action ? resError('Bạn không có quyền thao tác') : '';

        $note == ''             ? resError('Hãy nhập mô tả') : '';
        !is_array($attach)      ? resError('Attach không hợp lệ') : '';

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

        $exc = $this->Job_model->add_rework($job['id_order'], $id_job, json_encode($db_attach), $note, $cur_uid);

        $this->Order_model->update_status_order($order['id_order'], ORDER_REWORK);

        $exc ? resSuccess('ok') : resError('Không lưu được vào lúc này, vui lòng thử lại');
    }

    function ajax_add_file_attach_rework()
    {
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $url_image = $this->input->post('url_image');
        $id_rework = $this->input->post('id_rework');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        $edit_action = $order['id_user'] == $cur_uid || $order['create_id_user'] != $cur_uid;
        !$edit_action ? resError('Bạn không có quyền thao tác') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

        !$copy['status'] ? resError($copy['error']) : '';

        //TODO: THIẾU GHI LOG
        $id_attach = generateRandomNumber();
        $info['attach'][$id_attach] = $copy['basename'];
        $this->Job_model->update_file_attach_rework($id_rework, json_encode($info['attach']));
        resSuccess($id_attach);
    }

    function ajax_edit_file_attach_rework()
    {

        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $url_image = $this->input->post('url_image');
        $id_rework = $this->input->post('id_rework');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ')      : '';
        !isIdNumber($id_attach) ? resError('ID attach không hợp lệ') : '';

        $rework = $this->Job_model->get_info_rework_by_id($id_rework);
        $rework == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($rework['id_order']);

        $edit_action = $order['id_user'] == $cur_uid || $order['create_id_user'] != $cur_uid;
        !$edit_action ? resError('Bạn không có quyền thao tác') : '';

        !isset($rework['attach'][$id_attach]) ? resError('ID attach không tồn tại') : '';

        $parse = parse_url($url_image);
        !isset($parse['host'])              ? resError('url image không hợp lệ (1)') : '';
        $parse['host'] != DOMAIN_NAME       ? resError('url image không hợp lệ (2)') : '';
        !strpos($url_image, 'uploads/tmp')  ? resError('url image không hợp lệ (3)') : '';

        $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
        $copy = copy_image_to_public_upload($url_image, $FDR_ORDER);

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

        $id_rework    = $this->input->post('id_rework');
        $id_attach = $this->input->post('id_attach');

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ')       : '';
        !isIdNumber($id_attach) ? resError('ID Attach không hợp lệ')    : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);
        empty($order) ? resError('Đơn hàng không tồn tại') : '';

        $edit_action = $order['id_user'] == $cur_uid || $order['create_id_user'] != $cur_uid;
        !$edit_action ? resError('Bạn không có quyền thao tác') : '';

        !isset($info['attach'][$id_attach]) ? resError('ID attach không tồn tại') : '';

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

        $id_rework    = $this->input->post('id_rework');
        $requirement = removeAllTags($this->input->post('requirement'));

        !isIdNumber($id_rework) ? resError('Rework không hợp lệ') : '';
        !strlen($requirement) ? resError('Requirement không được bỏ trống') : '';

        $info = $this->Job_model->get_info_rework_by_id($id_rework);
        $info == [] ? resError('Rework không tồn tại') : '';

        $order = $this->Order_model->get_info_order($info['id_order']);

        $edit_action = $order['id_user'] == $cur_uid || $order['create_id_user'] != $cur_uid;
        !$edit_action ? resError('Bạn không có quyền thao tác') : '';

        //TODO: THIẾU GHI LOG
        $this->Job_model->update_requirement_rework($id_rework, $requirement);
        resSuccess('Thành công');
    }

    function ajax_popup_payment($id_order)
    {
        $cur_uid     = $this->_session_uid();
        $id_order = isIdNumber($id_order) ? $id_order : 0;

        $LOCAL_PAY = $this->input->get('l');

        $order = $this->Order_model->get_info_order($id_order);
        if (empty($order) || $order['id_user'] != $cur_uid) {
            resError('Bạn không có quyền truy cập đến đơn hàng này');
        }


        $list_payment = $this->Payment_model->get_list_payment_by_order($id_order);

        // tong tien can thanh toan cua don hang
        $total_price_pay = 0;
        foreach ($list_payment as $pay) {
            if ($pay['is_payment'] == PAY_DANG_CHO) {
                $price = floatval($pay['price']);
                $price_vou = floatval($pay['price_voucher']);
                $price_pay = $price > $price_vou ? ($price - $price_vou) : 0;
                $total_price_pay += $price_pay;
            }
        }

        if ($total_price_pay > 0) {
            redirect('order/ajax-call-api-pay?id_order=' . $id_order . '&amount=' . $total_price_pay . '&l=' . $LOCAL_PAY);
        } else {
            echo "<script>localStorage.setItem('local_storage_pay', " . PAY_HOAN_THANH . ");window.close()</script>";
            die();
        }
    }

    function ajax_call_api_pay()
    {
        $id_order = $this->input->get('id_orderr');
        $amount = $this->input->get('amountt');
        $LOCAL_PAY = $this->input->get('l');

        if ($id_order != '' && $amount != '') {
            // $status = PAY_HUY;
            $status = PAY_HOAN_THANH;
            redirect('order/ajax_callback_payment?id_order=' . $id_order . '&status=' . $status . '&l=' . $LOCAL_PAY);
        } else {
            $this->load->view($this->_template_f . 'user/order_detail/_form_paypal.php');
        }
    }

    function ajax_callback_payment()
    {
        $id_order = $this->input->get('id_order');
        $status = $this->input->get('status');
        $LOCAL_PAY = $this->input->get('l');


        $trancsion = 'xxxxxx';
        $type_pay = PAYPAL;
        $update_time = date('Y-m-d H:i:s');
        if ($status == PAY_HOAN_THANH) {
            $this->Payment_model->update_status_payment_by_id_order($id_order, PAY_HOAN_THANH, $type_pay, $trancsion, $update_time);
            $this->Order_model->update_status_order($id_order, ORDER_PENDING);

            $local_storage_pay = PAY_HOAN_THANH;
        } else {
            $local_storage_pay = PAY_HUY;
        }

        echo "<script>localStorage.setItem($LOCAL_PAY, $local_storage_pay);window.close()</script>";
    }
}
