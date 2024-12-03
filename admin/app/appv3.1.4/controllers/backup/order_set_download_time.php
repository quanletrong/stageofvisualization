<?php

// $id_order = removeAllTags($this->input->post_get('order'));
// $filename = removeAllTags($this->input->post_get('filename'));

// $this->Backup_model->bak_order__download_time__update_v1($id_order, $filename);

// header('Content-Type: application/json');
// echo json_encode([$id_order, $filename]);
// exit();





$id_order = removeAllTags($this->input->post_get('order'));

$this->Backup_model->bak_order__download_time__update($id_order);

header('Content-Type: application/json');
echo json_encode([$id_order, $filename]);
exit();