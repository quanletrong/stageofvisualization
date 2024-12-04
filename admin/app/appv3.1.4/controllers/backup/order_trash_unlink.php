<?php

/**
 * Mục tiêu: Xóa những file tồn tại trong thư mục đơn nhưng không tồn tại trong db
 */

// Thoát chương trình nếu 'uploads/order' không tồn tại
$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_ORDER;
!is_dir($dir) ? exit() : '';

// B1. Gom những file cùng đơn vào 1 nhóm
$files_all  = $this->Backup_model->bak_order_file_list();
$order_files = groupOrderFiles($files_all);

// B2 Lọc file rác và xử lý xóa
foreach ($order_files as $folder => $files_db) {
  $order_dir = $dir . $folder;

  if (is_dir($order_dir)) {
    // Tìm các file trong thư mục đơn
    $files_folder = scanOrder($order_dir);

    // Tìm các file rác trong thư mục đơn. $files_folder - $files_db = file rác
    $files_trash =  array_diff($files_folder, $files_db);

    // Xóa các file rác
    deleteFiles($order_dir, $files_trash);
  }
}

function groupOrderFiles($files_all)
{
  $grouped_files = [];
  foreach ($files_all as $row) {
    $id_file = $row['id'];
    $folder = strtotime($row['order_create_time']) . '@' . $row['order_create_by'];
    $grouped_files[$folder][$id_file] = $row['filename'];
  }
  return $grouped_files;
}

function scanOrder($order_dir)
{
  $order_file_folder = [];
  foreach (scandir($order_dir) as $file) {
    if ($file !== '.' && $file !== '..' && is_file($order_dir . '/' . $file)) {
      $order_file_folder[] = $file;
    }
  }

  return $order_file_folder;
}

function deleteFiles($order_dir, $files)
{
  foreach ($files as $file) {
    @unlink($order_dir . '/' . $file);
    @unlink($order_dir . '/thumb/' . $file);
  }
}
