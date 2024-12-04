<?php

// Lấy token từ header
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

// Kiểm tra token hợp lệ
if ($token !== TOKEN_BACKUP) {
  header('HTTP/1.1 401 Unauthorized');
  echo "Invalid token.";
  exit;
}

// 💬 update thời gian download cho file
// $id_order = removeAllTags($this->input->post_get('order'));
// $filename = removeAllTags($this->input->post_get('filename'));

// $this->Backup_model->bak_order__download_time__update_v1($id_order, $filename);

// header('Content-Type: application/json');
// echo json_encode([$id_order, $filename]);
// exit();


// 💬 update thời gian download cho đơn
$id_order = removeAllTags($this->input->post_get('order'));
$this->Backup_model->bak_order__download_time__update($id_order);

header('Content-Type: application/json');
echo json_encode([$id_order]);
exit();
