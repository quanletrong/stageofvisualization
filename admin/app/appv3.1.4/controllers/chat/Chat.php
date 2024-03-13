<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chat extends MY_Controller
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

        $this->load->model('chat/Chat_model');
        $this->load->model('user/User_model');
    }

    // OLD TODO: bản cũ
    function _index($chat_user = '')
    {
        $data = [];
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        // danh sách người dùng chat
        $list_user_chat = $this->Chat_model->list_user_chat();
        $data['list_user_chat'] = $list_user_chat;

        $chat_user = isIdNumber($chat_user) ? $chat_user : '';
        $data['chat_user'] = $chat_user;
        $data['cur_uid'] = $this->_session_uid();
        $data['all_member'] = $this->User_model->get_list_user_working(1, implode(",", [ADMIN,SALE, QC, EDITOR]));

        $header = [
            'title' => 'Chat',
            'header_page_css_js' => 'home'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'chat/chat_view', $data);
        $this->_loadFooter();
    }

    function index($chat_user = '')
    {
        $data = [];
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $curr_uid = $this->_session_uid();

        // danh sách nhóm
        $list_group = $this->Chat_model->list_group_by_user($curr_uid);
        $data['list_group'] = $list_group;
        // var_dump($list_group);die;
        
        // danh sách người dùng chat
        $list_user_chat = $this->Chat_model->list_user_chat();
        $data['list_user_chat'] = $list_user_chat;

        $chat_user = isIdNumber($chat_user) ? $chat_user : '';
        $data['chat_user'] = $chat_user;
        $data['cur_uid'] = $this->_session_uid();
        $data['all_member'] = $this->User_model->get_list_user_working(1, implode(",", [ADMIN,SALE, QC, EDITOR]));

        $header = [
            'title' => 'Chat',
            'header_page_css_js' => 'home'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'chat/chat_view', $data);
        $this->_loadFooter();
    }

    function ajax_chat_list_by_user($chat_user)
    {

        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            resError('Tài khoản không có quyền truy cập!');
        }

        // set tất cả tin nhắn là đã xem
        $this->Chat_model->da_xem_all_chat_user($chat_user);

        // get lai list
        $chat_list = $this->Chat_model->chat_list_by_user($chat_user);

        resSuccess($chat_list);
    }

    function ajax_chat_add($chat_user)
    {
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
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

        $newid = $this->Chat_model->chat_add($chat_user, $content, $db_attach, $create_time, $status, $ip, $fullname, $phone, $email, $action_by);
        $info = $this->Chat_model->chat_info_by_action_by($newid);

        resSuccess($info);
    }

    function ajax_delete_chat_user($chat_user) {
        if (!in_array($this->_session_role(), [ADMIN, SALE])) {
            resError('Tài khoản không có quyền truy cập!');
        }

        if(isIdNumber($chat_user) || isIPV4($chat_user)) {
            $exc = $this->Chat_model->delete_chat_user($chat_user);

            resSuccess('ok');
        } else {
            resError('Người xóa không hợp lệ');
        }
       
    }

    function ajax_add_group() {

        // check right
        if (!in_array($this->_session_role(), [ADMIN, SALE, QC, EDITOR])) {
            resError('Tài khoản không có quyền truy cập!');
        }

        $curr_uid = $this->_session_uid();
        $all_member = $this->User_model->get_list_user_working(1, implode(",", [ADMIN,SALE, QC, EDITOR]));
        $all_group = $this->Chat_model->all_group();
        
        $name = removeAllTags($this->input->post('name_group'));
        $member = $this->input->post('member_group');

        // check memmber empty
        is_array($member) ? '' : resError('Không lấy được thành viên');
        count($member) ? '' : resError('Thành viên không được bỏ trống');

        // kiểm tra xem list member đã có id người tạo nhóm chưa
        // nếu chưa có thì thêm người tạo nhóm vào list member
        if(!in_array($curr_uid, $member)) {
            $member[] = $curr_uid;
        }
        
        // check member không tồn tại
        foreach($member as $id_member) {
            isset($all_member[$id_member]) ? '' : resError('Thành viên không tồn tại!');
        }

        // check nhóm đã tồn tại
        $nhom_da_ton_tai = $this->_check_group_da_ton_tai($member, $all_group);
        if($nhom_da_ton_tai) {
            resError('Nhóm đã tồn tại!');
        }

        $avatar = AVATAR_DEFAULT; //TODO: tạm fix, sau thêm chức năng upload avatar
        $create_time = date('Y-m-d H:i:s');
        $new_id_group = $this->Chat_model->add_group($name, $avatar, $curr_uid, $create_time);

        $username_member = ''; // TODO: tạm fix = '', chưa biết có cần hay không
        foreach($member as $id_member) {
            $this->Chat_model->add_member_group($new_id_group, $id_member,$username_member, $create_time);
        }


        redirect('chat'); 

        // resSuccess([
        //     'new_id' => $new_id_group,
        //     'avatar' => AVATAR_DEFAULT,
        //     'name' => $name,
        // ]);
    }

    // TRUE: nhóm đã tồn tại
    // FASLSE:  nhóm chưa tồn tại
    function _check_group_da_ton_tai($member,$all_group ) {

        $nhom_da_ton_tai = true;
        $nhom_chua_ton_tai = false;

        // nếu đã tồn tại nhóm có số lượng thành viên bằng $member thì báo $nhom_da_ton_tai
        foreach($all_group as $group) {

            if(count($group['members']) == count($member)) {

                $array_diff = array_diff($group['members'], $member);

                if(empty($array_diff)) {
                    return $nhom_da_ton_tai;
                }
            }
        }

        return $nhom_chua_ton_tai;
    }

    function ajax_list_msg_by_group($id_group) {
        if (!in_array($this->_session_role(), [ADMIN, SALE, EDITOR])) {
            resError('Tài khoản không có quyền truy cập!');
        }

        $curr_uid = $this->_session_uid();
        // set tất cả tin nhắn là đã xem
        // $this->Chat_model->da_xem_all_chat_user($id_group);

        $list_group = $this->Chat_model->list_group_by_user($curr_uid);

        isset($list_group['list'][$id_group]) ? '' : resError('Bạn không có quyền truy cập nhóm này');

        // get lai list
        $chat_list = $this->Chat_model->chat_list_by_group($id_group);

        resSuccess($chat_list);
    }

    function ajax_msg_add_to_group($id_gchat)
    {
        if (!in_array($this->_session_role(), [ADMIN, SALE, EDITOR])) {
            resError('Tài khoản không có quyền truy cập!');
        }
        $curr_uid = $this->_session_uid();

        // check right
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        $list_group = $this->Chat_model->list_group_by_user($curr_uid);
        isset($list_group['list'][$id_gchat]) ? '' : resError('Bạn không có quyền truy cập nhóm này');

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
        $db_attach =  json_encode($db_attach, JSON_FORCE_OBJECT);

        $status    = 1;          // TODO: trường này chưa có trong db, có thể thêm sau
        $ip        = '';         // TODO: trường này chưa có trong db, có thể thêm sau
        $fullname  = '';         // TODO: trường này chưa có trong db, có thể thêm sau
        $email     = '';         // TODO: trường này chưa có trong db, có thể thêm sau
        $phone     = '';         // TODO: trường này chưa có trong db, có thể thêm sau
        $action_by = $curr_uid;  // TODO: trường này chưa có trong db, có thể thêm sau

        $new_id_msg = $this->Chat_model->msg_add_to_group($id_gchat, $curr_uid, $content, $db_attach, $create_time, $status, $ip, $fullname, $phone, $email, $action_by);
        $info = $this->Chat_model->msg_info($new_id_msg);

        resSuccess($info);
    }
}
