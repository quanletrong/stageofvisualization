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
<div class="my-3">
    <h4>Bộ lọc <small onclick="$('#filter_form').slideToggle()" style="cursor: pointer;"> [Ẩn/hiện]</small></h4>
</div>
<form method="GET" action="order" id="filter_form">
    <div class="rounded px-2 pt-2 pb-0 mb-2" style="background-color: #dee2e6;">
        <div class="row">

            <!-- STATUS -->
            <div class="col-md-4 mb-2">
                <small>Status - Trạng thái đơn hàng</small>
                <select class="select2" name="filter_status[]" multiple="multiple">
                    <?php foreach ($all_status as $id => $it) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_status) ? 'selected' : '' ?>><?= trim($it['text']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- END STATUS -->
            <!-- TÌM ORDER CODE -->
            <div class="col-md-4 mb-2">
                <small>JID - Code đơn hàng</small>
                <input type="text" class="form-control" placeholder="Nhập Code đơn hàng" value="<?= htmlentities($filter_code_order) ?>" name="filter_code_order" autocomplete="off">
            </div>
            <!-- END TÌM ORDER CODE -->

            <!-- TÌM USER CODE -->
            <div class="col-md-4 mb-2">
                <small>Cid - Code khách hàng</small>
                <input type="text" class="form-control" placeholder="Nhập Code khách hàng" value="<?= htmlentities($filter_user_code) ?>" name="filter_user_code" autocomplete="off">
            </div>
            <!-- END TÌM USER CODE -->

            <!-- SERVICES -->
            <div class="col-md-4 mb-2">
                <small>Job type - Loại dịch vụ </small>
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
                    <small>Phân đơn - Đơn giành cho loại ED</small>
                    <select class="select2" name="filter_order_ed_type[]" multiple="multiple" data-minimum-results-for-search="Infinity">
                        <?php foreach ($all_ed_type as $id => $text) { ?>
                            <option value="<?= $id ?>" <?= in_array($id, $filter_order_ed_type) ? 'selected' : '' ?>><?= $text ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

            <!-- ĐƠN KHÁCH TẠO/ĐƠN NỘI BỘ/ĐƠN TẠO HỘ -->
            <div class="col-md-4 mb-2">
                <small>Loại đơn hàng</small>
                <select class="select2" name="filter_order_type[]" multiple="multiple" data-minimum-results-for-search="Infinity">
                    <?php foreach ($all_order_type as $id => $text) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_order_type) ? 'selected' : '' ?>><?= $text ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- ngày tạo -->
            <div class="col-md-4 mb-2">
                <small>Date - Ngày tạo đơn hàng</small>
                <div class="input-group">
                    <input type="text" class="form-control daterange-btn" placeholder="Nhập khoảng ngày" id="create_time" value="">
                    <input type="hidden" name="filter_fdate" value="<?= $filter_fdate ?>">
                    <input type="hidden" name="filter_tdate" value="<?= $filter_tdate ?>">
                    <div class="input-group-append daterange-btn" id="">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

            <!-- Lọc theo user -->
            <?php if ($role != EDITOR) { ?>
                <div class="col-md-4 mb-2">
                    <small>Team working - Lọc theo tài khoản</small>
                    <select class="select2" name="filter_id_user[]" multiple="multiple" id="filter_id_user">
                        <?php foreach ($all_user as $id => $it) { ?>
                            <option value="<?= $id ?>" <?= in_array($id, $filter_id_user) ? 'selected' : '' ?>><?= $it['username'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>


            <!-- search -->
            <div class="col-md-4 mb-2 ">
                <small>&nbsp;</small>
                <div class="d-flex" style="gap:5px">
                    <button type="submit" class="btn btn-primary w-75" title="Tìm kiếm"><i class="fas fa-search"></i> Tìm kiếm</button>
                    <a href="order" class="btn btn-danger w-25" title="Làm mới bộ lọc"><i class="fas fa-sync-alt"></i></a>
                </div>

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


        // ngày tạo
        //Set mặc định ngày
        let startDate = moment().subtract(29, 'days');
        let endDate = moment();
        try {
            <?php if ($filter_fdate != '') { ?>
                startDate = moment('<?= $filter_fdate ?>');
            <?php } ?>

            <?php if ($filter_tdate != '') { ?>
                endDate = moment('<?= $filter_tdate ?>');
            <?php } ?>
        } catch (error) {
            console.log(error);
        }

        //Date range as a button
        $('.daterange-btn').daterangepicker({
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(6, 'days'), moment()],
                    '30 ngày trước': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                },
                startDate: startDate,
                endDate: endDate,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            },
            function(start, end) {
                $('input[name="filter_fdate"]').val(start.format('YYYY-MM-D'))
                $('input[name="filter_tdate"]').val(end.format('YYYY-MM-D'))
            }
        )

    })
</script>