<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller{
	
	public function __construct()
	{
		$this->_module = trim(strtolower(__CLASS__));
		parent::__construct();
	}
	
	public function index()
	{
		$this->session->sess_destroy();
		
		redirect(site_url('login'));
		die();
	}
}