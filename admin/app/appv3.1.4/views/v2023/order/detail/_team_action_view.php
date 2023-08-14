<div class="card card-primary">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div>TEAM ACTION</div>
            </h3>
        </div>
    </div>
    <div class="card-body">
        <div>
            <b>Countdown time</b>
            <div id="cdt_<?= $order['id_order'] ?>" style=" border: 1px solid #ddd; padding: 3px 10px; border-radius: 4px; text-align: center; background: #eee; font-weight: bold;">
                <?= count_down_time_order($order) ?>
            </div>
        </div>

        <!-- TODO: TẠM ẨN -->
        <div class="mt-3 d-none">
            <b>Custom time (thêm thời gian cho đơn)</b>
            <p>
                <input type="text" class="form-control" style="text-align: center; font-weight: bold;" value="00:02:57:00">
            </p>
        </div>

        <!-- STATUS -->
        <div class="mt-3">
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

            $list_status = button_status_order_by_role($role);
            ?>

            <div class="dropdown">
                <button class="btn dropdown-toggle w-100" type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white; background-color: <?= @$s['bg'] ?>">
                    <?= @$s['text'] ?>
                </button>
                <div class="dropdown-menu w-100 p-1" aria-labelledby="dropdownStatus">
                    <?php foreach ($list_status as $key => $status) { ?>
                        <button class="dropdown-item w-100 mb-1 text-center" href="#" style="color:white; background-color: <?= @$status['bg'] ?>;" onclick="ajax_change_status_order(this, '<?= $order['id_order'] ?>', '<?= $key ?>')"><?= $status['text'] ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <hr>
        <!-- END STATUS -->

        <div class="mt-3">
            <p><b style="color: orange;">ID Customer: CID<?= $order['id_user'] ?></b></p>
            <p><b style="color: orange;">ID Order: OID<?= $order['id_order'] ?></b></p>
        </div>

        <div class="mt-3">
            <?php foreach ($list_type_service as $type => $val) { ?>
                <p><b style="color: orange;"><?= $type ?>: <?= count($val) ?></b></p>
            <?php } ?>
            <p><b style="color: orange;">TOTAL: [<?= $total_type_service ?>]</b></p>
        </div>
        <hr>

        <!-- ASSIGN -->
        <div class="mt-3">
            <b>Assign</b>
            <select class="select2" id="tag" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                <?php foreach ($all_qc_ed as $id_user => $user) { ?>
                    <?php $selected = isset($order['assign_user'][$id_user]) ? 'selected' : '' ?>
                    <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mt-3">
            <b>WORKING EDITOR</b>
            <div class="mt-1">
                <?php $i = 1; ?>
                <?php foreach ($list_job as $id_job => $job) { ?>
                    <div class="d-flex mt-1" style="align-items: center;">
                        <div style="color: red; width:150px">IMAGE <?= $i++ ?> (<?= $job['type_service'] ?>)</div>
                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select Editor" style="width: 100%">
                            <?php foreach ($all_qc_ed as $id_user => $user) { ?>
                                <?php $selected = $job['id_ed'] == $id_user ? 'selected' : '' ?>
                                <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="mt-3">
            <b>WORKING QC</b>
            <div class="mt-1">
                <div class="mt-1">
                    <?php $i = 1; ?>
                    <?php foreach ($list_job as $id_job => $job) { ?>
                        <div class="d-flex mt-1" style="align-items: center;">
                            <div style="color: red; width:150px">IMAGE <?= $i++ ?> (<?= $job['type_service'] ?>)</div>
                            <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select Editor" style="width: 100%">
                                <?php foreach ($all_qc_ed as $id_user => $user) { ?>
                                    <?php if ($user['role'] == QC) { ?>
                                        <?php $selected = $job['id_qc'] == $id_user ? 'selected' : '' ?>
                                        <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <b>GIÁ CUSTOM (cộng thêm tiền cho đơn)</b>
            <div class="mt-1">
                <?php $disable = in_array($role, [QC, EDITOR]) ? 'disabled' : ''; ?>
                <div class="d-flex mt-1" style="align-items: center;">
                    <div style="color: red; font-weight: bold; width: 150px;">Tổng custom</div>
                    <div class="input-group">
                        <input id="textCustomOrder" type="number" min="0" class="form-control" value="<?= $order['custom'] ?>" <?= $disable ?> style="color: red; font-weight: bold;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnCustomOrder" <?= $disable ?> onclick="ajax_change_custom_order(this, '<?= $order['id_order'] ?>', $('#textCustomOrder').val())" style="width: 60px;">Save</button>
                        </div>
                    </div>
                </div>

                <!-- TODO: tạm để AD SL không có quyên set -->
                <?php foreach ($order['assign_user'] as $id_user) { ?>
                    <div class="d-flex mt-1">
                        <div style="color: red; width: 150px;"><?= $id_user ?></div>
                        <input class="form-control" value="">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })

    function ajax_change_status_order(btn, id_order, new_status) {
        let new_text = $(btn).text();
        let new_bg = $(btn).css('backgroundColor');
        let old_text = $('#dropdownStatus').html();
        if (confirm(`Bạn muốn đơn này chuyển sang ${ new_text}?`) == true) {

            $('#dropdownStatus').html(' <i class="fas fa-sync fa-spin"></i>');
            $('#dropdownStatus').prop("disabled", true);
            $.ajax({
                url: `order/ajax_change_status_order/${id_order}/${new_status}`,
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {

                        toasts_success();
                        $('#dropdownStatus').html(new_text);
                        $('#dropdownStatus').css('backgroundColor', new_bg);
                    } else {
                        $('#dropdownStatus').html(old_text);
                        toasts_danger();
                    }

                    $('#dropdownStatus').prop("disabled", false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        }
    }

    function ajax_change_custom_order(btn, id_order, custom) {
        let old_text = $(btn).html();

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `order/ajax_change_custom_order/${id_order}/${custom}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    // thành công 
                    toasts_success()
                } else {
                    toasts_danger();
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
</script>