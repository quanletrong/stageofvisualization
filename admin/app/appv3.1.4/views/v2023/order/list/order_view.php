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

            <?php $this->load->view(TEMPLATE_FOLDER . 'order/list/bo_loc_view.php'); ?>

            <!-- BẢNG DỮ LIỆU -->
            <table id="example1" class="table table-bordered table-striped">
                <thead class="thead-danger">
                    <tr>
                        <th class="text-center" style="max-width: 200px;">JID</th>
                        <th class="text-center">CID</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">JOB TYPE</th>
                        <th class="text-center">IMAGE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">COUNDOWN TIME</th>
                        <th class="text-center">TEAM WORKING</th>
                        <?php if (in_array($role, [ADMIN, SALE])) { ?>
                            <th class="text-center">PHÂN ĐƠN</th>
                        <?php } ?>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($list_order as $id_order => $order) { ?>
                        <tr class="text-default">
                            <td class="align-middle text-center" style="max-width: 200px; line-break: anywhere"><?= $order['code_order'] == '' ? 'OID' . $order['id_order'] : $order['code_order'] ?></td>
                            <td class="align-middle text-center"><?= $order['code_user'] == '' ? 'UID' . $order['id_user'] :  $order['code_user'] ?></td>
                            <td class="align-middle text-center"><span title="<?= $order['create_time'] ?>"><?= timeSince($order['create_time']) ?> trước </span></td>
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
                            <td class="align-middle" style="max-width: 150px;">
                                <?php foreach ($order['team'] as $id_user => $user) { ?>
                                    <img src="<?= url_image($user['avatar'], 'uploads/avatar/') ?>" title="<?= $user['username'] . ' - ' . $user['fullname'] ?>" alt="<?= $user['username'] ?>" class="img-circle shadow" style="margin-bottom: 5px; width: 36px; aspect-ratio: 1; object-fit: cover;">
                                <?php } ?>

                            </td>
                            <?php if (in_array($role, [ADMIN, SALE])) { ?>
                                <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="drop_change_don_ed_type_<?= $order['id_order'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php if ($order['ed_type'] == ED_NOI_BO) { ?>
                                                <span class="text-secondary">ED nội bộ</span>
                                            <?php } else { ?>
                                                <span class="text-success">ED cộng tác</span>
                                            <?php } ?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="drop_change_don_ed_type_<?= $order['id_order'] ?>">
                                            <button class="dropdown-item" type="button" onclick="drop_change_don_ed_type(<?= ED_NOI_BO ?>, <?= $order['id_order'] ?>)">
                                                <span class="text-secondary">ED Nội bộ</span>
                                            </button>
                                            <button class="dropdown-item" type="button" onclick="drop_change_don_ed_type(<?= ED_CTV ?>, <?= $order['id_order'] ?>)">
                                                <span class="text-success">ED cộng tác</span>
                                            </button>
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
    // sau 5 phut sẽ load lại trang
    setTimeout(() => {
        // window.location.reload();
    }, 1000 * 60 * 5);

    $(function() {

        $("#example1").DataTable({
            "order": [],
            "lengthChange": true,
            "pageLength": 50,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


    });

    function drop_change_don_ed_type(ed_type, id_order) {
        $('#drop_change_don_ed_type_' + id_order).html('<i class="fas fa-sync fa-spin "></i>');

        $.ajax({
            type: "POST",
            url: "<?= site_url('order/ajax_update_ed_type') ?>", // dùng chung trong auction
            data: {
                'ed_type': ed_type,
                'id_order': id_order,
            },
            success: function(res) {
                try {
                    let resData = JSON.parse(res);
                    if (resData.status) {

                        if (ed_type == <?= ED_NOI_BO ?>) {
                            $('#drop_change_don_ed_type_' + id_order).html('<span class="text-secondary">ED nội bộ</span>');

                        } else {
                            $('#drop_change_don_ed_type_' + id_order).html('<span class="text-success">ED cộng tác</span<');
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