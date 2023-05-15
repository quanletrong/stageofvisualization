<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service extends MY_Controller
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
        $this->load->model('service/Service_model');
    }

    function index()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $header = [
            'title' => 'Quản lý dịch vụ',
            'header_page_css_js' => 'service'
        ];

        // SUBMIT FORM (nếu có)
        if (isset($_POST['action'])) {
            $name         = $this->input->post('name');
            $sapo         = $this->input->post('sapo');
            $price        = $this->input->post('price');
            $status       = $this->input->post('status');
            $image_before = $this->input->post('image_before');
            $image_after  = $this->input->post('image_after');
            $id_service   = $this->input->post('id_service');
            $id_service   = is_numeric($id_service) && $id_service > 0 ? $id_service : 0;
            $create_time = date('Y-m-d H:i:s');


            // TẠO MỚI 
            if ($_POST['action'] == 'add') {

                // TODO: validate dữ liệu submit
                //END validate

                $copy_before = copy_image_from_file_manager_to_public_upload($image_before, date('Y'), date('m'));
                $copy_after = copy_image_from_file_manager_to_public_upload($image_after, date('Y'), date('m'));
                if ($copy_before['status'] && $copy_after['status']) {
                    $exc = $this->Service_model->add($name, $sapo, $copy_before['basename'], $copy_after['basename'], $price, $status, $this->_session_uid(), $create_time);
                    $msg = $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!';
                } else {
                    $msg = isset($copy_before['error']) ? $copy_before['error'] : $image_after['error'];
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('service');
            }

            // CẬP NHẬT
            if ($_POST['action'] == 'edit') {

                // TODO: validate dữ liệu submit
                //END validate

                $info   = $this->Service_model->get_info($id_service);
                if (empty($info)) {
                    $msg = 'Lưu không thành công vui lòng thử lại!';
                } else {

                    $year   = date('Y', strtotime($info['create_time']));
                    $monthe = date('m', strtotime($info['create_time']));
                    $image_before_ok = $info['image_before'];
                    $image_after_ok = $info['image_after'];
                    $update_time = date('Y-m-d H:i:s');

                    // copy anh truoc nếu upload mới
                    if (basename($image_before) != $info['image_before']) {
                        $copy_before = copy_image_from_file_manager_to_public_upload($image_before, $year, $monthe);
                        if ($copy_before['status']) {
                            $image_before_ok = $copy_before['basename'];
                        } else {
                            $this->session->set_flashdata('flsh_msg', $copy_before['error']);
                            redirect('service');
                        }
                    }

                    // copy anh sau nếu upload mới
                    if (basename($image_after) != $info['image_after']) {
                        $copy_after = copy_image_from_file_manager_to_public_upload($image_after, $year, $monthe);
                        if ($copy_after['status']) {
                            $image_after_ok = $copy_after['basename'];
                        } else {
                            $this->session->set_flashdata('flsh_msg', $copy_after['error']);
                            redirect('service');
                        }
                    }

                    $exc = $this->Service_model->edit($name, $sapo, $price, $image_before_ok, $image_after_ok, $status, $update_time, $id_service);
                    $this->session->set_flashdata('flsh_msg', $exc ? 'OK' : 'Lưu không thành công vui lòng thử lại!');
                    redirect('service');
                }
                $this->session->set_flashdata('flsh_msg', $msg);
                redirect('service');
            }
        }

        $list =  $this->Service_model->get_list();
        $data['list'] = $list;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'service/service_view', $data);
        $this->_loadFooter();
    }
}
