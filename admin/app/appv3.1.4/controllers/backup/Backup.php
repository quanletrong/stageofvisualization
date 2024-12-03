<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();
        $this->load->model('backup/Backup_model');
    }

    function order_insert()
    {
        require_once('order_job_insert.php');
        require_once('order_rework_insert.php');
        require_once('order_discuss_insert.php');
    }
    function order_discuss_unlink()
    {
        require_once('order_discuss_unlink.php');
    }

    function order_trash_unlink()
    {
        require_once('order_trash_unlink.php');
    }

    function send_order_to_local()
    {
        require_once('send_order_to_local.php');
    }

    // Đánh dấu file đã được tải về máy local
    function order_set_download_time()
    {
        require_once('order_set_download_time.php');
    }
}
