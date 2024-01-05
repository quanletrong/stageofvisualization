<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($name,$type_service, $sapo, $image, $room, $price, $status, $id_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $update_time = $create_time;
        $sql = "INSERT INTO tbl_service (name, type_service, sapo, image, room, price, status, id_user, create_time, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $type_service, $sapo, $image, $room, $price, $status, $id_user, $create_time, $update_time];

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
        $where .= $status !== '' ? " AND A.status = ? " : "";

        $sql = "
        SELECT A.*, B.username 
        FROM tbl_service as A 
        LEFT JOIN tbl_user as B ON A.id_user = B.id_user 

        ORDER BY A.update_time DESC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                         // path image service
                        $row['image_path'] = url_image($row['image'], FOLDER_SERVICES);
                        // path image room
                        $arr_room = json_decode($row['room'], true);
                        foreach($arr_room as $id => $it){
                            $arr_room[$id]['name'] = $it['name'];
                            $arr_room[$id]['image'] = $it['image'];
                            $arr_room[$id]['image_path'] = url_image($it['image'], FOLDER_SERVICES);
                        }
                        $row['room'] = json_encode($arr_room);
                        $data[$row['id_service']] = $row;
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

    function get_info($id_service)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_service WHERE id_service = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_service])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function edit($name, $type_service, $sapo, $price, $image, $room, $status, $update_time, $id_service)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_service SET name=?, type_service=?,sapo=?, price=?, image=?, room=?, status=?, update_time=? WHERE id_service=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $type_service, $sapo, $price, $image, $room, $status, $update_time, $id_service];

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
