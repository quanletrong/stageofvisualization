
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends CI_Model
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

        $sql = "SELECT A.*, B.username 
            FROM tbl_service as A 
            LEFT JOIN tbl_user as B ON A.id_user = B.id_user 

            ORDER BY sort ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // path image service
                        $row['image_path'] = url_image($row['image'], FOLDER_SERVICES);

                        // path image room
                        $arr_room = json_decode($row['room'], true);
                        foreach ($arr_room as $id => $it) {
                            $arr_room[$id]['name'] = $it['name'];
                            $arr_room[$id]['image'] = $it['image'];
                            $arr_room[$id]['image_path'] = url_image($it['image'], FOLDER_SERVICES);;
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
}
