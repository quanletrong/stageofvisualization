<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Library_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    // TODO: 
    function add($id_room, $id_style, $name, $image, $status, $id_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_library (id_room, id_style, name, image, status, id_user, create_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_room, $id_style, $name, $image, $status, $id_user, $create_time];

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

    function get_list($status = '', $id_room = '', $id_style = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $status   !== '' ? " AND A.status = ? " : "";
        $where .= $id_room  !== '' ? " AND A.id_room = ? " : "";
        $where .= $id_style !== '' ? " AND A.id_style = ? " : "";

        $sql = "
        SELECT A.*, B.username, C.name as style_name, D.name as room_name 
        FROM tbl_library as A 
        LEFT JOIN tbl_user as B ON A.id_user = B.id_user 
        LEFT JOIN tbl_style as C ON A.id_style = C.id_style 
        LEFT JOIN tbl_room as D ON A.id_room = D.id_room 

        ORDER BY A.name DESC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['image_path'] = url_image($row['image'], FOLDER_LIBRARY);
                        $row['image_path_thumb'] = url_image($row['image'], FOLDER_LIBRARY_THUMB);
                        $row['name_show'] = pathinfo($row['name'], PATHINFO_FILENAME);
                        $data[$row['id_library']] = $row;
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

    function get_info($id_library)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_library WHERE id_library = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_library])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function edit($name, $sapo, $image, $slide, $status, $update_time, $id_library)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_library SET name=?, sapo=?, image=?, slide=?, status=?, update_time=? WHERE id_library=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $sapo, $image, $slide, $status, $update_time, $id_library];

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

    function get_list_room_has_image()
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT *
            FROM tbl_room
            WHERE tbl_room.id_room IN (SELECT tbl_library.id_room FROM tbl_library GROUP BY tbl_library.id_room)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_room']] = $row;
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

    function get_list_style_has_image()
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT *
            FROM tbl_style
            WHERE tbl_style.id_style IN (SELECT tbl_library.id_style FROM tbl_library GROUP BY tbl_library.id_style)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_style']] = $row;
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
}
