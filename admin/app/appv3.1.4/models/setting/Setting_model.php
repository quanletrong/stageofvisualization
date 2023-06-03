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

    function update_home_why_virtually($why_virtually_stage)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET why_virtually_stage=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$why_virtually_stage])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_home_why_stageofvisualization($why_stageofvisualization)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET why_stageofvisualization=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$why_stageofvisualization])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_home_asked_question($asked_question)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET asked_question=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$asked_question])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_home_feedback($feedback)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET feedback=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$feedback])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_partner($partner)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET partner=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$partner])) {
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
