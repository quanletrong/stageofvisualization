<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_info_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.code_user, B.username FROM tbl_order A
        INNER JOIN tbl_user B ON A.id_user = B.id_user
        WHERE A.id_order = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $list_job = $this->_get_list_job_by_order($data['id_order'], $iconn);
                    $data['job'] = $list_job;
                    $data['total_type_service']    = 0;
                    $data['list_type_service']     = [];
                    $data['list_type_service']     = [];
                    $data['working_custom_active'] = [];
                    $data['working_custom_block']  = [];
                    $data['working_ed_active']     = [];
                    $data['working_ed_block']      = [];
                    $data['working_qc_in_active']  = [];
                    $data['working_qc_out_active'] = [];
                    $data['working_qc_in_block']   = [];
                    $data['working_qc_out_block']  = [];
                    $data['total_custom_used']     = 0;   // số lượng custom đã dùng trong đơn

                    foreach ($data['job'] as $id_job => $job) {
                        // gán working ed
                        $data['job'][$id_job]['working_ed_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_EDITOR, $iconn);
                        $data['job'][$id_job]['working_ed_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_EDITOR, $iconn);

                        // gán working qc in
                        $data['job'][$id_job]['working_qc_in_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_QC_IN, $iconn);
                        $data['job'][$id_job]['working_qc_in_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_QC_IN, $iconn);
                        // gán working qc out
                        $data['job'][$id_job]['working_qc_out_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_QC_OUT, $iconn);
                        $data['job'][$id_job]['working_qc_out_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_QC_OUT, $iconn);

                        // gán rework
                        $data['job'][$id_job]['rework']  = $this->_get_list_rework_user_by_job($id_job, $iconn);

                        if (!empty($data['job'][$id_job]['working_ed_active'])) {
                            $data['working_ed_active'][]   = $data['job'][$id_job]['working_ed_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_ed_block'])) {
                            $data['working_ed_block'][]   = $data['job'][$id_job]['working_ed_block'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_in_active'])) {
                            $data['working_qc_in_active'][]   = $data['job'][$id_job]['working_qc_in_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_out_active'])) {
                            $data['working_qc_out_active'][]   = $data['job'][$id_job]['working_qc_out_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_in_block'])) {
                            $data['working_qc_in_block'][]   = $data['job'][$id_job]['working_qc_in_block'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_out_block'])) {
                            $data['working_qc_out_block'][]   = $data['job'][$id_job]['working_qc_out_block'];
                        }

                        // gán type_service to order
                        $data['list_type_service'][$job['type_service']][] = $id_job;
                        $data['total_type_service'] += 1;
                    }

                    // gán working custom
                    $data['working_custom_active'] = $this->_get_list_job_user_by_job($id_order, 0, 1, WORKING_CUSTOM, $iconn);
                    $data['working_custom_block']  = $this->_get_list_job_user_by_job($id_order, 0, 0, WORKING_CUSTOM, $iconn);
                    foreach ($data['working_custom_active'] as $custom_active) {
                        $data['total_custom_used'] += $custom_active['custom'];
                    }
                }

                // gán danh sách qc, ed, custom vào đơn
                if (!empty($data)) {
                    $data['team'] =  $this->_get_list_editor_by_id_order($data['id_order'], $iconn);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_list($status = '')
    {
        $list_order = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.code_user as code_user
        FROM tbl_order as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        ORDER BY A.status ASC, A.create_time ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // gán list type_service vào đơn
                        $type_service = $this->_get_type_service_job_by_id_order($row['id_order'], $iconn);
                        $row['type_service'] = $type_service;

                        // gán total job vào đơn
                        $total_job = $this->_get_total_job_by_id_order($row['id_order'], $iconn);
                        $row['total_job'] = $total_job;

                        // gán danh sách qc, ed, custom vào đơn
                        $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        $row['team'] = $team;

                        $list_order[$row['id_order']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_order;
    }

    function get_list_for_qc($id_user, $status = '')
    {
        $list_order = [];
        $iconn = $this->db->conn_id;
        // lấy list id order theo user từ bảng `tbl_job_user`
        $list_id_order = $this->_get_list_id_order_by_user_id($id_user, $iconn);
        $str_id_order = implode(',', $list_id_order);

        $ORDER_PENDING = ORDER_PENDING;
        $sql = "SELECT A.*, B.code_user as code_user
        FROM tbl_order as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.status != $ORDER_PENDING ";

        if ($str_id_order != '') {
            $sql .= " OR A.id_order IN ($str_id_order) ";
        }

        $sql .= " ORDER BY A.status ASC, A.create_time ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // gán list type_service vào đơn
                        $type_service = $this->_get_type_service_job_by_id_order($row['id_order'], $iconn);
                        $row['type_service'] = $type_service;

                        // gán total job vào đơn
                        $total_job = $this->_get_total_job_by_id_order($row['id_order'], $iconn);
                        $row['total_job'] = $total_job;

                        // gán danh sách qc, ed, custom vào đơn
                        $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        $row['team'] = $team;

                        $list_order[$row['id_order']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_order;
    }


    function get_list_order_by_status($status = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $status !== '' ? " AND A.status =? " : "";

        $sql = "
        SELECT A.*, B.name as style
        FROM tbl_order as A
        LEFT JOIN tbl_style as B ON A.id_style = B.id_style
        $where
        ORDER BY A.status ASC, A.create_time ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_order']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }


    /** 
     * LẤY DANH SÁCH ĐƠN THEO USER
     */
    function get_list_order_by_id_user($id_user)
    {
        $list_id_order = [];
        $list_order = [];
        $iconn = $this->db->conn_id;

        // lấy list id order theo user từ bảng `tbl_job_user`
        $list_id_order = $this->_get_list_id_order_by_user_id($id_user, $iconn);

        // lấy list info order theo list id_order
        if (!empty($list_id_order)) {
            $str_id_order = implode(',', $list_id_order);
            $sql = "SELECT A.*, B.code_user as code_user
            FROM tbl_order as A
            INNER JOIN tbl_user B ON A.id_user = B.id_user 
            WHERE id_order IN ($str_id_order)
            ORDER BY A.status ASC, A.create_time ASC";

            $stmt = $iconn->prepare($sql);
            if ($stmt) {
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // gán list type_service vào đơn
                            $type_service = $this->_get_type_service_job_by_id_order($row['id_order'], $iconn);
                            $row['type_service'] = $type_service;

                            // gán total job vào đơn
                            $total_job = $this->_get_total_job_by_id_order($row['id_order'], $iconn);
                            $row['total_job'] = $total_job;

                            // gán danh sách qc, ed, custom vào đơn
                            $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                            $row['team'] = $team;

                            $list_order[$row['id_order']] = $row;
                        }
                    }
                } else {
                    var_dump($stmt->errorInfo());
                    die;
                }
            }
            $stmt->closeCursor();
        }
        return $list_order;
    }

    function _get_list_id_order_by_user_id($id_user, $iconn)
    {
        $list_id_order = [];
        // lấy list id order theo user từ bảng `tbl_job_user`
        $sql = "SELECT A.id_order
        FROM tbl_job_user as A
        WHERE A.id_user= $id_user AND A.status = 1
        GROUP BY A.id_order";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $list_id_order[] = $row['id_order'];
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        return $list_id_order;
    }

    function _get_type_service_job_by_id_order($id_odrer, $iconn)
    {
        $data = [];
        $sql = "SELECT A.type_service
        FROM tbl_job as A
        WHERE id_order = $id_odrer
        GROUP BY A.type_service";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row['type_service'];
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function _get_total_job_by_id_order($id_odrer, $iconn)
    {
        $data = 0;
        $sql = "SELECT count(*) as total
        FROM tbl_job
        WHERE id_order= $id_odrer";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = $row['total'];
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function _get_list_editor_by_id_order($id_odrer, $iconn)
    {
        $data = [];
        $sql = "SELECT B.id_user, B.username, B.fullname, B.avatar
        FROM tbl_job_user as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user
        WHERE id_order= $id_odrer AND type_job_user IN (2,3,4,5) AND A.status = 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['id_user']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }


    function _get_list_job_by_order($id_order, $iconn)
    {
        $data = [];
        $where = 'WHERE 1=1 ';
        $where .= $id_order !== '' ? " AND A.id_order =? " : "";

        $sql = "
        SELECT A.*, B.name as room, C.name as service, C.type_service as type_service, D.name as style
        FROM tbl_job as A
        LEFT JOIN tbl_room as B ON A.id_room = B.id_room
        LEFT JOIN tbl_service as C ON A.id_service = C.id_service
        LEFT JOIN tbl_style as D ON A.id_style = D.id_style
        $where ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['year'] = date('Y', strtotime($row['create_time']));
                        $row['month'] = date('m', strtotime($row['create_time']));
                        $row['file_complete'] = $row['file_complete'] == null ? [] : json_decode($row['file_complete'], true);
                        $data[$row['id_job']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        return $data;
    }

    function _get_list_job_user_by_job($id_order, $id_job, $status, $type_working, $iconn)
    {
        $data = [];
        $sql = "SELECT A.*
        FROM tbl_job_user as A
        WHERE id_order = $id_order AND id_job = $id_job AND status = $status AND type_job_user = $type_working";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['id_user']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }
    //  END LẤY DANH SÁCH ĐƠN THEO USER

    // DANH SÁCH REWORK CỦA JOB
    function _get_list_rework_user_by_job($id_job, $iconn)
    {
        $data = [];
        $sql = "SELECT * FROM tbl_job_rework  WHERE id_job = $id_job ";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['attach']               = $row['attach']        == null ? [] : json_decode($row['attach'], true);
                $row['file_complete']        = $row['file_complete'] == null ? [] : json_decode($row['file_complete'], true);
                $data[$row['id_job_rework']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }
    // END DANH SÁCH REWORK CỦA JOB

    function box_count($list_order)
    {
        $box = [];
        foreach ($list_order as $id_order => $order) {
            $status = $order['status'];

            if ($status == ORDER_PENDING) {
                $box['pending'] = isset($box['pending']) ? $box['pending'] + 1 : 1;
            }
            if ($status == ORDER_REWORK) {
                $box['rework'] = isset($box['rework']) ? $box['rework'] + 1 : 1;
            }
            if ($status == ORDER_COMPLETE) {
                $box['complete'] = isset($box['complete']) ? $box['complete'] + 1 : 1;
            }
            if (in_array($status, [ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE])) {
                $box['progress'] = isset($box['progress']) ? $box['progress'] + 1 : 1;
            }

            if (is_late_order($order)) {
                $box['late'] = isset($box['late']) ? $box['late'] + 1 : 1;
            }
        }

        $list_image_avaiable = $this->danh_sach_image_avaiable();
        $box['image_avaiable'] = count($list_image_avaiable);

        return $box;
    }

    function box_for_qc_ed($list_order)
    {
        $box = [];
        foreach ($list_order as $id_order => $order) {
            $status = $order['status'];

            if (in_array($status, [ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE])) {
                $box['progress'] = isset($box['progress']) ? $box['progress'] + 1 : 1;
            }

            if ($status == ORDER_REWORK) {
                $box['rework'] = isset($box['rework']) ? $box['rework'] + 1 : 1;
            }

            if ($status == ORDER_COMPLETE) {
                $box['complete'] = isset($box['complete']) ? $box['complete'] + 1 : 1;
            }


            if (is_late_order($order)) {
                $box['late'] = isset($box['late']) ? $box['late'] + 1 : 1;
            }
        }

        return $box;
    }

    function tim_don_gan_nhat_cho_ed()
    {
        $data = 0;
        $iconn = $this->db->conn_id;
        $trang_thai = implode(',', [ORDER_AVAIABLE, ORDER_PROGRESS]);
        $sql = "SELECT * FROM tbl_order WHERE `status` IN ($trang_thai) ORDER BY id_order ASC LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            var_dump($stmt->errorInfo());
            die;
        }
        $stmt->closeCursor();
        return $data;
    }

    // TODO: chưa có lưu update_time bổ sung sau
    function update_status_order($id_order, $new_status)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET status=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$new_status, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function luu_thoi_gian_kiem_tra_don($id_order, $thoi_gian_kiem_tra)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_sale_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_kiem_tra, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function luu_thoi_gian_lam_xong_don($id_order, $thoi_gian_lam_xong)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_editor_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_lam_xong, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function luu_thoi_gian_giao_hang($id_order, $thoi_gian_giao_hang)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_qc_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_giao_hang, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function update_custom_order($id_order, $custom)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET custom=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$custom, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function update_custom_order_for_user($id_order, $custom, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET custom=?, withdraw_status = 0 WHERE id_order=? AND id_job = 0 AND id_user=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$custom, $id_order, $id_user];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function add_job_user($id_order, $id_job, $id_user, $username, $type_service, $type_job_user, $status, $time_join, $unit)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_job_user (id_order, id_job, id_user, username, type_service, type_job_user, status, time_join, custom) VALUES (?, ?, ?, ?, ?, ?, ?, ?, $unit)";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_job, $id_user, $username, $type_service, $type_job_user, $status, $time_join];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function kiem_tra_user_da_ton_tai_trong_job_chua($id_order, $id_job, $type_job_user, $id_user)
    {
        $data = false;
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_job_user WHERE id_order=? AND id_job=? AND type_job_user=? AND id_user=? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order, $id_job, $type_job_user, $id_user])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $data = true;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        return $data;
    }

    function change_status_job_user($status, $id_order, $id_job, $type_job_user, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET status=? WHERE id_order=? AND id_job=? AND type_job_user=? AND id_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$status, $id_order, $id_job, $type_job_user, $id_user];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function thay_doi_status_tat_ca_job_user($status, $id_order, $id_job, $type_job_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET status=? WHERE id_order=? AND id_job=? AND type_job_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$status, $id_order, $id_job, $type_job_user];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function danh_sach_image_avaiable()
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.* 
        FROM `tbl_job` A
        WHERE A.id_order IN (SELECT B.id_order FROM tbl_order B WHERE B.status IN (?,?,?))
        AND A.id_job NOT IN (SELECT C.id_job FROM tbl_job_user C WHERE C.id_job = A.id_job AND C.type_job_user = ? AND C.status = 1)
        ORDER BY A.create_time ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE, WORKING_EDITOR])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $data[$row['id_job']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

    function get_order_info_by_code($code)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_order WHERE code_order = ? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$code])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_code_order($id_order, $code)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET code_order=? WHERE id_order=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$code, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function update_custom_time_order($id_order, $second)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET custom_time=? WHERE id_order=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$second, $id_order];

            if ($stmt->execute($param)) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function tinh_tien_cho_cac_user_dang_active($id_order)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET withdraw=1 WHERE id_order= $id_order AND status=1;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function add_order($id_style, $create_time, $id_user, $pending_pay, $status, $order_type, $create_id_user)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order (id_style, create_time, id_user, pending_pay, status, order_type, create_id_user) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_style, $create_time, $id_user, $pending_pay, $status, $order_type, $create_id_user];

            if ($stmt->execute($param)) {
                $new_id = $iconn->lastInsertId();
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $new_id;
    }

    function add_order_job($id_order, $id_service, $type_service, $price, $price_unit, $id_room, $id_style, $image, $attach, $requirement, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_job (id_order, id_service, type_service, price, price_unit, id_room, id_style, image, attach, requirement, create_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_service, $type_service, $price, $price_unit, $id_room, $id_style, $image, $attach, $requirement, $create_time];

            if ($stmt->execute($param)) {
                $new_id = $iconn->lastInsertId();
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $new_id;
    }

    function delete_order_and_job($id_order)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "DELETE FROM tbl_order WHERE id_order= $id_order; ";
        $sql .= "DELETE FROM tbl_job WHERE id_order= $id_order; ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }
}
