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

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
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
            $id_user      = removeAllTags($this->input->post('id_user'));
            $id_user      = is_numeric($id_user) && $id_user > 0 ? $id_user : 0;

            $create_time = date('Y-m-d H:i:s');

            // VALIDATE DATA
            $username  = $username  != '' ? $username : false;
            $fullname  = $fullname  != '' ? $fullname : false;
            $phone     = $phone     != '' ? $phone : false;
            $email     = $email     != '' ? $email : false;
            $role      = in_array($role, [ADMIN, QC, SALE, EDITOR, CUSTOMER]) ? $role : false;
            $type      = in_array($type, ['1', '2']) ? $type : false;

            // dữ liệu không hợp lệ => báo lỗi
            if (!$username || !$fullname || !$phone || !$email || !$role || !$type) {
                $this->session->set_flashdata('flsh_msg', 'Dữ liệu không hợp lệ!');
                redirect('user');
            }

            // Dịch vụ được cấp không tồn tại => báo lỗi
            $user_service  = $user_service  == null ? [] : $user_service;
            $user_service_db = [];
            foreach ($user_service as $id_service) {
                if (isset($list_service[$id_service]) == false) {
                    $this->session->set_flashdata('flsh_msg', 'Dịch vụ được cấp không tồn tại!');
                    redirect('user');
                } else {
                    $user_service_db[$id_service] = $list_service[$id_service]['type_service'];
                }
            }

            //END VALIDATE
            $user_by_code  = $this->User_model->get_user_info_by_code($code_user);
            $user_by_phone = $this->User_model->get_user_info_by_phone($phone);
            $user_by_email = $this->User_model->get_user_info_by_email($email);

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // check username
                $preg_match_username = preg_match('/^[a-zA-Z0-9_]+$/', $username);
                if (!$preg_match_username) {
                    $this->session->set_flashdata('flsh_msg', 'Username chỉ bao gồm chữ hoa, chữ thường, chữ số và dấu gạch dưới');
                    redirect('user');
                }

                // check passwork
                $check_match = password_streng($password);
                if (!$check_match) {
                    $this->session->set_flashdata('flsh_msg', 'Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*');
                    redirect('user');
                }

                // check code user đã tồn tại =>  báo lỗi
                if ($code_user != '' && !empty($user_by_code)) {
                    $this->session->set_flashdata('flsh_msg', 'Code user đã tồn tại!');
                    redirect('user');
                }

                // check phone đã tồn tại =>  báo lỗi
                if (!empty($user_by_phone)) {
                    $this->session->set_flashdata('flsh_msg', 'Số điện thoại đã tồn tại!');
                    redirect('user');
                }

                // check email đã tồn tại =>  báo lỗi
                if (!empty($user_by_email)) {
                    $this->session->set_flashdata('flsh_msg', 'Email đã tồn tại!');
                    redirect('user');
                }

                // check avatar == mặc định
                $avatar = AVATAR_DEFAULT;
                if (basename($hdd_avatar) != AVATAR_DEFAULT) {
                    $copy_attach = copy_image_to_public_upload($hdd_avatar, FOLDER_AVATAR);
                    if ($copy_attach['status']) {
                        $avatar = $copy_attach['basename'];
                    }
                }

                // add user
                $password_hash = PasswordHash::hash($username, md5($password));
                $newid = $this->User_model->add($code_user, $username, $password_hash, $fullname, $phone, $email, $status, $role, $type, json_encode($user_service_db, JSON_FORCE_OBJECT), $create_time, $avatar);

                //error
                $this->session->set_flashdata('flsh_msg', $newid ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                redirect('user');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                $info =  $this->User_model->get_user_info_by_id($id_user);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    if ($password != $info['password']) {
                        // check passwork
                        $check_match = password_streng($password);
                        if (!$check_match) {
                            $this->session->set_flashdata('flsh_msg', 'Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*');
                            redirect('user');
                        }
                        $password_hash = PasswordHash::hash($info['username'], md5($password));
                    } else {
                        $password_hash = $info['password'];
                    }

                    // check code user đã tồn tại =>  báo lỗi
                    if ($code_user != '' && $info['code_user'] != $code_user && !empty($user_by_code)) {
                        $this->session->set_flashdata('flsh_msg', 'Code user đã tồn tại!');
                        redirect('user');
                    }


                    // check phone đã tồn tại =>  báo lỗi
                    if ($info['phone'] != $phone && !empty($user_by_phone)) {
                        $this->session->set_flashdata('flsh_msg', 'Số điện thoại đã tồn tại!');
                        redirect('user');
                    }

                    // check email đã tồn tại =>  báo lỗi
                    if ($info['email'] != $email && !empty($user_by_email)) {
                        $this->session->set_flashdata('flsh_msg', 'Email đã tồn tại!');
                        redirect('user');
                    }

                    // check avatar == mặc định
                    $new_avatar = $info['avatar'];
                    if (basename($hdd_avatar) != AVATAR_DEFAULT && basename($hdd_avatar) != $info['avatar']) {
                        $copy_attach = copy_image_to_public_upload($hdd_avatar, FOLDER_AVATAR);
                        if ($copy_attach['status']) {
                            $new_avatar = $copy_attach['basename'];
                        }
                    }

                    // update voucher
                    $exc = $this->User_model->edit($code_user, $fullname, $password_hash, $phone, $email, $status, $role, $type, json_encode($user_service_db, JSON_FORCE_OBJECT), $create_time, $id_user, $new_avatar);

                    //error
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('user');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('user');
            }
        }

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
