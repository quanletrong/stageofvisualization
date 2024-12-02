<?php ?>
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<style>
    .avatar-working {
        margin-right: -20px;
        margin-bottom: 5px;
        width: 36px;
        aspect-ratio: 1;
        object-fit: cover;
        border: 2px solid white;
    }

    .list-avatar-working:hover .avatar-working {
        margin-right: 0;
        transition-property: margin-right;
        transition-duration: 500ms;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">

            <!-- SMALL BOX -->
            <?php
            if ($role == ADMIN || $role == SALE || $role == QC) {
                $this->load->view(TEMPLATE_FOLDER . 'order/list/small_box_sale_admin_view.php');
            } else if ($role == EDITOR) {
                $this->load->view(TEMPLATE_FOLDER . 'order/list/small_box_qc_ed_view.php');
            }
            ?>

            <!-- BỘ LỌC -->
            <?php $this->load->view(TEMPLATE_FOLDER . 'order/list/bo_loc_view.php'); ?>

            <!-- ACTION BUTTON -->
            <div class="d-flex mt-2" style="gap:10px">
                <button id="cancle_order" class="btn btn btn-danger" style="display: none;" onclick="ajax_cancle_order(this)"><i class="fas fa-trash"></i> Xóa đơn hàng đã chọn</button>
            </div>

            <!-- BẢNG DỮ LIỆU -->
            <table id="example1" class="table table-bordered table-striped">
                <thead class="thead-danger">
                    <tr>

                        <th class="text-center" style="width: 50px;">
                            <?php if ($role == ADMIN || $role == SALE) { ?>
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkbox_all_order">
                                    <label for="checkbox_all_order">#</label>
                                </div>
                            <?php } else { ?>
                                #
                            <?php } ?>
                        </th>

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
                    <?php foreach ($list_order as $id_order => $order) { ?>
                        <tr class="text-default">

                            <td class="align-middle text-center">
                                <?php if ($role == ADMIN || $role == SALE) { ?>
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkbox_<?= $id_order ?>" class="checkox_order" data-order="<?= $id_order ?>">
                                        <label for="checkbox_<?= $id_order ?>">#<?= $id_order ?></label>
                                    </div>
                                <?php } else { ?>
                                    <b># <?= $id_order ?></b>
                                <?php } ?>
                            </td>

                            <td class="align-middle text-center" style="max-width: 200px; line-break: anywhere"><?= $order['code_order'] == '' ? 'OID' . $order['id_order'] : $order['code_order'] ?></td>
                            <td class="align-middle text-center"><?= $order['code_user'] == '' ? 'UID' . $order['id_user'] :  $order['code_user'] ?></td>
                            <td class="align-middle text-center"><span title="<?= $order['create_time'] ?>"><?= timeSince($order['create_time']) ?> trước </span></td>
                            <td class="align-middle text-center">
                                <?php foreach ($order['list_service'] as $val) { ?>
                                    <small class="badge badge-danger"><?= $all_service[$val]['type_service'] ?></small>
                                <?php } ?>
                            </td>
                            <td class="align-middle text-center"><?= count($order['list_job']) ?></td>
                            <td class="align-middle text-center">
                                <?php
                                if ($order['status'] == ORDER_DONE) {
                                    $s = status_late_order('DONE', $order['done_editor_time'], $order['expire']);
                                } else if ($order['status'] == ORDER_DELIVERED) {
                                    $s = status_late_order('DELIVERED', $order['done_qc_time'], $order['expire']);
                                } else if ($order['status'] == ORDER_COMPLETE) {
                                    $s = status_late_order('COMPLETE', $order['done_qc_time'], $order['expire']);
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
                                <div class="d-flex flex-wrap list-avatar-working" style="gap:5px">
                                    <?php foreach ($order['list_user'] as $id_user) { ?>
                                        <img src="<?= url_image($all_user[$id_user]['avatar'], 'uploads/avatar/') ?>" title="<?= $all_user[$id_user]['username'] . ' - ' . $all_user[$id_user]['fullname'] ?>" alt="<?= $all_user[$id_user]['username'] ?>" class="avatar-working img-circle shadow bg-white">
                                    <?php } ?>
                                </div>
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

            <!-- PAGING -->
            <div class="row py-3">
                <div class="col-md-6">
                    <div style="color: gray;">
                        Số đơn hàng đã tìm thấy <b><?php echo $total_order ?></b>.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex" style="flex-wrap: wrap; gap:15px;">
                        <div style="display: flex; gap:10px; align-items: center;">
                            <div style="color: gray;">Số đơn hàng/trang</div>
                            <select class="form-control" style="width: fit-content;" onchange="reload_page_by_row(this.value)">
                                <option value="30" <?php echo $filter_row == 30 ? 'selected' : '' ?>>30</option>
                                <option value="50" <?php echo $filter_row == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?php echo $filter_row == 100 ? 'selected' : '' ?>>100</option>
                                <option value="200" <?php echo $filter_row == 200 ? 'selected' : '' ?>>200</option>
                                <option value="300" <?php echo $filter_row == 300 ? 'selected' : '' ?>>300</option>
                            </select>
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="pagination" style="margin-bottom: 0;"></ul>
                        </nav>

                    </div>
                </div>
            </div>
            <!-- END PAGING -->

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<form id="frmExp" action="order/export" method="post" style="display: none;">
    <input type="hidden" id="hdData" name="hdData" value="<?= htmlspecialchars(json_encode($list_order)); ?>" />
    <input type="hidden" name="fromdate" value="<?= $filter_fdate; ?>" />
    <input type="hidden" name="todate" value="<?= $filter_tdate; ?>" />
</form>

<script>
    // sau 5 phut sẽ load lại trang
    setTimeout(() => {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('filter_page', 1);
        window.location.href = currentUrl.href;
    }, 1000 * 60 * 5);

    $(function() {
        var table = $("#example1").DataTable({
            "lengthChange": true,
            "aLengthMenu": [
                [50, 100, 150, 300, 500, -1],
                [50, 100, 150, 300, 500, "All"]
            ],
            "responsive": true,
            "autoWidth": false,
            "searching": false,
            "paging": false,
            "ordering": true,
            "info": false, // Tắt thông tin
            "order": []
        })
        $('#example1_paginate').parent().prepend($('#example1_length'));
        $('#example1_paginate').parent().css({
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'space-between'
        })

        $('#pagination').twbsPagination({
            totalPages: <?php echo $total_page ?>,
            startPage: <?php echo $filter_page ?>, // Trang bắt đầu
            visiblePages: 5,
            first: '<i class="fas fa-angle-double-left"></i>', // Icon First
            prev: '<i class="fas fa-angle-left"></i>', // Icon Previous
            next: '<i class="fas fa-angle-right"></i>', // Icon Next
            last: '<i class="fas fa-angle-double-right"></i>', // Icon Last
            initiateStartPageClick: false, // Không tự động nhảy đến trang đầu khi khởi tạo
            onPageClick: function(event, page) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('filter_page', page);
                window.location.href = currentUrl.href;
            }
        });

        $('.checkox_order').on('change', function() {
            let total = $('.checkox_order').length;
            let total_checked = $('.checkox_order:checked').length;

            if (total_checked == total) {
                $('#checkbox_all_order').prop('checked', true);
            } else {
                $('#checkbox_all_order').prop('checked', false);
            }
            if (total_checked == 0) {
                $('#cancle_order').hide();
            } else {
                $('#cancle_order').show();
            }
        })

        $('#checkbox_all_order').on('change', function() {
            if ($(this).is(':checked')) {
                $('.checkox_order').prop('checked', true);
                $('#cancle_order').show();
            } else {
                $('.checkox_order').prop('checked', false);
                $('#cancle_order').hide();
            }

        })
    });

    function reload_page_by_row(row) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('filter_row', row);
        currentUrl.searchParams.set('filter_page', 1);
        window.location.href = currentUrl.href;
    }

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

    function ajax_cancle_order(btn) {

        let arr_order_selected = [];
        $('.checkox_order:checked').each(function() {
            arr_order_selected.push($(this).data('order'));
        })

        if (arr_order_selected.length) {
            if (confirm("Bạn có chắc chắn muốn xóa những đơn hàng đã chọn!") == true) {

                next_ajax_cancle_order(arr_order_selected, 1, btn);
            }

        } else {
            toasts_danger('Hãy chọn đơn hàng cần xóa');
        }
    }

    function next_ajax_cancle_order(arr_order_selected, i, btn) {
        $.ajax({
            type: "POST",
            url: `<?= site_url('order/ajax_change_status_order') ?>/${arr_order_selected[i-1]}/<?= ORDER_CANCLE ?>`,
            beforeSend: function() {
                $(btn).html(`<i class="fas fa-sync fa-spin"></i> Xóa đơn hàng đã chọn - ${i}/${arr_order_selected.length}`).prop("disabled", true);
            },
            success: function(res) {
                try {
                    let resData = JSON.parse(res);
                    if (resData.status) {


                    } else {
                        toasts_danger(resData.error);
                    }
                } catch (error) {
                    console.log(error)
                    toasts_danger();
                } finally {

                    if (i == arr_order_selected.length) {
                        $(btn).html('Xóa đơn hàng đã chọn').prop("disabled", false);
                        toasts_success('Xóa thành công');

                        setTimeout(() => {
                            window.location.reload()
                        }, 2000);
                    } else {
                        next_ajax_cancle_order(arr_order_selected, ++i, btn);
                    }
                }
            }
        });
    }

    function export_file() {
        const info = $('#hdData').val();

        console.log(info)
        if (info != "") {
            $('#frmExp').submit();
        } else {
            toasts_danger('Chưa load xong dữ liệu để export');
        }
    }
</script>