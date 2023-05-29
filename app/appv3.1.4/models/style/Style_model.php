
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Style_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }


    function get_list($status)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $status !== '' ? " AND A.status = ? " : "";

        $sql = "SELECT A.* FROM tbl_style as A 
            $where
            ORDER BY sort ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // path image style
                        $row['image_path'] = '';
                        $year = date('Y', strtotime($row['create_time']));
                        $month = date('m', strtotime($row['create_time']));
                        $row['image_path'] = ROOT_DOMAIN . PUBLIC_UPLOAD_PATH . $year . '/' . $month . '/' . $row['image'];

                        // path image slide
                        $arr_slide = json_decode($row['slide'], true);
                        foreach ($arr_slide as $id => $it) {
                            $path_imge = ROOT_DOMAIN . PUBLIC_UPLOAD_PATH . $year . '/' . $month . '/' . $it['image'];
                            $arr_slide[$id]['name'] = $it['name'];
                            $arr_slide[$id]['image'] = $it['image'];
                            $arr_slide[$id]['image_path'] = $path_imge;
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
}
