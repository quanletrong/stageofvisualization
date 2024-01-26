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
    }

    function index($chat_user = '')
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
}
