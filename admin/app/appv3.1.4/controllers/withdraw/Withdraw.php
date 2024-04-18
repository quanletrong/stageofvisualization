<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        if (!$this->_isLogin()) {
            if ($this->input->is_ajax_request()) {
                resError('unlogin');
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        $this->load->model('order/Order_model');
        $this->load->model('withdraw/Withdraw_model');
        $this->load->model('user/User_model');
    }

    function index()
    {
        $withdraw = $this->Withdraw_model->withdraw_get_list();

        $data['withdraw'] = $withdraw;
        $data['title'] = 'Danh sách yêu cầu rút tiền';

        $header = [
            'title' => $data['title'],
            'header_page_css_js' => 'withdraw'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'withdraw/list/withdraw_view', $data);
        $this->_loadFooter();
    }

    function detail($id_user)
    {
        if (!isIdNumber($id_user)) {
            dbClose();
            redirect(site_url('withdraw', $this->_langcode));
            die();
        }

        $uinfo = $this->User_model->get_user_info_by_id($id_user);

        if (empty($uinfo)) {
            dbClose();
            redirect(site_url('withdraw', $this->_langcode));
            die();
        }

        $status_pending = 0;
        $list_waiting = $this->Withdraw_model->withdraw_get_detail($id_user, $status_pending);

        $status_done = 1;
        $list_done = $this->Withdraw_model->withdraw_get_detail($id_user, $status_done);

        $data['uinfo']      = $uinfo;
        $data['tong_hop_pending']   = $list_waiting['tong_hop'];
        $data['all_pending']        = $list_waiting['all'];
        $data['group_date_pending'] = $list_waiting['group_date'];

        $data['tong_hop_done']   = $list_done['tong_hop'];
        $data['all_done']        = $list_done['all'];
        $data['group_date_done'] = $list_done['group_date'];

        $data['title']      = "Yêu cầu rút tiền của người dùng [" . $uinfo['username'] . "]";

        $header = [
            'title' => $data['title'],
            'header_page_css_js' => 'withdraw'
        ];
        $this->_loadHeader($header);
        $this->load->view($this->_template_f . 'withdraw/detail/withdraw_detail_view', $data);
        $this->_loadFooter();
    }

    function ajax_get_rut_tien()
    {
        $cur_uid = $this->_session_uid();
        $list_job_chua_rut = $this->Withdraw_model->danh_sach_chua_rut_tien($cur_uid);

        $data = [];
        foreach ($list_job_chua_rut as $item) {
            $type_service = $item['type_service'];
            $type_job_user = $item['type_job_user'];

            // CHECK IN
            if ($type_job_user == WORKING_QC_IN) {
                if (isset($data['CHECK_IN'])) {
                    $num = $data['CHECK_IN'] + $item['num'];
                } else {
                    $num = $item['num'];
                }
                $data['CHECK_IN'] = $num;
            }
            // CHECK OUT
            else if ($type_job_user == WORKING_QC_OUT) {
                if (isset($data['CHECK_OUT'])) {
                    $num = $data['CHECK_OUT'] + $item['num'];
                } else {
                    $num = $item['num'];
                }
                $data['CHECK_OUT'] = $num;
            }
            // CUSTOM
            else if ($type_job_user == WORKING_CUSTOM) {
                if (isset($data['CUSTOM'])) {
                    $num = $data['CUSTOM'] + $item['num'];
                } else {
                    $num = $item['num'];
                }
                $data['CUSTOM'] = $num;
            }
            // TYPE SERVICES
            else {
                if (isset($data[$type_service])) {
                    $num = $data[$type_service] + $item['num'];
                } else {
                    $num = $item['num'];
                }
                $data[$type_service] = $num;
            }
        }
        resSuccess($data);
    }

    function ajax_set_rut_tien()
    {
        $cur_uid = $this->_session_uid();
        $create_time = date('Y-m-d H:i:s');

        $list_job_chua_rut = $this->Withdraw_model->danh_sach_chua_rut_tien($cur_uid);

        // TODO: tối ưu code

        if (empty($list_job_chua_rut)) {
            resError('Bạn chưa có đơn hàng hoàn thành');
        } else {

            // tao_yeu_cau_rut_tien
            $service_request = [];
            foreach ($list_job_chua_rut as $id_job_user => $job_user) {
                $id_user = $job_user['id_user'];
                $id_order = $job_user['id_order'];
                $id_job = $job_user['id_job'];
                $type_job_user = $job_user['type_job_user'];

                if ($type_job_user == WORKING_QC_IN) {
                    $type_service = 'CHECK_IN';
                } else if ($type_job_user == WORKING_QC_OUT) {
                    $type_service = 'CHECK_OUT';
                } else {
                    $type_service = $job_user['type_service'];
                }

                $custom = $job_user['num']; // số lượng rút

                if(isset($service_request[$type_service])) {
                    $service_request[$type_service] += $custom;
                } else {
                    $service_request[$type_service] = $custom;
                }
               
                $this->Withdraw_model->tao_yeu_cau_rut_tien($id_user, $id_order, $id_job, $id_job_user, $type_service, $custom, $create_time);
            }
            // end tao_yeu_cau_rut_tien

            // email gui den ADMIN va nguoi rut
            $uinfo = $this->User_model->get_user_info_by_id($cur_uid);
            $all_admin = $this->User_model->get_list_user_working(1, ADMIN);

            $data['fullname'] = $uinfo['fullname'];
            $data['by'] = $uinfo['fullname'];
            $data['create_time'] = $create_time;
            $data['service_request'] = $service_request;

            $email['to'][] = $uinfo['email'];
            foreach ($all_admin as $val) {
                $email['to'][] = $val['email'];
            }

            $email['to'] = implode(',', $email['to']);
            $email['subject'] = "Yêu cầu rút tiền mới - " . $uinfo['fullname'];
            $email['body']    = $this->load->view('v2023/component/tmpl_withdrawal_request_received', $data, true);

            @sendmail($email);
            // end email

            dbClose();
            resSuccess('ok');
        }
    }

    function ajax_phe_duyet_rut_tien($id_user)
    {
        !isIdNumber($id_user) ? resError('Error') : '';
        $uinfo = $this->User_model->get_user_info_by_id($id_user);
        empty($uinfo) ?  resError('Error') : '';

        $status_pending = 0;
        $list_waiting = $this->Withdraw_model->withdraw_get_detail($id_user, $status_pending);

        if (empty($list_waiting['all'])) {
            resError('Không có yêu cầu nào được thực hiện.');
        }

        $arr_id_withdraw = [];
        $arr_id_job_user = [];
        foreach ($list_waiting['all'] as $id_withdraw => $withdraw) {
            $id_job_user = $withdraw['id_job_user'];
            $arr_id_withdraw[] = $id_withdraw;
            $arr_id_job_user[$id_job_user] = $id_job_user;
        }

        $exc = $this->Withdraw_model->phe_duyet_yeu_cau_rut_tien(implode(',', $arr_id_withdraw), implode(',', $arr_id_job_user));
        resSuccess('ok');
    }

    function ajax_set_rut_tien_ho()
    {
        $cur_uid     = $this->_session_uid();
        $create_time = date('Y-m-d H:i:s');
        $id_user     = $this->input->post('id_user');
        $fdate       = $this->input->post('fdate');
        $tdate       = $this->input->post('tdate');

        //validate filter_id_user
        $all_user = $this->User_model->get_list_user_working('0,1', implode(",", [ADMIN, SALE, QC, EDITOR]));
        $id_user = isIdNumber($id_user) ? $id_user : resError('Tài khoản không hợp lệ (1)');
        $id_user = isset($all_user[$id_user]) ? $id_user : resError('Tài khoản không hợp lệ (2)');

        //validate filter date
        $fdate = strtotime($fdate) !== false ? $fdate : resError('Ngày bắt đầu không hợp lệ');
        $tdate = strtotime($tdate) !== false ? $tdate : resError('Ngày kết thúc không hợp lệ');

        // danh sach job chua rut tien
        $list_job_chua_rut = $this->Withdraw_model->danh_sach_chua_rut_tien($id_user, $fdate . ' 00:00:00', $tdate . ' ' . date('H:s:i'));

        if (empty($list_job_chua_rut)) {
            resError('Chưa có đơn hàng hoàn thành');
        } else {

            $service_request = [];
            foreach ($list_job_chua_rut as $id_job_user => $job_user) {
                $id_user = $job_user['id_user'];
                $id_order = $job_user['id_order'];
                $id_job = $job_user['id_job'];
                $type_job_user = $job_user['type_job_user'];

                if ($type_job_user == WORKING_QC_IN) {
                    $type_service = 'CHECK_IN';
                } else if ($type_job_user == WORKING_QC_OUT) {
                    $type_service = 'CHECK_OUT';
                } else {
                    $type_service = $job_user['type_service'];
                }

                $custom = $job_user['num']; // số lượng rút

                if(isset($service_request[$type_service])) {
                    $service_request[$type_service] += $custom;
                } else {
                    $service_request[$type_service] = $custom;
                }

                $this->Withdraw_model->tao_yeu_cau_rut_tien($id_user, $id_order, $id_job, $id_job_user, $type_service, $custom, $create_time);
            }


            // email gui den ADMIN va nguoi rut
            $uinfo = $this->User_model->get_user_info_by_id($cur_uid);
            $all_admin = $this->User_model->get_list_user_working(1, ADMIN);

            $data['fullname'] = $all_user[$id_user]['fullname'];
            $data['by'] = $all_user[$cur_uid]['fullname'];
            $data['create_time'] = $create_time;
            $data['service_request'] = $service_request;

            $email['to'][] = $uinfo['email'];
            foreach ($all_admin as $val) {
                $email['to'][] = $val['email'];
            }

            $email['to'] = implode(',', $email['to']);
            $email['subject'] = "Yêu cầu rút tiền mới - " . $uinfo['fullname'];
            $email['body']    = $this->load->view('v2023/component/tmpl_withdrawal_request_received', $data, true);

            @sendmail($email);
            // end email

            dbClose();
            resSuccess('ok');
        }
    }
}
