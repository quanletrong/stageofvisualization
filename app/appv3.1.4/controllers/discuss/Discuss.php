<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Discuss extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('discuss/Discuss_model');
        $this->load->model('order/Order_model');
    }

    function ajax_discuss_list()
    {
        if (!$this->_isLogin()) {
            resError('unlogin');
        }

        // check right
        $cur_uid = $this->_session_uid();
        $id_order = $this->input->post('id_order');

        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Order does not exist') : '';
        $order['id_user'] != $cur_uid ? resError('You do not have access') : '';

        // get list discuss theo order
        $list = $this->Discuss_model->discuss_list_by_id_order($id_order, CHAT_KHACH);

        resSuccess($list);
    }

    function ajax_discuss_add()
    {
        if (!$this->_isLogin()) {
            resError('unlogin');
        }

        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $id_order = $this->input->post('id_order');
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Order does not exist') : '';
        $order['id_user'] != $cur_uid ? resError('You do not have access') : '';

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('Invalid attachment (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('Invalid attachment (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('Invalid attachment (3)') : '';

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

    // TODO: tamj thoiw lam sau
    function ajax_discuss_khach_da_xem()
    {
        if (!$this->_isLogin()) {
            resError('unlogin');
        }

        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $id_order = $this->input->post('id_order');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Order does not exist') : '';
        $order['id_user'] != $cur_uid ? resError('You do not have access') : '';

        //call db

        $exc = $this->Discuss_model->discuss_da_xem($id_order, CHAT_KHACH);
        resSuccess('ok');
    }

    function ajax_discuss_edit() {}

    function ajax_discuss_delete() {}

    function ajax_chat_tong_list()
    {
        $list_chat = [];
        if ($this->_isLogin()) {

            $cur_uid = $this->_session_uid();
            $room_info  = $this->Discuss_model->room_info_by_id_user($cur_uid);

            if ($room_info !== false) {
                $id_room = $room_info['id_room'];
                $list_chat = $this->Discuss_model->list_chat_by_room($id_room);
            }
        } else {

            // $ip = ip_address();
            // $list_chat = $this->Discuss_model->chat_list_by_vang_lai($ip);
            $list_chat = []; // TODO: chưa xử lý
        }

        resSuccess($list_chat);
    }

    function ajax_chat_tong_add()
    {

        // check right
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');


        if ($this->_isLogin()) {
            $cur_uid = $this->_session_uid();
            $action_by = $this->_session_uid();
            $fullname =  '';
            $email = '';
            $phone = '';
        } else {
            $cur_uid = ip_address();
            $action_by = 0;
            $fullname = removeAllTags($this->input->post('fullname'));
            $email = removeAllTags($this->input->post('email'));
            $phone = removeAllTags($this->input->post('phone'));
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
        }

        // get list discuss theo order
        $ip = ip_address();
        $status = 1;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $db_attach =  json_encode($db_attach, JSON_FORCE_OBJECT);

        $id_room = '';
        $room_info  = $this->Discuss_model->room_info_by_id_user($cur_uid);

        // user chưa có room thực hiện tạo room
        if ($room_info == false) {
            $id_room = $this->Discuss_model->room_add($cur_uid);
        }
        // room da tồn tại 
        else {
            $id_room = $room_info['id_room'];
        }

        // $newid = $this->Discuss_model->chat_add($cur_uid, $content, $db_attach, $create_time, $status, $ip, $fullname, $phone, $email, $action_by);
        $newid = $this->Discuss_model->chat_add_v2($id_room, $cur_uid, $content, $db_attach, $created_at, $updated_at);

        // cập nhật id_msg mới nhất vào
        $this->Discuss_model->update_newest_msg_to_room($newid, $id_room);

        if ($this->_isLogin()) {
            // $info = $this->Discuss_model->chat_info_by_id_user($newid);
            $info = $this->Discuss_model->chat_info_v2($newid);
            $res['content']    = $info['content'];
            $res['file_list']  = $info['file_list'];
            $res['id_user']    = $info['id_user'];
            $res['fullname']   = $info['fullname'];
            $res['avatar_url'] = $info['avatar_url'];
        } else {
            // $res = $this->Discuss_model->chat_info_by_vang_lai($newid);
            resError('Chức năng đang bảo trì.');  // TODO: chưa xử lý
        }

        resSuccess($res);

        // $data = [
        //     "ip" => ip_address(),
        //     "id_discuss" => "_",
        //     "id_order" => "_",
        //     "id_user" => "987654343",
        //     "content" => "Test default",
        //     "file" => [],
        //     "create_time" => "2023-12-21 15:18:38",
        //     "status" => "1",
        //     "type" => "3",
        //     "username" => "KHACH_DEV_01",
        //     "role" => "5",
        //     "fullname" => "KHÁCH DEV 01",
        //     "avatar" => "user-default.png",
        //     "avatar_url" => "http://stageofvisualization.local/uploads/avatar/user-default.png",
        //     "file_list" => []
        // ];

        // resSuccess($data);
    }
}
