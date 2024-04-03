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
        $this->load->model('service/Service_model');
    }

    function index()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN]) ? redirect(site_url('', $this->_langcode)) : '';

        $all_role = [ADMIN, SALE, QC, EDITOR, CUSTOMER];
        $limit = 10000; //fix
        $offset = 0; // fix

        $filter_username = removeAllTags($this->input->get('filter_username'));
        $filter_fullname = removeAllTags($this->input->get('filter_fullname'));
        $filter_code     = removeAllTags($this->input->get('filter_code'));
        $filter_phone    = removeAllTags($this->input->get('filter_phone'));
        $filter_email    = removeAllTags($this->input->get('filter_email'));
        $filter_role     = removeAllTags($this->input->get('filter_role'));
        $filter_status   = removeAllTags($this->input->get('filter_status'));

        //validate role
        $filter_role = in_array($filter_role, $all_role) ? $filter_role : '';

        //validate role
        $filter_status = in_array($filter_status, ['1', '0']) ? $filter_status : '';

        $list = $this->User_model->get_list_user($filter_code, $filter_username, $filter_fullname, $filter_phone, $filter_email, $filter_role, $filter_status, $limit, $offset);

        $list_service = $this->Service_model->get_list();

        $data = [];
        $data['list']     = $list;
        $data['list_service']  = $list_service;


        $data['filter_status']   = $filter_status;
        $data['filter_username'] = $filter_username;
        $data['filter_fullname'] = $filter_fullname;
        $data['filter_code']     = $filter_code;
        $data['filter_phone']    = $filter_phone;
        $data['filter_email']    = $filter_email;
        $data['filter_role']     = $filter_role;
        $data['limit']    = $limit;
        $data['offset']   = $offset;

        $header = [
            'title' => 'Quản lý tài khoản',
            'header_page_css_js' => 'voucher'
        ];

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'user/user_view', $data);
        $this->_loadFooter();
    }

    function ajax_add_user()
    {
        $role = $this->_session_role();
        in_array($role, [ADMIN]) ? '' : resError('Bạn không có quyền truy cập');

        $code_user    = removeAllTags($this->input->post('code_user'));
        $username     = removeAllTags($this->input->post('username'));
        $password     = removeAllTags($this->input->post('password'));
        $fullname     = removeAllTags($this->input->post('fullname'));
        $phone        = removeAllTags($this->input->post('phone'));
        $email        = removeAllTags($this->input->post('email'));
        $role         = removeAllTags($this->input->post('role'));
        $type         = removeAllTags($this->input->post('type'));
        $user_service = $this->input->post('user_service[]');
        $status       = removeAllTags($this->input->post('status'));
        $hdd_avatar   = removeAllTags($this->input->post('hdd_avatar'));
        $status       = $status == 'on' ? 1 : 0;

        # VALIDATE DATA

        // check code user
        if ($code_user != '') {
            $user_by_code  = $this->User_model->get_user_info_by_code($code_user);
            empty($user_by_code) ? '' : resError('Code user đã tồn tại');
        }

        // check username
        $username  = $username  != '' ? $username : resError('Username không được bỏ trống');
        $preg_match_username = preg_match('/^[a-zA-Z0-9_]+$/', $username);
        if (!$preg_match_username) {
            resError('Username chỉ bao gồm chữ hoa, chữ thường, chữ số và dấu gạch dưới');
        } else {
            $user_by_uname = $this->User_model->get_user_info_by_uname($username);
            empty($user_by_uname) ? '' : resError('Username đã tồn tại');
        }

        // fullname, role, type
        $fullname  = $fullname  != '' ? $fullname : resError('Fullname không được bỏ trống');
        $role      = in_array($role, [ADMIN, QC, SALE, EDITOR, CUSTOMER]) ? $role : resError('Role không hợp lệ');
        $type      = in_array($type, [ED_NOI_BO, ED_CTV]) ? $type : resError('Loại tài khoản không hợp lệ');

        // check services
        $user_service  = $user_service  == null ? [] : $user_service;
        $list_service = $this->Service_model->get_list();
        $user_service_db = [];
        foreach ($user_service as $id_service) {
            if (isset($list_service[$id_service]) == false) {
                resError('Dịch vụ được cấp không tồn tại!');
            } else {
                $user_service_db[$id_service] = $list_service[$id_service]['type_service'];
            }
        }

        // check phone
        $phone != '' ? '' : resError('Phonenumber không được bỏ trống');
        $user_by_phone = $this->User_model->get_user_info_by_phone($phone);
        empty($user_by_phone) ? '' : resError('Phonenumber đã tồn tại');

        // check email 
        $email != '' ? '' : resError('Email không được bỏ trống');
        $user_by_email = $this->User_model->get_user_info_by_email($email);
        empty($user_by_email) ? '' : resError('Email đã tồn tại');

        // check avatar 
        $avatar = AVATAR_DEFAULT;
        if (basename($hdd_avatar) != AVATAR_DEFAULT) {
            $copy_attach = copy_image_to_public_upload($hdd_avatar, FOLDER_AVATAR);
            if ($copy_attach['status']) {
                $avatar = $copy_attach['basename'];
            }
        }

        #END VALIDATE

        # ADD
        $create_time = date('Y-m-d H:i:s');
        $password_hash = PasswordHash::hash($username, md5($password));

        $newid = $this->User_model->add($code_user, $username, $password_hash, $fullname, $phone, $email, $status, $role, $type, json_encode($user_service_db, JSON_FORCE_OBJECT), $create_time, $avatar);

        $newid ? resSuccess($newid) : resError('Lưu không thành công vui lòng thử lại!');
        #END ADD
    }

    function ajax_edit_user()
    {
        $role = $this->_session_role();
        in_array($role, [ADMIN]) ? '' : resError('Bạn không có quyền truy cập');

        $code_user    = removeAllTags($this->input->post('code_user'));
        $password     = removeAllTags($this->input->post('password'));
        $fullname     = removeAllTags($this->input->post('fullname'));
        $phone        = removeAllTags($this->input->post('phone'));
        $email        = removeAllTags($this->input->post('email'));
        $role         = removeAllTags($this->input->post('role'));
        $type         = removeAllTags($this->input->post('type'));
        $user_service = $this->input->post('user_service[]');
        $status       = removeAllTags($this->input->post('status'));
        $hdd_avatar   = removeAllTags($this->input->post('hdd_avatar'));
        $status       = $status == 'on' ? 1 : 0;
        $id_user      = removeAllTags($this->input->post('id_user'));
        $id_user      = is_numeric($id_user) && $id_user > 0 ? $id_user : 0;

        # VALIDATE DATA
        // check info user
        $info =  $this->User_model->get_user_info_by_id($id_user);
        !empty($info) ? '' : resError('Tài khoản không tồn tại');

        // check code user
        $user_by_code  = $this->User_model->get_user_info_by_code($code_user);
        if ($code_user != '' && $info['code_user'] != $code_user && !empty($user_by_code)) {
            resError('Code user đã tồn tại!');
        }

        // check passwork
        if ($password != $info['password']) {
            $check_match = password_streng($password);
            $check_match ? '' : resError('Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*');
            $password_hash = PasswordHash::hash($info['username'], md5($password));
        } else {
            $password_hash = $info['password'];
        }

        // fullname, role, type
        $fullname  = $fullname  != '' ? $fullname : resError('Fullname không được bỏ trống');
        $role      = in_array($role, [ADMIN, QC, SALE, EDITOR, CUSTOMER]) ? $role : resError('Role không hợp lệ');
        $type      = in_array($type, [ED_NOI_BO, ED_CTV]) ? $type : resError('Loại tài khoản không hợp lệ');

        // check phone
        $phone != '' ? '' : resError('Phonenumber không được bỏ trống');
        $user_by_phone = $this->User_model->get_user_info_by_phone($phone);
        if ($info['phone'] != $phone && !empty($user_by_phone)) {
            resError('Số điện thoại đã tồn tại!');
        }

        // check email
        $email != '' ? '' : resError('Email không được bỏ trống');
        $user_by_email = $this->User_model->get_user_info_by_email($email);
        if ($info['email'] != $email && !empty($user_by_email)) {
            resError('Email đã tồn tại!');
        }

        // check services
        $user_service  = $user_service  == null ? [] : $user_service;
        $list_service = $this->Service_model->get_list();
        $user_service_db = [];
        foreach ($user_service as $id_service) {
            if (isset($list_service[$id_service]) == false) {
                resError('Dịch vụ được cấp không tồn tại!');
            } else {
                $user_service_db[$id_service] = $list_service[$id_service]['type_service'];
            }
        }

        // check avatar
        $new_avatar = $info['avatar'];
        if (basename($hdd_avatar) != AVATAR_DEFAULT && basename($hdd_avatar) != $info['avatar']) {
            $copy_attach = copy_image_to_public_upload($hdd_avatar, FOLDER_AVATAR);
            if ($copy_attach['status']) {
                $new_avatar = $copy_attach['basename'];
            }
        }
        #END VALIDATE

        # EDIT
        $updatetime = date('Y-m-d H:i:s');
        $password_hash = PasswordHash::hash($info['username'], md5($password));

        $exc = $this->User_model->edit($code_user, $fullname, $password_hash, $phone, $email, $status, $role, $type, json_encode($user_service_db, JSON_FORCE_OBJECT), $updatetime, $id_user, $new_avatar);

        $exc ? resSuccess('Thành công') : resError('Lưu không thành công vui lòng thử lại!');
        #END EDIT
    }

    function ajax_change_code_user()
    {
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $id_user = $this->input->post('id_user');
        $code    = $this->input->post('code');

        $code = removeAllTags($code);
        // $code = str_replace(' ', '_', $code);
        $user = $this->User_model->get_user_info_by_id($id_user);
        // $infoUserCode = $this->User_model->get_user_info_by_code($code);

        $user == []                     ? resError('User không tồn tại') : '';
        // $infoUserCode != []             ? resError('Code Customer đã tồn tại') : '';

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

        $data['code'] = $user['code_user'];
        $data['fullname'] = $user['fullname'];
        $data['username'] = $user['username'];
        $data['email'] = $user['email'];
        $data['phone'] = $user['phone'];
        $data['avatar_url'] = $user['avatar_url'];

        resSuccess($data, 'Thành công');
    }

    function info()
    {
        $id_user = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $header = [
            'title' => 'Thông tin tài khoản',
            'header_page_css_js' => 'voucher'
        ];

        $data['list_service'] = $this->Service_model->get_list();
        $data['info'] = $this->User_model->get_user_info_by_id($id_user);
        // var_dump($data['info']);die;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'user/info_view', $data);
        $this->_loadFooter();
    }

    function ajax_edit_info()
    {
        $id_user = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $fullname     = removeAllTags($this->input->post('fullname'));
        $phone        = removeAllTags($this->input->post('phone'));
        $email        = removeAllTags($this->input->post('email'));
        $hdd_avatar   = removeAllTags($this->input->post('hdd_avatar'));

        // VALIDATE DATA

        $info          = $this->User_model->get_user_info_by_id($id_user);
        $user_by_phone = $this->User_model->get_user_info_by_phone($phone);
        $user_by_email = $this->User_model->get_user_info_by_email($email);

        // dữ liệu không hợp lệ => báo lỗi
        if ($fullname == '' || $phone == '' || $email == '') {
            resError('Dữ liệu không hợp lệ!');
        }
        // check phone đã tồn tại =>  báo lỗi
        if ($info['phone'] != $phone && !empty($user_by_phone)) {
            resError('Số điện thoại đã tồn tại!');
        }

        // check email đã tồn tại =>  báo lỗi
        if ($info['email'] != $email && !empty($user_by_email)) {
            resError('Email đã tồn tại!');
        }

        // check avatar == mặc định
        $new_avatar = $info['avatar'];
        if (basename($hdd_avatar) != AVATAR_DEFAULT && basename($hdd_avatar) != $info['avatar']) {
            $copy_attach = copy_image_to_public_upload($hdd_avatar, FOLDER_AVATAR);
            if ($copy_attach['status']) {
                $new_avatar = $copy_attach['basename'];
            }
        }

        //END VALIDATE

        // update
        $exc = $this->User_model->edit_info($fullname, $phone, $email, $id_user, $new_avatar);

        //error
        if ($exc) {
            resSuccess('ok');
        } else {
            resError('Lưu không thành công vui lòng thử lại!');
        }
    }

    function ajax_edit_password()
    {
        $id_user = $this->_session_uid();
        $role = $this->_session_role();
        !in_array($role, [ADMIN, SALE, QC, EDITOR]) ? resError('Tài khoản không có quyền thực hiện chức năng này') : '';

        $password     = removeAllTags($this->input->post('password'));
        $new_password = removeAllTags($this->input->post('new_password'));
        $re_password  = removeAllTags($this->input->post('re_password'));


        // VALIDATE DATA
        $info = $this->User_model->get_user_info_by_id($id_user);

        empty($info) ? resError('User không tồn tại') : '';

        if ($password == '' || $new_password == '' || $re_password == '') {
            resError('Chưa nhập đủ dữ liệu!');
        }

        $new_password != $re_password ? resError('Mật khẩu nhập lại không khớp nhập khẩu mới') : '';

        if (!password_streng($password)) {
            resError('Mật khẩu cũ tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*');
        }

        if (!password_streng($new_password)) {
            resError('Mật khẩu mới tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*');
        }

        $check_pass_cu = PasswordHash::hash_verify($info['username'], $info['password'], md5($password));
        !$check_pass_cu ? resError('Bạn đã nhập sai mật khẩu cũ') : '';

        //END VALIDATE

        // update
        $new_password_hash = PasswordHash::hash($info['username'], md5($new_password));
        $exc = $this->User_model->edit_password($new_password_hash, $id_user);

        //error
        if ($exc) {
            resSuccess('ok');
        } else {
            resError('Lưu không thành công vui lòng thử lại!');
        }
    }
}
