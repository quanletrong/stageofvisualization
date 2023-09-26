<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($name, $type_service, $sapo, $image, $room, $price, $status, $id_user, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_service (name, type_service, sapo, image, room, price, status, id_user, create_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $type_service, $sapo, $image, $room, $price, $status, $id_user, $create_time];

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

    function get_list($id_voucher, $value_type, $f_value, $t_value, $value_unit, $code, $f_expire, $t_expire, $type_assign, $id_assign, $id_used, $id_order, $status, $f_create_time, $t_create_time, $note, $limit, $offset)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $LIKE_note = "%$note%";

        $where = 'WHERE 1=1 ';
        $where .= $id_voucher  !== '' ? " AND A.id_voucher     = $id_voucher " : "";
        $where .= $value_type  !== '' ? " AND A.value_type     = $value_type " : "";
        $where .= $value_unit  !== '' ? " AND A.value_unit     = $value_unit " : "";
        $where .= $code        !== '' ? " AND A.code           = '$code' " : "";
        $where .= $type_assign !== '' ? " AND A.type_assign    = $type_assign " : "";
        $where .= $id_assign   !== '' ? " AND A.id_assign      = $id_assign " : "";
        $where .= $id_used     !== '' ? " AND A.id_used        = $id_used " : "";
        $where .= $id_order    !== '' ? " AND A.id_order       = $id_order " : "";
        $where .= $status      !== '' ? " AND A.status         = $status " : "";
        $where .= $note        !== '' ? " AND A.note           LIKE  $LIKE_note " : "";

        $where .= $f_value !== '' && $t_value === '' ? " AND A.value >= $f_value " : "";
        $where .= $f_value === '' && $t_value !== '' ? " AND A.value <= $t_value " : "";
        $where .= $f_value !== '' && $t_value !== '' ? " AND A.value BETWEEN $f_value AND $t_value " : "";

        $where .= $f_expire !== '' && $t_expire === '' ? " AND A.expire >= '$f_expire' " : "";
        $where .= $f_expire === '' && $t_expire !== '' ? " AND A.expire <= '$t_expire' " : "";
        $where .= $f_expire !== '' && $t_expire !== '' ? " AND A.expire BETWEEN '$f_expire' AND '$t_expire' " : "";

        $where .= $f_create_time !== '' && $t_create_time === '' ? " AND A.create_time >= '$f_create_time' " : "";
        $where .= $f_create_time === '' && $t_create_time !== '' ? " AND A.create_time <= '$t_create_time' " : "";
        $where .= $f_create_time !== '' && $t_create_time !== '' ? " AND A.create_time BETWEEN '$f_create_time' AND '$t_create_time' " : "";

        $sql = "
        SELECT A.*, B.username as assign, B.code_user as code_user_assign, C.username as used, C.code_user as code_user_used, D.code_order
        FROM tbl_voucher as A 
        LEFT JOIN tbl_user as B ON A.id_assign = B.id_user 
        LEFT JOIN tbl_user as C ON A.id_used = C.id_user 
        LEFT JOIN tbl_order as D ON A.id_order = D.id_order 
        $where 
        ORDER BY A.id_voucher ASC 
        LIMIT $limit OFFSET $offset";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_voucher']] = $row;
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
