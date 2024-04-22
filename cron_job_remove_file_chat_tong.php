<?php

// cron job xoa file chat_tong 
// schedule: 0h mỗi tháng
// xoa file có thời gian tạo cách thời điểm hiện tại 30 ngày
$dir_chat_tong = 'uploads/chat_tong/';
if ($dh = opendir($dir_chat_tong)) {
    while (($file = readdir($dh)) !== false) {

        // If file
        if (is_file($dir_chat_tong . $file)) {
            if ($file != '' && $file != '.' && $file != '..' && $file != 'transparent.gif') {

                $filename = $dir_chat_tong . $file;
                $file_creation_date = filectime($filename);

                if ((time() - $file_creation_date) >  60 * 60 * 24 * 30) {
                    unlink($filename);
                }
            }
        }
    }
    closedir($dh);
}