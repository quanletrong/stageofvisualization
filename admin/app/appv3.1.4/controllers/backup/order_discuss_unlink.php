<?php
set_time_limit(3600);

$order_discuss_file_list  = $this->Backup_model->order_discuss_file_list();

// Lưu danh sách discuss đã xóa vào đây.
$rm = [];

// Duyệt qua toàn bộ discuss để xóa
foreach ($order_discuss_file_list as $row) {

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
  $this->Backup_model->order_discuss_file__bak_date_time__update($rm);
}
