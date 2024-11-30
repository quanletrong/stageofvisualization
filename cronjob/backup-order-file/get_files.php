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

// Xác định tháng cụ thể (ở đây là tháng 3)
$month = 3;

// Truy vấn SQL
$sql = "
    SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_discuss, b.file
    FROM tbl_order as a
    INNER JOIN tbl_order_discuss as b ON a.id_order = b.id_order AND b.file != '{}'
    INNER JOIN tbl_user as c ON c.id_user = a.id_user
    WHERE 
        a.status = 9
        AND MONTH(a.create_time) = $month AND YEAR(a.create_time) = YEAR(CURDATE())
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
while ($row = mysqli_fetch_assoc($result)) {

  $id_order =  $row['id_order'];
  $create_time =  $row['create_time'];
  $username =  $row['username'];
  $file_raw =  $row['file'];

  $fldr_order = strtotime($create_time) . '@' . $username;
  $link = 'https://stageofvisualization.com/uploads/order/' . $fldr_order;

  $file_decode = json_decode($file_raw, true);

  // khởi tạo thư mục $id_order lần đầu
  $NAMEORDER = "ORDER_$id_order";
  if (!isset($data_export[$NAMEORDER])) {
    $data_export[$NAMEORDER] = [];
  }

  foreach ($file_decode as $file) {
    $data_export[$NAMEORDER][] = $link . '/' . $file;
  }
}

// Giải phóng bộ nhớ và đóng kết nối
mysqli_free_result($result);
mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($data_export);
exit();
