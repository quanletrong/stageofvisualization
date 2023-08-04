<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add_order($id_style, $create_time, $id_user, $coupon, $pending_pay, $status)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order (id_style, create_time, id_user, coupon, pending_pay, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_style, $create_time, $id_user, $coupon, $pending_pay, $status];

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

    function add_order_job($id_order, $id_service, $type_service, $price, $price_unit, $id_room, $id_style, $image, $attach, $requirement, $create_time)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_job (id_order, id_service, type_service, price, price_unit, id_room, id_style, image, attach, requirement, create_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_service, $type_service, $price, $price_unit, $id_room, $id_style, $image, $attach, $requirement, $create_time];

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

    function delete_order_and_job($id_order)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "DELETE FROM tbl_order WHERE id_order= $id_order; ";
        $sql .= "DELETE FROM tbl_job WHERE id_order= $id_order; ";
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
}
