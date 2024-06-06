<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Discuss extends MY_Controller
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

        $this->load->model('discuss/Discuss_model');
        $this->load->model('order/Order_model');
        $this->load->model('user/User_model');
    }

    function ajax_discuss_list_khach()
    {
        $id_order = $this->input->post('id_order');
        $id_order = isIdNumber($id_order) ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';

        // get list discuss theo order
        $list = $this->Discuss_model->discuss_list_by_id_order($id_order, CHAT_KHACH);
        resSuccess($list);
    }


    function ajax_discuss_list_noi_bo()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $id_order = $this->input->post('id_order');

        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';
        if ($role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        // get list discuss theo order
        $list = $this->Discuss_model->discuss_list_by_id_order($id_order, CHAT_NOI_BO);

        resSuccess($list);
    }

    function ajax_discuss_noi_bo_add()
    {
        // check right
        $cur_uid = $this->_session_uid();
        $role = $this->_session_role();

        $id_order = $this->input->post('id_order');
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';
        if ($role == EDITOR) {
            !isset($order['team'][$cur_uid]) ? resError('Tài khoản của bạn chưa tham gia đơn hàng này') : '';
        }

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('url file không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url file không hợp lệ (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('url file không hợp lệ (3)') : '';

            $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
            $copy = copy_image_to_public_upload($url_file, $FDR_ORDER);

            !$copy['status'] ? resError($copy['error']) : '';

            // move to thumb
            if(stringIsImage($url_file)) {
                copy_image_to_thumb($url_file, $FDR_ORDER . 'thumb', THUMB_WIDTH, THUMB_HEIGHT);
            }

            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        // get list discuss theo order
        $create_time = date('Y-m-d H:i:s');
        $status = 1;

        $newid = $this->Discuss_model->discuss_add($cur_uid, $id_order, $content, json_encode($db_attach, JSON_FORCE_OBJECT), $create_time, $status, CHAT_NOI_BO);

        $info = $this->Discuss_model->discuss_info($newid);

        resSuccess($info);
    }

    function ajax_discuss_khach_add()
    {
        // check right
        $cur_uid = $this->_session_uid();

        $id_order = $this->input->post('id_order');
        $content = removeAllTags($this->input->post('content'));
        $attach = $this->input->post('attach');

        // validate đơn
        $id_order = isIdNumber($id_order)    ? $id_order : 0;
        $order = $this->Order_model->get_info_order($id_order);
        $order == [] ? resError('Đơn không tồn tại') : '';

        //validate file đính kèm
        $db_attach = [];
        $attach = is_array($attach) ? $attach : [];
        foreach ($attach as $i => $url_file) {
            $parse = parse_url($url_file);
            !isset($parse['host'])              ? resError('url file không hợp lệ (1)') : '';
            $parse['host'] != DOMAIN_NAME       ? resError('url file không hợp lệ (2)') : '';
            !strpos($url_file, 'uploads/tmp')  ? resError('url file không hợp lệ (3)') : '';

            $FDR_ORDER = FOLDER_ORDER . strtotime($order['create_time']) . '@' . $order['username'] . '/';
            $copy = copy_image_to_public_upload($url_file, $FDR_ORDER);

            !$copy['status'] ? resError($copy['error']) : '';
            // move to thumb
            if(stringIsImage($url_file)) {
                copy_image_to_thumb($url_file, $FDR_ORDER . 'thumb', THUMB_WIDTH, THUMB_HEIGHT);
            }
            
            $id_attach = generateRandomNumber();
            $db_attach[$id_attach] = $copy['basename'];
        }

        // get list discuss theo order
        $create_time = date('Y-m-d H:i:s');
        $status = 1;

        $newid = $this->Discuss_model->discuss_add($cur_uid, $id_order, $content, json_encode($db_attach, JSON_FORCE_OBJECT), $create_time, $status, CHAT_KHACH);

        $info = $this->Discuss_model->discuss_info($newid);

        resSuccess($info);
    }

    function ajax_discuss_edit()
    {
    }

    function ajax_discuss_delete()
    {
    }
}
