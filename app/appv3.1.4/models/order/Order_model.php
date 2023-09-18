<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add_order($id_style, $create_time, $id_user, $coupon, $pending_pay, $status)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order (id_style, create_time, id_user, coupon, pending_pay, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_style, $create_time, $id_user, $coupon, $pending_pay, $status];

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

    /** 
     * LẤY DANH SÁCH ĐƠN THEO USER
     */
    function get_list_order_by_id_user($id_user)
    {
        $list_order = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*
            FROM tbl_order as A
            WHERE id_user = $id_user
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
                        // $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        // $row['team'] = $team;

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
    //  END LẤY DANH SÁCH ĐƠN THEO USER


    function get_info_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.username FROM tbl_order as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user
        WHERE A.id_order = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_list_job_by_order($id_order = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

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
}
