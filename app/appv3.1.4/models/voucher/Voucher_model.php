<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }
    function voucher_customer_list($id_customer, $now)
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
            if ($stmt->execute([$id_customer, $now])) {
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
