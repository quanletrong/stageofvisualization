<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Discuss_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function discuss_list_by_id_order($id_order, $type)
    {
        $list_order = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname, B.avatar as avatar
        FROM tbl_order_discuss as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_order = $id_order AND A.type = $type
        ORDER BY A.id_discuss ASC; ";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['avatar_url'] = url_image($row['avatar'], FOLDER_AVATAR);
                        $row['file_list']  = json_decode($row['file'], true);

                        $list_order[] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_order;
    }

    function discuss_info($id_discuss)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username as username, B.role as role, B.fullname as fullname, B.avatar as avatar
        FROM tbl_order_discuss as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.id_discuss = $id_discuss;";

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

    function discuss_add($id_user, $id_order, $content, $file, $create_time, $status, $type)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order_discuss (id_user, id_order, content, file, create_time, status, `type`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_user, $id_order, $content, $file, $create_time, $status, $type];

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

    function discuss_edit($id_discuss, $content, $file, $update_time, $status)
    {
    }

    function discuss_delete($id_discuss)
    {
    }
}
