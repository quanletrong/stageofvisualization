<?php

// 1710411380@SALE_CHINH

// Lấy tất cả file unlink_time = NULL
$order_file_list  = $this->Backup_model->bak_order_file_list();

$order_file_group = []; // Lưu danh sách file của từng order

// Duyệt qua toàn bộ discuss để xóa
foreach ($order_file_list as $row) {
  $id_order = $row['id_order'];
  $filename = $row['filename'];

  $FDR_ORDER = strtotime($row['order_create_time']) . '@' . $row['order_create_by'];

  $order_file_group[$FDR_ORDER][] =  $filename;
}

$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_ORDER;
if (is_dir($dir)) {
  foreach ($order_file_group as $folder => $order_file_db) {

    $order_dir = $dir . $folder;

    if (is_dir($order_dir)) {

      $order_file_folder = [];

      // Duyệt qua danh sách và chỉ lấy các file (không lấy thư mục con)
      foreach (scandir($order_dir) as $file) {
        // Kiểm tra nếu là file và không phải thư mục
        if ($file !== '.' && $file !== '..' && is_file($order_dir . '/' . $file)) {
          $order_file_folder[] = $file;
        }
      }

      // Lấy các file có trong thư mục nhưng không có trong cơ sở dữ liệu
      $order_file_unlink = array_diff($order_file_folder, $order_file_db);

      // Duyệt qua danh sách $order_file_unlink để xóa file
      foreach ($order_file_unlink as $file) {

        @unlink($order_dir . '/' . $file); // xóa file
        @unlink($order_dir . '/thumb/' . $file); // xóa cả file thumb
      }
    }
  }
}
