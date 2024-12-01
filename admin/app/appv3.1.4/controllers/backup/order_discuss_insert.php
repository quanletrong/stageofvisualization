<?php

// Thực thi truy vấn
$order_discuss_file_list  = $this->Backup_model->order_discuss_file_list(ORDER_COMPLETE);

// Hiển thị dữ liệu
$type_discuss = FILE_DISCUSS;

// Duyệt qua danh sach để tạo dữ liệu INSERT
$insert_values = [];
foreach ($order_discuss_file_list as $row) {

  $id_order    = $row['id_order'];
  $create_time = $row['create_time'];
  $id_discuss  = $row['id_discuss'];
  $username    = $row['username'];
  $file_raw    = $row['file'];

  // tạo insert file
  $file_decode = json_decode($file_raw, true);
  if (is_array($file_decode)) {
    foreach ($file_decode as $file) {
      $insert_values[] = "($id_order, '$create_time', '$username', '$file', $id_discuss, $type_discuss)";
    }
  }
}

// Kiểm tra mảng dữ liệu insert
if (count($insert_values)) {
  $str_values = implode(',', $insert_values);
  $this->Backup_model->bak_order_discuss_file_insert($str_values);
}
