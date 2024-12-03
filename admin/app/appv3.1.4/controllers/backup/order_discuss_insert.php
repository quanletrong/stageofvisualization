<?php

// Thực thi truy vấn
$origin_order_discuss_file_list  = $this->Backup_model->origin_order_discuss_file_list(ORDER_COMPLETE);

// Hiển thị dữ liệu
$type_discuss = FILE_DISCUSS;

// Duyệt qua danh sach để tạo dữ liệu INSERT
$insert_values = [];
foreach ($origin_order_discuss_file_list as $row) {

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

  // Mỗi lần insert 1000 mảng, để tránh lỗi sql vượt quá số ký tự
  $insert_values_chunk = array_chunk($insert_values, 1000);

  // Duyệt qua danh sách mảng chunk để insert
  foreach ($insert_values_chunk as $values) {
    $str_values = implode(',', $values);
    $this->Backup_model->bak_order_discuss_file_insert($str_values);
  }
}
