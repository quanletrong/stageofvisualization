<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Job_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_list_job_by_order($id_order = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $id_order !== '' ? " AND A.id_order =? " : "";

        $sql = "
        SELECT A.*, B.name as room, C.name as service, C.type_service as type_service, D.name as style
        FROM tbl_job as A
        LEFT JOIN tbl_room as B ON A.id_room = B.id_room
        LEFT JOIN tbl_service as C ON A.id_service = C.id_service
        LEFT JOIN tbl_style as D ON A.id_style = D.id_style
        $where ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['year'] = date('Y', strtotime($row['create_time']));
                        $row['month'] = date('m', strtotime($row['create_time']));
                        $data[$row['id_job']] = $row;
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

    function get_list_job_by_id_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT *
        FROM tbl_job
        WHERE id_order= $id_order";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_job']] = $row;
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

    function get_list_job_user_by_id_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, A.status as job_user_status, B.*
        FROM tbl_job_user as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user
        WHERE id_order= $id_order";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_job_user']] = $row;
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

    function get_info_job_by_id($id_job)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.* FROM tbl_job as A WHERE id_job= ?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_job])) {
                if ($stmt->rowCount() > 0) {

                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!empty($data)) {
                    }

                    $data['year'] = date('Y', strtotime($data['create_time']));
                    $data['month'] = date('m', strtotime($data['create_time']));
                    $data['file_complete'] = $data['file_complete'] == null ? [] : json_decode($data['file_complete'], true);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_info_rework_by_id($id_rework)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.create_time FROM tbl_job_rework as A 
        INNER JOIN tbl_job as B ON A.id_job = B.id_job
        WHERE id_job_rework = ?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_rework])) {
                if ($stmt->rowCount() > 0) {

                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!empty($data)) {
                    }

                    $data['year'] = date('Y', strtotime($data['create_time']));
                    $data['month'] = date('m', strtotime($data['create_time']));
                    $data['file_complete'] = $data['file_complete'] == null ? [] : json_decode($data['file_complete'], true);
                    $data['attach'] = $data['attach'] == null ? [] : json_decode($data['attach'], true);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_image_job($id_job, $image){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job SET image=? WHERE id_job=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$image, $id_job];

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
    
    function update_attach_job($id_job, $attach){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job SET attach=? WHERE id_job=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$attach, $id_job];

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

    function update_file_complete_job($id_job, $file_complete){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job SET file_complete=? WHERE id_job=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$file_complete, $id_job];

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

    function update_file_complete_rework($id_rework, $file_complete){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_rework SET file_complete=? WHERE id_job_rework=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$file_complete, $id_rework];

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

    function update_file_attach_rework($id_rework, $file_complete){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_rework SET attach=? WHERE id_job_rework=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$file_complete, $id_rework];

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

    function update_requirement_job($id_job, $requirement){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job SET requirement=? WHERE id_job=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$requirement, $id_job];

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

    function update_requirement_rework($id_rework, $note){
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_rework SET note=? WHERE id_job_rework=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$note, $id_rework];

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

    function add_rework($id_order, $id_job, $attach, $note, $id_user)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $create_time = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tbl_job_rework (id_order, id_job, attach, note, id_user, create_time) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_job, $attach, $note, $id_user, $create_time];

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
}
