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
                    $name_file = generateRandomString(5) . '-' . basename($name_file);
                    $target_file = $target_dir . $name_file;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $data[$i]['status'] = 1;
                    $data[$i]['name'] = $name_file;


                    $check = @getimagesize($tmp_name);
                    if ($check !== false) {
                    } else {
                        $data[$i]['status'] = 0;
                        $data[$i]['error'][] = 'File is not an image.';
                    }

                    // Check if file already exists
                    if (file_exists($target_file)) {
                        $name_file = generateRandomString(5) . '-' . $name_file;
                        $target_file = $target_dir . $name_file;
                    }

                    // Check file size
                    if ($size > 5000000) {
                        $data[$i]['status'] = 0;
                        $data[$i]['error'][] = 'Sorry, your file is too large, limit 50Mb';
                    }

                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        $data[$i]['status'] = 0;
                        $data[$i]['error'][] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    }

                    if ($data[$i]['status']) {
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $link = ROOT_DOMAIN . '/uploads/tmp/' . $name_file;
                            $data[$i]['link'] = $link;
                        } else {
                            $data[$i]['status'] = 0;
                            $data[$i]['error'][] = 'Sorry, there was an error uploading your file.';
                        }
                    }
                }

                resSuccess($data);

            } else {
                resError('Xin lỗi, không tìm thấy ảnh.');
            }
        }
    }
}
