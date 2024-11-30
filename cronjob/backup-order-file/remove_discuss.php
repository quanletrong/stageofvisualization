<?php

// Lấy token từ header
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

// Kiểm tra token hợp lệ
if ($token !== '123') {
  // Token không hợp lệ, trả về lỗi
  header('HTTP/1.1 401 Unauthorized');
  echo "Invalid token.";
  exit;
}

// Kiểm tra nếu có tham số 'file' trong URL
if (isset($_GET['file'])) {
  $file = $_GET['file'];

  // Đường dẫn đầy đủ đến file cần xóa (file nằm ở thư mục gốc của website)
  $filePath = $_SERVER['DOCUMENT_ROOT'] . '/cronjob/backup-order-file/' . $file; // Sử dụng DOCUMENT_ROOT để tham chiếu tới thư mục gốc của website

  // Kiểm tra xem file có tồn tại và có thể xóa được không
  if (file_exists($filePath)) {
    // Cố gắng xóa file
    if (unlink($filePath)) {
      // Trả về mã thành công
      echo "File '$file' has been deleted successfully.";
    } else {
      // Lỗi khi xóa file
      header('HTTP/1.1 500 Internal Server Error');
      echo "Failed to delete the file '$file'.";
    }
  } else {
    // Nếu file không tồn tại
    header('HTTP/1.1 404 Not Found');
    echo "File '$file' does not exist.";
  }
} else {
  // Nếu không có tham số 'file'
  header('HTTP/1.1 400 Bad Request');
  echo "No file specified for removal.";
}
