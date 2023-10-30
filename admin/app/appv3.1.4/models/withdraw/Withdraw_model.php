<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }


    function danh_sach_chua_rut_tien($id_user)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT id_job_user, id_user, id_order, id_job, type_job_user, type_service, (custom-withdraw_custom) as num
        FROM tbl_job_user
        WHERE id_user = $id_user AND withdraw = 1 AND custom > 0 AND (withdraw_custom < custom);";

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

    function tao_yeu_cau_rut_tien($id_user, $id_order, $id_job, $id_job_user, $type_service, $custom, $create_time)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = " UPDATE tbl_job_user SET withdraw_status = 1, withdraw_custom = custom  WHERE id_job_user = $id_job_user; ";
        $sql .= "INSERT INTO tbl_withdraw (id_user, id_order, id_job, id_job_user, type_service, custom, status, create_time) 
        VALUES ($id_user, $id_order, $id_job, $id_job_user, '$type_service', $custom, 0, '$create_time');";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        // $stmt->closeCursor(); CLOSE BÊN CONTROLLER
        return $execute;
    }

    function withdraw_get_list($status = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.username, B.role, B.code_user, B.avatar, B.fullname 
        FROM tbl_withdraw as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user 
        WHERE A.id_withdraw IN (SELECT MAX(id_withdraw) AS id FROM tbl_withdraw GROUP BY id_user) 
        ORDER BY A.status ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_withdraw']] = $row;
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

    function withdraw_get_detail($id_user, $status)
    {
        $data['tong_hop'] = [];
        $data['group_date'] = [];
        $data['all'] = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*, B.username, B.role, B.code_user, B.avatar, B.fullname, C.code_order ";
        $sql .= "FROM tbl_withdraw as A ";
        $sql .= "INNER JOIN tbl_user as B ON A.id_user = B.id_user ";
        $sql .= "INNER JOIN tbl_order as C ON A.id_order = C.id_order ";
        $sql .= "WHERE A.id_user = $id_user AND A.status = $status ";
        $sql .= "ORDER BY A.create_time ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // all
                        $data['all'][$row['id_withdraw']] = $row;

                        // cộng type_service giống nhau
                        
                        $row['type_service'] = $row['type_service'] == '' ? 'CUSTOM' : $row['type_service'];

                        if (isset($data['tong_hop'][$row['type_service']])) {
                            $data['tong_hop'][$row['type_service']] += $row['custom'];
                        } else {
                            $data['tong_hop'][$row['type_service']] = $row['custom'];
                        }

                        // nhóm tất cả withdraw cùng 1 thời gian
                        $data['group_date'][$row['create_time']][$row['id_withdraw']] = $row;
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

    function phe_duyet_yeu_cau_rut_tien($str_id_withdraw, $str_id_job_user)
    {

        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_withdraw SET status = 1 WHERE id_withdraw IN ($str_id_withdraw);";
        $sql .= "UPDATE tbl_job_user SET withdraw_status = 2 WHERE id_job_user IN ($str_id_job_user);"; 

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        // $stmt->closeCursor(); CLOSE BÊN CONTROLLER
        return $execute;
    }
}
