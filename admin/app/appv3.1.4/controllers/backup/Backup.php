<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();
        $this->load->model('backup/Backup_model');
    }

    /** Thống kê */
    function report()
    {
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
        $header = [
            'title' => "Thống kê file backup",
            'header_page_css_js' => 'voucher'
        ];

        $data['report'] = $this->Backup_model->bak_thong_ke();
        // renderTable($this->Backup_model->bak_thong_ke());
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'backup/report_view', $data);
        $this->_loadFooter();
    }

    /**
     * Mục tiêu:
     * Lưu file đủ điều kiện backup vào bảng
     */
    function order_insert()
    {
        require_once('order_job_insert.php');
        require_once('order_rework_insert.php');
        require_once('order_discuss_insert.php');
    }

    /**
     * Mục tiêu:
     * Xóa tất cả file discuss
     * Tạo /thumb trước khi xóa.
     * Xóa xong lưu unlink_time
     */
    function order_discuss_unlink()
    {
        require_once('order_discuss_unlink.php');
    }

    /**
     * Mục tiêu:
     * Xóa tất cả file rác
     * File tồn tại trên server nhưng không có tên trong db
     */
    function order_trash_unlink()
    {
        require_once('order_trash_unlink.php');
    }

    /**
     * Mục tiêu:
     * Làm cho local sử dụng.
     * Trả về danh sách file cho local tải về
     * Yêu cầu token
     */
    function send_order_to_local()
    {
        require_once('send_order_to_local.php');
    }

    /**
     * Mục tiêu:
     * Làm cho local sử dụng.
     * Đánh dấu file đã được tải về máy local
     * Yêu cầu token
     */
    function order_set_download_time()
    {
        require_once('order_set_download_time.php');
    }

    /**
     * Mục tiêu:
     * Xóa tất cả file MAIN REF COMPLETE đã được download về local
     * Tạo /thumb trước khi xóa.
     * Xóa xong lưu unlink_time
     */
    function order_unlink()
    {
        require_once('order_unlink.php');
    }

    function chat_image_to_thumb(){
        require_once('chat_image_to_thumb.php');
    }
}
