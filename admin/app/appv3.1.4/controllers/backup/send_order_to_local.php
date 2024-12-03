<?php

// send main, ref, complete
// không send discuss
$bak_send_order_to_local  = $this->Backup_model->bak_send_order_to_local([FILE_MAIN, FILE_REF, FILE_COMPLETE]);

$data_export = [];
foreach ($bak_send_order_to_local as $row) {
  $id_order =  $row['id_order'];
  $create_time = $row['order_create_time'];
  $username =  $row['order_create_by'];
  $filename =  $row['filename'];

  // $link = ROOT_DOMAIN . 'uploads/order/' . strtotime($create_time) . '@' . $username;
  $link = 'https://stageofvisualization.com/uploads/order/' . strtotime($create_time) . '@' . $username;

  // check khởi tạo thư mục
  // $NAMEORDER = "ORDER_$id_order";
  if (!isset($data_export[$id_order])) {
    $data_export[$id_order] = [];
  }

  $data_export[$id_order][] = $link . '/' . $filename;
}

header('Content-Type: application/json');
echo json_encode($data_export);
exit();
