<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_list_user_working($status, $role)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT *
        FROM tbl_user
        WHERE status = $status AND role IN ($role) 
        ORDER BY role ASC";
        
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_user']] = $row;
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

    function get_user_info_by_id($uid)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = " SELECT * FROM tbl_user WHERE id_user = ?";
        
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$uid])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_user_info_by_code($code)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE code_user = ? LIMIT 1";
        
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

    function update_code_user($id_user, $code)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user SET code_user=? WHERE id_user=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$code, $id_user];

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
