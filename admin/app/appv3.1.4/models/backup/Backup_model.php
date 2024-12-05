<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    function origin_order_job_file_list($status_order)
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    function origin_order_rework_file_list($status_order)
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    function origin_order_discuss_file_list($status_order)
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    function bak_order_discuss_file_list()
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_bak_order  WHERE file_type = ? AND ISNULL(unlink_time);";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([FILE_DISCUSS])) {
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    function bak_order__unlink_time__update($list_id)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $datatime = date('Y-m-d H:i:s');

        $placeholders = implode(',', array_fill(0, count($list_id), '?'));
        $sql = "UPDATE tbl_bak_order SET unlink_time = '$datatime' WHERE id IN ($placeholders)";

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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    /**
     * Lọc file để xóa rác, điều kiện:
     * - 
     */
    function bak_order_file_list()
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_bak_order  WHERE ISNULL(unlink_time);";

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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    /**
     * Lọc file gửi cho local, điều kiện:
     * - chỉ lấy file có file_type: main, ref, complete
     * - file chưa đc tải: download_time = null
     * ❌ đang lọc theo từng tháng vì dữ liệu rất nhiều.
     */
    function bak_send_order_to_local($type)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $placeholders = implode(',', array_fill(0, count($type), '?'));
        $sql = "SELECT * FROM tbl_bak_order  WHERE ISNULL(download_time) AND file_type IN ($placeholders) AND MONTH(order_create_time) = 4;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute($type)) {
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

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    /**
     * update download_time theo filename
     * ❌ đang tạm bỏ
     */
    function bak_order__download_time__update_v1($id_order, $filename)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $datatime = date('Y-m-d H:i:s');
        $sql = "UPDATE tbl_bak_order SET download_time = '$datatime' WHERE id_order =? AND filename =? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $stmt->execute([$id_order, $filename]);
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        return $exc;
    }

    // ✅ ✅ ✅ ✅ ✅ ✅ 
    /**
     * update download_time theo đơn
     */
    function bak_order__download_time__update($id_order)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $datatime = date('Y-m-d H:i:s');
        $sql = "UPDATE tbl_bak_order SET download_time = '$datatime' WHERE id_order =? AND file_type IN (?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $stmt->execute([$id_order, FILE_MAIN, FILE_REF, FILE_COMPLETE]);
            if ($stmt->execute()) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        return $exc;
    }


    /**
     * Lọc file để unlink, điều kiện:
     * - chỉ unlink file có file_type: MAIN REF COMPLETE
     * - file đã đc tải: download_time != null
     * ❌ đang lọc theo từng tháng vì dữ liệu rất nhiều.
     */
    function bak_order_unlink_list()
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_bak_order  WHERE !ISNULL(download_time) AND ISNULL(unlink_time) AND file_type IN (?, ?, ?);";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([FILE_MAIN, FILE_REF, FILE_COMPLETE])) {
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

    function bak_thong_ke()
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT 
                CONCAT(MONTH(order_create_time), '-', YEAR(order_create_time)) AS 'Tháng',
                COUNT(DISTINCT id_order) AS 'Tổng đơn',  -- Tổng số bản ghi `id_order` khác nhau
                SUM(CASE WHEN file_type = 1 THEN 1 ELSE 0 END) AS 'Files Main',
                SUM(CASE WHEN file_type = 2 THEN 1 ELSE 0 END) AS 'Files Ref',
                SUM(CASE WHEN file_type = 3 THEN 1 ELSE 0 END) AS 'Files Complete',
                SUM(CASE WHEN file_type = 5 THEN 1 ELSE 0 END) AS 'Files Nội Bộ',
                COUNT(*) AS 'Tổng Files',  -- Tổng số bản ghi
                SUM(CASE WHEN unlink_time IS NOT NULL THEN 1 ELSE 0 END) AS 'Tổng Files delete',
                SUM(CASE WHEN download_time IS NOT NULL THEN 1 ELSE 0 END) AS 'Files backup'
            FROM 
                tbl_bak_order
            GROUP BY 
                YEAR(order_create_time), MONTH(order_create_time)
            ORDER BY 
                YEAR(order_create_time), MONTH(order_create_time);
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
}
