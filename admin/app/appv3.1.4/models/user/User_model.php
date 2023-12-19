<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_list_user($code, $username, $fullname, $phone, $email, $role, $status, $limit, $offset)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $SQL['query'] = '';
        $SQL['param'] = [];

        $SQL['query'] = "
        SELECT A.*
        FROM tbl_user as A ";

        $SQL['query'] .= " WHERE 1=1 ";
        $SQL = sql_like($code, 'A.code_usre', $SQL);
        $SQL = sql_like($username, 'A.username', $SQL);
        $SQL = sql_like($fullname, 'A.fullname', $SQL);
        $SQL = sql_like($phone, 'A.phone', $SQL);
        $SQL = sql_like($email, 'A.email', $SQL);
        $SQL = sql_in($role, 'A.role', $SQL);
        $SQL = sql_in($status, 'A.status', $SQL);

        $SQL['query'] .= " ORDER BY A.id_user DESC  LIMIT $limit OFFSET $offset";

        $stmt = $iconn->prepare($SQL['query']);
        if ($stmt) {
            if ($stmt->execute($SQL['param'])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['user_service'] = json_decode($row['user_service'], true);
                        $row['avatar'] = url_image($row['avatar'], FOLDER_AVATAR);
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

    function add($code_user, $username, $pass_hash, $fullname, $phone, $email, $status, $role, $type, $user_service_db, $create_time, $avatar)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_user (code_user, username, `password`, fullname, phone, email, `status`, `role`, `type`, user_service, create_time, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$code_user, $username, $pass_hash, $fullname, $phone, $email, $status, $role, $type, $user_service_db, $create_time, $avatar];

            if ($stmt->execute($param)) {
                $new_id = $iconn->lastInsertId();
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $new_id;
    }

    function edit($code_user, $fullname, $pass_hash, $phone, $email, $status, $role, $type, $user_service_db, $update_time, $id_user, $avatar)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user 
        SET code_user=?, fullname=?, `password`=?, phone=?, email=?, `status`=?, `role`=?, `type`=?, `user_service`=?, update_time=?, `avatar`=? 
        WHERE id_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$code_user, $fullname, $pass_hash, $phone, $email, $status, $role, $type, $user_service_db, $update_time, $avatar, $id_user])) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function edit_info($fullname, $phone, $email, $id_user, $avatar)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user 
        SET fullname=?, phone=?, email=?, `avatar`=? 
        WHERE id_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$fullname, $phone, $email, $avatar, $id_user])) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function edit_password($pass_hash, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_user 
        SET `password`=?
        WHERE id_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$pass_hash, $id_user])) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function get_list_user_working($status, $role)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT *
        FROM tbl_user
        WHERE status IN ($status) AND role IN ($role) 
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
                $data['avatar_url'] = url_image($data['avatar'], FOLDER_AVATAR);
                $data['user_service'] = json_decode($data['user_service'], true);
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

    function get_user_info_by_phone($phone)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE phone = ? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$phone])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_user_info_by_email($email)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE email = ? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$email])) {
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
