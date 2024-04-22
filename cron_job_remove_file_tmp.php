<?php

$dir = 'uploads/tmp/';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {

        // If file
        if (is_file($dir . $file)) {
            if ($file != '' && $file != '.' && $file != '..' && $file != 'transparent.gif') {

                $filename = $dir . $file;
                $file_creation_date = filectime($filename);

                if ((time() - $file_creation_date) >  60 * 60 * 24 * 5) {
                    unlink($filename);
                }
            }
        }
    }
    closedir($dh);
}
die;
