<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/../../libraries/facebook/autoload.php");
require_once(dirname(__FILE__) . "/../../libraries/googleapi/vendor/autoload.php");
class Signup extends MY_Controller {
	
	function __construct()
	{
		$this->_module = trim(strtolower(__CLASS__));
		parent::__construct();
        
        $this->load->model('account/Account_model');
  
	}
    
    function index(){
        if ($this->_isLogin()) {
            redirect(site_url(""));
            die();
        }
        $data = [];
        
        $error = array();
        
        $error['username'] = false;
        $error['password'] = false;
        $error['repassword'] = false;
        $error['email'] = false;


        $info = array();
        $info['username'] = '';
        $info['email'] = '';
        $info['fullname'] = '';
        $info['phone'] = '';
        $info['success'] = "";
        
        
        if (isset($_POST['email'])) {
            $uname = trim(removeAllTags($this->input->post('uname')));
            $fullname = trim(removeAllTags($this->input->post('fullname')));
            $email = strtolower(removeAllTags(trim($this->input->post('email'))));
            $phone = trim(removeAllTags($this->input->post('phone')));
            $password = trim($this->input->post('pword'));
            $repassword = trim($this->input->post('repword'));
            
            //validate
            if(!$this->chkuname($uname)){
                $error['username'] = true;
            }
            
            if(!$this->chkemail($email)){
                $error['username'] = true;
            }
            
            if ($password == '' || $repassword == '') {
                $error['password'] = $password == '' ? true : false;
                $error['repassword'] = $repassword == '' ? true : false;
            } else if ($password != $repassword) {
                $error['repassword'] = true;
            } else if (strlen($password) < 8) {
                $error['password'] = true;
            }
            if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*]{8,}$/', $password)) {
                $error['password'] = true;
            }
    
            
            if($phone != ""){
                $uinfo = $this->Account_model->get_user_info_by_phone($phone);
                if(!str_valid_phone($phone) || !empty($uinfo)){
                    $phone = "";
                }
            }
            if ($error['username'] || $error['password'] || $error['repassword'] || $error['email']) {
                $info['username'] = $error['username'] ? "" : $uname;
                $info['email'] = $error['email'] ? "" : $email;
                
                $info['fullname'] =  $fullname;
                $info['phone'] = $phone;
                $info['success'] = "0";
            } else {
                $password_hash = PasswordHash::hash($uname, md5($password));
                $user_id = $this->Account_model->add($uname, $password_hash, $fullname, $email, $phone, "", CUSTOMER, 1, 0);
                if($user_id > 0){
                    $info['success'] = "1";
                }
            }
        }
        
        $data['info'] = $info;
        
        
        //tao link button login
        //GOOGLE
        $gClient = new Google_Client();
        $gClient->setClientId(gg_ClientId);
        $gClient->setClientSecret(gg_ClientSecret);
        $gClient->setApplicationName('Đất Đông Anh');
        $gClient->setRedirectUri(ROOT_DOMAIN.LINK_USER_LOGIN.'/ggcallback');
        $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

        $loginUrlgg = $gClient->createAuthUrl();
        $data['loginUrlgg'] = $loginUrlgg;
        
        $this->load->view($this->_template_f . 'signup/signup_view', $data);
    }
    
    function chkuname($username)
    {
        // Kiểm tra độ dài tên người dùng
        if (strlen($username) < 3 || strlen($username) > 20 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return false;
        }
        
        $uinfo = $this->Account_model->get_user_info_by_uname($username);
        if(!empty($uinfo)){
            return false;
        }
        
        return true;
    }
    
    function chkemail($email)
    {
        if ($email == "" || !validEmail($email)) {
            return false;
        }
        $uinfo = $this->Account_model->get_user_info_by_email($email);
        if(!empty($uinfo)){
            return false;
        }
        
        return true;
    }
    
}
