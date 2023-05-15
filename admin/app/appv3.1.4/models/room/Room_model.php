<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Room_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($id_service, $name, $image_before, $image_after, $status, $id_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_room (id_service, name, image_before, image_after, status, id_user, create_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_service, $name, $image_before, $image_after, $status, $id_user, $create_time];

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

    function get_list($status = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $status !== '' ? " AND A.status =? " : "";

        $sql = "
        SELECT A.*, B.username, C.name as service_name
        FROM tbl_room as A 
        LEFT JOIN tbl_user as B ON A.id_user = B.id_user 
        LEFT JOIN tbl_service as C ON A.id_service = C.id_service 
        $where
        ORDER BY sort ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['image_before_path'] = '';
                        $year = date('Y', strtotime($row['create_time']));
                        $month = date('m', strtotime($row['create_time']));
                        $row['image_before_path'] = ROOT_DOMAIN . PUBLIC_UPLOAD_PATH . $year . '/' . $month . '/' . $row['image_before'];

                        $row['image_after_path'] = '';
                        $year = date('Y', strtotime($row['create_time']));
                        $month = date('m', strtotime($row['create_time']));
                        $row['image_after_path'] = ROOT_DOMAIN . PUBLIC_UPLOAD_PATH . $year . '/' . $month . '/' . $row['image_after'];


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

    function get_info($id_room)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_room WHERE id_room = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_room])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function edit($id_service, $name, $image_before, $image_after, $status, $update_time, $id_room)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_room SET id_service=?, name=?, image_before=?, image_after=?, status=?, update_time=? WHERE id_room=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_service, $name, $image_before, $image_after, $status, $update_time, $id_room];

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
