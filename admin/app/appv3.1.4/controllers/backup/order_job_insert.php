<?php

// Lấy danh sách file của job
$origin_order_job_file_list  = $this->Backup_model->origin_order_job_file_list(ORDER_COMPLETE);

// Hiển thị dữ liệu
$type_main     = FILE_MAIN;
$type_ref      = FILE_REF;
$type_complete = FILE_COMPLETE;

// Duyệt qua danh sach để tạo dữ liệu INSERT
$insert_values = [];
foreach ($origin_order_job_file_list as $row) {

  $id_order    = $row['id_order'];
  $create_time = $row['create_time'];
  $username    = $row['username'];
  $id_job      = $row['id_job'];
  $image       = $row['image'];
  $ref         = $row['attach'];
  $complete    = $row['file_complete'];

  // tạo insert file main
  $insert_values[] = "($id_order, '$create_time', '$username', $id_job, '$image', $type_main)";

  // tạo insert file ref
  $ref_decode = json_decode($ref, true);
  if (is_array($ref_decode)) {
    foreach ($ref_decode as $file) {
      $insert_values[] = "($id_order, '$create_time', '$username', $id_job, '$file', $type_ref)";
    }
  }

  // tạo insert file complete
  $complete_decode = json_decode($complete, true);
  if (is_array($complete_decode)) {
    foreach ($complete_decode as $file) {
      $insert_values[] = "($id_order, '$create_time', '$username', $id_job, '$file', $type_complete)";
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
    $this->Backup_model->bak_order_job_file_insert($str_values);
  }
}
