<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Style_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($name, $sapo, $image, $slide, $status, $id_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $update_time = $create_time;
        $sql = "INSERT INTO tbl_style (name, sapo, image, slide, status, id_user, create_time, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $sapo, $image, $slide, $status, $id_user, $create_time, $update_time];

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
        $where .= $status !== '' ? " AND A.status = $status " : "";

        $sql = "
        SELECT A.*, B.username 
        FROM tbl_style as A 
        LEFT JOIN tbl_user as B ON A.id_user = B.id_user 
        $where
        ORDER BY A.update_time DESC;";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // path image slide
                        $row['image_path'] = url_image($row['image'], FOLDER_STYLE);

                        // path image slide
                        $arr_slide = json_decode($row['slide'], true);
                        foreach ($arr_slide as $id => $it) {
                            $arr_slide[$id]['name'] = $it['name'];
                            $arr_slide[$id]['image'] = $it['image'];
                            $arr_slide[$id]['image_path'] = url_image($it['image'], FOLDER_STYLE);
                        }
                        $row['slide'] = json_encode($arr_slide);
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

    function get_info($id_style)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_style WHERE id_style = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_style])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function edit($name, $sapo, $image, $slide, $status, $update_time, $id_style)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_style SET name=?, sapo=?, image=?, slide=?, status=?, update_time=? WHERE id_style=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $sapo, $image, $slide, $status, $update_time, $id_style];

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
