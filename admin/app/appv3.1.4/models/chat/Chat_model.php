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

    function da_xem_all_msg_group($chat_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat__ SET da_xem=1 WHERE id_user= ?";

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

    function add_member_group($id_gchat, $id_member, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat__member_group (id_gchat, id_user, join_time) VALUES (?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_gchat, $id_member, $create_time];

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

    function list_group_by_user($id_user)
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
            WHERE row_num = 1 
            ORDER BY create_time DESC;
            
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
                    $row['strtotime'] = strtotime($row['create_time']);
                    $data['msg_newest'][$row['id_gchat']] = $row;
                }

                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $data['member'][$row['id_gchat']][$row['id_user']] = $row;
                }

                // dữ liệu bổ sung
                foreach ($data['list'] as $id_gchat => $gchat) {

                    // bổ sung tên nhóm nếu tên nhóm chưa được đặt
                    if ($gchat['name'] === null || $gchat['name'] === '') {
                        $list_mem = $data['member'][$id_gchat];
                        $name_group = [];
                        foreach ($list_mem as $id_mem => $mem) {
                            $name_group[$id_mem] = $mem['fullname'];
                            $data['list'][$id_gchat]['name'] = implode(', ', $name_group);
                        }
                    }

                    // bổ sung tin nhắn mới nhất của nhóm
                    $msg_newest = isset($data['msg_newest'][$id_gchat]) ? $data['msg_newest'][$id_gchat] : [];
                    $data['list'][$id_gchat]['msg_newest'] = $msg_newest;
                }

                // sắp xếp lại mảng gruop theo tin nhắn mới nhất lên đầu mảng
                // function cb($a, $b){
                //     if(!isset($a['msg_newest']['strtotime']) || !isset($b['msg_newest']['strtotime'])) {
                //         return 0;
                //     }
                //     if($a['msg_newest']['strtotime'] == $b['msg_newest']['strtotime']){
                //         return 0;
                //     }
                //     return ($a['msg_newest']['strtotime'] > $b['msg_newest']['strtotime']) ? -1 : 1;
                // }
                // uasort($data['list'], "cb");

            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        // die;
        $stmt->closeCursor();
        return $data;
    }

    function gchat_info($id_gchat, $id_user)
    {
        $data = [];
        $data['info'] = [];
        $data['msg_newest'] = [];
        $data['member'] = [];
        $data['member_ids'] = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT * FROM tbl_chat__all_group WHERE id_gchat=$id_gchat;

            /* tin nhắn mới nhất trong nhóm */
            SELECT * FROM tbl_chat__msg WHERE id_gchat = $id_gchat ORDER BY create_time DESC LIMIT 1;
            
            /* ds thành viên trong nhóm */
            SELECT t1.*, t2.username, t2.fullname, t2.avatar  
            FROM tbl_chat__member_group t1
            INNER JOIN tbl_user t2 ON t1.id_user = t2.id_user
            WHERE id_gchat = $id_gchat;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {

                // info nhóm
                $info = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($info !== false) {
                    $info['avatar_url'] = url_image($info['avatar'] == null ? AVATAR_DEFAULT : $info['avatar'], FOLDER_AVATAR);
                    $data['info'] = $info;
                }

                // tin nhắn mới nhất trong nhóm
                $stmt->nextRowset();
                $msg = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($msg !== false) {
                    $msg['strtotime'] = strtotime($msg['create_time']);
                    $data['msg_newest'] = $msg;
                }

                // thành viên trong nhóm
                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // không thêm user hiện tại vào mảng member
                    if($row['id_user'] != $id_user) {
                        $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                        $data['member'][$row['id_user']] = $row;
                    }
                    
                    $data['member_ids'][] = $row['id_user'];
                }

                // bổ sung tên nhóm nếu tên nhóm chưa được đặt
                if ($info !== false) {
                    if ($info['name'] === null || $info['name'] === '') {
                        $name_group = [];
                        foreach ($data['member'] as $id_mem => $mem) {
                            $name_group[$id_mem] = $mem['fullname'];
                            $data['info']['name'] = implode(', ', $name_group);
                        }
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        // die;
        $stmt->closeCursor();
        return $data;
    }

    function chat_list_by_group($id_group)
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname_user, B.avatar as avatar
        FROM tbl_chat__msg as A
        LEFT JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_gchat = ?
        ORDER BY A.id_msg ASC; ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_group])) {
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

    function msg_add_to_group($id_gchat, $id_user, $content, $file, $create_time, $status, $ip, $fullname, $phone, $email, $action_by)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat__msg (id_user, content, file, create_time, id_gchat) VALUES (?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_user, $content, $file, $create_time, $id_gchat];

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

    function msg_info($id_msg)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname, B.avatar as avatar
        FROM tbl_chat__msg as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_msg = $id_msg;";

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

    // END GROUP
}
