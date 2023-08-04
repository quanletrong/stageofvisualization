<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_info_order($id_order) {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_order WHERE id_order = ? LIMIT 1";
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
}
