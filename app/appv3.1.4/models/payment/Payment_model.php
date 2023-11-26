
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_list_payment_by_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_payment_order WHERE id_order = ? ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
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

    function update_status_payment_by_id_order($id_order, $is_payment, $type_payment, $transaction, $update_time)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_payment_order 
        SET `is_payment`=?, `type_payment`=?, `transaction`=?, `update_time`=?
        WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$is_payment, $type_payment, $transaction, $update_time, $id_order])) {
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
