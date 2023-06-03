<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller
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

        $this->load->model('setting/Setting_model');
    }

    function home()
    {
        $data = [];
        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }
        $header = [
            'title' => 'Trang chủ',
            'header_page_css_js' => 'setting'
        ];

        $setting = $this->Setting_model->get_setting();
        $home_slide = json_decode($setting['home_slide'], true);
        foreach ($home_slide as $id => $it) {
            $home_slide[$id]['image'] = ROOT_DOMAIN . PUBLIC_UPLOAD_PATH . SLIDE_FOLDER . '/' . $it['image'];
        }
        $setting['home_slide'] = json_encode($home_slide);
        $data['setting'] = $setting;


        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_home_view', $data);
        $this->_loadFooter();
    }

    function submit_home($action)
    {

        if ($this->_session_role() != ADMIN) {
            show_custom_error('Tài khoản không có quyền truy cập!');
        }

        // update slide
        if ($action == 'slide') {
            $str_slide = $this->input->post('slide');
            $arr_slide = json_decode($str_slide, true);
            if (empty($arr_slide)) {
                $this->session->set_flashdata('flsh_msg', 'Có lỗi xảy ra!');
            } else {
                $ok_slide = [];
                foreach ($arr_slide as $id => $slide) {
                    $copy = copy_image_to_public_upload($slide['image'], SLIDE_FOLDER);
                    if ($copy['status']) {
                        $ok_slide[$id]['name'] = removeAllTags($slide['name']);
                        $ok_slide[$id]['image'] = $copy['basename'];

                        unlink($_SERVER["DOCUMENT_ROOT"] . parse_url($slide['image'], PHP_URL_PATH));
                    } else {
                        break;
                    }
                }

                if (empty($ok_slide)) {
                    $this->session->set_flashdata('flsh_msg', 'Có lỗi xảy ra!');
                } else {
                    $this->Setting_model->update_home_slide(json_encode($ok_slide));
                    $this->session->set_flashdata('flsh_msg', 'OK');
                }
            }
            redirect('setting/home');
        }

        // update why_virtually_stage
        if ($action == 'why_virtually_stage') {
            $content_why_virtually_stage = $this->input->post('content_why_virtually_stage', false);
            $content_why_virtually_stage = (htmlentities(htmlspecialchars($content_why_virtually_stage)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if($content_why_virtually_stage != '') {
                $this->Setting_model->update_home_why_virtually($content_why_virtually_stage);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Không được bỏ trống');
            }
           
            redirect('setting/home');
        }
        // update why_virtually
        if ($action == 'why_stageofvisualization') {
            $content_why_stageofvisualization = $this->input->post('content_why_stageofvisualization', false);
            $content_why_stageofvisualization = (htmlentities(htmlspecialchars($content_why_stageofvisualization)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if($content_why_stageofvisualization != '') {
                $this->Setting_model->update_home_why_stageofvisualization($content_why_stageofvisualization);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Không được bỏ trống');
            }
           
            redirect('setting/home');
        }

        // update asked_question
        if ($action == 'asked_question') {
            $asked_question = removeAllTags($this->input->post('asked_question'));

            if($asked_question != '') {
                $this->Setting_model->update_home_asked_question($asked_question);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Không được bỏ trống');
            }
           
            redirect('setting/home');
        }

         // update feedback
         if ($action == 'feedback') {
            $feedback = removeAllTags($this->input->post('feedback'));

            if($feedback != '') {
                $this->Setting_model->update_home_feedback($feedback);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Không được bỏ trống');
            }
           
            redirect('setting/home');
        }
    }
}
