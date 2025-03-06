<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chat_customer extends MY_Controller
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

        $this->load->model('chat_customer/Chat_customer_model');
        $this->load->model('user/User_model');
    }

    function index($id_room = '')
    {
        $data = [];

        // mảng chứa danh sách room
        $room_list = $this->Chat_customer_model->room_list();
        $data['room_list'] = $room_list;

        $data['id_room_active'] = isIdNumber($id_room) ? $id_room : '';
        $data['cur_uid'] = $this->_session_uid();
        $data['cur_fullname'] = $this->_session_fullname();

        $header = [
            'title' => 'Chat với khách hàng',
            'header_page_css_js' => 'home'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'chat_customer/chat_customer_view', $data);
        $this->_loadFooter();
    }

    function ajax_msg_list_by_room($id_room)
    {
        // set tất cả tin nhắn là đã xem sau đó nới get list
        // $this->Chat_customer_model->set_da_xem_all_msg_group_v2($id_group, $curr_uid);

        // get lai list
        $msg_list = $this->Chat_customer_model->msg_list_by_room($id_room);

        resSuccess($msg_list);
    }

    function ajax_msg_add_to_room($id_room)
    {
        $action_by = $this->_session_uid();
        // check right
        $id_room      = isIdNumber($id_room) ? $id_room : 0;
        $content      = removeAllTags($this->input->post('content'));
        $attach       = $this->input->post('attach');
        $id_msg_reply = removeAllTags($this->input->post('id_msg_reply'));

        // kiểm tra nhóm có đúng không
        $room_info  = $this->Chat_customer_model->room_info($id_room);
        if ($room_info === false) resError('Nhóm không đúng');

        // validate file đính kèm
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

            // nếu file là ảnh thì copy to thumb
            if (stringIsImage($url_file)) {
                $copyThumb = copy_image_to_thumb($url_file, FOLDER_CHAT_TONG_THUMB, THUMB_WIDTH, THUMB_HEIGHT);
                !$copyThumb['status'] ? resError($copyThumb['error']) : '';
            }
        }

        // reply info (nếu có)
        $db_reply = null;
        if (is_numeric($id_msg_reply) && $id_msg_reply > 0) {

            $msg_reply_info = $this->Chat_customer_model->msg_info($id_msg_reply);
            if (is_array($msg_reply_info)) {
                $db_reply =  json_encode($msg_reply_info, JSON_FORCE_OBJECT);
            }
        }


        // luu vao bang tbl_msg
        $create_time = date('Y-m-d H:i:s');
        $db_attach =  json_encode($db_attach, JSON_FORCE_OBJECT);

        $new_id_msg = $this->Chat_customer_model->msg_add_to_room($id_room, $action_by, $content, $db_attach, $create_time, $db_reply);

        // cập nhật id_msg mới nhất vào
        $this->Chat_customer_model->update_newest_msg_to_room($new_id_msg, $id_room);

        // tra du lieu socket
        $msg_info = $this->Chat_customer_model->msg_info($new_id_msg);


        // mảng chưa danh sách ADMIN, SALE, và KHÁCH nhận tin nhắn
        $manager = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE]));
        foreach ($manager as $id_manager => $val) {
            $member_ids[] = $id_manager;
        }
        $socket['member_ids'] = $member_ids;
        $socket['member_ids'][] = $room_info['id_customer'];

        // thông tin msg
        $socket['id_room']    = $id_room;
        $socket['id_msg']     = $new_id_msg;
        $socket['file_list']  = $msg_info['file_list'];
        $socket['id_user']    = $msg_info['id_user'];     // người tạo tin nhắn
        $socket['content']    = $msg_info['content'];     // nội dung tin nhắn
        $socket['fullname']   = $msg_info['fullname'];    // tên người tạo tin nhăn
        $socket['avatar_url'] = $msg_info['avatar_url'];  // ảnh người tạo tin nhắn
        $socket['created_at'] = $msg_info['created_at'];  // thời gian tạo tin nhắn
        $socket['reply']      = $msg_info['reply'];       // phản hồi tin nhắn

        resSuccess($socket);
    }

    // TODO: chưa xử lý xóa tin nhắn
    function _ajax_del_msg_group($id_msg)
    {
        $id_msg = isIdNumber($id_msg) ? $id_msg : 0;
        $info = $this->Chat_customer_model->msg_info($id_msg);

        if (!empty($info)) {

            $text_del = '<i>Tin nhắn đã bị xóa</i>';

            $this->Chat_customer_model->delete_msg_group($id_msg, $text_del);

            // xoa file cu
            foreach ($info['file_list'] as $file) {
                //xóa file
                @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_CHAT_TONG . $file);
                //xóa thumb
                if (stringIsImage($file)) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_CHAT_TONG_THUMB . $file);
                }
            }

            resSuccess($text_del);
        } else {
            resSuccess('Message invalid!');
        }
    }

    // TODO: chưa xử lý xóa nhóm chat
    function ajax_delete_gchat($id_gchat)
    {
        if (isIdNumber($id_gchat)) {

            $gchat_info = $this->Chat_customer_model->gchat_info($id_gchat,  0);
            $socket['id_gchat']   = $id_gchat;
            $socket['member_ids'] = $gchat_info['member_ids'];
            $this->Chat_customer_model->delete_group($id_gchat);

            // TODO: xu ly del file
            resSuccess($socket);
        } else {
            resSuccess('Message invalid!');
        }
    }

    function ajax_count_msg_unread()
    {
        resSuccess($this->Chat_customer_model->count_msg_unread());
    }
}
