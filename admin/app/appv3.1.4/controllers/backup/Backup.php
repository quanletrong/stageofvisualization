<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();
        $this->load->model('backup/Backup_model');
    }

    function rm_discuss()
    {
        set_time_limit(3600);

        $discuss_list  = $this->Backup_model->discuss_list();

        // Lưu danh sách discuss đã xóa vào đây.
        $rm = [];

        // Duyệt qua toàn bộ discuss để xóa
        foreach ($discuss_list as $row) {

            $filename = $row['filename'];
            $FDR_ORDER = FOLDER_ORDER . strtotime($row['order_create_time']) . '@' . $row['order_create_by'] . '/';
            $ORDER_DIR = $_SERVER["DOCUMENT_ROOT"] . '/' . $FDR_ORDER;
            $THUMB_DIR = $_SERVER["DOCUMENT_ROOT"] . '/' . $FDR_ORDER . 'thumb/';

            if (is_file($ORDER_DIR . $filename)) {

                // tạo thumb trước khi xóa, điều kiện file là ảnh
                if (stringIsImage($filename) && !is_file($THUMB_DIR . $filename)) {

                    $url_file = url_image($filename, $FDR_ORDER);

                    copy_image_to_thumb($url_file, $FDR_ORDER . 'thumb', THUMB_WIDTH, THUMB_HEIGHT);
                }

                // xóa file
                unlink($ORDER_DIR . $filename);
            }

            $rm[] = $row['id_discuss'];
        }

        // cập nhật thời gian xóa
        if (count($rm)) {
            $this->Backup_model->discuss_update_bak_date_time($rm);
        }
    }
}
