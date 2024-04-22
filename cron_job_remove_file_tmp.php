<?php

// cron job xoa file tmp 
// schedule: 0h mỗi ngày
// xoa file có thời gian tạo cách thời điểm hiện tại 1 ngày
$dir_tmp = 'uploads/tmp/';
if ($dh = opendir($dir_tmp)) {
    while (($file = readdir($dh)) !== false) {

        // If file
        if (is_file($dir_tmp . $file)) {
            if ($file != '' && $file != '.' && $file != '..' && $file != 'transparent.gif') {

                $filename = $dir_tmp . $file;
                $file_creation_date = filectime($filename);

                if ((time() - $file_creation_date) >  60 * 60 * 24) {
                    unlink($filename);
                }
            }
        }
    }
    closedir($dh);
}
die;
