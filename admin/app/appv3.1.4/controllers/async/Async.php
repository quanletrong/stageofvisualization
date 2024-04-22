<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Async extends MY_Controller
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

        $this->load->model('async/Async_model');
    }

    function process()
    {
        $order = [];
        $jobs = $this->Async_model->process_get_file_order();
        $id_orders = [];
        foreach ($jobs as $id_job => $job) {

            $id_order         = $job['id_order'];
            $id_orders[] = $id_order;

            $image            = $job['image'];
            $file_complete    = $job['file_complete'];
            $attach           = $job['attach'];
            $rw_attach        = $job['rw_attach'];
            $rw_file_complete = $job['rw_file_complete'];
            $create_time      = $job['create_time'];
            $id_order = $id_order. '_' .strtotime($create_time);


            $file_complete_arr    = json_decode($file_complete, true);
            $attach_arr           = json_decode($attach, true);
            $rw_attach_arr        = json_decode($rw_attach, true);
            $rw_file_complete_arr = json_decode($rw_file_complete, true);

            $file_complete_arr    = $file_complete_arr    == null ? [] : $file_complete_arr;
            $attach_arr           = $attach_arr           == null ? [] : $attach_arr;
            $rw_attach_arr        = $rw_attach_arr        == null ? [] : $rw_attach_arr;
            $rw_file_complete_arr = $rw_file_complete_arr == null ? [] : $rw_file_complete_arr;

            if ($image != '') {
                $order[$id_order][] = $image;
            }

            if (count($file_complete_arr)) {
                foreach ($file_complete_arr as $filename) {
                    $order[$id_order][] = $filename;
                }
            }

            if (count($attach_arr)) {
                foreach ($attach_arr as $filename) {
                    $order[$id_order][] = $filename;
                }
            }

            if (count($rw_attach_arr)) {
                foreach ($rw_attach_arr as $filename) {
                    $order[$id_order][] = $filename;
                }
            }

            if (count($rw_file_complete_arr)) {
                foreach ($rw_file_complete_arr as $filename) {
                    $order[$id_order][] = $filename;
                }
            }

            
        }

        $order_discuss = $this->Async_model->process_get_file_discuss_order(implode(',', $id_orders));
        foreach ($order_discuss as $discuss) {

            $id_order          = $discuss['id_order'];
            $file              = $discuss['file'];
            $create_time_order = $discuss['create_time_order'];

            $id_order = $id_order. '_' .strtotime($create_time_order);

            $file_arr    = json_decode($file, true);
            $file_arr    = $file_arr == null ? [] : $file_arr;

            if (count($file_arr)) {
                foreach ($file_arr as $filename) {
                    $order[$id_order][] = $filename;
                }
            }
        }
        var_dump($order);
        die;
    }
}
