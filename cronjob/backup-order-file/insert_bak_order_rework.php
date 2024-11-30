<?php

// Lấy token từ header
// $headers = getallheaders();
// $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

// // Kiểm tra token hợp lệ
// if ($token !== '123') {
//   // Token không hợp lệ, trả về lỗi
//   header('HTTP/1.1 401 Unauthorized');
//   echo "Invalid token.";
//   exit;
// }


// Kết nối đến database
$host = '82.180.152.103'; // Tên host
$dbname = 'u966959669_virtualstage'; // Tên database
$username = 'u966959669_virtualstage'; // Tên người dùng
$password = 'Ay$8&98[gG7'; // Mật khẩu

$conn = mysqli_connect($host, $username, $password, $dbname);
// Kiểm tra kết nối
if (!$conn) {
  die("Kết nối thất bại: " . mysqli_connect_error());
}

// Truy vấn SQL
$sql = "
  SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_job, b.id_job_rework, b.attach, b.file_complete
  FROM tbl_order as a
  INNER JOIN tbl_job_rework as b ON a.id_order = b.id_order
  INNER JOIN tbl_user as c ON c.id_user = a.id_user
  WHERE 
    1 = 1
    AND a.status = 9
    AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
  ORDER BY a.id_order;
";

// Thực thi truy vấn
$result = mysqli_query($conn, $sql);

// Kiểm tra kết quả
if (!$result) {
  die("Truy vấn thất bại: " . mysqli_error($conn));
}

// Hiển thị dữ liệu
$type_main     = 1;
$type_ref      = 2;
$type_complete = 3;
$type_rework   = 4;
$type_discuss  = 5;
$insert_values = [];
while ($row = mysqli_fetch_assoc($result)) {

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
      $insert_values[] = "($id_order, '$create_time', '$username', $id_job, $id_rework, '$file', $type_rework)";
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

  $query_insert = "INSERT IGNORE INTO tbl_bak_order (id_order, order_create_time, order_create_by, id_job, id_rework, filename, file_type) VALUES $str_values";

  // Thực thi truy vấn
  $insert = mysqli_query($conn, $query_insert);

  // Kiểm tra kết quả
  if (!$insert) {
    die("INSERT thất bại: " . mysqli_error($conn));
  }
}

// Đóng kết nối
mysqli_free_result($result);
mysqli_close($conn);
