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
    SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_discuss, b.file
    FROM tbl_order as a
    INNER JOIN tbl_order_discuss as b ON a.id_order = b.id_order AND b.file != '{}'
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
$data_export = [];
$type_discuss = 5;
$insert_values = [];
while ($row = mysqli_fetch_assoc($result)) {

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

  $str_values = implode(',', $insert_values);

  $query_insert = "INSERT IGNORE INTO tbl_bak_order (id_order, order_create_time, order_create_by, filename, id_discuss, file_type) VALUES $str_values";
  
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
