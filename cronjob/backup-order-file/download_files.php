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

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$files = [
    // "$DOCUMENT_ROOT/uploads/order/1697866506@KHACH_DEV_01/KRYPv-image.png"
    "$DOCUMENT_ROOT/uploads/order/1710411188@SALE_CHINH//thumb/q7pGw-IM.jpg"
];

function zip_files($files)
{
    // Create ZIP file
    $zip = new ZipArchive();
    $filename = "attach_files_" . $_GET['file'] . "_" . time() . ".zip";

    if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
        exit("cannot open <$filename>\n");
    }

    // Create zip
    foreach ($files as $file) {
        if (is_file($file)) {
            $new_filename = substr($file, strrpos($file, '/') + 1);
            $zip->addFile($file, $new_filename);
        }
    }
    $zip->close();

    if (headers_sent()) {
        echo 'HTTP header already sent';
    } else {
        if (!is_file($filename)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo 'File not found';
        } else if (!is_readable($filename)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
            echo 'File not readable';
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length: " . filesize($filename));
            header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
            readfile($filename);

            // unlink($filename);
            exit;
        }
    }
}

zip_files($files);
