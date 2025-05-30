<style>
    .select2-selection__choice {
        font-size: 0.75rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding: 0 4px;
        margin-top: 0.55rem;
    }

    /* ẩn nút search */
    /* .select2-search {
        display: none;
    } */
</style>

<form method="GET" action="order" id="filter_form">
    <div class="rounded px-2 py-1 pb-0 mb-2" style="background-color: #dee2e6;">
        <div class="row">
            <!-- STATUS -->
            <div class="col-md-5 mb-2">
                <small>Status - Trạng thái đơn hàng</small> <br>
                <select class="select2" name="filter_status[]" multiple="multiple">
                    <?php foreach ($all_status as $id => $it) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_status) ? 'selected' : '' ?>><?= trim($it['text']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- END STATUS -->

            <!-- TÌM ORDER CODE -->
            <div class="col-md-3 mb-2">
                <small>JID - Code đơn hàng</small><br>
                <input type="text" class="form-control" placeholder="Nhập Code đơn hàng" value="<?= htmlentities($filter_code_order) ?>" name="filter_code_order" autocomplete="off">
            </div>
            <!-- END TÌM ORDER CODE -->

            <!-- search -->
            <div class="col-md-4 mb-2 ">
                <small>&nbsp;</small>
                <div class="d-flex justify-content-between" style="gap:5px; align-items: center;">
                    <div>
                        <button type="submit" class="btn btn-primary" title="Tìm kiếm"><i class="fas fa-search"></i> Lọc đơn</button>
                        <a href="order" class="btn" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
                        <button type="button" class="btn" title="Thêm option tìm kiếm" onclick="$('#filter-expand').slideToggle()">
                            <i class="fas fa-sliders-h"></i>
                        </button>
                    </div>

                    <div>
                        <?php if ($role == EDITOR) { ?>
                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal-start" onclick="ajax_find_order()">
                                <i class="fas fa-play"></i> START (<?= $box['image_avaiable'] ?>)
                            </button>
                        <?php } ?>

                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal-withdraw-balance" onclick="ajax_get_rut_tien()">
                            <i class="fas fa-wallet"></i> RÚT TIỀN
                        </button>

                        <button id="btn_export" type="button" class="btn btn-success " onclick="export_file(this)" title="Xuất báo cáo">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="filter-expand" style="display: none;">

            <!-- LỌC THEO NGÀY -->
            <div class="col-md-4 mb-2">
                <small>Date - Ngày tạo đơn hàng</small><br>
                <div class="input-group">
                    <input type="text" class="form-control daterange-btn" placeholder="Nhập khoảng ngày" id="create_time" value="<?= $filter_time ?>" autocomplete="off">
                    <input type="hidden" name="filter_fdate" value="<?= $filter_fdate ?>">
                    <input type="hidden" name="filter_tdate" value="<?= $filter_tdate ?>">
                    <div class="input-group-append daterange-btn" id="">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <!-- END LỌC THEO NGÀY -->

            <!-- TÌM USER CODE -->
            <div class="col-md-4 mb-2">
                <small>Cid - Code khách hàng</small><br>                
                <select class="select2" name="filter_user_code[]" multiple="multiple">
                    <?php foreach ($all_user_code as $id => $it) { ?>
                        <?php if ($it['code_user'] != '') { ?>
                            <option value="<?= $it['code_user'] ?>" <?= in_array($it['code_user'], $filter_user_code) ? 'selected' : '' ?>><?= $it['code_user'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <!-- END TÌM USER CODE -->

            <!-- SERVICES -->
            <div class="col-md-4 mb-2">
                <small>Job type - Loại dịch vụ </small><br>
                <select class="select2" name="filter_service[]" multiple="multiple">
                    <?php foreach ($all_service as $id => $it) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_service) ? 'selected' : '' ?>><?= $it['type_service'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- END STATUS -->

            <!-- TÌM ED NỘI BỘ hoặc ED CTV -->
            <?php if ($role != EDITOR) { ?>
                <div class="col-md-4 mb-2">
                    <small>Phân đơn - Đơn giành cho loại ED</small><br>
                    <select class="select2" name="filter_order_ed_type[]" multiple="multiple" data-minimum-results-for-search="Infinity">
                        <?php foreach ($all_ed_type as $id => $text) { ?>
                            <option value="<?= $id ?>" <?= in_array($id, $filter_order_ed_type) ? 'selected' : '' ?>><?= $text ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

            <!-- ĐƠN KHÁCH TẠO/ĐƠN NỘI BỘ/ĐƠN TẠO HỘ -->
            <div class="col-md-4 mb-2">
                <small>Loại đơn hàng</small><br>
                <select class="select2" name="filter_order_type[]" multiple="multiple" data-minimum-results-for-search="Infinity">
                    <?php foreach ($all_order_type as $id => $text) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_order_type) ? 'selected' : '' ?>><?= $text ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Lọc theo user -->
            <?php if ($role != EDITOR) { ?>
                <div class="col-md-4 mb-2">
                    <small>Team working - Lọc theo tài khoản</small><br>
                    <select class="select2" name="filter_id_user[]" multiple="multiple" id="filter_id_user">
                        <?php foreach ($all_user as $id => $it) { ?>
                            <option value="<?= $id ?>" <?= in_array($id, $filter_id_user) ? 'selected' : '' ?>><?= $it['username'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

            <!-- Lọc theo đơn custom -->
            <?php if ($role != EDITOR) { ?>
                <div class="col-md-4 mb-2">
                    <small>Đơn custom</small><br>
                    <select class="select2" name="filter_custom" id="filter_custom" data-minimum-results-for-search="Infinity">
                        <option value=">=" <?= $filter_custom == '>=' ? 'selected' : '' ?>>Tất cả</option>
                        <option value=">" <?= $filter_custom == '>' ? 'selected' : '' ?>>Có custom</option>
                        <option value="=" <?= $filter_custom == '=' ? 'selected' : '' ?>>Không custom</option>
                    </select>
                </div>
            <?php } ?>

            <div class="col-md-4 mb-2">
                <small>&nbsp;</small><br>
                <button type="submit" class="btn btn-primary" title="Tìm kiếm"><i class="fas fa-search"></i> Lọc đơn</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {

        //select 2
        $('#filter_form .select2').select2({
            closeOnSelect: false,
            allowClear: true,
            placeholder: 'select..'
        });


        // tim theo ngày tạo don
        $('.daterange-btn').daterangepicker({
            autoUpdateInput: false,
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày trước': [moment().subtract(6, 'days'), moment()],
                '30 ngày trước': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $('.daterange-btn').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            $('input[name="filter_fdate"]').val(picker.startDate.format('YYYY-MM-D'));
            $('input[name="filter_tdate"]').val(picker.endDate.format('YYYY-MM-D'));
        });

        $('.daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
            $('input[name="filter_fdate"]').val('');
            $('input[name="filter_tdate"]').val('');
        });
    })
</script>