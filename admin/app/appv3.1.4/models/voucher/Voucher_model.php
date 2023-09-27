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


    function get_list2($id_voucher, $f_price, $t_price, $price_unit, $code, $f_expire, $t_expire, $status, $f_create_time, $t_create_time, $note, $create_by, $limit, $offset)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $SQL['query'] = '';
        $SQL['param'] = [];

        $SQL['query'] = "
        SELECT A.*, B.username
        FROM tbl_voucher as A 
        LEFT JOIN tbl_user as B ON A.create_by = B.id_user ";

        $SQL['query'] .= " WHERE 1=1 ";
        $SQL = sql_in($id_voucher, 'A.id_voucher', $SQL);
        $SQL = sql_in($create_by, 'A.create_by', $SQL);
        $SQL = sql_in($status, 'A.status', $SQL);
        $SQL = sql_in($price_unit, 'A.price_unit', $SQL);
        $SQL = sql_like($code, 'A.code', $SQL);
        $SQL = sql_like($note, 'A.price', $SQL);
        $SQL = sql_between_number($f_price, $t_price, 'A.price', $SQL);
        $SQL = sql_between_number($f_expire, $t_expire, 'A.expire_date', $SQL);
        $SQL = sql_between_number($f_create_time, $t_create_time, 'A.create_time', $SQL);

        $SQL['query'] .= " ORDER BY A.id_voucher ASC  LIMIT $limit OFFSET $offset";

        $stmt = $iconn->prepare($SQL['query']);
        if ($stmt) {
            if ($stmt->execute($SQL['param'])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // sanh sách người dùng được cấp quyền sử dụng mã
                        $row['voucher_user'] = $this->voucher_user_get_list($row['id_voucher'], $iconn);
                        // danh sách đơn hàng đã sử dụng mã 
                        $row['voucher_order'] = $this->voucher_order_get_list($row['id_voucher'], $iconn);

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

    function voucher_user_get_list($id_voucher, $iconn = null)
    {

        if($iconn === null) {
            $iconn = $this->db->conn_id;
        }

        $data = [];
        $sql = "SELECT A.*, B.username, B.role, B.code_user
        FROM tbl_voucher_user as A
        LEFT JOIN tbl_user as B ON A.id_user = B.id_user
        WHERE id_voucher = $id_voucher";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['id_user']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function voucher_order_get_list($id_voucher, $iconn = null)
    {

        if($iconn === null) {
            $iconn = $this->db->conn_id;
        }

        $data = [];
        $sql = "SELECT A.*, B.code_order
        FROM tbl_voucher_order as A
        LEFT JOIN tbl_order as B ON A.id_order = B.id_order
        WHERE id_voucher = $id_voucher";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['id_order']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }
}
