<?php

set_time_limit(3600); // 1h

// Create ZIP file
$zip = new ZipArchive();
$filename = time() . ".zip";

if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
    exit("cannot open <$filename>\n");
}

$dir = 'uploads/order/';

// Create zip
createZip($zip, $dir);

$zip->close();

// Create zip
function createZip($zip, $dir)
{
    if (is_dir($dir)) {

        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                // If file
                if (is_file($dir . $file)) {
                    if ($file != '' && $file != '.' && $file != '..') {

                        $okAddFile = $zip->addFile($dir . $file);
                        if ($okAddFile !== true) {
                            echo 'addFile error: ' . $dir . $file;
                            die;
                        }
                    }
                } else {
                    // If directory
                    if (is_dir($dir . $file)) {

                        if ($file != '' && $file != '.' && $file != '..') {

                            // Add empty directory
                            $okAddEmptyDir = $zip->addEmptyDir($dir . $file);
                            if ($okAddEmptyDir !== true) {
                                echo 'addEmptyDir error: ' . $dir . $file;
                                die;
                            }

                            $folder = $dir . $file . '/';

                            // Read data of the folder
                            createZip($zip, $folder);
                        }
                    }
                }
            }
            closedir($dh);
        }
    }
}

// $filename = "myzipfile.zip";


// $file = $filename;
// if (headers_sent()) {
//     echo 'HTTP header already sent';
// } else {
//     if (!is_file($file)) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
//         echo 'File not found';
//     } else if (!is_readable($file)) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
//         echo 'File not readable';
//     } else {
//         header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
//         header("Content-Type: application/zip");
//         header("Content-Transfer-Encoding: Binary");
//         header("Content-Length: " . filesize($file));
//         header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
//         readfile($file);

//         unlink($filename);
//         exit;
//     }
// }


file_put_contents($filename, fopen('http://stageofvisualization.local/'.$filename, 'r'));
unlink($filename);
exit;


// $filename = "myzipfile.zip";
// if (file_exists($filename)) {
//     header('Content-Type: application/zip');
//     header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
//     header('Content-Length: ' . filesize($filename));

//     flush();
//     readfile($filename);
//     // delete file
//     unlink($filename);
// }
