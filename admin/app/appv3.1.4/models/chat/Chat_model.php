<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function list_user_chat()
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql =
            "WITH ranked_chat AS (
            SELECT m.*, ROW_NUMBER() OVER (PARTITION BY id_user ORDER BY id_chat DESC) AS rn
            FROM tbl_chat AS m
        )
        SELECT A.*,  B.username as `username`, B.role as `role`, B.fullname as `fullname_user`, B.avatar as `avatar`
        FROM ranked_chat as A
        LEFT JOIN tbl_user B ON B.id_user = A.id_user
        WHERE rn = 1;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['file'], true);

                        $list_chat[] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_chat;
    }

    //TODO: bỏ
    function list_vang_lai_chat()
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql =
            "WITH ranked_chat AS (
            SELECT m.*, ROW_NUMBER() OVER (PARTITION BY ip ORDER BY id_chat DESC) AS rn
            FROM tbl_chat AS m
              WHERE m.id_user = 0
        )
        SELECT A.* FROM ranked_chat as A WHERE rn = 1;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image(AVATAR_DEFAULT, FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['file'], true);

                        $list_chat[] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_chat;
    }

    function chat_list_by_user($id_user)
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname_user, B.avatar as avatar
        FROM tbl_chat as A
        LEFT JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_user = ?
        ORDER BY A.id_chat ASC; ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_user])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image($row['avatar'] == '' ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['file'], true);

                        $list_chat[] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_chat;
    }

    //TODO: bỏ
    function chat_list_by_vang_lai($ip)
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*
        FROM tbl_chat as A
        WHERE A.ip = '$ip' AND A.id_user = 0
        ORDER BY A.id_chat ASC; ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image(AVATAR_DEFAULT, FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['file'], true);

                        $list_chat[] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_chat;
    }

    function chat_add($id_user, $content, $file, $create_time, $status, $ip, $fullname, $phone, $email, $action_by)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat (id_user, content, file, create_time, status, ip, fullname, phone, email, action_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_user, $content, $file, $create_time, $status, $ip, $fullname, $phone, $email, $action_by];

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

    function chat_info_by_action_by($id_chat)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname, B.avatar as avatar
        FROM tbl_chat as A
        INNER JOIN tbl_user B ON A.action_by = B.id_user 
        WHERE A.id_chat = $id_chat;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $data['avatar_url'] = url_image($data['avatar'], FOLDER_AVATAR);
                    $data['file_list']  = json_decode($data['file'], true);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }
    // END CHAT TONG

    function delete_chat_user($chat_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "DELETE FROM `tbl_chat` WHERE `id_user` = '$chat_user' ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function da_xem_all_chat_user($chat_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat SET da_xem=1 WHERE id_user= ?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$chat_user];

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

    // GROUP 
    function all_group()
    {
        $all_group = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT * FROM tbl_chat__all_group;";
        $sql .=
            "SELECT A.id_gchat, GROUP_CONCAT( DISTINCT A.id_user ) members 
            FROM tbl_chat__member_group A 
            WHERE id_gchat IN ( SELECT id_gchat FROM tbl_chat__all_group ) 
            GROUP BY A.id_gchat";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {


                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $all_group[$row['id_gchat']] = $row;
                    }
                }

                // Lấy ra thành viên trong nhóm
                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $members = [];
                    foreach (explode(',', $row['members']) as $id_member) {
                        $members[$id_member] = $id_member;
                    }

                    $all_group[$row['id_gchat']]['members'] = $members;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $all_group;
    }

    function add_group($name, $avatar, $key_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat__all_group (name, avatar, key_user, created_time) VALUES (?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $avatar, $key_user, $create_time];

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

    function add_member_group($id_gchat, $id_member, $username, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat__member_group (id_gchat, id_user, username, join_time) VALUES (?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_gchat, $id_member, $username, $create_time];

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

    function list_group($id_user)
    {
        $data = [];
        $data['list'] = [];
        $data['msg_newest'] = [];
        $data['member'] = [];
        $iconn = $this->db->conn_id;

        $sql =
            "CREATE TEMPORARY TABLE tmp_all_group (
                SELECT * 
                FROM tbl_chat__all_group 
                WHERE id_gchat IN (SELECT b.id_gchat FROM tbl_chat__member_group b WHERE b.id_user = $id_user)
            );
            
            CREATE TEMPORARY TABLE tmp_ranked_chat (
                SELECT *, ROW_NUMBER () OVER ( PARTITION BY id_gchat ORDER BY id_msg DESC ) AS row_num 
                FROM tbl_chat__msg 
                WHERE id_gchat IN (SELECT id_gchat FROM tmp_all_group)
            );
            
            /* ds nhóm*/
            SELECT * FROM tmp_all_group;

            /* tin nhắn mới nhất trong nhóm */
            SELECT a.*, IF(b.id_da_xem IS NULL, 0, 1) da_xem FROM tmp_ranked_chat a 
            LEFT JOIN tbl_chat__msg_da_xem b ON a.id_msg = b.id_msg AND b.id_user = $id_user
            WHERE row_num = 1;
            
            /* ds thành viên trong nhóm */
            SELECT t1.*, t2.username, t2.fullname, t2.avatar  
            FROM tbl_chat__member_group t1
            INNER JOIN tbl_user t2 ON t1.id_user = t2.id_user
            WHERE id_gchat IN(SELECT id_gchat FROM tmp_all_group) AND t1.id_user <> $id_user;

            DROP TABLE IF EXISTS tmp_all_group;
            DROP TABLE IF EXISTS tmp_ranked_chat;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {

                $stmt->nextRowset();
                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $data['list'][$row['id_gchat']] = $row;
                }

                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data['msg_newest'][$row['id_gchat']] = $row;
                }

                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $data['member'][$row['id_gchat']][$row['id_user']] = $row;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }
    // END GROUP
}
