<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chat_customer extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('chat_customer/Chat_customer_model');
        $this->load->model('order/Order_model');
        $this->load->model('account/Account_model');
    }

    // OK 2025
    function ajax_msg_list_by_room()
    {
        $list_chat = [];
        if ($this->_isLogin()) {

            $cur_uid = $this->_session_uid();
            $room_info  = $this->Chat_customer_model->room_info_by_id_user($cur_uid);

            if ($room_info !== false) {
                $id_room = $room_info['id_room'];

                // set tất cả tin nhắn của quản lý thành đã xem trước khi lấy list
                $this->Chat_customer_model->set_seen_all_msg_of_manager($id_room, $cur_uid);

                // lấy list
                $list_chat = $this->Chat_customer_model->list_chat_by_room($id_room);
            }
        } else {
            $list_chat = []; // TODO: chưa xử lý
        }

        resSuccess($list_chat);
    }

    // OK 2025
    function ajax_msg_add_to_room()
    {
        // check right
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        if ($this->_isLogin()) {
            $cur_uid = $this->_session_uid();
        } else {
            $cur_uid = ip_address();
        }

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('Invalid attachment (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('Invalid attachment (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('Invalid attachment (3)') : '';

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

        // Biến lưu id_room. Nếu room chưa tồn tại thì tạo mới.
        $room_info = $this->Chat_customer_model->room_info_by_id_user($cur_uid);
        $id_room = $room_info !== false ? $room_info['id_room'] : $this->Chat_customer_model->room_add($cur_uid);

        $reply = null; // TODO: chưa xử lý reply
        $created_at = date('Y-m-d H:i:s');
        $db_attach =  json_encode($db_attach, JSON_FORCE_OBJECT);
        $newid = $this->Chat_customer_model->msg_add_to_room($id_room, $cur_uid, $content, $db_attach, $created_at, $reply);

        // cập nhật id_msg mới nhất vào
        $this->Chat_customer_model->update_newest_msg_to_room($newid, $id_room);

        // tra du lieu socket
        if ($this->_isLogin()) {
            $msg_info = $this->Chat_customer_model->msg_info($newid);
            // mảng chưa danh sách ADMIN, SALE, và KHÁCH nhận tin nhắn
            $manager = $this->Account_model->get_list_user_working(1, implode(",", [ADMIN, SALE]));
            $member_ids[] = $cur_uid;
            foreach ($manager as $id_manager => $val) {
                $member_ids[] = $id_manager;
            }
            $socket['member_ids'] = $member_ids;

            // thông tin msg
            $socket['id_room']    = $id_room;
            $socket['id_msg']     = $newid;
            $socket['file_list']  = $msg_info['file_list'];
            $socket['id_user']    = $msg_info['id_user'];     // người tạo tin nhắn
            $socket['content']    = $msg_info['content'];     // nội dung tin nhắn
            $socket['fullname']   = $msg_info['fullname'];    // tên người tạo tin nhăn
            $socket['avatar_url'] = $msg_info['avatar_url'];  // ảnh người tạo tin nhắn
            $socket['created_at'] = $msg_info['created_at'];  // thời gian tạo tin nhắn
            $socket['reply']      = $msg_info['reply'];       // phản hồi tin nhắn

            //  thông tin room
            $socket['room_info'] = $this->Chat_customer_model->room_info_by_id_user($cur_uid);

            resSuccess($socket);
        } else {
            resError('Chức năng đang bảo trì.');  // TODO: chưa xử lý
        }
    }

    //  đếm số tin nhắn chưa đọc của khách
    function ajax_count_msg_unread_of_manager()
    {
        $id_customer = $this->_session_uid();

        resSuccess($this->Chat_customer_model->count_msg_unread_of_manager($id_customer));
    }
}
