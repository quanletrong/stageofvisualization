<?php

// Thực thi truy vấn
$order_rework_file_list  = $this->Backup_model->order_rework_file_list(ORDER_COMPLETE);

// Hiển thị dữ liệu
$type_ref = FILE_REF;
$type_complete = FILE_COMPLETE;

// Duyệt qua danh sach để tạo dữ liệu INSERT
$insert_values = [];
foreach ($order_rework_file_list as $row) {
  $id_order    = $row['id_order'];
  $create_time = $row['create_time'];
  $username    = $row['username'];
  $id_job      = $row['id_job'];
  $id_rework   = $row['id_job_rework'];
  $ref         = $row['attach'];
  $complete    = $row['file_complete'];

  // tạo insert file ref
  $ref_decode = json_decode($ref, true);
  if (is_array($ref_decode)) {
    foreach ($ref_decode as $file) {
      $insert_values[] = "($id_order, '$create_time', '$username', $id_job, $id_rework, '$file', $type_ref)";
    }
  }

  // tạo insert file complete
  $complete_decode = json_decode($complete, true);
  if (is_array($complete_decode)) {
    foreach ($complete_decode as $file) {
      $insert_values[] = "($id_order, '$create_time', '$username', $id_job, $id_rework, '$file', $type_complete)";
    }
  }
}

// Kiểm tra mảng dữ liệu insert
if (count($insert_values)) {
  $str_values = implode(',', $insert_values);
  $this->Backup_model->bak_order_rework_file_insert($str_values);
}
