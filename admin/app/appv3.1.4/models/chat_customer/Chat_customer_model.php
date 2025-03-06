<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat_customer_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function room_list()
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT 
                -- thông tin nhóm
                a.*,
                -- thông tin người tạo nhóm
                b.username , b.fullname, b.email, b.phone, b.avatar, 

                -- nội dung tin nhắn mới nhất trong nhóm
                c.content as newest_content, c.files as newest_files, c.id_user_seen, c.created_at as newst_created_at

            -- lấy thông tin nhóm
            FROM tbl_chat_customer__room as a

            -- lấy thông tin người tạo nhóm
            INNER JOIN tbl_user as b ON a.id_customer = b.id_user

            -- lấy nội dung tin nhắn mới nhất trong nhóm
            INNER JOIN tbl_chat_customer__msg as c ON a.id_msg_newest = c.id_msg
            
            -- Săp xếp nhóm có tin nhắn mới nhất lên đâu
            ORDER BY c.created_at DESC;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $row['file_list']  = json_decode($row['newest_files'], true);
                    $data[$row['id_room']] = $row;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

    function msg_list_by_room($id_room)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT 
                -- thông tin nhóm
                a.*,
                -- thông tin người tạo tin nhắn
                b.username , b.fullname, b.email, b.phone, b.avatar

            -- lấy thông tin nhóm
            FROM tbl_chat_customer__msg as a

            -- lấy thông tin người tạo tin nhắn
            INNER JOIN tbl_user as b ON a.id_user = b.id_user

            WHERE id_room = ?

            -- Săp xếp tin nhắn mới nhất lên đâu
            ORDER BY a.id_msg DESC;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_room])) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row['avatar_url'] = url_image($row['avatar'] == null ? AVATAR_DEFAULT : $row['avatar'], FOLDER_AVATAR);
                    $row['file_list']  = json_decode($row['files'], true);
                    $data[] = $row;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

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


    function room_info($id_room)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT 
                -- thông tin nhóm
                a.*, 
                -- thông tin khách
                b.username, b.avatar,
                -- nội dung tin nhắn mới nhất trong nhóm
                c.content as newest_content, c.files as newest_files, c.id_user_seen, c.created_at as newst_created_at

            FROM tbl_chat_customer__room as a

            -- lấy thông khách tạo nhóm
            INNER JOIN tbl_user as b ON a.id_customer = b.id_user

            -- lấy nội dung tin nhắn mới nhất trong nhóm
            INNER JOIN tbl_chat_customer__msg as c ON a.id_msg_newest = c.id_msg

            WHERE a.id_room = ?;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_room])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $data['avatar_url'] = url_image($data['avatar'], FOLDER_AVATAR);
                    $data['file_list']  = json_decode($data['newest_files'], true);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $data;
    }

    function msg_add_to_room($id_room, $id_user, $content, $file, $create_time, $reply)
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

    function set_seen_all_msg_of_customer($id_room, $id_user_seen, $id_customer)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_chat_customer__msg SET id_user_seen=$id_user_seen WHERE id_room = $id_room AND id_user = $id_customer";

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

    // Đêm số tin nhắn chưa đọc của khách
    function count_msg_unread_of_customer()
    {
        $total_unread = 0;
        $iconn = $this->db->conn_id;
        $sql =
            "SELECT count(*) as total FROM tbl_chat_customer__msg as a
            INNER JOIN tbl_chat_customer__room as b ON a.id_user = b.id_customer
            WHERE ISNULL(id_user_seen)";
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
}
