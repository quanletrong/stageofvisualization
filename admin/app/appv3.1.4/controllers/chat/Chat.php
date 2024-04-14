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
        // danh sách người dùng chat
        $list_user_chat = $this->Chat_model->list_user_chat();
        $data['list_user_chat'] = $list_user_chat;

        $chat_user = isIdNumber($chat_user) ? $chat_user : '';
        $data['chat_user'] = $chat_user;
        $data['cur_uid'] = $this->_session_uid();
        $data['all_member'] = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));

        $header = [
            'title' => 'Chat',
            'header_page_css_js' => 'home'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'chat/chat_view', $data);
        $this->_loadFooter();
    }

    function index($active_gchat = '')
    {
        $data = [];
        $curr_uid = $this->_session_uid();

        // danh sách nhóm
        $list_group = $this->Chat_model->list_group_by_user($curr_uid);
        $data['list_group'] = $list_group;

        // danh sách người dùng chat
        $list_user_chat = $this->Chat_model->list_user_chat();
        $data['list_user_chat'] = $list_user_chat;

        $active_gchat = isIdNumber($active_gchat) ? $active_gchat : '';
        $data['active_gchat'] = $active_gchat;
        $data['cur_uid'] = $this->_session_uid();
        $data['all_member'] = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));

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
        // set tất cả tin nhắn là đã xem
        $this->Chat_model->da_xem_all_chat_user($chat_user);

        // get lai list
        $chat_list = $this->Chat_model->chat_list_by_user($chat_user);

        resSuccess($chat_list);
    }

    function ajax_chat_add($chat_user)
    {
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

    function ajax_delete_chat_user($chat_user)
    {
        if (isIdNumber($chat_user) || isIPV4($chat_user)) {
            $exc = $this->Chat_model->delete_chat_user($chat_user);

            resSuccess('ok');
        } else {
            resError('Người xóa không hợp lệ');
        }
    }

    function ajax_add_group()
    {
        $curr_uid = $this->_session_uid();
        $all_member = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));

        $name = removeAllTags($this->input->post('name_group'));
        $member = $this->input->post('member_group');

        // check memmber empty
        is_array($member) ? '' : resError('Không lấy được thành viên');
        count($member) ? '' : resError('Thành viên không được bỏ trống');

        // kiểm tra xem list member đã có id người tạo nhóm chưa
        // nếu chưa có thì thêm người tạo nhóm vào list member
        if (!in_array($curr_uid, $member)) {
            $member[] = $curr_uid;
        }

        // check member không tồn tại
        foreach ($member as $id_member) {
            isset($all_member[$id_member]) ? '' : resError('Thành viên không tồn tại!');
        }

        // thêm nhóm
        $avatar = AVATAR_DEFAULT; //TODO: tạm fix, sau thêm chức năng upload avatar
        $create_time = date('Y-m-d H:i:s');
        $new_id_group = $this->Chat_model->add_group($name, $avatar, $curr_uid, $create_time);

        // thêm tin nhắn đầu tiên
        $this->Chat_model->msg_add_to_group($new_id_group, $curr_uid, '<i>Đã tạo đoạn chat.</i>', '{}', $create_time, '', '', '', '', '', $curr_uid);

        // thêm thành viên vào nhóm
        foreach ($member as $id_member) {
            $this->Chat_model->add_member_group($new_id_group, $id_member, $create_time);
        }

        $gchat_info = $this->Chat_model->gchat_info($new_id_group, $curr_uid);
        $socket['id_gchat']   = $new_id_group;
        $socket['name_gchat'] = $gchat_info['info']['name'];
        $socket['members']    = $gchat_info['members'];
        $socket['member_ids'] = $gchat_info['member_ids'];
        $socket['msg_newest'] = $gchat_info['msg_newest'];
        $socket['action_by']  = $curr_uid;
        resSuccess($socket);
    }

    // TRUE: nhóm đã tồn tại
    // FASLSE:  nhóm chưa tồn tại
    function _check_group_da_ton_tai($member, $all_group)
    {
        // nếu đã tồn tại nhóm có số lượng thành viên bằng $member thì báo $nhom_da_ton_tai
        foreach ($all_group as $id_gchat => $group) {

            if (count($group['members']) == count($member)) {

                $array_diff = array_diff($group['members'], $member);

                if (empty($array_diff)) {
                    return $id_gchat;
                }
            }
        }

        return 0;
    }

    function ajax_list_msg_by_group($id_group)
    {
        $curr_uid = $this->_session_uid();

        // set tất cả tin nhắn là đã xem
        // $this->Chat_model->set_da_xem_all_msg_group($id_group);

        $list_group = $this->Chat_model->list_group_by_user($curr_uid);

        isset($list_group['list'][$id_group]) ? '' : resError('Bạn không có quyền truy cập nhóm này');

        // get lai list
        $chat_list = $this->Chat_model->chat_list_by_group($id_group);

        resSuccess($chat_list);
    }

    function ajax_msg_add_to_group($id_gchat)
    {
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

        // trả dư liệu
        $msg_info = $this->Chat_model->msg_info($new_id_msg);
        $gchat_info = $this->Chat_model->gchat_info($id_gchat, $curr_uid);

        $socket['id_gchat']    = $id_gchat;
        $socket['name_gchat']  = $gchat_info['info']['name'];
        $socket['members']     = $gchat_info['members'];
        $socket['member_ids']  = $gchat_info['member_ids'];
        $socket['msg_newest']  = $gchat_info['msg_newest'];
        $socket['id_msg']      = $new_id_msg;
        $socket['file_list']   = $msg_info['file_list'];
        $socket['id_user']     = $msg_info['id_user'];
        $socket['content']     = $msg_info['content'];
        $socket['avatar_url']  = $msg_info['avatar_url'];
        $socket['create_time'] = $msg_info['create_time'];
        $socket['fullname']    = $msg_info['fullname'];
        $socket['action_by']   = $curr_uid;

        resSuccess($socket);
    }

    function ajax_modal_group_info($id_group)
    {
        $curr_uid = $this->_session_uid();
        $list_group = $this->Chat_model->list_group_by_user($curr_uid);
        isset($list_group['list'][$id_group]) ? '' : resError('Bạn không có quyền truy cập nhóm này');

        $members = $list_group['members'][$id_group];
        $name = $list_group['list'][$id_group]['name'];

        resSuccess(['members' => array_keys($members), 'name' => $name]);
    }

    function ajax_edit_group()
    {
        $curr_uid = $this->_session_uid();

        $id_group = removeAllTags($this->input->post('id_group'));
        $name_post = removeAllTags($this->input->post('name_group'));
        $member_post = $this->input->post('member_group');

        $all_member = $this->User_model->get_list_user_working(1, implode(",", [ADMIN, SALE, QC, EDITOR]));
        $list_group = $this->Chat_model->list_group_by_user($curr_uid);
        isset($list_group['list'][$id_group]) ? '' : resError('Bạn không có quyền truy cập nhóm này');

        $name_old = $list_group['list'][$id_group]['name'];
        $member_old = $list_group['members'][$id_group];
        $member_old = array_keys($member_old);


        // check memmber empty
        is_array($member_post) ? '' : resError('Không lấy được thành viên');
        count($member_post) ? '' : resError('Thành viên không được bỏ trống');

        // check member không tồn tại
        foreach ($member_post as $id_member) {
            isset($all_member[$id_member]) ? '' : resError('Thành viên không tồn tại!');
        }

        // member_new, member_remove
        $member_new = array_values(array_diff($member_post, $member_old));
        $member_del = array_values(array_diff($member_old, $member_post));
        $create_time = date('Y-m-d H:i:s');

        // check xem có gi thay đổi không
        if ($member_new == [] && $member_del == [] && $name_post == $name_old) {
            resError('Không có gì thay đổi cả');
        } else {
            // thêm thành viên vào nhóm
            $member_new_fullname = [];
            foreach ($member_new as $id_member) {
                $this->Chat_model->add_member_group($id_group, $id_member, $create_time);
                $member_new_fullname[] = "<u>" . $all_member[$id_member]['fullname'] . "</u>";
            }

            // xóa thành viên
            $member_del_fullname = [];
            foreach ($member_del as $id_member) {
                $this->Chat_model->delete_member_group($id_group, $id_member);
                $member_del_fullname[] = "<u>" . $all_member[$id_member]['fullname'] . "</u>";
            }

            // lưu tên mới
            if ($name_old !== $name_post) {
                $this->Chat_model->edit_name_group($id_group, $name_post);
                $msg[] = '<i>Đã đổi tên đoạn chat</i>';
            }

            // lưu log msg
            $log = [];
            if (count($member_new)) {
                $log[] = '<i>Đã thêm <u>' . implode(", ", $member_new_fullname) . '</u></i>';
            }
            if (count($member_del)) {
                $log[] = '<i>Đã xóa <u>' . implode(", ", $member_del_fullname) . '</u></i>';
            }
            if ($name_old !== $name_post) {
                $this->Chat_model->edit_name_group($id_group, $name_post);
                $log[] = '<i>Đã đổi tên đoạn chat thành <u>' . $name_post . '</u></i>';
            }

            $new_id_msg = $this->Chat_model->msg_add_to_group($id_group, $curr_uid, implode('<br>', $log), '{}', $create_time, '', '', '', '', '', $curr_uid);
            // end log

            // tra ve du lieu
            $msg_info = $this->Chat_model->msg_info($new_id_msg);
            $gchat_info = $this->Chat_model->gchat_info($id_group, $curr_uid);

            $socket['id_gchat']   = $id_group;
            $socket['name_gchat'] = $gchat_info['info']['name'];
            $socket['members']    = $gchat_info['members'];
            $socket['member_ids'] = $gchat_info['member_ids'];
            $socket['msg_newest'] = $gchat_info['msg_newest'];

            $socket['id_msg']      = $new_id_msg;
            $socket['file_list']   = $msg_info['file_list'];
            $socket['id_user']     = $msg_info['id_user'];
            $socket['content']     = $msg_info['content'];
            $socket['avatar_url']  = $msg_info['avatar_url'];
            $socket['create_time'] = $msg_info['create_time'];
            $socket['fullname']    = $msg_info['fullname'];

            $socket['member_new'] = $member_new;
            $socket['member_del'] = $member_del;
            $socket['action_by']  = $curr_uid;

            resSuccess($socket);
        }
    }

    function ajax_del_msg_group($id_msg)
    {

        if (isIdNumber($id_msg)) {
            $text_del = '<i>Tin nhắn đã bị xóa</i>';
            $this->Chat_model->delete_msg_group($id_msg, $text_del);

            // TODO: xu ly del file
            resSuccess($text_del);
        } else {
            resSuccess('Message invalid!');
        }
    }

    function ajax_delete_gchat($id_gchat)
    {
        if (isIdNumber($id_gchat)) {

            $gchat_info = $this->Chat_model->gchat_info($id_gchat,  0);
            $socket['id_gchat']   = $id_gchat;
            $socket['member_ids'] = $gchat_info['member_ids'];
            $this->Chat_model->delete_group($id_gchat);

            // TODO: xu ly del file
            resSuccess($socket);
        } else {
            resSuccess('Message invalid!');
        }
    }
}
