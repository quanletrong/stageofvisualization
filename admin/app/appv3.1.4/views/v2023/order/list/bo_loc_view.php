<style>
    #filter_form .select2 {
        width: 100% !important;
    }
</style>
<form method="GET" action="bds" id="filter_form">
    <div class="rounded px-2 pt-2 pb-0 mb-2" style="background-color: #dee2e6;">
        <div class="row">
            <!-- TÌM ORDER CODE -->
            <div class="col-md-6 mb-2">
                <input type="text" class="form-control" placeholder="Nhập ID Order" value="<?= $filter_order_code ?>" name="filter_order_code" autocomplete="off">
            </div>
            <!-- END TÌM ORDER CODE -->

            <!-- STATUS -->
            <div class="col-md-6 mb-2">
                <select class="select2" name="filter_status[]" multiple="multiple">
                    <option value="" <?= $filter_status == [] ? 'selected' : '' ?>>-Tất cả trạng thái-</option>
                    <?php foreach ($all_status as $id => $it) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_status) ? 'selected' : '' ?>>
                            <?= $it['text'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <!-- END STATUS -->

            <!-- TÌM USER CODE -->
            <div class="col-md-6 mb-2">
                <input type="text" class="form-control" placeholder="Nhập ID User" value="<?= $filter_user_code ?>" name="filter_user_code" autocomplete="off">
            </div>
            <!-- END TÌM USER CODE -->

            <!-- SERVICES -->
            <div class="col-md-6 mb-2">
                <select class="select2" name="filter_services[]" multiple="multiple">
                    <option value="" <?= $filter_services == [] ? 'selected' : '' ?>>-Tất cả dịch vụ-</option>
                    <?php foreach ($all_service as $id => $it) { ?>
                        <option value="<?= $id ?>" <?= in_array($id, $filter_services) ? 'selected' : '' ?>>
                            <?= $it['type_service'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <!-- END STATUS -->

            <!-- TÌM ED NỘI BỘ hoặc ED CTV -->
            <div class="col-md-2 mb-2">
                <select class="select2" name="filter_order_ed_type" data-minimum-results-for-search="Infinity">
                    <option value="" <?= $filter_order_ed_type == '' ? 'selected' : '' ?>>ED nội bộ và CTV</option>
                    <option value="<?= ED_NOI_BO ?>" <?= $filter_order_ed_type == ED_NOI_BO ? 'selected' : '' ?>>ED nội bộ</option>
                    <option value="<?= ED_CTV ?>" <?= $filter_order_ed_type == ED_CTV ? 'selected' : '' ?>>ED cộng tác viên</option>
                </select>
            </div>

            <!-- search -->
            <div class="col-md-2 mb-2 d-flex" style="gap:5px">
                <button type="submit" class="btn btn-primary w-75" title="Tìm kiếm"><i class="fas fa-search"></i> Tìm kiếm</button>
                <a href="/admin" class="btn btn-danger w-25" title="Làm mới bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {

        $('#filter_form .select2').select2({
            closeOnSelect: false
        });

    })
</script>