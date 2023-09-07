<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        $this->load->model('library/Library_model');
        $this->load->model('room/Room_model');
        $this->load->model('style/Style_model');
        $this->load->model('service/Service_model');
        $this->load->model('order/Order_model');
        $this->load->model('login/Login_model');
    }

    function index()
    {
        $data = [];

        $header = [
            'title' => 'Order',
            'active_link' => 'home',
            'header_page_css_js' => 'order'
        ];

        $room = $this->Room_model->get_list(1);
        $style = $this->Style_model->get_list(1);
        $library = $this->Library_model->get_list(1);
        $service = $this->Service_model->get_list(1);

        $data['list_room'] = $room;
        $data['list_service'] = $service;
        $data['list_style'] = $style;
        $data['list_library'] = $library;

        $user_info = [];
        if($this->_session_uname() != '') {
            $user_info = $this->Login_model->get_user_info_by_username($this->_session_uname());
        }
        $data['user_info'] = $user_info;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/order_view', $data);

        $this->_loadFooter();
    }

    function submit()
    {
        // TODO: sale admin qc ed muốn tạo đơn có được không?

        $this->_islogin() ? "" : resError('error_attach');

        $all_room    = $this->Room_model->get_list(1);
        $all_service = $this->Service_model->get_list(1);
        $all_style   = $this->Style_model->get_list(1);

        $order = $this->input->post('order');
        $style       = $order['style'];
        $id_user     = $this->_session_uid();
        $create_time = date('Y-m-d H:i:s');
        $coupon      = $order['coupon'];
        $list_job  = $order['job'];

        // VALIDATE
        # check style
        if(isIdNumber($style)) {
            isset($all_style[$style]) ? '' : resError('error_style');
        }
        
        $FDR_ORDER = FOLDER_ORDER . strtotime($create_time) .'@'.$this->_session_uname();

        foreach ($list_job as $id_job => $job) {
            $room        = $job['room'];
            $service     = $job['service'];
            $image       = $job['image'];
            $requirement = $job['requirement'];
            $attach      = @$job['attach'];      // k bat buoc nhap attach nen de @

            # check room, service
            isset($all_room[$room])         ? '' : resError('error_room');
            isset($all_service[$service])   ? '' : resError('error_service');

            # lưu ảnh image
            $copy_image = copy_image_to_public_upload($image, $FDR_ORDER);
            $copy_image['status'] ? '' : resError('error_image');
            $list_job[$id_job]['image_ok'] = $copy_image['basename'];

            # lưu ảnh attachments
            $attach_ok = [];
            foreach ($attach as $id_attach => $image_attach) {
                $copy_attach = copy_image_to_public_upload($image_attach, $FDR_ORDER);
                if ($copy_attach['status']) {
                    $attach_ok[$id_attach] = $copy_attach['basename'];
                } else {
                    deleteDirectory($FDR_ORDER);
                    resError('error_attach');
                }
            }
            $list_job[$id_job]['attach_ok'] = $attach_ok;
        }
        // END VALIDATE

        // Tạo đơn vào tbl_order
        // TODO: dòng bên dưới tạm fix PAY_HOAN_THANH, sau bổ sung paypal sẽ thay bằng PAY_DANG_CHO
        $new_order = $this->Order_model->add_order($style, $create_time, $id_user, $coupon, PAY_HOAN_THANH, ORDER_PENDING);

        $flag_error = false;
        if ($new_order) {
            // số lượng job của đơn lưu vào tbl_job
            foreach ($list_job as $job) {

                $room           = $job['room'];
                $service        = $job['service'];
                $type_service   = $all_service[$service]['type_service'];
                $price          = $all_service[$service]['price'];
                $price_unit     = '2'; //TODO: 1 VND, 2 Đô, ...
                $image_ok       = $job['image_ok'];
                $json_attach_ok = json_encode($job['attach_ok'], JSON_FORCE_OBJECT);
                $requirement    = $job['requirement'];

                $new_order_job_service = $this->Order_model->add_order_job($new_order, $service, $type_service, $price, $price_unit, $room, $style, $image_ok, $json_attach_ok, $requirement, $create_time);

                if (!$new_order_job_service) {
                    $flag_error = true;
                    break;
                }
            }

            if ($flag_error) {
                $this->Order_model->delete_order_and_job($new_order);
                // Xóa ảnh của job, ảnh attach...TODO:
                resError('Loi luu job');
            } else {
                resSuccess('ok');
            }
        } else {
            resError('Loi luu don');
        }
    }
}
