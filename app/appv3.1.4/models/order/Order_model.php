<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{	
	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add($name, $lastname, $email, $phone, $id_style, $create_time, $id_user, $coupon, $pending_pay, $status)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order (name, lastname, email, phone, id_style, create_time, id_user, coupon, pending_pay, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$name, $lastname, $email, $phone, $id_style, $create_time, $id_user, $coupon, $pending_pay, $status];

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

    function add_item($id_order, $id_room, $id_service, $id_user, $image, $requirement, $attach, $create_time, $status) {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order_detail (id_order, id_room, id_service, id_user, image, requirement, attach, create_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_room, $id_service, $id_user, $image, $requirement, $attach, $create_time, $status];

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