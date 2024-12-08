<?php

/**
 * Mục tiêu: Xóa những file tồn tại trong thư mục đơn nhưng không tồn tại trong db
 */

// Thoát chương trình nếu 'uploads/chat_tong' không tồn tại
$DIR_CHAT_TONG = $_SERVER['DOCUMENT_ROOT'] . '/' . FOLDER_CHAT_TONG;
!is_dir($DIR_CHAT_TONG) ? exit() : '';

// Tìm các file trong thư mục đơn
$files = [];
foreach (scandir($DIR_CHAT_TONG) as $file) {
  if ($file !== '.' && $file !== '..' && is_file($DIR_CHAT_TONG . '/' . $file)) {
    $files[] = $file;
  }
}

// nếu file là ảnh thi copy to thumb
foreach ($files as $file) {
  if (stringIsImage($file) && !is_file(FOLDER_CHAT_TONG_THUMB . $file)) {
    $url_file = url_image($file, FOLDER_CHAT_TONG);
    copy_image_to_thumb($url_file, FOLDER_CHAT_TONG_THUMB, THUMB_WIDTH, THUMB_HEIGHT);
  }
}

// unlink file
// foreach ($files as $file) {
//   @unlink($dir . '/' . $file);
// }
