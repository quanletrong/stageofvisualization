<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller
{
    protected $_numShowItem = 30;
    protected $_template_f = '';
    protected $_langcode = '';
    protected $_langcode_id = '';
    protected $_languri = '';
    protected $_module = '';
    protected $_function = '';
    protected $_product_name = '';
    protected $_product_key = '';

    // list banner type: CPC, CPM
    protected $_bantype = NULL;

    // check login for module
    protected $_check_login = FALSE;
    // list function of module do not check login
    protected $_lstUnCheckLoginFunc = NULL;

    // common lang
    protected $_common_lang = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->_langcode = get_langcode();
        $this->_langcode_id = get_langcode_id($this->_langcode);

        // check su dung giao dien moi hay ko
        $this->_template_f = $this->config->item('template_f');

        $this->_product_name = $this->config->item('product_name');
        $this->_product_key = $this->config->item('product_key');
        $this->_bantype = $this->config->item('bantype');
        $this->load->language('common', $this->_langcode);
        $this->_common_lang = $this->lang->line('common_lang');
        $this->_base_url = $this->config->item('base_url');

        $this->_validUrl();

        // kiem tra tai khoan co quyen truy cap vao module hay không
        $this->_permission_role();

        // init and assign common value to view: language, common lang
        $preHeader = array();

        $preHeader['base_url']     = $this->_base_url;
        $preHeader['common_lang']  = $this->_common_lang;
        $preHeader['langcode']     = $this->_langcode;
        $preHeader['langcodeid']   = $this->_langcode_id;
        $preHeader['template_f']   = $this->_template_f;
        $preHeader['username']     = '';
        $preHeader['userid']       = 0;
        $preHeader['role']         = 0;
        $preHeader['langcode_url'] = '';
        $preHeader['module_name']  = $this->_module;
        $preHeader['product_name'] = $this->_product_name;

        // set lang code for multi language url
        $arrLang = $this->config->item('lang_uri_abbr');
        $langcodeUrl = '';
        if (MULTI_LANGUAGE) {
            foreach ($arrLang as $key => $langItem) {
                if ($this->_langcode == $langItem) {
                    $langcodeUrl = $key;
                    break;
                }
            }
        }

        $preHeader['langcode_url'] = $langcodeUrl;

        if ($this->_islogin()) {
            $preHeader['isLogin'] = true;
            $preHeader['username'] = $this->_session_uname();
            $preHeader['userid'] = $this->_session_uid();
            $preHeader['role'] = $this->_session_role();
        }

        // assign all common param to view
        $this->load->view($this->_template_f . 'preheader_view', $preHeader);
    }

    protected function _loadHeader($data = NULL, $menuTab = 0, $subMenuTab = 0, $loadHeader = TRUE)
    {
        $header = array();

        $header['title'] = isset($data['title']) ? $data['title'] : '';
        $header['metaTitle'] = isset($data['metaTitle']) ? $data['metaTitle'] : '';
        $header['metaKeyword'] = isset($data['metaKeyword']) ? $data['metaKeyword'] : '';
        $header['metaDesc'] = isset($data['metaDesc']) ? $data['metaDesc'] : '';

        $header['loadHeader'] = $loadHeader;
        $header['menuTab'] = $menuTab;
        $header['subMenuTab'] = $subMenuTab;

        // for new skin
        // check load header css, js file by page hay ko
        // $this->uri->rsegments[1].'_'.$this->uri->rsegments[2]
        $header['header_page_css_js'] = isset($data['header_page_css_js']) ? trim($data['header_page_css_js']) : '';

        //set active menu
        $header['active_link'] = isset($data['active_link']) ? $data['active_link'] : $this->uri->rsegments[1];
        $header['active_sub_link'] = isset($data['active_sub_link']) ? $data['active_sub_link'] : $this->uri->rsegments[2];

        //load header
        $this->load->view($this->_template_f . 'header_view', $header);
    }

    protected function _loadFooter()
    {
        $footer = array();
        $footer['is_login'] = $this->_islogin();
        $this->load->view($this->_template_f . 'footer_view', $footer);
    }

    protected function _session_uid()
    {
        $user_id = trim($this->session->userdata('uid'));
        $user_id = isIdNumber($user_id) ? $user_id : 0;
        return $user_id;
    }

    protected function _session_role()
    {
        $role = trim($this->session->userdata('role'));
        return $role;
    }

    protected function _session_uname()
    {
        $uname = $this->session->userdata('uname');
        $uname = strtolower($uname);
        $uname = preg_match('/^[a-z0-9_@\-\.]+$/', $uname) ? $uname : '';
        return $uname;
    }

    protected function _session_fullname()
    {
        $fullname = $this->session->userdata('fullname');
        $fullname = strtolower($fullname);
        $fullname = preg_match('/^[a-z0-9_@\-\.]+$/', $fullname) ? $fullname : '';
        return $fullname;
    }

    protected function _islogin()
    {
        $user_id = $this->_session_uid();
        $uname = $this->_session_uname();
        return ($user_id > 0 && $uname != '') ? TRUE : FALSE;
    }

    protected function _validUrl()
    {
        $url = HTTP_PROTOCOL . '://' . DOMAIN_NAME . $_SERVER["REQUEST_URI"];
        $text = $url;

        $text = rawurldecode($text);
        $text = htmlspecialchars_decode(
            html_entity_decode($text, ENT_QUOTES | ENT_IGNORE, "UTF-8"),
            ENT_QUOTES | ENT_IGNORE
        );
        $text = trim($text);
        $url = $text;
        // PHP's strip_tags() function will remove tags, but it
        // doesn't remove scripts, styles, and other unwanted
        // invisible text between tags.  Also, as a prelude to
        // tokenizing the text, we need to insure that when
        // block-level tags (such as <p> or <div>) are removed,
        // neighboring words aren't joined.
        $text = preg_replace(
            array(
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',

                // Add line breaks before & after blocks
                '@<((br)|(hr))@iu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                "\n\$0",
                "\n\$0",
                "\n\$0",
                "\n\$0",
                "\n\$0",
                "\n\$0",
                "\n\$0",
                "\n\$0",
            ),
            $text
        );

        // Remove all remaining tags and comments and return.
        $text = strip_tags($text);
        if ($text != $url) {
            dbClose();
            show_404('', FALSE);
            die();
        }
    }

    protected function _permission_role()
    {
        if ($this->_islogin()) {
            $role = $this->_session_role();

            if (!in_array($role, [ADMIN, SALE, QC, EDITOR])) {
                show_custom_error();
            }

            $block_method = [
                'async' => [
                    'process' => [SALE, QC, EDITOR]
                ],
                'chat' => [
                    'index' => [],
                    'ajax_chat_list_by_user' => [],
                    'ajax_chat_add' => [SALE, QC, EDITOR],
                    'ajax_delete_chat_user' => [SALE, QC, EDITOR],
                    'ajax_add_group' => [SALE, QC, EDITOR],
                    'ajax_list_msg_by_group' => [],
                    'ajax_msg_add_to_group' => [],
                    'ajax_modal_group_info' => [],
                    'ajax_edit_group' => [SALE, QC, EDITOR],
                    'ajax_del_msg_group' => [SALE, QC, EDITOR],
                    'ajax_delete_gchat' => [SALE, QC, EDITOR],
                    'ajax_count_msg_chua_xem' => [],
                ],
                'discuss' => [
                    'ajax_discuss_list_khach' => [QC, EDITOR],
                    'ajax_discuss_list_noi_bo' => [],
                    'ajax_discuss_noi_bo_add' => [],
                    'ajax_discuss_khach_add' => [QC, EDITOR],
                ],
                'kpi' => [
                    'index' => [EDITOR]
                ],
                'library' => [
                    'index' => [SALE, QC, EDITOR],
                    'ajax_delete' => [SALE, QC, EDITOR],
                ],
                'order' => [
                    'index' => [],
                    'detail' => [],
                    'add_private' => [QC, EDITOR],
                    'add_customer' => [QC, EDITOR],
                    'submit_add' => [QC, EDITOR],
                    'ajax_find_order' => [],
                    'ajax_change_status_order' => [],
                    'ajax_assign_job_user' => [],
                    'ajax_remove_job_user' => [],
                    'ajax_change_custom_order' => [QC, EDITOR],
                    'ajax_change_custom_order_for_user' => [],
                    'ajax_ed_join_order' => [],
                    'ajax_change_code_order' => [QC, EDITOR],
                    'ajax_change_expire' => [QC, EDITOR],
                    'ajax_edit_main_file' => [EDITOR],
                    'ajax_add_attach_file' => [EDITOR],
                    'ajax_delete_attach_file' => [EDITOR],
                    'ajax_edit_attach_file' => [EDITOR],
                    'ajax_update_requirement' => [EDITOR],
                    'ajax_add_file_complete' => [],
                    'ajax_edit_file_complete' => [],
                    'ajax_delete_file_complete' => [],
                    'ajax_add_rework' => [QC, EDITOR],
                    'ajax_add_file_attach_rework' => [EDITOR],
                    'ajax_edit_file_attach_rework' => [EDITOR],
                    'ajax_delete_file_attach_rework' => [EDITOR],
                    'ajax_update_requirement_rework' => [EDITOR],
                    'ajax_add_file_complete_rework' => [],
                    'ajax_edit_file_complete_rework' => [],
                    'ajax_delete_file_complete_rework' => [],
                    'ajax_update_ed_type' => [QC, EDITOR],
                    'ajax_zip_attach' => [],
                    'ajax_zip_attach_rework' => [],
                    'export' => [],
                    'ajax_log_list' => [],
                ],
                'room' => [
                    'index' => [SALE, QC, EDITOR],
                ],
                'service' => [
                    'index' => [SALE, QC, EDITOR],
                ],
                'setting' => [
                    'info' => [SALE, QC, EDITOR],
                    'home' => [SALE, QC, EDITOR],
                    'submit_home' => [SALE, QC, EDITOR],
                    'privacy_policy' => [SALE, QC, EDITOR],
                    'refund_policy' => [SALE, QC, EDITOR],
                    'termsofuse' => [SALE, QC, EDITOR],
                    'max_order_working' => [SALE, QC, EDITOR],
                    'hiw' => [SALE, QC, EDITOR],
                    'ajax_hiw_submit' => [SALE, QC, EDITOR],
                ],
                'style' => [
                    'index' => [SALE, QC, EDITOR],
                ],
                'upload' => [
                    'index' => [],
                    'siglefile' => [],
                ],
                'user' => [
                    'index' => [SALE, QC, EDITOR],
                    'ajax_add_user' => [SALE, QC, EDITOR],
                    'ajax_edit_user' => [SALE, QC, EDITOR],
                    'ajax_change_code_user' => [QC, EDITOR],
                    'ajax_load_info_user_create_order' => [QC, EDITOR],
                    'info' => [],
                    'ajax_edit_info' => [],
                    'ajax_edit_password' => [],
                ],
                'voucher' => [
                    'index' => [QC, EDITOR],
                    'ajax_get_list_voucher' => [QC, EDITOR],
                    'ajax_get_list_voucher_for_create_order_by_sale' => [QC, EDITOR],
                ],
                'withdraw' => [
                    'index' => [SALE, QC, EDITOR],
                    'detail' => [SALE, QC, EDITOR],
                    'ajax_get_rut_tien' => [],
                    'ajax_set_rut_tien' => [],
                    'ajax_phe_duyet_rut_tien' => [SALE, QC, EDITOR],
                    'ajax_set_rut_tien_ho' => [QC, EDITOR],
                ],
                'backup' => [
                    'report' => [EDITOR]
                ],
            ];

            // khong phai admin 
            $method = $this->uri->rsegment(1);
            $func = $this->uri->rsegment(2);

            $list_role_block = $block_method[$method][$func];

            if (!empty($list_role_block)) {
                if (in_array($role, $list_role_block)) {
                    if ($this->input->is_ajax_request()) {
                        resError('Tài khoản không có quyền truy cập');
                    } else {
                        show_custom_error();
                    }
                }
            }
        }
    }

    protected function validKey($k1, $k2, $uid = '', $pid = '')
    {
        $data = array();
        $fromdate = '';
        $todate = '';
        $key = trim($k1);
        $enc_id = trim($k2);
        $enc_id = EncryptData::Decode($enc_id);
        $enc_id = json_decode($enc_id, true);
        $chk_key = false;
        if (is_array($enc_id)) {
            $tmp_ctt = isset($enc_id['ctt']) ? $enc_id['ctt'] : '';
            $tmp_userid = isset($enc_id['uid']) ? $enc_id['uid'] : '';
            $tmp_fromDate = isset($enc_id['fdate']) ? $enc_id['fdate'] : '';
            $tmp_toDate = isset($enc_id['tdate']) ? $enc_id['tdate'] : '';
            $tmp_id = isset($enc_id['id']) ? $enc_id['id'] : '';
            $lstid = isset($enc_id['lstid']) ? $enc_id['lstid'] : '';
            $getid = ($lstid == '') ? $tmp_id : $lstid;

            $key_id = $tmp_ctt . $tmp_userid . $tmp_fromDate . $tmp_toDate . $getid;
            $key_id = EncryptData::Encode($key_id);

            $chk_key = (($key_id == $key) && ($uid == $tmp_userid) && ($pid == $getid)) ? true : false;
            $fromdate = $tmp_fromDate;
            $todate = $tmp_toDate;
            $data['enc_data'] = $enc_id;
        }

        $data['chk_key'] = $chk_key;
        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;

        return $data;
    }
}
