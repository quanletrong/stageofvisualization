<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Style extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        if (!$this->_isLogin()) {
            if ($this->input->is_ajax_request()) {
                echo 'unlogin';
                die();
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        // model
        $this->load->model('style/Style_model');
    }

    function index()
    {
        $data = [];
        $header = [
            'title' => 'Quản lý phong cách thiết kế',
            'header_page_css_js' => 'style'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $name        = $this->input->post('name');
            $sapo        = $this->input->post('sapo');
            $status      = $this->input->post('status');
            $image       = $this->input->post('image');
            $slide        = $this->input->post('slide');
            $id_style  = $this->input->post('id_style');
            $id_style  = is_numeric($id_style) && $id_style > 0 ? $id_style : 0;
            $create_time = date('Y-m-d H:i:s');

            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                // copy and validate slide
                $arr_slide = json_decode($slide, true);
                $slide_ok = [];
                foreach ($arr_slide as $id => $it) {
                    $img_slide = $it['image'];
                    $copy = copy_image_to_public_upload($img_slide, FOLDER_STYLE);
                    if ($copy['status']) {
                        $slide_ok[$id]['name'] = $it['name'];
                        $slide_ok[$id]['image'] = $copy['basename'];
                    }
                }
                // end copy and validate room

                $copy = copy_image_to_public_upload($image, FOLDER_STYLE);
                if ($copy['status']) {
                    $exc = $this->Style_model->add($name, $sapo, $copy['basename'], json_encode($slide_ok, JSON_FORCE_OBJECT), $status, $this->_session_uid(), $create_time);
                    $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                } else {
                    $msg = $copy['error'];
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('style');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                // TODO: validate dữ liệu submit
                $status = $status == 'on' ? 1 : 0;
                //END validate

                $info   = $this->Style_model->get_info($id_style);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    $image_ok = $info['image'];
                    $update_time = date('Y-m-d H:i:s');

                    // copy anh truoc nếu upload mới
                    if (basename($image) != $info['image']) {
                        $copy = copy_image_to_public_upload($image, FOLDER_STYLE);
                        if ($copy['status']) {
                            $image_ok = $copy['basename'];
                        } else {
                            $this->session->set_flashdata('flsh_msg', $copy['error']);
                            redirect('style');
                        }
                    }

                    // copy and validate slide
                    $slide_old = json_decode($info['slide'], true);
                    $slide_ok = [];
                    $arr_slide = json_decode($slide, true);
                    foreach ($arr_slide as $id => $it) {

                        // slide cũ
                        if (isset($slide_old[$id])) {
                            $slide_ok[$id]['name'] = $it['name'];

                            // slide cũ thay đổi ảnh
                            $img_slide = $it['image'];
                            if (strpos($it['image'], 'uploads/tmp') !== false) {
                                $copy = copy_image_to_public_upload($img_slide, FOLDER_STYLE);
                                if ($copy['status']) {
                                    $slide_ok[$id]['image'] = $copy['basename'];
                                }
                            } else {
                                $slide_ok[$id]['image'] = $slide_old[$id]['image'];
                            }
                        }
                        // thêm slide mới
                        else {
                            $img_slide = $it['image'];
                            $copy = copy_image_to_public_upload($img_slide, FOLDER_STYLE);
                            if ($copy['status']) {
                                $slide_ok[$id]['name'] = $it['name'];
                                $slide_ok[$id]['image'] = $copy['basename'];
                            }
                        }
                    }
                    // end copy and validate slide

                    $exc = $this->Style_model->edit($name, $sapo, $image_ok, json_encode($slide_ok, JSON_FORCE_OBJECT), $status, $update_time, $id_style);
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('style');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('style');
            }
        }

        $list =  $this->Style_model->get_list();
        $data['list'] = $list;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'style/style_view', $data);
        $this->_loadFooter();
    }
}
