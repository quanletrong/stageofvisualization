<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }


    function danh_sach_chua_rut_tien($id_user, $fdate = '', $tdate = '')
    {
        $data = [];
        $iconn = $this->db->conn_id;

        $this->session->set_flashdata('PARAMS', []);
        $sql = "SELECT id_job_user, id_user, id_order, id_job, type_job_user, type_service, (custom-withdraw_custom) as num
        FROM tbl_job_user
        WHERE 
            id_user = $id_user 
            AND withdraw = 1 AND custom > 0 
            AND (withdraw_custom < custom)
            AND " . QSQL_BETWEEN('tbl_job_user.time_join', $fdate, $tdate, $this) . " ;";

        $PARAMS = $this->session->flashdata('PARAMS');
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute($PARAMS)) {
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

    function withdraw_get_list_v2()
    {
        $data = [];
        $iconn = $this->db->conn_id;
        $sql = "SELECT A.create_time, A.approve_time, SUM(A.custom) custom, A.`status`, A.id_user, B.username, B.role, B.code_user, B.avatar, B.fullname 
        FROM tbl_withdraw as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user 
        GROUP BY A.id_user, A.create_time, A.status
        ORDER BY A.status ASC, A.create_time ASC";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }

    function withdraw_get_detail_v2($id_user, $create_time, $status)
    {
        $data['list'] = [];
        $data['services'] = [];

        $iconn = $this->db->conn_id;
        $sql = "SELECT A.*, B.username, B.role, B.code_user, B.avatar, B.fullname, C.code_order 
        FROM tbl_withdraw as A
        INNER JOIN tbl_user as B ON A.id_user = B.id_user
        INNER JOIN tbl_order as C ON A.id_order = C.id_order
        WHERE A.id_user = $id_user AND A.create_time = '$create_time' AND A.`status` = $status
        ORDER BY A.id_order;
        
        SELECT A.type_service, sum(A.custom) custom
        FROM tbl_withdraw as A
        WHERE A.id_user = $id_user AND A.create_time = '$create_time' AND A.`status` = $status
        GROUP BY A.type_service;";

        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data['list'][$row['id_withdraw']] = $row;
                }

                $stmt->nextRowset();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data['services'][$row['type_service']] = $row['custom'];
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


    function __withdraw_get_detail($id_user, $status)
    {
        $data['tong_hop'] = [];
        $data['group_date'] = [];
        $data['all'] = [];
        $iconn = $this->db->conn_id;

        $sql =
            "SELECT
            A.id_order,
            A.status,
            A.type_service,
            A.create_time,
            sum( A.custom ) AS custom,
            B.username,
            B.role,
            B.code_user,
            B.avatar,
            B.fullname,
            C.code_order 
        FROM
            tbl_withdraw AS A
            INNER JOIN tbl_user AS B ON A.id_user = B.id_user
            INNER JOIN tbl_order AS C ON A.id_order = C.id_order 
        WHERE
            A.id_user = $id_user 
            AND A.status = $status 
        GROUP BY
            A.create_time,
            A.type_service,
            A.id_order 
        ORDER BY
            A.create_time,  A.id_order ;
        ";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // all
                        $data['all'][] = $row;

                        // cộng type_service giống nhau

                        $row['type_service'] = $row['type_service'] == '' ? 'CUSTOM' : $row['type_service'];

                        if (isset($data['tong_hop'][$row['type_service']])) {
                            $data['tong_hop'][$row['type_service']] += $row['custom'];
                        } else {
                            $data['tong_hop'][$row['type_service']] = $row['custom'];
                        }

                        // nhóm tất cả withdraw cùng 1 thời gian
                        $data['group_date'][$row['create_time']][] = $row;
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
        $approve_time = date('Y-m-d H:i:s');
        $sql = "UPDATE tbl_withdraw SET status = 1, approve_time = '$approve_time' WHERE id_withdraw IN ($str_id_withdraw);";
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

    function get_kpi($filter)
    {
        $data['user'] = [];
        $data['list_service'] = [];
        $iconn = $this->db->conn_id;

        $filter_fdate   = isset($filter['fdate'])   ? $filter['fdate']  : '';
        $filter_tdate   = isset($filter['tdate'])   ? $filter['tdate']  : '';
        $filter_id_user = isset($filter['id_user']) ? $filter['id_user'] : '';
        $filter_role    = isset($filter['role'])    ? $filter['role']   : '';

        $this->session->set_flashdata('PARAMS', []);
        $sql =
            "SELECT
                tbl_user.username,
                tbl_user.fullname,
                tbl_user.role,
                tbl_user.avatar,
                tbl_withdraw.id_user,
                GROUP_CONCAT( DISTINCT tbl_withdraw.type_service ) AS `service`,
                sum( tbl_withdraw.custom ) AS total 
            FROM
                `tbl_withdraw`
                INNER  JOIN tbl_user ON tbl_user.id_user = tbl_withdraw.id_user 
            WHERE
                tbl_withdraw.`status` = 0 
                AND " . QSQL_IN('tbl_withdraw.`id_user`', $filter_id_user, $this) . " 
                AND " . QSQL_IN('tbl_user.`role`', $filter_role, $this) . " 
                AND " . QSQL_BETWEEN('tbl_withdraw.create_time', $filter_fdate, $filter_tdate, $this) . " 
            GROUP BY
                tbl_withdraw.id_user,
                tbl_withdraw.type_service";

        $PARAMS = $this->session->flashdata('PARAMS');
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute($PARAMS)) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $id_user = $row['id_user'];

                        $data['user'][$id_user]['id_user']    = $row['id_user'];
                        $data['user'][$id_user]['username']   = $row['username'];
                        $data['user'][$id_user]['fullname']   = $row['fullname'];
                        $data['user'][$id_user]['role']       = $row['role'];
                        $data['user'][$id_user]['avatar_url'] = url_image($row['avatar'], FOLDER_AVATAR);

                        if (isset($data['user'][$id_user]['total'])) {
                            $data['user'][$id_user]['total'] += $row['total'];
                        } else {
                            $data['user'][$id_user]['total'] = $row['total'];
                        }

                        $data['user'][$id_user]['list_service'][$row['service']] = $row['total'];
                        $data['list_service'][$row['service']] = $row['service'];
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

    function get_kpi_2($filter)
    {
        $data['user'] = [];
        $data['list_service'] = [];
        $iconn = $this->db->conn_id;

        $filter_fdate   = isset($filter['fdate'])   ? $filter['fdate']  : '';
        $filter_tdate   = isset($filter['tdate'])   ? $filter['tdate']  : '';
        $filter_id_user = isset($filter['id_user']) ? $filter['id_user'] : '';
        $filter_role    = isset($filter['role'])    ? $filter['role']   : '';

        $this->session->set_flashdata('PARAMS', []);

        $sql =
            "SELECT
                tbl_user.id_user,
                tbl_user.username,
                tbl_user.fullname,
                tbl_user.role,
                tbl_user.avatar,
                
                GROUP_CONCAT( DISTINCT tbl_job_user.type_service ) AS `service`,
                
                sum( tbl_job_user.custom ) AS total 
                
            FROM
                `tbl_job_user`
                INNER JOIN tbl_user ON tbl_user.id_user = tbl_job_user.id_user 
            WHERE
                tbl_job_user.withdraw = 1 
                AND tbl_job_user.custom > 0 
                AND ( tbl_job_user.withdraw_custom < tbl_job_user.custom ) 
                AND " . QSQL_IN('tbl_job_user.`id_user`', $filter_id_user, $this) . " 
                AND " . QSQL_IN('tbl_job_user.`role`', $filter_role, $this) . " 
                AND " . QSQL_BETWEEN('tbl_job_user.time_join', $filter_fdate, $filter_tdate, $this) . " 
            GROUP BY
                tbl_job_user.id_user,
                tbl_job_user.type_service
                ";

        $PARAMS = $this->session->flashdata('PARAMS');
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute($PARAMS)) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $id_user = $row['id_user'];

                        $data['user'][$id_user]['id_user']    = $row['id_user'];
                        $data['user'][$id_user]['username']   = $row['username'];
                        $data['user'][$id_user]['fullname']   = $row['fullname'];
                        $data['user'][$id_user]['role']       = $row['role'];
                        $data['user'][$id_user]['avatar_url'] = url_image($row['avatar'], FOLDER_AVATAR);

                        if (isset($data['user'][$id_user]['total'])) {
                            $data['user'][$id_user]['total'] += $row['total'];
                        } else {
                            $data['user'][$id_user]['total'] = $row['total'];
                        }

                        $data['user'][$id_user]['list_service'][$row['service']] = $row['total'];
                        $data['list_service'][$row['service']] = $row['service'];
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
