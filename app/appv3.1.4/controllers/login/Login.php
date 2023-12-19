<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/../../libraries/facebook/autoload.php");
require_once(dirname(__FILE__) . "/../../libraries/googleapi/vendor/autoload.php");
class Login extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        // model
        $this->load->model('login/Login_model');
        $this->load->model('account/Account_model');
    }

    function auth()
    {
        $currUrl = removeAllTags($this->input->get('url'));
        $userame = removeAllTags($this->input->post('username'));
        $password = removeAllTags($this->input->post('password'));
        // validate current url for redirect
        if ($currUrl != '') {
            // check is url
            if (isUrl($currUrl)) {
                // check domain of url
                $currUrl = strtolower(getDomainFromUrl($currUrl)) ==  strtolower(DOMAIN_NAME) ? $currUrl : '';
            } else {
                $currUrl = '';
            }
        }

        $currUrl = $currUrl != '' ? $currUrl : site_url('home', $this->_langcode);

        $userInfo = $this->Login_model->get_user_info_by_username($userame);

        $redirect = '';
        if (!empty($userInfo)) {
            $passVerify = PasswordHash::hash_verify($userInfo['username'], $userInfo['password'], md5($password));

            if ($passVerify) {

                if ($userInfo['status']) {
                    // unset all session before init
                    session_unset();
                    session_regenerate_id(true);

                    $this->session->set_userdata('uname', $userInfo['username']);
                    $this->session->set_userdata('uid', $userInfo['id_user']);
                    $this->session->set_userdata('role', $userInfo['role']);
                    $this->session->set_userdata('phone', $userInfo['phone']);
                    $this->session->set_userdata('email', $userInfo['email']);
                    $this->session->set_userdata('fullname', $userInfo['fullname']);

                    //Update login date TODO: có dùng
                    // $this->Login_model->user_last_login_log($userInfo['user_id']);

                    if (in_array($userInfo['role'], [ADMIN, SALE, QC, EDITOR])) {
                        $redirect = 'admin/';
                    } else {
                        $redirect = urldecode($currUrl);
                    }
                } else {
                    //redirect to login
                $this->session->set_userdata('login_fail', 'Tài khoản đã bị khóa!');
                $redirect = site_url('login?url=' . urlencode($currUrl), $this->_langcode);
                }

            } else {
                //redirect to login
                $this->session->set_userdata('login_fail', 'Sai tài khoản hoặc mật khẩu!');
                $redirect = site_url('login?url=' . urlencode($currUrl), $this->_langcode);
            }
        } else {

            //redirect to login
            $this->session->set_userdata('login_fail', 'Sai tài khoản hoặc mật khẩu!');
            $redirect = site_url('login?url=' . urlencode($currUrl), $this->_langcode);
        }

        dbClose();
        redirect($redirect);
        die();
    }

    function index()
    {
        $currUrl = removeAllTags(urldecode($this->input->get('url')));
        // validate current url for redirect
        if ($currUrl != '') {
            // check is url
            if (isUrl($currUrl)) {
                // check domain of url
                $currUrl = strtolower(getDomainFromUrl($currUrl)) ==  strtolower(DOMAIN_NAME) ? $currUrl : '';
            } else {
                $currUrl = '';
            }
        }
        $currUrl = $currUrl != '' ? $currUrl : site_url('home', $this->_langcode);

        // da login
        if ($this->_islogin()) {
            dbClose();

            $login_url = site_url('login', $this->_langcode);
            if ($currUrl != $login_url) {
                redirect($currUrl);
                die();
            } else {
                redirect(site_url('home', $this->_langcode));
                die();
            }
        } else {
            $data['login_fail'] = $this->session->has_userdata('login_fail') ? $this->session->userdata('login_fail') : '';
            $this->session->unset_userdata('login_fail');
            $data['currUrl'] = $currUrl;


            //tao link button login
            //GOOGLE
            $gClient = new Google_Client();
            $gClient->setClientId(gg_ClientId);
            $gClient->setClientSecret(gg_ClientSecret);
            $gClient->setApplicationName('Đất Đông Anh');
            $gClient->setRedirectUri(ROOT_DOMAIN . LINK_USER_LOGIN . '/ggcallback');
            $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

            $loginUrlgg = $gClient->createAuthUrl();
            $data['loginUrlgg'] = $loginUrlgg;

            $this->load->view($this->_template_f . 'login/login_view', $data);
        }
    }

    function ggcallback()
    {
        die('dang phat trien');
        $gClient = new Google_Client();
        $gClient->setClientId(gg_ClientId);
        $gClient->setClientSecret(gg_ClientSecret);
        $gClient->setApplicationName('Đất Đông Anh');
        $gClient->setRedirectUri(ROOT_DOMAIN . LINK_USER_LOGIN . '/ggcallback');
        $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

        if (isset($_SESSION['access_token'])) {
            $gClient->setAccessToken($_SESSION['access_token']);
        } else if (isset($_GET['code'])) {
            $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
            $_SESSION['access_token'] = $token;
        } else {
            redirect(site_url("login"));
            exit;
        }

        $oAuth = new Google_Service_Oauth2($gClient);
        $userData = $oAuth->userinfo_v2_me->get();

        $uinfo = $this->Account_model->get_user_info_by_email($userData['email']);

        if (!empty($uinfo)) {
            $this->session->set_userdata('uname', $uinfo['username']);
            $this->session->set_userdata('uid', $uinfo['id_user']);
            $this->session->set_userdata('role', $uinfo['role']);
            $this->session->set_userdata('phone', $uinfo['phone']);
            $this->session->set_userdata('email', $uinfo['email']);
            $this->session->set_userdata('fullname', $uinfo['fullname']);
        } else {
            $password_hash = PasswordHash::hash($userData['id'], md5($this->generateRandomPassword()));

            // avatar new user
            $avatar = generateRandomString(10) . '.jpg';
            @file_put_contents(FOLDER_AVATAR . $avatar, @file_get_contents($userData['picture']));

            $user_id = $this->Account_model->add($userData['id'], $password_hash, $userData['name'], $userData['email'], "", $avatar, CUSTOMER, 1, 0);


            if ($user_id > 0) {
                $uinfo = $this->Account_model->get_user_info_by_uid($user_id);

                $this->session->set_userdata('uname', $uinfo['username']);
                $this->session->set_userdata('uid', $uinfo['id_user']);
                $this->session->set_userdata('role', $uinfo['role']);
                $this->session->set_userdata('phone', $uinfo['phone']);
                $this->session->set_userdata('email', $uinfo['email']);
                $this->session->set_userdata('fullname', $uinfo['fullname']);
            }
        }
        $this->session->set_userdata('avatar', $userData['picture']);

        dbClose();
        if (in_array($uinfo['role'], [ADMIN, SALE, QC, EDITOR])) {
            redirect('admin/');
        } else {
            redirect(site_url(""));
        }
        die();
    }


    function generateRandomPassword($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }

        return $password;
    }
}
