<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    private $_status_sort = [ORDER_PENDING, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_FIX, ORDER_REWORK, ORDER_DONE, ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE];

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function add_order($id_style, $create_time, $id_user, $status, $order_type, $create_id_user, $ed_type)
    {
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_order (id_style, create_time, id_user, status, order_type, create_id_user, ed_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_style, $create_time, $id_user, $status, $order_type, $create_id_user, $ed_type];

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

    /** 
     * LẤY DANH SÁCH ĐƠN THEO USER
     */
    function get_list_order_by_id_user($id_user)
    {
        $list_order = [];
        $iconn = $this->db->conn_id;

        $sql = "SELECT A.*
            FROM tbl_order as A
            WHERE id_user = $id_user
            ORDER BY FIELD(A.status, " . implode(',', $this->_status_sort) . "), A.create_time DESC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // gán list type_service vào đơn
                        $type_service = $this->_get_type_service_job_by_id_order($row['id_order'], $iconn);
                        $row['type_service'] = $type_service;

                        // gán total job vào đơn
                        $total_job = $this->_get_total_job_by_id_order($row['id_order'], $iconn);
                        $row['total_job'] = $total_job;

                        // gán danh sách qc, ed, custom vào đơn
                        // $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        // $row['team'] = $team;

                        $list_order[$row['id_order']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        $stmt->closeCursor();
        return $list_order;
    }

    function _get_type_service_job_by_id_order($id_odrer, $iconn)
    {
        $data = [];
        $sql = "SELECT A.type_service
        FROM tbl_job as A
        WHERE id_order = $id_odrer
        GROUP BY A.type_service";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row['type_service'];
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function _get_total_job_by_id_order($id_odrer, $iconn)
    {
        $data = 0;
        $sql = "SELECT count(*) as total
        FROM tbl_job
        WHERE id_order= $id_odrer";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = $row['total'];
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function get_list_v2($filter = [])
    {
        $list_order = [];
        $iconn = $this->db->conn_id;

        $filter_code_order   = isset($filter['code_order'])     ? $filter['code_order']     : '';
        $filter_user_code    = isset($filter['user_code'])      ? $filter['user_code']      : '';
        $filter_status       = isset($filter['status'])         ? $filter['status']         : '';
        $filter_type_service = isset($filter['type_service'])   ? $filter['type_service']   : '';      // vs vr ...
        $filter_order_type   = isset($filter['order_type'])     ? $filter['order_type']     : '';      // khách tạo, nội bộ, tạo hộ
        $filter_ed_type      = isset($filter['ed_type'])        ? $filter['ed_type']        : '';      // nội bộ, ctv
        $filter_fdate        = isset($filter['fdate'])          ? $filter['fdate']          : '';
        $filter_tdate        = isset($filter['tdate'])          ? $filter['tdate']          : '';
        $filter_id_user      = isset($filter['id_user'])        ? $filter['id_user']        : '';
        $filter_order_user   = isset($filter['order_user'])     ? $filter['order_user']     : '';

        $this->session->set_flashdata('PARAMS', []);
        // SELECT
        $SQL =
            "SELECT
                tbl_order.*,
                tbl_user.code_user,
                tbl_tam.list_job,
                tbl_tam.list_service,
                tbl_tam.list_user 
            FROM
                tbl_order
                INNER JOIN tbl_user ON tbl_user.id_user = tbl_order.id_user 
                LEFT JOIN (
                    SELECT
                        tbl_job.id_order,
                        GROUP_CONCAT( DISTINCT tbl_job.id_job ) AS list_job,
                        GROUP_CONCAT( DISTINCT tbl_job.type_service ) AS list_service,
                        GROUP_CONCAT( DISTINCT tbl_job_user.id_user ) AS list_user 
                    FROM
                        tbl_job
                        LEFT JOIN tbl_job_user ON tbl_job.id_order = tbl_job_user.id_order AND tbl_job_user.`status` = 1 
                    GROUP BY
                        tbl_job.id_order 
                ) AS tbl_tam ON tbl_tam.id_order = tbl_order.id_order 
            WHERE
                1 = 1 
                AND " . QSQL_IN('tbl_order.`status`', $filter_status, $this) . " 
                AND " . QSQL_IN('tbl_order.order_type', $filter_order_type, $this) . "
                AND " . QSQL_IN('tbl_order.ed_type', $filter_ed_type, $this) . "
                AND " . QSQL_IN('tbl_order.id_user', $filter_order_user, $this) . "
                
                AND " . QSQL_LIKE('tbl_order.code_order', $filter_code_order, $this) . "
                AND " . QSQL_LIKE('tbl_user.code_user', $filter_user_code, $this) . "

                AND (" . QSQL_LIKE_OR('concat(",", tbl_tam.list_user, ",")', $filter_id_user, $this) . ")
                AND (" . QSQL_LIKE_OR('concat(",", tbl_tam.list_service, ",")', $filter_type_service, $this) . ") 
                AND " . QSQL_BETWEEN('tbl_order.create_time', $filter_fdate, $filter_tdate, $this) . "

            ORDER BY 
                FIELD(tbl_order.status, " . implode(',', $this->_status_sort) . "), tbl_order.create_time DESC; 
        ";

        $PARAMS = $this->session->flashdata('PARAMS');
        $stmt = $iconn->prepare($SQL);
        if ($stmt) {
            if ($stmt->execute($PARAMS)) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $row['list_service'] = $row['list_service'] == '' ? [] : explode(",", $row['list_service']);
                        $row['list_job']     = $row['list_job']     == '' ? [] : explode(",", $row['list_job']);
                        $row['list_user']    = $row['list_user']    == '' ? [] : explode(",", $row['list_user']);
                        $list_order[$row['id_order']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $list_order;
    }
    //  END LẤY DANH SÁCH ĐƠN THEO USER

    // Cập nhât trạng thái đơn
    // TODO: chưa có lưu update_time bổ sung sau
    function update_status_order($id_order, $new_status)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET status=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$new_status, $id_order];

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
    // end cập nhật trạng thái đơn

    function get_info_order_old($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.code_user, B.username FROM tbl_order A
        INNER JOIN tbl_user B ON A.id_user = B.id_user
        WHERE A.id_order = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $list_job = $this->_get_list_job_by_order($data['id_order'], $iconn);
                    $data['job'] = $list_job;
                    $data['total_type_service']    = 0;
                    $data['list_type_service']     = [];
                    $data['list_type_service']     = [];
                    $data['working_custom_active'] = [];
                    $data['working_custom_block']  = [];
                    $data['working_ed_active']     = [];
                    $data['working_ed_block']      = [];
                    $data['working_qc_in_active']  = [];
                    $data['working_qc_out_active'] = [];
                    $data['working_qc_in_block']   = [];
                    $data['working_qc_out_block']  = [];
                    $data['total_custom_used']     = 0;   // số lượng custom đã dùng trong đơn

                    foreach ($data['job'] as $id_job => $job) {
                        // gán working ed
                        $data['job'][$id_job]['working_ed_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_EDITOR, $iconn);
                        $data['job'][$id_job]['working_ed_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_EDITOR, $iconn);

                        // gán working qc in
                        $data['job'][$id_job]['working_qc_in_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_QC_IN, $iconn);
                        $data['job'][$id_job]['working_qc_in_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_QC_IN, $iconn);
                        // gán working qc out
                        $data['job'][$id_job]['working_qc_out_active'] = $this->_get_list_job_user_by_job($id_order, $id_job, 1, WORKING_QC_OUT, $iconn);
                        $data['job'][$id_job]['working_qc_out_block']  = $this->_get_list_job_user_by_job($id_order, $id_job, 0, WORKING_QC_OUT, $iconn);

                        // gán rework
                        $data['job'][$id_job]['rework']  = $this->_get_list_rework_user_by_job($id_job, $iconn);

                        if (!empty($data['job'][$id_job]['working_ed_active'])) {
                            $data['working_ed_active'][]   = $data['job'][$id_job]['working_ed_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_ed_block'])) {
                            $data['working_ed_block'][]   = $data['job'][$id_job]['working_ed_block'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_in_active'])) {
                            $data['working_qc_in_active'][]   = $data['job'][$id_job]['working_qc_in_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_out_active'])) {
                            $data['working_qc_out_active'][]   = $data['job'][$id_job]['working_qc_out_active'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_in_block'])) {
                            $data['working_qc_in_block'][]   = $data['job'][$id_job]['working_qc_in_block'];
                        }
                        if (!empty($data['job'][$id_job]['working_qc_out_block'])) {
                            $data['working_qc_out_block'][]   = $data['job'][$id_job]['working_qc_out_block'];
                        }

                        // gán type_service to order
                        $data['list_type_service'][$job['type_service']][] = $id_job;
                        $data['total_type_service'] += 1;
                    }

                    // gán working custom
                    $data['working_custom_active'] = $this->_get_list_job_user_by_job($id_order, 0, 1, WORKING_CUSTOM, $iconn);
                    $data['working_custom_block']  = $this->_get_list_job_user_by_job($id_order, 0, 0, WORKING_CUSTOM, $iconn);
                    foreach ($data['working_custom_active'] as $custom_active) {
                        $data['total_custom_used'] += $custom_active['custom'];
                    }
                }

                // gán danh sách qc, ed, custom vào đơn
                if (!empty($data)) {
                    $data['team'] =  $this->_get_list_editor_by_id_order($data['id_order'], $iconn);
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function get_info_order($id_order)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.code_user, B.username FROM tbl_order A
        INNER JOIN tbl_user B ON A.id_user = B.id_user
        WHERE A.id_order = ? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $list_job = $this->_get_list_job_by_order($data['id_order'], $iconn);
                    $data['job'] = $list_job;
                    $data['total_type_service']    = 0;
                    $data['list_type_service']     = [];
                    $data['working_custom_active'] = [];
                    $data['working_custom_block']  = [];
                    $data['total_custom_used']     = 0;   // số lượng custom đã dùng trong đơn

                    foreach ($data['job'] as $id_job => $job) {

                        // get working (chưa cần)
                        // $working = $this->_get_working_job_v2($id_order, $id_job, $iconn);
                        // $data['job'][$id_job]['working_ed_active']     = $working['working_ed_active'];
                        // $data['job'][$id_job]['working_ed_block']      = $working['working_ed_block'];
                        // $data['job'][$id_job]['working_qc_in_active']  = $working['working_qc_in_active'];
                        // $data['job'][$id_job]['working_qc_in_block']   = $working['working_qc_in_block'];
                        // $data['job'][$id_job]['working_qc_out_active'] = $working['working_qc_out_active'];
                        // $data['job'][$id_job]['working_qc_out_block']  = $working['working_qc_out_block'];

                        // get rework
                        $data['job'][$id_job]['rework'] = $this->_get_list_rework_user_by_job($id_job, $iconn);

                        // danh sach type_service cua order
                        $data['list_type_service'][$job['type_service']][] = $id_job;
                        $data['total_type_service'] += 1;
                    }

                    // gán working custom (chưa cần)
                    // $working_custom = $this->_get_working_job_v2($id_order, 0, $iconn);
                    // $data['working_custom_active'] = $working_custom['working_custom_active'];
                    // $data['working_custom_block']  = $working_custom['working_custom_block'];
                    // foreach ($working_custom['working_custom_active'] as $custom_active) {
                    //     $data['total_custom_used'] += $custom_active['custom'];
                    // }
                }

                // gán danh sách qc, ed, custom vào đơn (chưa cần)
                // if (!empty($data)) {
                //     $data['team'] =  $this->_get_list_editor_by_id_order($data['id_order'], $iconn);
                // }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function _get_working_job_v2($id_order, $id_job, $iconn)
    {
        $working['working_ed_active']     = [];
        $working['working_ed_block']      = [];
        $working['working_qc_in_active']  = [];
        $working['working_qc_in_block']   = [];
        $working['working_qc_out_active'] = [];
        $working['working_qc_out_block']  = [];
        $working['working_custom_active'] = [];
        $working['working_custom_block']  = [];

        $sql = "SELECT A.*
        FROM tbl_job_user A
        WHERE id_order = $id_order AND id_job = $id_job";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $id_user       = $row['id_user'];
                $type_job_user = $row['type_job_user'];
                $status        = $row['status'];

                $type_job_user == WORKING_EDITOR && $status == 1 ? $working['working_ed_active'][$id_user] = $row : '';
                $type_job_user == WORKING_EDITOR && $status == 0 ? $working['working_ed_block'][$id_user] = $row : '';

                $type_job_user == WORKING_QC_IN && $status == 1 ? $working['working_qc_in_active'][$id_user] = $row : '';
                $type_job_user == WORKING_QC_IN && $status == 0 ? $working['working_qc_in_block'][$id_user] = $row : '';

                $type_job_user == WORKING_QC_OUT && $status == 1 ? $working['working_qc_out_active'][$id_user] = $row : '';
                $type_job_user == WORKING_QC_OUT && $status == 0 ? $working['working_qc_out_block'][$id_user] = $row : '';

                $type_job_user == WORKING_CUSTOM && $status == 1 ? $working['working_custom_active'][$id_user] = $row : '';
                $type_job_user == WORKING_CUSTOM && $status == 0 ? $working['working_custom_block'][$id_user] = $row : '';
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $working;
    }


    function _get_list_job_by_order($id_order, $iconn)
    {
        $data = [];
        $where = 'WHERE 1=1 ';
        $where .= $id_order !== '' ? " AND A.id_order =? " : "";

        $sql = "
        SELECT A.*, B.name as room, C.name as service, C.type_service as type_service, D.name as style
        FROM tbl_job as A
        LEFT JOIN tbl_room as B ON A.id_room = B.id_room
        LEFT JOIN tbl_service as C ON A.id_service = C.id_service
        LEFT JOIN tbl_style as D ON A.id_style = D.id_style
        $where ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['year'] = date('Y', strtotime($row['create_time']));
                        $row['month'] = date('m', strtotime($row['create_time']));
                        $row['file_complete'] = $row['file_complete'] == null ? [] : json_decode($row['file_complete'], true);
                        $data[$row['id_job']] = $row;
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        return $data;
    }

    function _get_list_job_user_by_job($id_order, $id_job, $status, $type_working, $iconn)
    {
        $data = [];
        $sql = "SELECT A.*
        FROM tbl_job_user as A
        WHERE id_order = $id_order AND id_job = $id_job AND status = $status AND type_job_user = $type_working";

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

    function _get_list_editor_by_id_order($id_odrer, $iconn)
    {
        $data = [];
        $sql = "SELECT B.id_user, B.username, B.fullname, B.avatar
        FROM tbl_job_user as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user
        WHERE id_order= $id_odrer AND type_job_user IN (2,3,4,5) AND A.status = 1";

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

    function get_list_job_by_order($id_order = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $id_order !== '' ? " AND A.id_order =? " : "";

        $sql = "
        SELECT A.*, B.name as room, C.name as service, C.type_service as type_service, D.name as style
        FROM tbl_job as A
        LEFT JOIN tbl_room as B ON A.id_room = B.id_room
        LEFT JOIN tbl_service as C ON A.id_service = C.id_service
        LEFT JOIN tbl_style as D ON A.id_style = D.id_style
        $where ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['year'] = date('Y', strtotime($row['create_time']));
                        $row['month'] = date('m', strtotime($row['create_time']));
                        $data[$row['id_job']] = $row;
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

    function _get_list_rework_user_by_job($id_job, $iconn)
    {
        $data = [];
        $sql = "SELECT * FROM tbl_job_rework  WHERE id_job = $id_job ";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['attach']               = $row['attach']        == null ? [] : json_decode($row['attach'], true);
                $row['file_complete']        = $row['file_complete'] == null ? [] : json_decode($row['file_complete'], true);
                $data[$row['id_job_rework']] = $row;
            }
        } else {
            var_dump($stmt->errorInfo());
            die;
        }

        return $data;
    }

    function add_payment_order($id_order, $id_voucher, $code_voucher, $price, $price_voucher, $sale_voucher, $is_payment, $type_payment, $create_time){
        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_payment_order (id_order, id_voucher, code_voucher, price, price_voucher, sale_voucher, is_payment, type_payment, create_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_voucher, $code_voucher, $price, $price_voucher, $sale_voucher, $is_payment, $type_payment, $create_time];

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
