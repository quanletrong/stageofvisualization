<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    #box_custom_time * {
        border-radius: 0;
    }
</style>
<div class="card card-primary">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div>TEAM ACTION</div>
            </h3>
        </div>
    </div>
    <div class="card-body">
        <?php if (in_array($role, [ADMIN, SALE])) { ?>
            <div class="mb-3">
                <b>Realtime</b>
                <small onclick="alert('real time = (time tạo đơn + 24h) - time hiện tại')">[Mô tả]</small>
                <div id="cdt_real_time" style=" border: 1px solid #ddd; padding: 3px 10px; border-radius: 4px; text-align: center; background: #eee; font-weight: bold;" title="<?= $order['create_time'] ?>">
                    <script>
                        // real time = (time tạo đơn + 24h) - time hiện tại
                        <?php $time_chot_don = strtotime($order['create_time']) + 86400; ?>
                        count_down_time('<?= date('Y-m-d H:i:s', $time_chot_don) ?>', 'cdt_real_time')
                    </script>
                </div>
            </div>
        <?php } ?>
        <div>
            <b>Countdown time</b>
            <small onclick="alert('countdown time = (time tạo + time custom ) - time hiện tại')">[Mô tả]</small>
            <div id="cdt_<?= $order['id_order'] ?>" style=" border: 1px solid #ddd; padding: 3px 10px; border-radius: 4px; text-align: center; background: #eee; font-weight: bold;">
                <!-- countdown time = (time tạo + time custom ) - time hiện tại -->
                <?= count_down_time_order($order) ?>
            </div>
        </div>

        <div class="mt-3">
            <b>Time custom</b>
            <small onclick="alert('Thời gian phải hoàn thành tính từ khi tạo đơn')">[Mô tả]</small>
            <p>
            <div class="d-flex" id="box_custom_time">
                <?php $disable = in_array($role, [ADMIN, SALE]) ? '' : 'disabled'; ?>
                <div class="d-flex">
                    <div style="position: relative; width: 33%;">
                        <input type="number" min=0 class="form-control" id="dCustom" value="" <?= $disable ?>>
                        <span style="position: absolute; top:6px; right:6px">Ngày</span>
                    </div>
                    <div style="position: relative; width: 33%;">
                        <input type="number" min=0 class="form-control" id="hCustom" value="" <?= $disable ?>>
                        <span style="position: absolute; top:6px; right:6px">Giờ</span>
                    </div>
                    <div style="position: relative; width: 33%;">
                        <input type="number" min=0 class="form-control" id="mCustom" value="" <?= $disable ?>>
                        <span style="position: absolute; top:6px; right:6px">Phút</span>
                    </div>
                    <div style="position: relative; display: none;">
                        <input type="number" min=0 class="form-control" id="sCustom" value="" <?= $disable ?>>
                        <span style="position: absolute; top:6px; right:6px">Giây</span>
                    </div>
                </div>

                <button class="btn btn-warning" type="button" <?= $disable ?> onclick="ajax_change_custom_time(this, <?= $order['id_order'] ?>)" style="width: 50px;"><i class="fas fa-save"></i></button>
            </div>
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
            $disable     = allow_show_button_status_order_by_role($role, $order['status']) ? '' : 'disabled';
            ?>

            <div class="dropdown">
                <button class="btn dropdown-toggle w-100" type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white; background-color: <?= @$s['bg'] ?>" <?= $disable ?>>
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
            <?php foreach ($order['list_type_service'] as $type => $val) { ?>
                <p><b style="color: orange;"><?= $type ?>: <?= count($val) ?></b></p>
            <?php } ?>
            <p><b style="color: orange;">TOTAL: [<?= $order['total_type_service'] ?>]</b></p>

            <div class="d-flex mt-1" style="align-items: center;">
                <div style="color: red; width:150px">#ID Customer</div>
                <div class="input-group">
                    <?php $disable = in_array($role, [ADMIN, SALE]) ? '' : 'disabled'; ?>
                    <input id="textCodeUser" class="form-control" value="<?= htmlentities($order['code_user']) ?>" <?= $disable ?> style="color: red; font-weight: bold;">
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="button" <?= $disable ?> onclick="ajax_change_code_user(this, <?= $order['id_user'] ?>)" style="width: 50px;"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>

            <div class="d-flex mt-1" style="align-items: center;">
                <div style="color: red; width:150px">#ID Order</div>
                <div class="input-group">
                    <?php $disable = in_array($role, [ADMIN, SALE]) ? '' : 'disabled'; ?>
                    <input id="textCodeOrder" class="form-control" value="<?= htmlentities($order['code_order']) ?>" <?= $disable ?> style="color: red; font-weight: bold;">
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="button" <?= $disable ?> onclick="ajax_change_code_order(this, <?= $order['id_order'] ?>)" style="width: 50px;"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <!-- WORKING -->
        <div class="mt-3">
            <div class="d-flex mt-1" style="align-items: center;">
                <div style="width:150px"> <b>WORKING QC</b></div>
                <div class="d-flex w-100" style="gap: 20px;">
                    <div class="w-50">IN</div>
                    <div class="w-50">OUT</div>
                </div>
            </div>
            <!-- <small onclick="alert('Chức năng gán QC không giành cho Editor')">[Mô tả]</small> -->
            <div class="mt-1">
                <div class="mt-1">

                    <?php $i = 1; ?>
                    <?php $disabled_order = in_array($order['status'], [ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE]) ? 'disabled' : '' ?>
                    <?php $disabled_role = in_array($role, [ADMIN, SALE, QC]) ? '' : 'disabled' ?>
                    <?php foreach ($order['job'] as $id_job => $job) { ?>
                        <div class="d-flex mt-1" style="align-items: center;">
                            <div style="color: red; width:150px">IMAGE <?= $i++ ?> (<?= $job['type_service'] ?>)</div>
                            <div class="d-flex w-100" style="gap: 20px;">
                                <!-- QC IN -->
                                <div class="d-flex w-50">
                                    <select class="assignWorkingQC" multiple="multiple" data-working="<?= WORKING_QC_IN ?>" data-job="<?= $id_job ?>" style="width: 100%" <?= $disabled_order ?> <?= $disabled_role ?>>
                                        <?php foreach ($all_user_working as $id_user => $user) { ?>
                                            <?php if ($user['role'] != EDITOR) { ?>
                                                <?php $selected = isset($job['working_qc_in_active'][$id_user]) ? 'selected' : '' ?>
                                                <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if ($disabled_order != 'disabled' && $disabled_role != 'disabled') { ?>
                                        <?php if (isset($job['working_qc_out_active'][$curr_uid])) { ?>
                                            <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_remove_job_user('<?= WORKING_QC_IN ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')"><i class="fas fa-times"></i></button>
                                        <?php } else { ?>
                                            <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_assign_job_user('<?= WORKING_QC_IN ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')" <?= empty($job['working_qc_in_active']) ? '' : 'disabled' ?>><i class="fas fa-plus"></i></button>
                                        <?php } ?>
                                    <?php } ?>
                                </div>

                                <!-- QC OUT -->
                                <div class="d-flex w-50">
                                    <select class="assignWorkingQC" multiple="multiple" data-working="<?= WORKING_QC_OUT ?>" data-job="<?= $id_job ?>" style="width: 100%" <?= $disabled_order ?> <?= $disabled_role ?>>
                                        <?php foreach ($all_user_working as $id_user => $user) { ?>
                                            <?php if ($user['role'] != EDITOR) { ?>
                                                <?php $selected = isset($job['working_qc_out_active'][$id_user]) ? 'selected' : '' ?>
                                                <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if ($disabled_order != 'disabled' && $disabled_role != 'disabled') { ?>
                                        <?php if (isset($job['working_qc_out_active'][$curr_uid])) { ?>
                                            <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_remove_job_user('<?= WORKING_QC_OUT ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')"><i class="fas fa-times"></i></button>
                                        <?php } else { ?>
                                            <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_assign_job_user('<?= WORKING_QC_OUT ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')" <?= empty($job['working_qc_out_active']) ? '' : 'disabled' ?>><i class="fas fa-plus"></i></button>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <b>WORKING EDITOR</b>
            <small onclick="alert('')">[Mô tả]</small>
            <div class="mt-1">
                <?php $i = 1; ?>
                <?php $disabled_order = in_array($order['status'], [ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE]) ? 'disabled' : '' ?>
                <?php $disabled_role = in_array($role, [ADMIN, SALE, QC]) ? '' : 'disabled' ?>
                <?php foreach ($order['job'] as $id_job => $job) { ?>
                    <div class="d-flex mt-1" style="align-items: center;">
                        <div style="color: red; width:150px">IMAGE <?= $i++ ?> (<?= $job['type_service'] ?>)</div>
                        <div class="d-flex w-100">
                            <select class="assignWorkingED" multiple="multiple" data-working="<?= WORKING_EDITOR ?>" data-job="<?= $id_job ?>" style="width: 100%" <?= $disabled_order ?> <?= $disabled_role ?>>
                                <?php foreach ($all_user_working as $id_user => $user) { ?>
                                    <?php $selected_otp = isset($job['working_ed_active'][$id_user]) ? 'selected' : '' ?>
                                    <?php
                                    $disabled_opt = '';
                                    if ($role == SALE && $user['role'] == ADMIN) {
                                        $disabled_opt = 'disabled';
                                    } else if ($role == QC && in_array($user['role'], [ADMIN, SALE])) {
                                        $disabled_opt = 'disabled';
                                    } else if ($role == EDITOR && in_array($user['role'], [ADMIN, SALE, QC])) {
                                        $disabled_opt = 'disabled';
                                    }
                                    ?>

                                    <option value="<?= $id_user ?>" <?= $selected_otp ?> <?= $disabled_opt ?>><?= $user['username'] ?></option>
                                <?php } ?>
                            </select>
                            <?php if ($disabled_order != 'disabled') { ?>
                                <?php if (isset($job['working_ed_active'][$curr_uid])) { ?>
                                    <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_remove_job_user('<?= WORKING_EDITOR ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')"><i class="fas fa-times"></i></button>
                                <?php } else { ?>
                                    <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_assign_job_user('<?= WORKING_EDITOR ?>','<?= $order['id_order'] ?>', '<?= $id_job ?>', '<?= $curr_uid ?>')" <?= empty($job['working_ed_active']) ? '' : 'disabled' ?>><i class="fas fa-plus"></i></button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="mt-3">
            <b>WORKING CUSTOM</b>
            <small onclick="alert('Chức năng gán CUSTOM user không giành cho Editor')">[Mô tả]</small>
            <div class="mt-1 d-flex">
                <?php $disabled_order = in_array($order['status'], [ORDER_DELIVERED, ORDER_COMPLETE, ORDER_CANCLE]) ? 'disabled' : '' ?>
                <?php $disabled_role = in_array($role, [ADMIN, SALE, QC]) ? '' : 'disabled' ?>
                <select class="assignWorkingCustom" multiple="multiple" data-working="<?= WORKING_CUSTOM ?>" data-job="0" style="width: 100%" <?= $disabled_order ?> <?= $disabled_role ?>>
                    <?php foreach ($all_user_working as $id_user => $user) { ?>
                        <?php
                        $selected = isset($order['working_custom_active'][$id_user]) ? 'selected' : '';
                        ?>
                        <option value="<?= $id_user ?>" <?= $selected ?>><?= $user['username'] ?></option>
                    <?php } ?>
                </select>
                <?php if ($disabled_order != 'disabled' && $disabled_role != 'disabled') { ?>
                    <?php if (isset($order['working_custom_active'][$curr_uid])) { ?>
                        <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_remove_job_user('<?= WORKING_CUSTOM ?>','<?= $order['id_order'] ?>',0, '<?= $curr_uid ?>')"><i class="fas fa-times"></i></button>
                    <?php } else { ?>
                        <button class="btn btn-warning ml-1" style="width: 50px;" onclick="ajax_assign_job_user('<?= WORKING_CUSTOM ?>','<?= $order['id_order'] ?>',0, '<?= $curr_uid ?>')"><i class="fas fa-plus"></i></button>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>
        <!-- END WORKING -->

        <div class="mt-3">
            <b>GIÁ CUSTOM (cộng thêm tiền cho đơn)</b>
            <div class="mt-1">
                <?php $disable = in_array($role, [QC, EDITOR]) ? 'disabled' : ''; ?>
                <div class="d-flex mt-1" style="align-items: center;">
                    <div style="color: red; font-weight: bold; width: 150px;">Tổng custom</div>
                    <div class="input-group">
                        <input id="textCustomOrder" type="number" min="0" class="form-control" value="<?= $order['custom'] ?>" <?= $disable ?> style="color: red; font-weight: bold;">
                        <div class="input-group-append">
                            <button class="btn btn-warning" type="button" <?= $disable ?> onclick="ajax_change_custom_order(this, '<?= $order['id_order'] ?>', $('#textCustomOrder').val())" style="width: 50px;"><i class="fas fa-save"></i></button>
                        </div>
                    </div>
                </div>

                <?php foreach ($order['working_custom_active'] as $id_user => $user) { ?>
                    <div class="d-flex mt-1">
                        <div style="color: red; width: 150px;"><?= $user['username'] ?></div>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control" value="<?= $user['custom'] ?>" id="textCustomOrder_<?= $id_user ?>" <?= $disable ?>>
                            <div class="input-group-append">
                                <button class="btn btn-warning" type="button" <?= $disable ?> onclick="ajax_change_custom_order_for_user(this, '<?= $order['id_order'] ?>', $('#textCustomOrder_<?= $id_user ?>').val(), '<?= $id_user ?>')" style="width: 50px;"><i class="fas fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="overlay dark d-none" id="team_action_overlay">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.assignWorkingED, .assignWorkingQC').select2({
            maximumSelectionLength: 1
        });
        $('.assignWorkingCustom').select2();

        $('.assignWorkingED, .assignWorkingQC, .assignWorkingCustom').on('select2:select', function(e) {
            let data = e.params.data;
            let id_user = data.id;
            let working_type = $(this).data('working');
            let id_job = $(this).data('job');
            let seft = this;
            ajax_assign_job_user(working_type, '<?= $order['id_order'] ?>', id_job, id_user)
            setTimeout(function() {
                $(seft).select2('close');
            }, 0);

        });

        $('.assignWorkingED, .assignWorkingQC, .assignWorkingCustom').on('select2:unselect', function(e) {
            let data = e.params.data;
            let id_user = data.id;
            let working_type = $(this).data('working');
            let id_job = $(this).data('job');
            let seft = this;
            ajax_remove_job_user(working_type, '<?= $order['id_order'] ?>', id_job, id_user)
            setTimeout(function() {
                $(seft).select2('close');
            }, 0);
        });

        // $(".js-programmatic-multi-set-val").on("click", function() {
        //     $exampleMulti.val(["CA", "AL"]).trigger("change");
        // });
    })

    function ajax_assign_job_user(working_type, id_order, id_job, id_user) {
        $('#team_action_overlay').toggleClass('d-none');

        $.ajax({
            url: `order/ajax_assign_job_user/${working_type}/${id_order}/${id_job}/${id_user}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success('Thêm thành công');
                    location.reload();
                } else {
                    toasts_danger(kq.error);
                }

                $('#team_action_overlay').toggleClass('d-none');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
                $('#team_action_overlay').toggleClass('d-none');
            }
        });
    }

    function ajax_remove_job_user(working_type, id_order, id_job, id_user) {
        $('#team_action_overlay').toggleClass('d-none');
        $.ajax({
            url: `order/ajax_remove_job_user/${working_type}/${id_order}/${id_job}/${id_user}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success('Xóa thành công');
                    location.reload();
                } else {
                    toasts_danger(kq.error);
                }
                $('#team_action_overlay').toggleClass('d-none');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
                $('#team_action_overlay').toggleClass('d-none');
            }
        });
    }

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
                        location.reload()
                    } else {
                        $('#dropdownStatus').html(old_text);
                        toasts_danger(`${kq.error}`);
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
                    toasts_success()
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

    function ajax_change_custom_order_for_user(btn, id_order, custom, id_user) {
        let old_text = $(btn).html();

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `order/ajax_change_custom_order_for_user/${id_order}/${custom}/${id_user}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success()
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

    function ajax_change_code_user(btn, id_user) {
        let old_text = $(btn).html();

        let code = $('#textCodeUser').val();
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `user/ajax_change_code_user`,
            type: "POST",
            data: {
                id_user,
                code
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success()
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

    function ajax_change_code_order(btn, id_order) {
        let old_text = $(btn).html();

        let code = $('#textCodeOrder').val();
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `order/ajax_change_code_order`,
            type: "POST",
            data: {
                id_order,
                code
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success()
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

    // CUSTOM TIME 
    function addCustom(date, day, hours, minus, second) {
        const dayToAdd = day * 24 * 60 * 60 * 1000;
        const hoursToAdd = hours * 60 * 60 * 1000;
        const minusToAdd = minus * 60 * 1000;
        const secondoAdd = second * 1000;
        date.setTime(date.getTime() + dayToAdd + hoursToAdd + minusToAdd + secondoAdd);
        return date;
    }
    let start = new Date('2023-08-30 01:30:00');
    console.log(start)
    let newDate1 = addCustom(start, 0, 1, 10, 30);
    console.log(newDate1); // 2022-05-15T19:00:00.000Z

    // count_down_time('$DMY_han_chot', 'cdt_$id_order')

    secondsToDhms(<?= $order['custom_time'] ?>);

    function secondsToDhms(seconds) {
        seconds = Number(seconds);
        var d = Math.floor(seconds / (3600 * 24));
        var h = Math.floor(seconds % (3600 * 24) / 3600);
        var m = Math.floor(seconds % 3600 / 60);
        var s = Math.floor(seconds % 60);

        $('#dCustom').val(d);
        $('#hCustom').val(h);
        $('#mCustom').val(m);
        $('#sCustom').val(s);
        let data = {
            d,
            h,
            m,
            s
        }
        return data;
    }

    function ajax_change_custom_time(btn, id_order) {
        let dCustom = $('#dCustom').val();
        let hCustom = $('#hCustom').val();
        let mCustom = $('#mCustom').val();
        let sCustom = $('#sCustom').val();

        let dToSecond = dCustom * 24 * 60 * 60;
        let hToSecond = hCustom * 60 * 60;
        let mToSecond = mCustom * 60;
        let sToSecond = sCustom * 1;

        let second = dToSecond + hToSecond + mToSecond + sToSecond;

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `order/ajax_change_custom_time`,
            type: "POST",
            data: {
                id_order,
                second
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success()
                    location.reload();
                } else {
                    toasts_danger(kq.error);
                }

                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }
</script>