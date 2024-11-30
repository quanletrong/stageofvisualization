<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function discuss_list()
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $FILE_DISCUSS = FILE_DISCUSS;
        $sql =
            "SELECT * FROM tbl_bak_order 
            WHERE file_type = $FILE_DISCUSS 
                AND ISNULL(bak_date_time) 
                AND MONTH(order_create_time) = 3 
                AND id_order = 142
                AND YEAR(order_create_time) = YEAR(CURDATE());";

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

    function discuss_update_bak_date_time($list_id)
    {
        $exc = false;
        $iconn = $this->db->conn_id;

        $placeholders = implode(',', array_fill(0, count($list_id), '?'));
        $sql = "UPDATE tbl_bak_order SET bak_date_time = NOW() WHERE id_discuss IN ($placeholders)";

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
}
