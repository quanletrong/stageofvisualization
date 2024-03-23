<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('service/Service_model');
        $this->load->model('style/Style_model');
        $this->load->model('setting/Setting_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Home',
            'active_link' => 'home',
            'header_page_css_js' => 'home'
        ];

        $service = $this->Service_model->get_list(1);
        $style = $this->Style_model->get_list(1);

        $setting = $this->Setting_model->get_setting();
        //full path ảnh slide
        $home_slide = json_decode($setting['home_slide'], true);
        foreach ($home_slide as $id => $it) {
            $home_slide[$id]['image'] = url_image($it['image'], FOLDER_SLIDE);
        }
        $setting['home_slide'] = $home_slide;

        //full path ảnh đối tác
        $partner = json_decode($setting['partner'], true);
        foreach ($partner['images'] as $id => $it) {
            $partner['images'][$id]['image'] = url_image($it['image'], FOLDER_PARTNER);
        }
        // path happy_guaranteed
        $happy_guaranteed = json_decode($setting['happy_guaranteed'], true);
        $happy_guaranteed['image_path'] = url_image(@$happy_guaranteed['image'], FOLDER_LOGO);;


        $data['service'] = $service;
        $data['style'] = $style;
        $data['setting'] = $setting;
        $data['partner'] = $partner;
        $data['happy_guaranteed'] = $happy_guaranteed;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'home/home_view', $data);

        $this->_loadFooter();
    }

    function hiw()
    {
        $data = [];

        $setting = $this->Setting_model->get_setting();

        $data['uid'] = $this->_session_uid();
        $data['role'] = $this->_session_role();
        $data['html_hiw'] = $setting['hiw'];

        $header = [
            'title' => 'How it works',
            'active_link' => 'hiw',
            'header_page_css_js' => 'home'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'home/hiw_view', $data);
        $this->_loadFooter();
    }

    function ajax_save_hiw()
    {

        $uid = $this->_session_uid();
        $role = $this->_session_role();

        $role == ADMIN ? '' : resError('Bạn không có quyền truy cập');

        $html_hiw = $this->input->post('html_hiw', false);

        # validate .edit_text
        $doc = new DOMDocument();
        $doc->loadHTML($html_hiw);
        $divs = $doc->getElementsByTagName('div');
        foreach ($divs as $index => $div) {
            if (strstr($div->getAttribute('class'), 'edit_text') == true) {
                $div->nodeValue = $div->textContent;
            }
        }

        $html_hiw = $doc->saveHTML();

        # validate .edit_image
        preg_match_all('/<img[^>]*src=([\'"])(?<src>.+?)\1[^>]*>/i', $html_hiw, $list_img_tmp);
        foreach (array_pop($list_img_tmp) as $img_tmp) {
            $copy = copy_image_to_public_upload($img_tmp, FOLDER_HIW);
            $img_link = $copy['status'] ? ROOT_DOMAIN . FOLDER_HIW . $copy['basename'] : '';
            $html_hiw = str_replace($img_tmp, $img_link, $html_hiw);

            $basename = basename($img_tmp);
            @unlink($_SERVER["DOCUMENT_ROOT"] . '/' . TMP_UPLOAD_PATH . $basename);
        }
        # end

        $this->Setting_model->update_hiw($html_hiw);

        resSuccess('Thành công');
    }
}
