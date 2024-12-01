<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();
        $this->load->model('backup/Backup_model');
    }

    function order_discuss_unlink()
    {
        require_once('order_discuss_unlink.php');
    }

    function order_trash_unlink() {
        require_once('order_trash_unlink.php');
    }
}
