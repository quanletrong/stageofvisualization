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
        $sql = "SELECT * FROM tbl_order WHERE id_order = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
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

        $sql = "SELECT A.*
        FROM tbl_order as A
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
            $sql = "SELECT A.*
            FROM tbl_order as A
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
        }

        $stmt->closeCursor();
        return $list_order;
    }

    function _get_list_id_order_by_user_id($id_user, $iconn)
    {
        $list_id_order = [];
        // lấy list id order theo user từ bảng `tbl_job_user`
        $sql = "SELECT A.id_order
        FROM tbl_job_user as A
        WHERE id_user= $id_user
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
        WHERE id_order= $id_odrer AND type_job_user IN (2,3,4)";

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

    function tim_don_gan_nhat($status)
    {
        $data = 0;
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_order WHERE status = $status LIMIT 1";

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
}
