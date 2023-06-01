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
        $list_room    = $this->Room_model->get_list(1);
        $list_service = $this->Service_model->get_list(1);
        $list_style   = $this->Style_model->get_list(1);

        $order = $this->input->post('order');
        $name        = $order['name'];
        $lastname    = $order['lastname'];
        $email       = $order['email'];
        $phone       = $order['phone'];
        $id_style    = $order['style'];
        $id_user     = 1; //TODO: fix tạm thời
        $create_time = date('Y-m-d H:i:s');
        $coupon      = $order['coupon'];
        // VALIDATE TODO:

        $order_item_ok = [];
        foreach ($order['order'] as $id_item => $item) {
            $id_room     = $item['room'];
            $id_service  = $item['service'];
            $image       = $item['image'];
            $requirement = $item['requirement'];
            $attach      = @$item['attach']; // k bat buoc nhap attach nen de @
            // VALIDATE TODO:

            # check room, style
            isset($list_room[$id_room])     ? '' : resError('error_room');
            isset($list_style[$id_style])   ? '' : resError('error_style');
            # check service
            empty($id_service) ? resError('empty_service') : '';
            foreach ($id_service as $sv_id => $sv_price) {
                isset($list_service[$sv_id]) ? '' : resError('isset_service');
                $list_service[$sv_id]['price'] == 0 ? resError('empty_price') : '';
                $list_service[$sv_id]['price'] != $sv_price ? resError('error_price') : '';
            }
            # lưu ảnh image
            $copy_image = copy_image_from_file_manager_to_public_upload($image, date('Y'), date('m'));
            $copy_image['status'] ? '' : resError('error_image');
           
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
            // ok data
            $order_item_ok[$id_item]['id_room']     = $id_room;
            $order_item_ok[$id_item]['id_service']  = json_encode($id_service, JSON_FORCE_OBJECT);
            $order_item_ok[$id_item]['image']       = $copy_image['basename'];
            $order_item_ok[$id_item]['attach']      = json_encode($attach_ok, JSON_FORCE_OBJECT);
            $order_item_ok[$id_item]['requirement'] = $requirement;
        }

        if (count($order['order']) == 0 || count($order['order']) != count($order_item_ok)) {
            resError('error_order_item');
        } else {
            $new_order = $this->Order_model->add($name, $lastname, $email, $phone, $id_style, $id_user, $create_time, $coupon, PAY_DANG_CHO, STATUS_CHUA_LAM);

            if ($new_order) {
                foreach ($order_item_ok as $id_item => $it) {
                    $new_order = $this->Order_model->add_item($new_order, $it['id_room'], $it['id_service'], $id_user, $it['image'], $it['attach'], $create_time, $id_user, $create_time, STATUS_CHUA_LAM);
                }
            }
        }
    }
}
