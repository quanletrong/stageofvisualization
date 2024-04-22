<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Async_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function process_get_file_order()
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "
            SELECT a.id_job, a.id_order, a.image, a.attach, a.file_complete, b.attach as rw_attach, b.file_complete as rw_file_complete, a.create_time, b.create_time as rw_create_time, c.done_qc_time, c.`status`
            FROM tbl_job a
            LEFT JOIN tbl_job_rework b on a.id_job = b.id_job
            INNER JOIN tbl_order c on a.id_order = c.id_order
            WHERE c.done_qc_time < '2024-04-01'
            ORDER BY a.id_order ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
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

    function process_get_file_discuss_order($id_orders)
    {

        $data = [];
        $iconn = $this->db->conn_id;

        $sql = 
        "SELECT a.file, a.id_order, b.create_time as create_time_order
        FROM tbl_order_discuss a
        INNER JOIN tbl_order b on a.id_order = b.id_order
        WHERE a.id_order IN ($id_orders) 
        ORDER BY a.id_order ASC";
        
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
