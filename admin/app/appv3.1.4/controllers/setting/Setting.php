<?php

 if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        if (!$this->_isLogin()) {
            if ($this->input->is_ajax_request()) {
                resError('unlogin');
                die();
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        $this->load->model('setting/Setting_model');
    }

    function info()
    {
        $data = [];
        $header = [
            'title' => 'Thông tin website',
            'header_page_css_js' => 'setting'
        ];

        if (isset($_POST['phone']) && isset($_POST['logo_ngang']) && isset($_POST['logo_vuong'])) {
            $phone          = removeAllTags($this->input->post('phone'));
            $email          = removeAllTags($this->input->post('email'));
            $address        = removeAllTags($this->input->post('address'));
            $link_facebook  = removeAllTags($this->input->post('link_facebook'));
            $link_youtube   = removeAllTags($this->input->post('link_youtube'));
            $link_instagram = removeAllTags($this->input->post('link_instagram'));
            $link_linkedin  = removeAllTags($this->input->post('link_linkedin'));

            $logo_vuong = removeAllTags($this->input->post('logo_vuong'));
            $logo_ngang = removeAllTags($this->input->post('logo_ngang'));

            if ($phone != '' && $logo_vuong != '' && $logo_ngang != '') {
                $copy_vuong = copy_image_to_public_upload($logo_vuong, FOLDER_LOGO);
                $copy_ngang = copy_image_to_public_upload($logo_ngang, FOLDER_LOGO);

                if ($copy_vuong['status'] && $copy_ngang['status']) {
                    $ngang_ok = $copy_ngang['basename'];
                    $vuong_ok = $copy_vuong['basename'];

                    $this->Setting_model->update_info($phone, $email, $address, $link_facebook, $link_youtube, $link_instagram, $link_linkedin, $ngang_ok, $vuong_ok);
                    $this->session->set_flashdata('flsh_msg', 'OK');
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Không lưu được logo');
                }
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu code 2');
            }

            redirect('setting/info');
        }
        $setting = $this->Setting_model->get_setting();

        // path anh slide
        $setting['logo_ngang_path'] = url_image($setting['logo_ngang'], FOLDER_LOGO);
        $setting['logo_vuong_path'] = url_image($setting['logo_vuong'], FOLDER_LOGO);
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_info_view', $data);
        $this->_loadFooter();
    }

    function home()
    {
        $data = [];
        $header = [
            'title' => 'Trang chủ',
            'header_page_css_js' => 'setting'
        ];

        $setting = $this->Setting_model->get_setting();

        // path anh slide
        $home_slide = json_decode($setting['home_slide'], true);
        foreach ($home_slide as $id => $it) {
            $home_slide[$id]['image'] = url_image($it['image'], FOLDER_SLIDE);
        }
        $setting['home_slide'] = json_encode($home_slide);

        // path anh partner
        $partner = json_decode($setting['partner'], true);
        foreach ($partner['images'] as $id => $it) {
            $partner['images'][$id]['image'] = url_image($it['image'], FOLDER_PARTNER);
        }
        $setting['partner'] = json_encode($partner);

        // path happy_guaranteed
        $happy_guaranteed = json_decode($setting['happy_guaranteed'], true);
        $happy_guaranteed['image_path'] = url_image(@$happy_guaranteed['image'], FOLDER_LOGO);
        $data['happy_guaranteed'] = $happy_guaranteed;

        $data['setting'] = $setting;



        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/home/setting_home_view', $data);
        $this->_loadFooter();
    }

    function submit_home($action)
    {        
        // update slide
        if ($action == 'slide') {
            $str_slide = $this->input->post('slide');
            $arr_slide = json_decode($str_slide, true);
            if (empty($arr_slide)) {
                $this->session->set_flashdata('flsh_msg', 'Có lỗi xảy ra!');
            } else {
                $ok_slide = [];
                foreach ($arr_slide as $id => $slide) {
                    $copy = copy_image_to_public_upload($slide['image'], FOLDER_SLIDE);
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

            if ($content_why_virtually_stage != '') {
                $this->Setting_model->update_home_why_virtually($content_why_virtually_stage);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/home');
        }
        // update why_virtually
        if ($action == 'why_stageofvisualization') {
            $content_why_stageofvisualization = $this->input->post('content_why_stageofvisualization', false);
            $content_why_stageofvisualization = (htmlentities(htmlspecialchars($content_why_stageofvisualization)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if ($content_why_stageofvisualization != '') {
                $this->Setting_model->update_home_why_stageofvisualization($content_why_stageofvisualization);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/home');
        }

        // update asked_question
        if ($action == 'asked_question') {
            $asked_question = removeAllTags($this->input->post('asked_question'));

            if ($asked_question != '') {
                $this->Setting_model->update_home_asked_question($asked_question);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/home');
        }

        // update feedback
        if ($action == 'feedback') {
            $feedback = removeAllTags($this->input->post('feedback'));

            if ($feedback != '') {
                $this->Setting_model->update_home_feedback($feedback);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/home');
        }

        // update happy_guaranteed
        if ($action == 'happy_guaranteed') {
            $title_happy = removeAllTags($this->input->post('title_happy'));
            $sapo_happy = removeAllTags($this->input->post('sapo_happy'));
            $image_happy = removeAllTags($this->input->post('image_happy'));

            $happy_guaranteed['title'] = $title_happy;
            $happy_guaranteed['sapo'] = $sapo_happy;
            $happy_guaranteed['image'] = '';

            if ($image_happy != '' && $title_happy != '' && $sapo_happy != '') {
                $copy = copy_image_to_public_upload($image_happy, FOLDER_LOGO);
                if ($copy['status']) {
                    $happy_guaranteed['image'] = $copy['basename'];
                    $this->Setting_model->update_happy_guaranteed(json_encode($happy_guaranteed, JSON_FORCE_OBJECT));
                    $this->session->set_flashdata('flsh_msg', 'OK');
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Lỗi không lưu được ảnh');
                }
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/home');
        }

        // update partner
        if ($action == 'partner') {
            $str_partner = $this->input->post('partner');
            $arr_partner = json_decode($str_partner, true);
            if (empty($arr_partner) || !isset($arr_partner['title']) || $arr_partner['title'] == '' || empty($arr_partner['images'])) {
                $this->session->set_flashdata('flsh_msg', 'Có lỗi dữ liệu!');
            } else {
                $ok_partner = [];
                $ok_partner['title'] = $arr_partner['title'];
                $ok_partner['sapo'] = $arr_partner['sapo'];
                foreach ($arr_partner['images'] as $id => $partner) {
                    $copy = copy_image_to_public_upload($partner['image'], FOLDER_PARTNER);
                    if ($copy['status']) {
                        $ok_partner['images'][$id]['name'] = removeAllTags($partner['name']);
                        $ok_partner['images'][$id]['image'] = $copy['basename'];

                        unlink($_SERVER["DOCUMENT_ROOT"] . parse_url($partner['image'], PHP_URL_PATH));
                    } else {
                        break;
                    }
                }

                if (empty($ok_partner['images'])) {
                    $this->session->set_flashdata('flsh_msg', 'Có lỗi xảy ra!');
                } else {
                    $this->Setting_model->update_partner(json_encode($ok_partner));
                    $this->session->set_flashdata('flsh_msg', 'OK');
                }
            }
            redirect('setting/home');
        }
    }

    function privacy_policy()
    {
        $data = [];
        $header = [
            'title' => 'Trang chính sách bảo mật',
            'header_page_css_js' => 'setting'
        ];

        if (isset($_POST['privacy_policy'])) {
            $privacy_policy = $this->input->post('privacy_policy', false);
            $privacy_policy = (htmlentities(htmlspecialchars($privacy_policy)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if ($privacy_policy != '') {
                $this->Setting_model->update_privacy_policy($privacy_policy);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/privacy_policy');
        }

        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_privacy_policy_view', $data);
        $this->_loadFooter();
    }

    function refund_policy()
    {
        $data = [];
        $header = [
            'title' => 'Trang chính hoàn tiền',
            'header_page_css_js' => 'setting'
        ];

        if (isset($_POST['refund_policy'])) {
            $refund_policy = $this->input->post('refund_policy', false);
            $refund_policy = (htmlentities(htmlspecialchars($refund_policy)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if ($refund_policy != '') {
                $this->Setting_model->update_refund_policy($refund_policy);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/refund_policy');
        }

        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_refund_policy_view', $data);
        $this->_loadFooter();
    }

    function termsofuse()
    {
        $data = [];
        $header = [
            'title' => 'Trang chính hoàn tiền',
            'header_page_css_js' => 'setting'
        ];

        if (isset($_POST['termsofuse'])) {
            $termsofuse = $this->input->post('termsofuse', false);
            $termsofuse = (htmlentities(htmlspecialchars($termsofuse)));  // render var_dump(html_entity_decode(htmlspecialchars_decode($maps)))

            if ($termsofuse != '') {
                $this->Setting_model->update_termsofuse($termsofuse);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Thiếu dữ liệu');
            }

            redirect('setting/termsofuse');
        }

        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_termsofuse_view', $data);
        $this->_loadFooter();
    }

    function max_order_working()
    {
        $data = [];
        $header = [
            'title' => 'Giới hạn tham gia đơn hàng',
            'header_page_css_js' => 'setting'
        ];

        if (isset($_POST['max_order_working'])) {
            $max_order_working = $this->input->post('max_order_working');

            if (is_numeric($max_order_working) && $max_order_working > 0) {
                $this->Setting_model->update_max_order_working($max_order_working);
                $this->session->set_flashdata('flsh_msg', 'OK');
            } else {
                $this->session->set_flashdata('flsh_msg', 'Dữ liệu không hợp lệ');
            }

            redirect('setting/max_order_working');
        }

        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/max_order_working_view', $data);
        $this->_loadFooter();
    }

    function hiw()
    {
        $data = [];
        $header = [
            'title' => 'Trang how it works',
            'header_page_css_js' => 'setting'
        ];

        $setting = $this->Setting_model->get_setting();
        $data['setting'] = $setting;

        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'setting/setting_hiw_view', $data);
        $this->_loadFooter();
    }

    function ajax_hiw_submit()
    {
        $setting = $this->Setting_model->get_setting();
        $hiw = json_encode($setting['hiw'], true);

        // POST
        $title = removeAllTags($this->input->post('title'));
        $desc = removeAllTags($this->input->post('desc'));

        $step_1_title = removeAllTags($this->input->post('step_1_title'));
        $step_1_desc = removeAllTags($this->input->post('step_1_desc'));
        $step_1_icon = removeAllTags($this->input->post('step_1_icon'));
        $step_1_image = removeAllTags($this->input->post('step_1_image'));

        $step_2_title = removeAllTags($this->input->post('step_2_title'));
        $step_2_desc = removeAllTags($this->input->post('step_2_desc'));
        $step_2_icon = removeAllTags($this->input->post('step_2_icon'));
        $step_2_image = removeAllTags($this->input->post('step_2_image'));

        $step_3_title = removeAllTags($this->input->post('step_3_title'));
        $step_3_desc = removeAllTags($this->input->post('step_3_desc'));
        $step_3_icon = removeAllTags($this->input->post('step_3_icon'));
        $step_3_image = removeAllTags($this->input->post('step_3_image'));

        $revisions_title = removeAllTags($this->input->post('revisions_title'));
        $revisions_desc = removeAllTags($this->input->post('revisions_desc'));

        $listing_title = removeAllTags($this->input->post('listing_title'));
        $listing_desc = removeAllTags($this->input->post('listing_desc'));

        // VALIDATE
        $title != '' ? '' : resError('#title', 'Thiếu dữ liệu');
        $desc  != '' ? '' : resError('#desc', 'Thiếu dữ liệu');

        $step_1_title != '' ? '' : resError('#step_1_title', 'Thiếu dữ liệu');
        $step_1_desc  != '' ? '' : resError('#step_1_desc', 'Thiếu dữ liệu');
        $step_1_icon  != '' ? '' : resError('#step_1_icon', 'Thiếu dữ liệu');
        $step_1_image != '' ? '' : resError('#step_1_image', 'Thiếu dữ liệu');

        $step_2_title != '' ? '' : resError('#step_2_title', 'Thiếu dữ liệu');
        $step_2_desc  != '' ? '' : resError('#step_2_desc', 'Thiếu dữ liệu');
        $step_2_icon  != '' ? '' : resError('#step_2_icon', 'Thiếu dữ liệu');
        $step_2_image != '' ? '' : resError('#step_2_image', 'Thiếu dữ liệu');

        $step_3_title != '' ? '' : resError('#step_3_title', 'Thiếu dữ liệu');
        $step_3_desc  != '' ? '' : resError('#step_3_desc', 'Thiếu dữ liệu');
        $step_3_icon  != '' ? '' : resError('#step_3_icon', 'Thiếu dữ liệu');
        $step_3_image != '' ? '' : resError('#step_3_image', 'Thiếu dữ liệu');

        $revisions_title != '' ? '' : resError('#revisions_title', 'Thiếu dữ liệu');
        $revisions_desc  != '' ? '' : resError('#revisions_desc', 'Thiếu dữ liệu');

        $listing_title != '' ? '' : resError('#listing_title', 'Thiếu dữ liệu');
        $listing_desc  != '' ? '' : resError('#listing_desc', 'Thiếu dữ liệu');

        // lưu image step 1
        if (basename($step_1_icon) != $hiw['step_1_icon']) {
            $copy_attach = copy_image_to_public_upload($step_1_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Icon step 1 không hợp lệ!');
            $hiw['step_1_icon'] = $copy_attach['basename'];
        }

        if (basename($step_1_image) != $hiw['step_1_image']) {
            $copy_attach = copy_image_to_public_upload($step_1_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Image step 1 không hợp lệ!');
            $hiw['step_1_image'] = $copy_attach['basename'];
        }

        // lưu image step 2
        if (basename($step_2_icon) != $hiw['step_2_icon']) {
            $copy_attach = copy_image_to_public_upload($step_2_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Icon step 2 không hợp lệ!');
            $hiw['step_2_icon'] = $copy_attach['basename'];
        }

        if (basename($step_2_image) != $hiw['step_2_image']) {
            $copy_attach = copy_image_to_public_upload($step_2_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Image step 2 không hợp lệ!');
            $hiw['step_2_image'] = $copy_attach['basename'];
        }

        // lưu image step 3
        if (basename($step_3_icon) != $hiw['step_3_icon']) {
            $copy_attach = copy_image_to_public_upload($step_3_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Icon step 3 không hợp lệ!');
            $hiw['step_3_icon'] = $copy_attach['basename'];
        }

        if (basename($step_3_image) != $hiw['step_3_image']) {
            $copy_attach = copy_image_to_public_upload($step_3_icon, FOLDER_HIW);
            $copy_attach['status'] ? '' : resError('Image step 3 không hợp lệ!');
            $hiw['step_3_image'] = $copy_attach['basename'];
        }

        $hiw['title']           = $title;
        $hiw['desc']            = $desc;
        $hiw['step_1_title']    = $step_1_title;
        $hiw['step_1_desc']     = $step_1_desc;
        $hiw['step_2_title']    = $step_2_title;
        $hiw['step_2_desc']     = $step_2_desc;
        $hiw['step_3_title']    = $step_3_title;
        $hiw['step_3_desc']     = $step_3_desc;
        $hiw['revisions_title'] = $revisions_title;
        $hiw['revisions_desc']  = $revisions_desc;
        $hiw['listing_title']   = $listing_title;
        $hiw['listing_desc']    = $listing_desc;

        $hiw_json = json_encode($hiw, JSON_FORCE_OBJECT);

        $this->Setting_model->update_hiw($hiw_json);
        resSuccess('OK');
    }
}
