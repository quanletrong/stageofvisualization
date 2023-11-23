<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($code, $note, $price, $price_unit, $status, $limit, $expire_date, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_voucher (code, note, price, price_unit, status, `limit`, expire_date, create_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$code, $note, $price, $price_unit, $status, $limit, $expire_date, $create_time];

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

    function get_info($id_voucher)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_voucher WHERE id_voucher = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_voucher])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                $data['voucher_user'] = $this->voucher_user_get_list($data['id_voucher'], '',  $iconn);
                $data['voucher_order'] = $this->voucher_order_get_list($data['id_voucher'], $iconn);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function edit($code, $note, $price, $price_unit, $status, $limit, $expire_date, $id_voucher)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_voucher 
        SET code=?, note=?, price=?, price_unit=?, `status`=?, `limit`=?, expire_date=? 
        WHERE id_voucher=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$code, $note, $price, $price_unit, $status, $limit, $expire_date, $id_voucher])) {
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
                        $row['voucher_user'] = $this->voucher_user_get_list($row['id_voucher'], '', $iconn);
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

    function voucher_user_get_list($id_voucher, $id_user, $iconn = null)
    {
        if ($iconn === null) {
            $iconn = $this->db->conn_id;
        }

        $data = [];
        $SQL['query'] = '';
        $SQL['param'] = [];

        $SQL['query'] = " SELECT A.*, B.username, B.role, B.code_user
            FROM tbl_voucher_user as A
            LEFT JOIN tbl_user as B ON A.id_user = B.id_user ";

        $SQL['query'] .= " WHERE 1=1 ";
        $SQL = sql_in($id_voucher, 'A.id_voucher', $SQL);
        $SQL = sql_in($id_user, 'A.id_user', $SQL);

        $stmt = $iconn->prepare($SQL['query']);
        if ($stmt->execute($SQL['param'])) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['id_voucher_user']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function voucher_order_get_list($id_voucher, $iconn = null)
    {

        if ($iconn === null) {
            $iconn = $this->db->conn_id;
        }

        $data = [];
        $sql = "SELECT A.*, B.code_order, C.username as khach, D.username as sale, E.code as code_voucher
        FROM tbl_payment_order as A
        
        -- lấy code_order
        LEFT JOIN tbl_order as B ON B.id_order = A.id_order
        -- lấy tên khách
        LEFT JOIN tbl_user as C ON C.id_user = B.id_user
        -- lấy tên ng giới thiệu
        LEFT JOIN tbl_user as D ON D.id_user = A.sale_voucher
        -- lấy mã khuyễn mại
        LEFT JOIN tbl_voucher as E ON E.id_voucher = A.id_voucher

        WHERE A.id_voucher = $id_voucher";

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

    // function _order_get_price($id_order, $iconn = null)
    // {

    //     if ($iconn === null) {
    //         $iconn = $this->db->conn_id;
    //     }

    //     $data = 0;
    //     $sql = "SELECT sum(A.price) as price
    //     FROM tbl_job as A
    //     WHERE id_order = $id_order";

    //     $stmt = $iconn->prepare($sql);
    //     if ($stmt->execute()) {
    //         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //             $data = $row['price'];
    //         }
    //     } else {
    //         var_dump($stmt->errorInfo());
    //         die;
    //     }

    //     return $data;
    // }

    function delete_multiple_voucher_user($arr_user, $id_voucher)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = '';
        foreach ($arr_user as $id_user) {
            $sql .= "DELETE FROM tbl_voucher_user WHERE id_user= $id_user AND id_voucher = $id_voucher ; ";
        }

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function add_multiple_voucher_user($arr_user, $id_voucher, $create_time)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = '';
        foreach ($arr_user as $id_user) {
            $sql .= "INSERT INTO tbl_voucher_user (id_user, id_voucher, create_time) VALUES ($id_user, $id_voucher, '$create_time') ; ";
        }

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $execute = true;
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $execute;
    }

    function get_list_voucher_for_create_order_by_sale($id_sale, $now)
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $SQL =
            "SELECT A.*, C.total
            FROM `tbl_voucher` A
            INNER JOIN tbl_voucher_user B ON B.id_voucher = A.id_voucher AND B.id_user = ? 
            LEFT JOIN 
                ( SELECT count(*) as total, id_voucher FROM tbl_payment_order WHERE tbl_payment_order.is_payment IN (0,1) GROUP BY id_voucher) as C 
                ON C.id_voucher = A.id_voucher
            WHERE 1=1
                AND A.`status` = 0
                AND A.expire_date > ?
            ";
        $stmt = $iconn->prepare($SQL);
        if ($stmt) {
            if ($stmt->execute([$id_sale, $now])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['limit'] > $row['total']) {
                            $str_exprire  = strtotime($row['expire_date']);
                            $row['expire_view'] = date('H:i', $str_exprire) . " ngày " . date('d-m-Y', $str_exprire);
                            $data[$row['id_voucher']] = $row;
                        }
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
