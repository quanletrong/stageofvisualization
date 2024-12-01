<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function order_discuss_file_list()
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

    function order_discuss_file__bak_date_time__update($list_id)
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

    function order_file_list()
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
