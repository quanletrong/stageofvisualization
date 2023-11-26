<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    private $_status_sort = [ORDER_PENDING, ORDER_DONE, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_FIX, ORDER_REWORK, ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE];

    private $_status_working = [ORDER_PENDING, ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE, ORDER_FIX, ORDER_REWORK];

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
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

                        // get working
                        $working = $this->_get_working_job_v2($id_order, $id_job, $iconn);
                        $data['job'][$id_job]['working_ed_active']     = $working['working_ed_active'];
                        $data['job'][$id_job]['working_ed_block']      = $working['working_ed_block'];
                        $data['job'][$id_job]['working_qc_in_active']  = $working['working_qc_in_active'];
                        $data['job'][$id_job]['working_qc_in_block']   = $working['working_qc_in_block'];
                        $data['job'][$id_job]['working_qc_out_active'] = $working['working_qc_out_active'];
                        $data['job'][$id_job]['working_qc_out_block']  = $working['working_qc_out_block'];

                        // get rework
                        $data['job'][$id_job]['rework'] = $this->_get_list_rework_user_by_job($id_job, $iconn);

                        // danh sach type_service cua order
                        $data['list_type_service'][$job['type_service']][] = $id_job;
                        $data['total_type_service'] += 1;
                    }

                    // gán working custom
                    $working_custom = $this->_get_working_job_v2($id_order, 0, $iconn);
                    $data['working_custom_active'] = $working_custom['working_custom_active'];
                    $data['working_custom_block']  = $working_custom['working_custom_block'];
                    foreach ($working_custom['working_custom_active'] as $custom_active) {
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

    function get_list($filter = [], $role)
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

        $SQL['query'] = '';
        $SQL['param'] = [];

        // SELECT
        $SQL['query'] = " SELECT A.*, 
            B.code_user as code_user, 
            COUNT(DISTINCT C.id_job) as total_job,
            GROUP_CONCAT(DISTINCT D.id_user) as working_active ";

        // TABLE
        $SQL['query'] .= " FROM tbl_order as A ";

        //INNER JOIN
        $SQL['query'] .= " INNER JOIN tbl_user B ON B.id_user = A.id_user ";
        $SQL['query'] .= " INNER JOIN tbl_job C ON C.id_order = A.id_order ";
        $SQL['query'] .= " LEFT JOIN tbl_job_user D ON ( D.id_order = A.id_order AND D.`status` = 1 ) ";
        $SQL['query'] .= " INNER JOIN tbl_service E ON E.id_service = C.id_service ";

        // WHERE
        $SQL['query'] .= " WHERE 1=1 ";
        $SQL = sql_like($filter_code_order, 'A.code_order', $SQL);
        $SQL = sql_in($filter_status, 'A.status', $SQL);
        $SQL = sql_in($filter_order_type, 'A.order_type', $SQL);
        $SQL = sql_in($filter_ed_type, 'A.ed_type', $SQL);
        $SQL = sql_in($filter_ed_type, 'A.ed_type', $SQL);
        $SQL = sql_between_number($filter_fdate, $filter_tdate, 'A.create_time', $SQL);

        $SQL = sql_like($filter_user_code, 'B.code_user', $SQL);

        $SQL = sql_in($filter_type_service, 'C.id_service', $SQL);

        // Lấy danh sách đơn cho admin sale
        if ($role == ADMIN || $role == SALE) {

            $SQL = sql_in($filter_id_user, 'D.id_user', $SQL);
        }
        // Lấy danh sách đơn cho QC
        else if ($role == QC) {
            // Lấy tất cả đơn ĐANG LÀM hoặc đơn KHÁC PENDING
            if ($filter_id_user == '') {

                $ORDER_PENDING = ORDER_PENDING;

                $ds_don = "SELECT id_order FROM tbl_job_user WHERE `status` = 1 GROUP BY id_order";

                $SQL['query'] .= " AND ( A.id_order IN ($ds_don) OR A.`status` NOT IN ($ORDER_PENDING) ) ";
            }
            // Lấy tất cả theo filter user
            else {

                $ds_don = "SELECT id_order FROM tbl_job_user WHERE `status` = 1 AND id_user IN ($filter_id_user) GROUP BY id_order";

                $SQL = sql_in($ds_don, 'A.id_order', $SQL);
            }
        }
        // Lấy danh sách đơn cho EDITOR
        else if ($role == EDITOR) {

            $ds_don = "SELECT id_order FROM tbl_job_user WHERE `status` = 1 AND id_user = $filter_id_user GROUP BY id_order";

            $SQL = sql_in($ds_don, 'A.id_order', $SQL);
        }

        //GROUP BY
        $SQL['query'] .= " GROUP BY C.id_order, D.id_order";

        //ORDER BY
        $SQL['query'] .= " ORDER BY FIELD(A.status, " . implode(',', $this->_status_sort) . "), A.create_time DESC; ";
        $stmt = $iconn->prepare($SQL['query']);
        if ($stmt) {
            if ($stmt->execute($SQL['param'])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // gán list type_service vào đơn
                        $type_service = $this->_get_type_service_job_by_id_order($row['id_order'], $iconn);
                        $row['type_service'] = $type_service;

                        // gán danh sách qc, ed, custom vào đơn
                        $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        $row['team'] = $team;

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

    function get_list_v2($filter = [], $role)
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
                        GROUP_CONCAT( DISTINCT tbl_job.id_service ) AS list_service,
                        GROUP_CONCAT( DISTINCT tbl_job_user.id_user ) AS list_user 
                    FROM
                        tbl_job
                        LEFT JOIN tbl_job_user ON tbl_job.id_order = tbl_job_user.id_order 
                        AND tbl_job_user.`status` = 1 
                    GROUP BY
                        tbl_job.id_order 
                ) AS tbl_tam ON tbl_tam.id_order = tbl_order.id_order 
            WHERE
                1 = 1 
                AND " . QSQL_IN('tbl_order.`status`', $filter_status, $this) . " 
                AND " . QSQL_IN('tbl_order.order_type', $filter_order_type, $this) . "
                AND " . QSQL_IN('tbl_order.ed_type', $filter_ed_type, $this) . "
                
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

    function get_list_for_qc($id_user, $status = '')
    {
        $list_order = [];
        $iconn = $this->db->conn_id;
        // lấy list id order theo user từ bảng `tbl_job_user`
        $list_id_order = $this->_get_list_id_order_by_user_id($id_user, $iconn);
        $str_id_order = implode(',', $list_id_order);

        $ORDER_PENDING = ORDER_PENDING;
        $sql = "SELECT A.*, B.code_user as code_user
        FROM tbl_order as A
        INNER JOIN tbl_user B ON A.id_user = B.id_user 
        WHERE A.status != $ORDER_PENDING ";

        if ($str_id_order != '') {
            $sql .= " OR A.id_order IN ($str_id_order) ";
        }

        $sql .= " ORDER BY FIELD(A.status, " . implode(',', $this->_status_sort) . "), A.create_time DESC ";

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
                        $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                        $row['team'] = $team;

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


    function get_list_order_by_status($status = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $where = 'WHERE 1=1 ';
        $where .= $status !== '' ? " AND A.status =? " : "";

        $sql = "
        SELECT A.*, B.name as style
        FROM tbl_order as A
        LEFT JOIN tbl_style as B ON A.id_style = B.id_style
        $where
        ORDER BY A.status ASC, A.create_time ASC";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$status])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $data[$row['id_order']] = $row;
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


    /** 
     * LẤY DANH SÁCH ĐƠN THEO USER
     */
    function get_list_order_by_id_user($id_user)
    {
        $list_id_order = [];
        $list_order = [];
        $iconn = $this->db->conn_id;

        // lấy list id order theo user từ bảng `tbl_job_user`
        $list_id_order = $this->_get_list_id_order_by_user_id($id_user, $iconn);

        // lấy list info order theo list id_order
        if (!empty($list_id_order)) {
            $str_id_order = implode(',', $list_id_order);
            $sql = "SELECT A.*, B.code_user as code_user
            FROM tbl_order as A
            INNER JOIN tbl_user B ON A.id_user = B.id_user 
            WHERE id_order IN ($str_id_order)
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
                            $team = $this->_get_list_editor_by_id_order($row['id_order'], $iconn);
                            $row['team'] = $team;

                            $list_order[$row['id_order']] = $row;
                        }
                    }
                } else {
                    var_dump($stmt->errorInfo());
                    die;
                }
            }
            $stmt->closeCursor();
        }
        return $list_order;
    }

    function get_total_order_working_by_id_user($id_user)
    {
        $total_order = 0;
        $iconn = $this->db->conn_id;

        // lấy list id order theo user từ bảng `tbl_job_user`
        $list_id_order = $this->_get_list_id_order_by_user_id($id_user, $iconn);

        // lấy list info order theo list id_order
        if (!empty($list_id_order)) {
            $str_id_order = implode(',', $list_id_order);
            $sql = "SELECT count(*) as total_order
            FROM tbl_order as A
            WHERE id_order IN ($str_id_order) AND status IN (" . implode(',', $this->_status_working) . ")";

            $stmt = $iconn->prepare($sql);
            if ($stmt) {
                if ($stmt->execute()) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $total_order = (int) $row['total_order'];
                    }
                } else {
                    var_dump($stmt->errorInfo());
                    die;
                }
            }
            $stmt->closeCursor();
        }
        return $total_order;
    }

    function _get_list_id_order_by_user_id($id_user, $iconn)
    {
        $list_id_order = [];
        // lấy list id order theo user từ bảng `tbl_job_user`
        $sql = "SELECT A.id_order
        FROM tbl_job_user as A
        WHERE A.id_user IN (?) AND A.status = 1
        GROUP BY A.id_order";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_user])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $list_id_order[] = $row['id_order'];
                    }
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }

        return $list_id_order;
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
    //  END LẤY DANH SÁCH ĐƠN THEO USER

    // DANH SÁCH REWORK CỦA JOB
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
    // END DANH SÁCH REWORK CỦA JOB

    function box_count($list_order)
    {
        $box = [];
        foreach ($list_order as $id_order => $order) {
            $status = $order['status'];

            if ($status == ORDER_PENDING) {
                $box['pending'] = isset($box['pending']) ? $box['pending'] + 1 : 1;
            }
            if ($status == ORDER_REWORK) {
                $box['rework'] = isset($box['rework']) ? $box['rework'] + 1 : 1;
            }
            if ($status == ORDER_COMPLETE) {
                $box['complete'] = isset($box['complete']) ? $box['complete'] + 1 : 1;
            }
            if (in_array($status, [ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE])) {
                $box['progress'] = isset($box['progress']) ? $box['progress'] + 1 : 1;
            }

            if (is_late_order($order)) {
                $box['late'] = isset($box['late']) ? $box['late'] + 1 : 1;
            }
        }

        $list_image_avaiable = $this->danh_sach_image_avaiable();
        $box['image_avaiable'] = count($list_image_avaiable);

        return $box;
    }

    function box_for_qc_ed($list_order)
    {
        $box = [];
        foreach ($list_order as $id_order => $order) {
            $status = $order['status'];

            if (in_array($status, [ORDER_QC_CHECK, ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE])) {
                $box['progress'] = isset($box['progress']) ? $box['progress'] + 1 : 1;
            }

            if ($status == ORDER_REWORK) {
                $box['rework'] = isset($box['rework']) ? $box['rework'] + 1 : 1;
            }

            if ($status == ORDER_COMPLETE) {
                $box['complete'] = isset($box['complete']) ? $box['complete'] + 1 : 1;
            }


            if (is_late_order($order)) {
                $box['late'] = isset($box['late']) ? $box['late'] + 1 : 1;
            }
        }

        return $box;
    }

    function tim_don_gan_nhat_cho_ed()
    {
        $data = 0;
        $iconn = $this->db->conn_id;
        $trang_thai = implode(',', [ORDER_AVAIABLE, ORDER_PROGRESS]);
        $sql = "SELECT * FROM tbl_order WHERE `status` IN ($trang_thai) ORDER BY id_order ASC LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            var_dump($stmt->errorInfo());
            die;
        }
        $stmt->closeCursor();
        return $data;
    }

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

    function luu_thoi_gian_kiem_tra_don($id_order, $thoi_gian_kiem_tra)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_sale_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_kiem_tra, $id_order];

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

    function luu_thoi_gian_lam_xong_don($id_order, $thoi_gian_lam_xong)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_editor_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_lam_xong, $id_order];

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

    function luu_thoi_gian_giao_hang($id_order, $thoi_gian_giao_hang)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET done_qc_time=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$thoi_gian_giao_hang, $id_order];

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

    function update_custom_order($id_order, $custom)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET custom=? WHERE id_order=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$custom, $id_order];

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

    function update_custom_order_for_user($id_order, $custom, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET custom=?, withdraw_status = 0 WHERE id_order=? AND id_job = 0 AND id_user=?";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$custom, $id_order, $id_user];

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

    function add_job_user($id_order, $id_job, $id_user, $username, $type_service, $type_job_user, $status, $time_join, $unit)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_job_user (id_order, id_job, id_user, username, type_service, type_job_user, status, time_join, custom) VALUES (?, ?, ?, ?, ?, ?, ?, ?, $unit)";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$id_order, $id_job, $id_user, $username, $type_service, $type_job_user, $status, $time_join];

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

    function kiem_tra_user_da_ton_tai_trong_job_chua($id_order, $id_job, $type_job_user, $id_user)
    {
        $data = false;
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_job_user WHERE id_order=? AND id_job=? AND type_job_user=? AND id_user=? LIMIT 1";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$id_order, $id_job, $type_job_user, $id_user])) {

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $data = true;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        return $data;
    }

    function change_status_job_user($status, $id_order, $id_job, $type_job_user, $id_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET status=? WHERE id_order=? AND id_job=? AND type_job_user=? AND id_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$status, $id_order, $id_job, $type_job_user, $id_user];

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

    function thay_doi_status_tat_ca_job_user($status, $id_order, $id_job, $type_job_user)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET status=? WHERE id_order=? AND id_job=? AND type_job_user=?";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$status, $id_order, $id_job, $type_job_user];

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

    function danh_sach_image_avaiable()
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.* 
        FROM `tbl_job` A
        WHERE A.id_order IN (SELECT B.id_order FROM tbl_order B WHERE B.status IN (?,?,?))
        AND A.id_job NOT IN (SELECT C.id_job FROM tbl_job_user C WHERE C.id_job = A.id_job AND C.type_job_user = ? AND C.status = 1)
        ORDER BY A.create_time ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([ORDER_AVAIABLE, ORDER_PROGRESS, ORDER_DONE, WORKING_EDITOR])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

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

    function get_order_info_by_code($code)
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_order WHERE code_order = ? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$code])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function update_code_order($id_order, $code)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET code_order=? WHERE id_order=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$code, $id_order];

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

    function update_custom_time_order($id_order, $second)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_order SET custom_time=? WHERE id_order=? LIMIT 1";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$second, $id_order];

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

    function tinh_tien_cho_cac_user_dang_active($id_order)
    {
        $execute = false;
        $iconn = $this->db->conn_id;
        $sql = "UPDATE tbl_job_user SET withdraw=1 WHERE id_order= $id_order AND status=1;";

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
