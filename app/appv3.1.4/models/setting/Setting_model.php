<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_setting()
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = " SELECT * FROM tbl_setting LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_home_slide($home_slide)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET home_slide=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$home_slide])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_hiw($hiw)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET hiw=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$hiw])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

}
