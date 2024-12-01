<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    // TODO: moi tạo model
    function order_job_file_list($status_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_job, b.image, b.attach, b.file_complete
             FROM tbl_order as a
             INNER JOIN tbl_job as b ON a.id_order = b.id_order
             INNER JOIN tbl_user as c ON c.id_user = a.id_user
             WHERE 
                 1 = 1
                 -- AND MONTH(a.create_time) =1
                 AND a.status = $status_order
                 AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
             ORDER BY a.id_order;
             ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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

    // TODO: moi tạo model
    function order_rework_file_list($status_order)
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_job, b.id_job_rework, b.attach, b.file_complete
            FROM tbl_order as a
            INNER JOIN tbl_job_rework as b ON a.id_order = b.id_order
            INNER JOIN tbl_user as c ON c.id_user = a.id_user
            WHERE 
                1 = 1
                AND a.status = $status_order
                AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
            ORDER BY a.id_order;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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

    // ✅
    function order_discuss_file_list($status_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_discuss, b.file
            FROM tbl_order as a
            INNER JOIN tbl_order_discuss as b ON a.id_order = b.id_order AND b.file != '{}'
            INNER JOIN tbl_user as c ON c.id_user = a.id_user
            WHERE 
            1 = 1
            AND a.status = $status_order
            AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
            ORDER BY a.id_order;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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

    // ✅
    function bak_order_job_file_insert($str_values)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $sql = "INSERT IGNORE INTO tbl_bak_order (id_order, order_create_time, order_create_by, id_job, filename, file_type) VALUES $str_values;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $exc;
    }

    // TODO: moi tạo model
    function bak_order_rework_file_insert($str_values)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $sql = "INSERT IGNORE INTO tbl_bak_order (id_order, order_create_time, order_create_by, id_job, id_rework, filename, file_type) VALUES $str_values";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $exc;
    }
    // ✅
    function bak_order_discuss_file_insert($str_values)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $sql = "INSERT IGNORE INTO tbl_bak_order (id_order, order_create_time, order_create_by, filename, id_discuss, file_type) VALUES $str_values";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $exc;
    }

    // ✅
    function bak_order_discuss_file_list()
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $FILE_DISCUSS = FILE_DISCUSS;
        $sql =
            "SELECT * FROM tbl_bak_order 
            WHERE file_type = $FILE_DISCUSS 
                AND ISNULL(bak_date_time);";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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

    // ✅
    function bak_order_discuss_file__bak_date_time__update($list_id)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $datatime = date('Y-m-d H:s:i');

        $placeholders = implode(',', array_fill(0, count($list_id), '?'));
        $sql = "UPDATE tbl_bak_order SET bak_date_time = '$datatime' WHERE id_discuss IN ($placeholders)";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $stmt->execute($list_id);
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        return $exc;
    }

    // ✅
    function bak_order_file_list()
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_bak_order  WHERE ISNULL(bak_date_time);";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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
