<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
	}

    function get_user_info_by_email($email) {
        $data = array();
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE email = :email";
        $stmt = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            } else {
                // var_dump($stmt->errorInfo());die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }
    
    function get_user_info_by_phone($phone) {
        $data = array();
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE phone = :phone";
        $stmt = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            } else {
                // var_dump($stmt->errorInfo());die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }
    
    function get_user_info_by_uname($uname) {
        $data = array();
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE username = :uname";
        $stmt = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':uname', $uname, PDO::PARAM_STR);

            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            } else {
                // var_dump($stmt->errorInfo());die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }
    
    function get_user_info_by_uid($uid) {
        $data = array();
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE id_user = :uid";
        $stmt = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);

            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            } else {
                // var_dump($stmt->errorInfo());die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }
    
    
    
    function add($uname, $pass, $fullname, $email, $phone, $avatar, $role, $status, $uid_creare = 0)
    {
        $id = 0;
        $type = 2;
        $user_service = '{}';
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_user (username, password, fullname, email, phone, avatar, role, status, `type`, user_service, id_user_create, create_time, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$uname, $pass, $fullname, $email, $phone, $avatar, $role, $status, $type, $user_service, $uid_creare , date('Y-m-d H:i:s'), ""];

            if ($stmt->execute($param)) {
                $id = $iconn->lastInsertId();
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $id;
    }

    function edit($fullname, $email, $phone, $avatar, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user SET fullname =?, email =?, phone =?, avatar =? WHERE id_user =? ;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {

            if ($stmt->execute([$fullname, $email, $phone, $avatar, $id_user])) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function edit_password($password, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user SET password =? WHERE id_user =? ;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {

            if ($stmt->execute([$password, $id_user])) {
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