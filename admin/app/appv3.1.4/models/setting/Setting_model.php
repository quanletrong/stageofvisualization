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

    function update_happy_guaranteed($happy_guaranteed)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET happy_guaranteed=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$happy_guaranteed])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_privacy_policy($privacy_policy)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET privacy_policy=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$privacy_policy])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_refund_policy($refund_policy)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET refund_policy=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$refund_policy])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_termsofuse($termsofuse)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET termsofuse=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$termsofuse])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_info($phone, $email, $address, $link_facebook, $link_youtube, $link_instagram, $link_linkedin, $logo_ngang, $logo_vuong)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET phone=?, email=?, address=?, link_facebook=?, link_youtube=?, link_instagram=?, link_linkedin=?, logo_ngang=?, logo_vuong=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$phone, $email, $address, $link_facebook, $link_youtube, $link_instagram, $link_linkedin, $logo_ngang, $logo_vuong])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_max_order_working($max_order_working)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "UPDATE tbl_setting SET max_order_working=? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$max_order_working])) {
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
