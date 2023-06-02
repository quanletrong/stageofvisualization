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
        $room = $this->Service_model->get_list(1);
        $style = $this->Style_model->get_list(1);
        $library = $this->Library_model->get_list(1);
        $service = $this->Service_model->get_list(1);

        $data['list_room'] = $room;
        $data['list_service'] = $service;
        $data['list_style'] = $style;
        $data['list_library'] = $library;

        $this->_loadHeader($header);

        $this->load->view($this->_template_f . 'order/order_view', $data);

        $this->_loadFooter();
    }

    function submit()
    {
        $all_room    = $this->Room_model->get_list(1);
        $all_service = $this->Service_model->get_list(1);
        $all_style   = $this->Style_model->get_list(1);

        $order = $this->input->post('order');
        $name        = $order['name'];
        $lastname    = $order['lastname'];
        $email       = $order['email'];
        $phone       = $order['phone'];
        $style       = $order['style'];
        $id_user     = 1; //TODO: fix tạm thời
        $create_time = date('Y-m-d H:i:s');
        $coupon      = $order['coupon'];
        $list_job  = $order['job'];

        // VALIDATE
        foreach ($list_job as $id_job => $job) {
            $room         = $job['room'];
            $service_list = $job['service'];
            $image        = $job['image'];
            $requirement  = $job['requirement'];
            $attach       = @$job['attach'];      // k bat buoc nhap attach nen de @

            # check room, style
            isset($all_room[$room])     ? '' : resError('error_room');
            isset($all_style[$style])   ? '' : resError('error_style');
            # check service
            empty($service_list) ? resError('empty_service') : '';
            foreach ($service_list as $id_service => $price) {
                isset($all_service[$id_service]) ? '' : resError('isset_service');
                $all_service[$id_service]['price'] == 0 ? resError('empty_price') : '';
                $all_service[$id_service]['price'] != $price ? resError('error_price') : '';
            }
            # lưu ảnh image
            $copy_image = copy_image_from_file_manager_to_public_upload($image, date('Y'), date('m'));
            $copy_image['status'] ? '' : resError('error_image');
            $list_job[$id_job]['image_ok'] = $copy_image['basename'];

            # lưu ảnh attachments
            $attach_ok = [];
            foreach ($attach as $id_attach => $image_attach) {
                $copy_attach = copy_image_from_file_manager_to_public_upload($image_attach, date('Y'), date('m'));
                if ($copy_attach['status']) {
                    $attach_ok[$id_attach] = $copy_attach['basename'];
                } else {
                    #xóa ảnh chính vưa lưu
                    @unlink(PUBLIC_UPLOAD_PATH . date('Y') . '/' . date('m') . '/' . $copy_image['basename']);
                    #xóa ảnh attach vừa lưu
                    foreach ($attach_ok as $attach_image) {
                        @unlink(PUBLIC_UPLOAD_PATH . date('Y') . '/' . date('m') . '/' . $attach_image);
                    }
                    resError('error_attach');
                }
            }
            $list_job[$id_job]['attach_ok'] = $attach_ok;
        }
        // END VALIDATE

        // Tạo đơn vào tbl_order
        $new_order = $this->Order_model->add_order($name, $lastname, $email, $phone, $create_time, $id_user, $coupon, PAY_DANG_CHO, STATUS_CHUA_LAM);

        $flag_error = false;
        if ($new_order) {
            // số lượng job của đơn lưu vào tbl_order_job
            foreach ($list_job as $job) {

                $new_order_job = $this->Order_model->add_order_job($new_order);

                $room           = $job['room'];
                $service_list   = $job['service'];
                $image_ok       = $job['image_ok'];
                $json_attach_ok = json_encode($job['attach_ok'], JSON_FORCE_OBJECT);
                $requirement    = $job['requirement'];

                if ($new_order_job) {
                    // số lượng service cần làm cho mỗi job lưu vào tbl_order_job_service
                    foreach ($service_list as $id_service => $price) {

                        $new_order_job_service = $this->Order_model->add_order_job_service($new_order_job, $new_order, $id_service, $price, $room, $style, $image_ok, $json_attach_ok, $requirement, STATUS_CHUA_LAM, $create_time);

                        if (!$new_order_job_service) {
                            $flag_error = true;
                            break;
                        }
                    }
                } else {
                    $flag_error = true;
                    break;
                }
            }

            if ($flag_error) {
                $this->Order_model->delete_order_job_service($new_order);
                // Xóa ảnh của job, ảnh attach...TODO:
                resError('Loi luu job hoac service job');
            } else {
                resSuccess('ok');
            }
        } else {
            resError('Loi luu don');
        }
    }
}
