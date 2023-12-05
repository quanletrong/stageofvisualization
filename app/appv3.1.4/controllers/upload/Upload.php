<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();
    }

    function index()
    {
        // SUBMIT FORM (nếu có)
        if (isset($_FILES['file'])) {
            if (count($_FILES['file']['name'])) {
                $data = [];
                for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

                    
                    $name_file = $_FILES['file']['name'][$i];
                    $name_file = str_replace(" ","", $name_file); // xoa khoang trang trong ten
                    $tmp_name = $_FILES['file']['tmp_name'][$i];
                    $size = $_FILES['file']['size'][$i];
                    $target_dir = $_SERVER["DOCUMENT_ROOT"] . "/uploads/tmp/";
                    $name_file = basename($name_file);
                    $target_file = $target_dir . $name_file;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $data[$i]['status'] = 1;
                    $data[$i]['name'] = $name_file;


                    if (is_file($tmp_name)) {
                    } else {
                        $data[$i]['status'] = 0;
                        $data[$i]['error'][] = 'Sorry, file not exits';
                    }

                    // Check if file already exists
                    if (file_exists($target_file)) {
                        $name_file = generateRandomString(5) . '-' . $name_file;
                        $target_file = $target_dir . $name_file;
                    }

                    // Check file size
                    if ($size > LIMIT_SIZE_IMAGE) {
                        $data[$i]['status'] = 0;
                        $data[$i]['error'][] = 'Sorry, your file is too large, limit 200Mb';
                    }

                    // Allow certain file formats TODO: chuaw giới hạn loại file
                    // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    //     $data[$i]['status'] = 0;
                    //     $data[$i]['error'][] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    // }

                    if ($data[$i]['status']) {
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $link = ROOT_DOMAIN . '/uploads/tmp/' . $name_file;
                            $data[$i]['link'] = $link;
                            $data[$i]['mime'] = $imageFileType;
                        } else {
                            $data[$i]['status'] = 0;
                            $data[$i]['error'][] = 'Sorry, there was an error uploading your file.';
                        }
                    }
                }

                resSuccess($data);
            } else {
                resError('Sorry. File not found.');
            }
        } else {
            resError('Sorry. File not found.');
        }
    }

    function paste()
    {
        // SUBMIT FORM (nếu có)
        if (isset($_FILES['file'])) {
            
            $name_file     = $_FILES['file']['name'];
            $name_file     = str_replace(" ", "", $name_file);  // xoa khoang trang trong ten
            $tmp_name      = $_FILES['file']['tmp_name'];
            $size          = $_FILES['file']['size'];
            $target_dir    = $_SERVER["DOCUMENT_ROOT"] . "/uploads/tmp/";
            $name_file     = basename($name_file);
            $target_file   = $target_dir . $name_file;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $data = [];
            $data['status'] = 1;
            $data['name'] = $name_file;

            if (is_file($tmp_name)) {
            } else {
                $data['status'] = 0;
                $data['error'][] = 'Sorry, file not exits';
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $name_file = generateRandomString(5) . '-' . $name_file;
                $target_file = $target_dir . $name_file;
            }

            // Check file size
            if ($size > LIMIT_SIZE_IMAGE) {
                $data['status'] = 0;
                $data['error'][] = 'Sorry, your file is too large, limit 200Mb';
            }

            // Allow certain file formats TODO: chuaw giới hạn loại file
            // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            //     $data[$i]['status'] = 0;
            //     $data[$i]['error'][] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
            // }

            if ($data['status']) {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $link = ROOT_DOMAIN . '/uploads/tmp/' . $name_file;
                    $data['link'] = $link;
                    $data['mime'] = $imageFileType;
                } else {
                    $data['status'] = 0;
                    $data['error'][] = 'Sorry, there was an error uploading your file.';
                }
            }

            resSuccess($data);
        } else {
            resError('Sorry. File not found.');
        }
    }
}
