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
        $data['members'] = [];
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
            SELECT a.*, b.seen_time FROM tmp_ranked_chat a 
            LEFT JOIN tbl_chat__msg_user b ON a.id_msg = b.id_msg AND b.id_user = $id_user
            WHERE a.row_num = 1 
            ORDER BY a.create_time DESC;
            
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
                    $data['members'][$row['id_gchat']][$row['id_user']] = $row;
                }

                // dữ liệu bổ sung
                foreach ($data['list'] as $id_gchat => $gchat) {

                    // bổ sung tên nhóm nếu tên nhóm chưa được đặt
                    if ($gchat['name'] === null || $gchat['name'] === '') {
                        $list_mem = $data['members'][$id_gchat];
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
        $data['members'] = [];
        $data['member_ids'] = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT * FROM tbl_chat__all_group WHERE id_gchat=$id_gchat;

            /* tin nhắn mới nhất trong nhóm */
            SELECT a.*, b.seen_time
            FROM tbl_chat__msg a
            INNER JOIN tbl_chat__msg_user b ON a.id_msg=b.id_msg AND b.id_user=$id_user
            WHERE a.id_gchat = $id_gchat ORDER BY a.create_time DESC LIMIT 1;
            
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
                    $data['msg_newest'] = $msg;
                }

                // thành viên trong nhóm
                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    // tất ca id thành viên
                    $data['member_ids'][] = $row['id_user'];

                    // không thêm user hiện tại vào mảng member
                    if ($row['id_user'] != $id_user) {
                        $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                        $data['members'][$row['id_user']] = $row;
                    }
                }

                // bổ sung tên nhóm nếu tên nhóm chưa được đặt
                if ($info !== false) {
                    if ($info['name'] === null || $info['name'] === '') {
                        $name_group = [];
                        foreach ($data['members'] as $id_mem => $mem) {
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

    function chat_list_by_group($id_group, $limit, $offset)
    {
        $data['total'] = 0;
        $data['list'] = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT count(*) as total FROM tbl_chat__msg WHERE id_gchat = $id_group;
        SELECT A.*, B.username as username, B.role as role, B.fullname as fullname_user, B.avatar as avatar
        FROM tbl_chat__msg as A
        LEFT JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_gchat = $id_group
        ORDER BY A.id_msg DESC
        LIMIT $limit OFFSET $offset; ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {

                $row =  $stmt->fetch(PDO::FETCH_ASSOC);
                $data['total'] = $row['total'];

                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $row['avatar_url'] = url_image($row['avatar'] == '' ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $row['file_list']  = json_decode($row['file'], true);

                    $data['list'][] = $row;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

    function msg_add_to_group($id_gchat, $id_user, $content, $file, $create_time, $reply)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $ip = ip_address();
        $sql = "INSERT INTO tbl_chat__msg (id_user, content, file, create_time, id_gchat, ip, reply) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_user, $content, $file, $create_time, $id_gchat, $ip, $reply];

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

    function sync_msg_to_tbl_msg_user($id_gchat, $id_msg, $created_time, $member_ids, $curr_uid)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat__msg_user (id_gchat, id_msg, id_user, created_time) VALUES ";

        //set other member = chua xem
        $value=[];
        foreach($member_ids as $id_user) {
            $value[] = "($id_gchat, $id_msg, $id_user, '$created_time')";
        }
        $sql .= implode(', ', $value) . ";";

        //set current member = da xem
        $sql .=  "UPDATE tbl_chat__msg_user SET seen_time = '$created_time'  WHERE id_gchat= $id_gchat AND id_user = $curr_uid; ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [];

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

    function delete_member_group($id_gchat, $id_member)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "DELETE FROM `tbl_chat__member_group` WHERE `id_gchat` = '$id_gchat' AND `id_user` = '$id_member';";
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

    function edit_name_group($id_gchat, $name_group)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat__all_group SET name=? WHERE id_gchat= ?; ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name_group, $id_gchat];

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

    function delete_msg_group($id_msg, $text_del)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE `tbl_chat__msg` SET `content` = '$text_del', `file` = '{}' WHERE `id_msg` = '$id_msg';";
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

    function delete_group($id_gchat)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        
        $sql = "DELETE FROM `tbl_chat__all_group` WHERE `id_gchat` = '$id_gchat'; ";
        $sql .= "DELETE FROM `tbl_chat__member_group` WHERE `id_gchat` = '$id_gchat'; ";
        $sql .= "DELETE FROM `tbl_chat__msg` WHERE `id_gchat` = '$id_gchat'; ";
        $sql .= "DELETE FROM `tbl_chat__msg_user` WHERE `id_gchat` = '$id_gchat'; ";

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

    function set_da_xem_all_msg_group_v2($id_group, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $seen_time = date('Y-m-d H:i:s');
        $sql = "UPDATE tbl_chat__msg_user SET seen_time = '$seen_time' WHERE id_gchat= $id_group AND id_user = $id_user AND ISNULL(seen_time);";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [];

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

    function count_msg_chua_xem($id_user) {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT count(*) num FROM tbl_chat__msg_user  WHERE id_user = $id_user AND ISNULL(seen_time);";

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
        return $data['num'];
    }
    // END GROUP
}
