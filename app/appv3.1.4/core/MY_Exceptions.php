<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{

    public function __construct()
    {
        parent::__construct();
    }

    function show_404($page = '', $log_error = TRUE)
    {

        $CI = &get_instance();
        if ($CI === null) {
            new CI_Controller();
            $CI = &get_instance();
        }

        redirect('home'); // tạm thời fix về trang chủ 
        
        $CI->load->view('404_read_view');
        echo $CI->output->get_output();
        exit;
    }
}
