<div class="content-wrapper">
    <div class="container-fluid">
        <!-- PAGE HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?= $title ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('withdraw') ?>">Rút tiền</a></li>
                            <li class="breadcrumb-item active"><?= $title ?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <?php if (count($list_order)) { ?>
            <!-- TỔNG HỢP PENDING -->
            <section>
                <div class="d-flex" style="gap: 10px; justify-content: center;">
                    <?php foreach ($services as $type_service => $custom) { ?>
                        <div class="info-box bg-<?= $status ? 'success' : 'danger' ?>" style="max-width: 20%">
                            <div class="info-box-content">
                                <span class="info-box-text"><?= $type_service ?></span>
                                <span class="info-box-number"><?= $custom ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>

            <!-- PHÊ DUYỆT YÊU CẦU  -->
            <?php if ($status == '0') { ?>
                <section class="text-center">
                    <button class="btn btn-success" onclick="ajax_phe_duyet_rut_tien(this)" style="width: 300px;">APPROVED</button>
                </section>
            <?php } ?>


            <!-- GROUP DATE PENDING -->
            <section>
                Chi tiết yêu cầu <small onclick="$('#chi_tiet_table').fadeToggle()" style="cursor: pointer;"> [Ẩn/hiện]</small>
                <div id="chi_tiet_table">
                    <table id="example1" class="table table-bordered">
                        <thead class="thead-<?= $status ? 'success' : 'danger' ?>">
                            <tr>
                                <th class="text-center" width="50">STT</th>
                                <th class="text-center">WITHDRAW ORDER</th>
                                <?php foreach ($services as $type_service => $custom) { ?>
                                    <th class="text-center" width="100"><?= $type_service ?></th>
                                <?php } ?>
                                <th class="text-center" width="100">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach ($list_order as $id_order => $item) { ?>
                                <tr class="text-default">
                                    <td class="align-middle text-center"><?= $index++ ?></td>
                                    <td class="align-middle">
                                        <a href="order/detail/<?= $id_order ?>"><?= '[ORDER ' . $id_order . ']' ?></a> <?= $item['code_order'] != '' ? $item['code_order'] : '' ?>
                                    </td>

                                    <?php foreach ($services as $type_service => $custom) { ?>
                                        <td class="align-middle text-center"><?= @$item['list_service'][$type_service] ?></td>
                                    <?php } ?>

                                    <td class="align-middle text-center">
                                        <?= $status ? '<span class="badge bg-success">APPROVED</span>' : '<span class="badge bg-danger">PENDING</span>' ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </section>
        <?php } else { ?>
            <span>Không có yêu cầu chờ xử lý. <a href="withdraw">Quay lại</a></span>
        <?php } ?>
    </div>
</div>

<script>
    $(function() {

        $("#example1").DataTable({
            "pageLength": 1000,
            "responsive": true,
            "autoWidth": false,
            "lengthChange": false,
            "searching": true,
            "buttons": ["excel", "pdf"],
            "ordering": true,
            "order": []
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    function ajax_phe_duyet_rut_tien(btn) {

        if (confirm(`Bạn chắc chắn muốn phê duyệt?`) == true) {
            let old_text = $(btn).html();
            $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
            $(btn).prop("disabled", true);
            $.ajax({
                url: `withdraw/ajax_phe_duyet_rut_tien`,
                data: {
                    'id_user': '<?= $id_user ?>',
                    'status': '<?= $status ?>',
                    'create_time': '<?= $create_time ?>',
                },
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        toasts_success();
                        location.reload();
                    } else {
                        toasts_danger(kq.error);
                    }

                    $(btn).html(old_text);
                    $(btn).prop("disabled", false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        }
    }
</script>