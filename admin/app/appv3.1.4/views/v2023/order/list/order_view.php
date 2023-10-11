<?php ?>
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách đơn hàng</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SMALL BOX -->
            <?php
            if ($role == ADMIN || $role == SALE) {
                $this->load->view(TEMPLATE_FOLDER . 'order/list/small_box_sale_admin_view.php');
            } else if ($role == QC || $role == EDITOR) {
                $this->load->view(TEMPLATE_FOLDER . 'order/list/small_box_qc_ed_view.php');
            }
            ?>

            <!-- BẢNG DỮ LIỆU -->
            <table id="example1" class="table table-bordered table-striped">
                <thead class="thead-danger">
                    <tr>
                        <th class="text-center">JID</th>
                        <th class="text-center">CID</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">JOB TYPE</th>
                        <th class="text-center">IMAGE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">COUNDOWN TIME</th>
                        <th class="text-center">TEAM WORKING</th>
                        <?php if (in_array($role, [ADMIN, SALE])) { ?>
                            <th class="text-center">NỘI BỘ</th>
                        <?php } ?>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($list_order as $id_order => $order) { ?>
                        <tr class="text-default">
                            <td class="align-middle text-center"><?= $order['code_order'] == '' ? 'OID' . $order['id_order'] : $order['code_order'] ?></td>
                            <td class="align-middle text-center"><?= $order['code_user'] == '' ? 'UID' . $order['id_user'] :  $order['code_user'] ?></td>
                            <td class="align-middle text-center"><span title="<?= timeSince($order['create_time']) ?> trước"><?= $order['create_time'] ?> </span></td>
                            <td class="align-middle text-center">
                                <?php foreach ($order['type_service'] as $val) { ?>
                                    <small class="badge badge-danger"><?= $val ?></small>
                                <?php } ?>
                            </td>
                            <td class="align-middle text-center"><?= $order['total_job'] ?></td>
                            <td class="align-middle text-center">
                                <?php
                                if ($order['status'] == ORDER_DONE) {
                                    $s = status_late_order('DONE', $order['create_time'], $order['done_editor_time'], $order['custom_time']);
                                } else if ($order['status'] == ORDER_DELIVERED) {
                                    $s = status_late_order('DELIVERED', $order['create_time'], $order['done_qc_time'], $order['custom_time']);
                                } else if ($order['status'] == ORDER_COMPLETE) {
                                    $s = status_late_order('COMPLETE', $order['create_time'], $order['done_qc_time'], $order['custom_time']);
                                } else {
                                    $s = status_order($order['status']);
                                }
                                echo '<small class="badge" style="color:white;background-color: ' . @$s['bg'] . '">' . @$s['text'] . '</small>';
                                ?>
                            </td>
                            <td class="align-middle text-center">
                                <span id="cdt_<?= $order['id_order'] ?>"><?= count_down_time_order($order) ?></span>

                            </td>
                            <td class="align-middle text-center" style="max-width: 350px;">
                                <?php foreach ($order['team'] as $id_user => $user) { ?>
                                    <img src="<?= url_image($user['avatar'], 'uploads/avatar/') ?>" title="<?= $user['username'] . ' - ' . $user['fullname'] ?>" alt="<?= $user['username'] ?>" class="img-circle shadow" style="width: 36px; aspect-ratio: 1; object-fit: cover;">
                                <?php } ?>

                            </td>
                            <?php if (in_array($role, [ADMIN, SALE])) { ?>
                                <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="drop_change_don_noi_bo_<?= $order['id_order'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php if ($order['noi_bo'] == '1') { ?>
                                                <i class="fas fa-lock text-secondary" title="Nội bộ"></i>
                                            <?php } else { ?>
                                                <i class="fas fa-globe-europe text-success" title="Công khai"></i>
                                            <?php } ?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="drop_change_don_noi_bo_<?= $order['id_order'] ?>">
                                            <button class="dropdown-item" type="button" onclick="drop_change_don_noi_bo(1, <?= $order['id_order'] ?>)">Nội bộ</button>
                                            <button class="dropdown-item" type="button" onclick="drop_change_don_noi_bo(2, <?= $order['id_order'] ?>)">Công khai</button>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                            <td class="align-middle text-center"><a href="order/detail/<?= $id_order ?>">[VIEW JOB]</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(function() {

        $("#example1").DataTable({
            "lengthChange": true,
            "pageLength": 50,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        
    });

    function drop_change_don_noi_bo(noi_bo, id_order) {
        $('#drop_change_don_noi_bo_' + id_order).html('<i class="fas fa-sync fa-spin "></i>');

        $.ajax({
            type: "POST",
            url: "<?= site_url('order/ajax_update_noi_bo') ?>", // dùng chung trong auction
            data: {
                'noi_bo': noi_bo,
                'id_order': id_order,
            },
            success: function(res) {
                try {
                    let resData = JSON.parse(res);
                    if (resData.status) {

                        if (noi_bo == 1) {
                            $('#drop_change_don_noi_bo_' + id_order).html('<i class="fas fa-lock text-secondary" title="Nội bộ"></i>');
                            
                        } else {
                            $('#drop_change_don_noi_bo_' + id_order).html('<i class="fas fa-globe-europe text-success" title="Công khai"></i>');
                        }
                        toasts_success();
                    } else {
                        toasts_danger(resData.data);
                    }
                } catch (error) {
                    toasts_danger();
                }
            }
        });
    }
</script>