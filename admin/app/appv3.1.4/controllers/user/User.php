<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
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

        // model
        $this->load->model('user/User_model');
    }

    function ajax_change_code_user()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_user = $this->input->post('id_user');
        $code    = $this->input->post('code');

        $code = removeAllTags($code);
        $code = str_replace(' ', '_', $code);
        $user = $this->User_model->get_user_info_by_id($id_user);
        $infoUserCode = $this->User_model->get_user_info_by_code($code);

        $user == []                     ? resError('User không tồn tại') : '';
        $infoUserCode != []             ? resError('Code Customer đã tồn tại') : '';

        $this->User_model->update_code_user($id_user, $code);
        resSuccess('Thành công');
    }

    // dùng riêng cho tạo đơn cho khách
    function ajax_load_info_user_create_order($id_user)
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';
        !isIdNumber($id_user)           ? resError('User không hợp lệ') : '';

        $user = $this->User_model->get_user_info_by_id($id_user);
        $user == [] ? resError('User không tồn tại') : '';

        $data['avatar_url'] = ROOT_DOMAIN . FOLDER_AVATAR . $user['avatar'];
        $data['code'] = $user['code_user'];
        $data['fullname'] = $user['fullname'];
        $data['username'] = $user['username'];
        $data['email'] = $user['email'];
        $data['phone'] = $user['phone'];

        resSuccess($data, 'Thành công');
    }
}
