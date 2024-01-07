<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function log_list($dk)
    {
        $type       = isset($dk['type'])         ? $dk['type'] : '';
        $sdate      = isset($dk['sdate'])        ? $dk['sdate'] : '';
        $edate      = isset($dk['edate'])        ? $dk['edate'] : '';
        $by_id_user = isset($dk['by_id_user'])   ? $dk['by_id_user'] : '';
        $id_order   = isset($dk['id_order'])     ? $dk['id_order'] : '';
        $id_job     = isset($dk['id_job'])       ? $dk['id_job'] : '';
        $id_rework  = isset($dk['id_rework'])    ? $dk['id_rework'] : '';
        $old        = isset($dk['old'])          ? $dk['old'] : '';
        $new        = isset($dk['new'])          ? $dk['new'] : '';
        $id_user    = isset($dk['id_user'])      ? $dk['id_user'] : '';
        $limit      = isset($dk['limit'])        ? $dk['limit'] : 10000;
        $offset     = isset($dk['offset'])       ? $dk['offset'] : 0;

        $data = [];
        $iconn = $this->db->conn_id;

        $SQL['query'] = '';
        $SQL['param'] = [];

        $SQL['query'] = "
        SELECT A.*, B.avatar as avatar, B.username as by_uname, C.username as username 
        FROM tbl_log as A ";
        $SQL['query'] .= " LEFT JOIN tbl_user as B ON A.by_id_user = B.id_user ";
        $SQL['query'] .= " LEFT JOIN tbl_user as C ON A.id_user = C.id_user ";

        $SQL['query'] .= " WHERE 1=1 ";
        $SQL = sql_in($type, 'A.type', $SQL);
        $SQL = sql_between_number($sdate, $edate, 'A.created_time', $SQL);
        $SQL = sql_in($by_id_user, 'A.by_id_user', $SQL);
        $SQL = sql_in($id_order, 'A.id_order', $SQL);
        $SQL = sql_in($id_job, 'A.id_job', $SQL);
        $SQL = sql_in($id_rework, 'A.id_rework', $SQL);
        $SQL = sql_in($id_user, 'A.id_user', $SQL);
        $SQL = sql_like($old, 'A.old', $SQL);
        $SQL = sql_like($new, 'A.new', $SQL);

        $SQL['query'] .= " ORDER BY A.created_time DESC  LIMIT $limit OFFSET $offset";

        $stmt = $iconn->prepare($SQL['query']);
        if ($stmt) {
            if ($stmt->execute($SQL['param'])) {
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $row['avatar'] = url_image($row['avatar'], FOLDER_AVATAR);
                        $data[$row['id_log']] = $row;
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

    function log_add($log, $order)
    {
        $type           = isset($log['type'])         ? $log['type'] : '';
        $created_time   = isset($log['created_time']) ? $log['created_time'] : date('Y-m-d H:i:s');
        $by_id_user     = isset($log['by_id_user'])   ? $log['by_id_user'] : $this->_session_uid();
        $by_username    = isset($log['by_username'])  ? $log['by_username'] : $this->_session_uname();
        $id_order       = isset($log['id_order'])     ? $log['id_order'] : '';
        $id_job         = isset($log['id_job'])       ? $log['id_job'] : '';
        $id_rework      = isset($log['id_rework'])    ? $log['id_rework'] : '';
        $price_id_user  = isset($log['price_id_user']) ? $log['price_id_user'] : '';
        $price_username = isset($log['price_username']) ? $log['price_username'] : '';
        $old            = isset($log['old'])          ? $log['old'] : '';
        $new            = isset($log['new'])          ? $log['new'] : '';
        $db_old         = isset($log['db_old'])       ? $log['db_old'] : '';
        $db_new         = isset($log['db_new'])       ? $log['db_new'] : '';

        $new_id = 0;
        $iconn = $this->db->conn_id;
        $sql = "INSERT INTO tbl_log (`type`, created_time, `by_id_user`, id_order, id_job, `id_rework`, `id_user`, `old`, new, db_old, db_new) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $iconn->prepare($sql);
        if ($stmt) {
            $param = [$type, $created_time, $by_id_user, $id_order, $id_job, $id_rework, $price_id_user, $old, $new, $db_old, $db_new];

            if ($stmt->execute($param)) {
                $new_id = $iconn->lastInsertId();
            } else {
                var_dump($stmt->errorInfo());
                die;
            }
        }
        $stmt->closeCursor();


        $this->_sendmail($type, $id_order, $id_rework, $id_job, $order, $price_username, $old, $new, $created_time, $by_username);

        return $new_id;
    }

    function _sendmail($type, $id_order, $id_rework, $id_job, $order, $price_username, $old, $new, $created_time, $by_username)
    {
        $body = "";

        // TITLE
        $body .= LOG[$type];

        // REWWORK NẾU CÓ
        $stt_rework = @array_search($id_rework, array_keys($order['job'][$id_job]['rework'])) + 1;
        $body .= $id_rework > 0 ? " <b>$stt_rework</b> " : '';

        // IMAGE NẾU CÓ
        $stt_image = @array_search($id_job, array_keys($order['job'])) + 1;
        $body .= $id_job > 0 ? ' của <b>IMAGE ' . $stt_image . ' (' . $order['job'][$id_job]['type_service'] . ')</b> ' : '';

        // CUSTOM PRICE USER NẾU CÓ
        $body .= $price_username != '' ? " <b>$price_username</b> " : '';

        // CŨ
        if ($old != '') {
            $body .= " từ $old ";
        }

        // MỚI
        if ($new != '') {
            $body .= " <span style='color: red'>→</span> $new ";
        }

        // TIME 
        $body .= "<p>Vào lúc: " . date("H:s d/m/Y", strtotime($created_time)) . "</p>";
        $body .= "<p>Bởi: $by_username</p>";

        $data['id_order'] = $id_order;
        $data['body']     = $body;

        // gửi mail đến tài khoản.

        # danh sách tài khoản trong order
        # loại mail
        # setting nhận loại mail
        // $order['']

        $email['to']      = 'lequanltv@gmail.com';
        $email['subject'] = "#$id_order " . LOG[$type];
        $email['body']    = $this->load->view('v2023/component/tmpl_email_order', $data, true);

        @sendmail($email);
    }

    // function edit($code_user, $fullname, $pass_hash, $phone, $email, $status, $role, $type, $user_service_db, $update_time, $id_user)
    // {
    //     $execute = false;
    //     $iconn = $this->db->conn_id;
    //     $sql = "UPDATE tbl_user 
    //     SET code_user=?, fullname=?, `password`=?, phone=?, email=?, `status`=?, `role`=?, `type`=?, `user_service`=?, update_time=? 
    //     WHERE id_user=?";
    //     $stmt = $iconn->prepare($sql);
    //     if ($stmt) {
    //         if ($stmt->execute([$code_user, $fullname, $pass_hash, $phone, $email, $status, $role, $type, $user_service_db, $update_time, $id_user])) {
    //             $execute = true;
    //         } else {
    //             var_dump($stmt->errorInfo());
    //             die;
    //         }
    //     }
    //     $stmt->closeCursor();
    //     return $execute;
    // }
}
