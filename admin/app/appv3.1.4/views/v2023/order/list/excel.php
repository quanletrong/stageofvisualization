<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// init PHPExcel
require_once(PHPEXCEL_LIB_PATH_2022 . 'MYExcel2022.php');

$MYExcel = new MYExcel();
$tittle = 'DANH SÁCH ĐƠN HÀNG';
$MYExcel->HeaderExcel($tittle, $fromdate, $todate);

//HEADER 
$MYExcel->row($MYExcel->style_header());
$MYExcel->col()->text('STT')->horizontal('center');
$MYExcel->col()->text('JID')->horizontal('center');
$MYExcel->col()->text('CID')->horizontal('center');
$MYExcel->col()->text('DATE')->horizontal('center');
$MYExcel->col()->text('JOB TYPE')->horizontal('center');
$MYExcel->col()->text('IMAGE')->horizontal('center');
$MYExcel->col()->text('STATUS')->horizontal('center');
$MYExcel->col()->text('COWNDOWN TIME')->horizontal('center')->merge(1);
$MYExcel->col()->text('TEAM WORKING')->horizontal('center');

//BODY
$stt = 1;
foreach ($list_order as $id_order => $order) {

    #
    $order_code        = $order['code_order'] == '' ? 'OID' . $order['id_order'] : $order['code_order'];
    $order_code_user   = $order['code_user']  == '' ? 'UID' . $order['id_user'] : $order['code_user'];
    $order_create_time = date('H:s d-m-Y', strtotime($order['create_time']));
    $order_total_job = count($order['list_job']);

    #
    $order_service = [];
    foreach ($order['list_service'] as $id_service) {
        $order_service[] = $all_service[$id_service]['type_service'];
    }
    $order_service = implode(", ", $order_service);

    #
    $order_cdt = count_down_time_order_for_export($order); // ["QUÁ HẠN", "0 ngày 0 giờ 30 phút 0 giấy"]

    #
    $order_working = [];
    foreach ($order['list_user'] as $id_user) {
        $order_working[] = $all_user[$id_user]['username'];
    }
    $order_working = implode("\n", $order_working);

    #
    if ($order['status'] == ORDER_DONE) {
        $s = status_late_order('DONE', $order['create_time'], $order['done_editor_time'], $order['custom_time_v2']);
    } else if ($order['status'] == ORDER_DELIVERED) {
        $s = status_late_order('DELIVERED', $order['create_time'], $order['done_qc_time'], $order['custom_time_v2']);
    } else if ($order['status'] == ORDER_COMPLETE) {
        $s = status_late_order('COMPLETE', $order['create_time'], $order['done_qc_time'], $order['custom_time_v2']);
    } else {
        $s = status_order($order['status']);
    }

    $order_status = @$s['text'];

    #
    $MYExcel->row($MYExcel->style_normal());
    $MYExcel->col()->number($stt++)->horizontal('center')->width(5);
    $MYExcel->col()->text($order_code)->width(50);
    $MYExcel->col()->text($order_code_user)->width(20);
    $MYExcel->col()->text($order_create_time)->horizontal('center')->width(20);
    $MYExcel->col()->text($order_service)->width(20);
    $MYExcel->col()->number($order_total_job)->horizontal('center');
    $MYExcel->col()->text($order_status)->horizontal('center')->width(20);
    $MYExcel->col()->text($order_cdt[0])->width(20)->horizontal('right');
    $MYExcel->col()->text($order_cdt[1])->width(30);
    $MYExcel->col()->text($order_working)->width(30);
}

// EXPORT FILE
$file_name = "BAO-CAO-DON-HANG-" . $cur_uname . "-from-" . date('d-m-Y', strtotime($fromdate)) . "-to-" . date('d-m-Y', strtotime($todate)) . ".xlsx";
$MYExcel->run($file_name);
exit;
