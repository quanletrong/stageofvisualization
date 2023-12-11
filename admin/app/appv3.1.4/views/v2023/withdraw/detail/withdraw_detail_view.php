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


        <h4 class="">YÊU CẦU CHƯA XỬ LÝ</h4>
        <?php if (count($all_pending)) { ?>
            <!-- TỔNG HỢP PENDING -->
            <section>
                <div class="d-flex" style="gap: 10px;">
                    <?php foreach ($tong_hop_pending as $type_service => $number) { ?>
                        <div class="info-box bg-danger" style="max-width: 25%">
                            <div class="info-box-content">
                                <span class="info-box-text"><?= $type_service ?></span>
                                <span class="info-box-number"><?= $number ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>

            <!-- PHÊ DUYỆT YÊU CẦU  -->
            <section class="text-center">
                <button class="btn btn-danger" onclick="ajax_phe_duyet_rut_tien(this, <?= $uinfo['id_user'] ?>)" style="width: 300px;">Phê duyệt yêu cầu rút tiền</button>
            </section>


            <!-- GROUP DATE PENDING -->
            <section>
                Chi tiết yêu cầu <small onclick="$('#example1').fadeToggle()" style="cursor: pointer;"> [Ẩn/hiện]</small>
                <table id="example1" class="table table-bordered">
                    <thead class="thead-danger">
                        <tr>
                            <th class="text-center" width="50">STT</th>
                            <th class="text-center">TYPE SERVICE</th>
                            <th class="text-center">WITHDRAW ORDER</th>
                            <th class="text-center">WITHDRAW NUMBER</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($group_date_pending as $create_date => $list_withdraw) { ?>
                            <tr class="text-default" style="background-color: #ffd6d6;">
                                <td colspan="5">Thời gian yêu cầu <?= date('H:i (d/m/Y)', strtotime($create_date)) ?></td>
                            </tr>
                            <?php foreach ($list_withdraw as $id_withdraw => $item) { ?>
                                <tr class="text-default">
                                    <td class="align-middle text-center"><?= $index++ ?></td>
                                    <td class="align-middle text-center"><?= $item['type_service'] ?></td>
                                    <td class="align-middle">
                                        <a href="order/detail/<?= $item['id_order'] ?>"><?= '[ORDER ' . $item['id_order'] . ']' ?></a> <?= $item['code_order'] != '' ? $item['code_order'] : '' ?>
                                    </td>
                                    <td class="align-middle text-center"><?= $item['custom'] ?></td>

                                    <td class="align-middle text-center">
                                        <?= $item['status'] ? '<span class="badge bg-success">DONE</span>' : '<span class="badge bg-danger">PENDING</span>' ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                    </tbody>
                </table>

            </section>
        <?php } else { ?>
            <span>Không có yêu cầu chờ xử lý. <a href="withdraw">Quay lại</a></span>
        <?php } ?>

        <h4 style="margin-top: 50px;">YÊU CẦU ĐÃ XỬ LÝ</h4>
        <?php if (count($all_done)) { ?>
            <!-- TỔNG HỢP PENDING -->
            <section>
                <div class="d-flex" style="gap: 10px;">
                    <?php foreach ($tong_hop_done as $type_service => $number) { ?>
                        <div class="info-box bg-success" style="max-width: 25%">
                            <div class="info-box-content">
                                <span class="info-box-text"><?= $type_service ?></span>
                                <span class="info-box-number"><?= $number ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>

            <!-- GROUP DATE DONE -->
            <section>
                <small onclick="$('#example2').fadeToggle()" style="cursor: pointer;">[Ẩn/hiện]</small>
                <table id="example2" class="table table-bordered">
                    <thead class="thead-success">
                        <tr>
                            <th class="text-center" width="50">STT</th>
                            <th class="text-center">TYPE SERVICE</th>
                            <th class="text-center">WITHDRAW ORDER</th>
                            <th class="text-center">WITHDRAW NUMBER</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($group_date_done as $create_date => $list_withdraw) { ?>
                            <tr class="text-default" style="background-color: #aeebbc;">
                                <td colspan="5">Thời gian yêu cầu <?= date('H:i (d/m/Y)', strtotime($create_date)) ?></td>
                            </tr>
                            <?php foreach ($list_withdraw as $id_withdraw => $item) { ?>
                                <tr class="text-default">
                                    <td class="align-middle text-center"><?= $index++ ?></td>
                                    <td class="align-middle text-center"><?= $item['type_service'] ?></td>
                                    <td class="align-middle">
                                        <a href="order/detail/<?= $item['id_order'] ?>"><?= '[ORDER ' . $item['id_order'] . ']' ?></a> <?= $item['code_order'] != '' ? $item['code_order'] : '' ?>
                                    </td>
                                    <td class="align-middle text-center"><?= $item['custom'] ?></td>

                                    <td class="align-middle text-center">
                                        <?= $item['status'] ? '<span class="badge bg-success">DONE</span>' : '<span class="badge bg-danger">PENDING</span>' ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                    </tbody>
                </table>
            </section>
        <?php } else { ?>
            <span>Không có yêu cầu đã xử lý.</span>
        <?php } ?>
    </div>
</div>

<script>
    function ajax_phe_duyet_rut_tien(btn, id_user) {

        if (confirm(`Bạn chắc chắn muốn phê duyệt?`) == true) {
            let old_text = $(btn).html();
            $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
            $(btn).prop("disabled", true);
            $.ajax({
                url: `withdraw/ajax_phe_duyet_rut_tien/${id_user}`,
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