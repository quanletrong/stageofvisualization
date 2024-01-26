<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Discuss extends MY_Controller
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

        $this->load->model('discuss/Discuss_model');
        $this->load->model('order/Order_model');
        $this->load->model('user/User_model');
    }

    function ajax_discuss_list_khach()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_order = $this->input->post('id_order');

        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';

        // get list discuss theo order
        $list = $this->Discuss_model->discuss_list_by_id_order($id_order, CHAT_KHACH);

        resSuccess($list);
    }


    function ajax_discuss_list_noi_bo()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_order = $this->input->post('id_order');

        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';
        if ($role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        // get list discuss theo order
        $list = $this->Discuss_model->discuss_list_by_id_order($id_order, CHAT_NOI_BO);

        resSuccess($list);
    }

    function ajax_discuss_noi_bo_add()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_order = $this->input->post('id_order');
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';
        if ($role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('url file không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url file không hợp lệ (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('url file không hợp lệ (3)') : '';

            $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
            $copy = copy_image_to_public_upload($url_file, $FDR_ORDER);

            !$copy['status'] ? resError($copy['error']) : '';
            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        // get list discuss theo order
        $create_time = date('Y-m-d H:i:s');
        $status = 1;

        $newid = $this->Discuss_model->discuss_add($cur_uid, $id_order, $content, json_encode($db_attach, JSON_FORCE_OBJECT), $create_time, $status, CHAT_NOI_BO);

        $info = $this->Discuss_model->discuss_info($newid);

        resSuccess($info);
    }

    function ajax_discuss_khach_add()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_order = $this->input->post('id_order');
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('url file không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url file không hợp lệ (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('url file không hợp lệ (3)') : '';

            $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
            $copy = copy_image_to_public_upload($url_file, $FDR_ORDER);

            !$copy['status'] ? resError($copy['error']) : '';
            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        // get list discuss theo order
        $create_time = date('Y-m-d H:i:s');
        $status = 1;

        $newid = $this->Discuss_model->discuss_add($cur_uid, $id_order, $content, json_encode($db_attach, JSON_FORCE_OBJECT), $create_time, $status, CHAT_KHACH);

        $info = $this->Discuss_model->discuss_info($newid);

        resSuccess($info);
    }

    function ajax_discuss_edit()
    {
    }

    function ajax_discuss_delete()
    {
    }

    function chat($chat_user = '')
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        // danh sách người dùng chat
        $list_user_chat = $this->Discuss_model->list_user_chat();
        $data['list_user_chat'] = $list_user_chat;

        // danh sách vãng lai chat
        $list_vang_lai_chat = $this->Discuss_model->list_vang_lai_chat();
        $data['list_vang_lai_chat'] = $list_vang_lai_chat;

        $chat_user = isIdNumber($chat_user) ? $chat_user : '';
        $data['chat_user'] = $chat_user;
        $data['cur_uid'] = $this->_session_uid();

        $header = [
            'title' => 'Chat',
            'header_page_css_js' => 'home'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'discuss/chat_view', $data);
        $this->_loadFooter();
    }

    function ajax_chat_list_by_user($chat_user)
    {

        if ($this->_session_role() != ADMIN) {
            resError('Tài khoản không có quyền truy cập!');
        }

        $chat_list = $this->Discuss_model->chat_list_by_user($chat_user);

        resSuccess($chat_list);
    }

    function ajax_chat_add($chat_user)
    {
        if ($this->_session_role() != ADMIN) {
            resError('Tài khoản không có quyền truy cập!');
        }

        // check right
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('url file không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url file không hợp lệ (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('url file không hợp lệ (3)') : '';

            $copy = copy_image_to_public_upload($url_file, FOLDER_CHAT_TONG);

            !$copy['status'] ? resError($copy['error']) : '';
            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        // get list discuss theo order
        $create_time = date('Y-m-d H:i:s');
        $status = 1;
        $db_attach =  json_encode($db_attach, JSON_FORCE_OBJECT);

        $ip = '';
        $fullname = '';
        $email = '';
        $phone = '';
        $action_by =  $this->_session_uid();

        $newid = $this->Discuss_model->chat_add($chat_user, $content, $db_attach, $create_time, $status, $ip, $fullname, $phone, $email, $action_by);
        $info = $this->Discuss_model->chat_info_by_action_by($newid);

        resSuccess($info);
    }
}
