<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat_customer_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    // OK 2025
    function room_info_by_id_user($id_customer)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT a.*, b.username, b.avatar 
            FROM `tbl_chat_customer__room` as a
            INNER JOIN tbl_user as b ON a.id_customer = b.id_user
            WHERE a.id_customer = ?;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_customer];

            if ($stmt->execute($param)) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    // OK 2025
    function room_add($id_customer)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_chat_customer__room (id_customer) VALUES (?);";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_customer];

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

    // OK 2025
    function list_chat_by_room($id_room)
    {
        $list_chat = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT a.*, b.username, b.avatar FROM `tbl_chat_customer__msg` as a
            INNER JOIN tbl_user as b ON a.id_user = b.id_user
            WHERE a.id_room = $id_room;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image($row['avatar'], FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['files'], true);

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

    // OK 2025
    function update_newest_msg_to_room($id_msg, $id_room)
    {
        $exc = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat_customer__room  SET id_msg_newest = ? WHERE id_room = ? ;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_msg, $id_room];

            if ($stmt->execute($param)) {
                $exc = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $exc;
    }

    // OK 2025
    function msg_info($id_msg)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT 
                -- nội dung tin nhắn
                a.*, 

                -- thông tin người tạo tin nhắn
                b.username, b.avatar, b.fullname

            FROM tbl_chat_customer__msg as a

            -- lấy thông tin người tạo tin nhắn
            INNER JOIN tbl_user as b ON a.id_user = b.id_user
            
            WHERE a.id_msg = ?;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_msg])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $data['avatar_url'] = url_image($data['avatar'], FOLDER_AVATAR);
                    $data['file_list']  = json_decode($data['files'], true);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

    // OK 2025
    function msg_add_to_room($id_room, $id_user, $content, $file, $create_time, $reply = null)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $ip = ip_address();
        $sql = "INSERT INTO tbl_chat_customer__msg(id_user, content, files, created_at, updated_at, id_room, ip, reply) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $updated_at = $create_time;
            $param = [$id_user, $content, $file, $create_time, $updated_at, $id_room, $ip, $reply];

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

    // 2025
    // Đêm số tin nhắn chưa đọc của khách
    function count_msg_unread_of_manager($id_customer)
    {
        $total_unread = 0;
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT count(*) as total
            FROM tbl_chat_customer__msg as a
            INNER JOIN tbl_chat_customer__room as b ON a.id_room = b.id_room
            WHERE ISNULL(id_user_seen) AND b.id_customer = $id_customer AND a.id_user != $id_customer";
        $stmt = $iconn->prepare($sql);

        if ($stmt) {
            if ($stmt->execute()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $total_unread = $data['total'];
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $total_unread;
    }

    // set đã xem tin nhắn của quản lý
    function set_seen_all_msg_of_manager($id_room, $id_customer)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat_customer__msg SET id_user_seen=$id_customer WHERE id_room = $id_room AND id_user != $id_customer";

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
}
