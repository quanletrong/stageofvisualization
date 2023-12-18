<div style="position: fixed; right:0; width: 100%;bottom: 0px; max-width:1200px; display: none; z-index: 2;" id="box_lich_su">
    <div class="card card-primary mb-0">
        <div class="card-header bg-danger text-white" onclick="open_close_lich_su()" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                    <div><i class="fas fa-history"></i> LỊCH SỬ ĐƠN HÀNG</div>
                    <div>x</div>
                </h6>
            </div>
        </div>
        <div class="card-body bg-white p-1">
            <div style=" height: 80vh;">
                <div class="timeline" style="overflow-y: auto; height: 100%;">
                    <?php foreach ($logs as $id_log => $log) { ?>
                        <div>
                            <img class="direct-chat-img" src="<?= $log['avatar'] ?>" alt="message user image" style="left: 18px;position: absolute;width: 30px;height: 30px;">
                            <div class="timeline-item"=>
                                <span class="time"><i class="fas fa-clock"></i> <?= date('H:i, d/m/Y', strtotime($log['created_time'])) ?></span>
                                <h3 class="timeline-header" onclick="$(this).parent().find('.timeline-body').slideToggle()" style="cursor: pointer;">

                                    <?= timeSince($log['created_time']) ?> trước
                                    <!-- TITLE -->
                                    <a href="javascript:void(0)"><?= $log['by_uname'] ?></a>

                                    <?= LOG[$log['type']] ?>

                                    <!-- REWWORK NẾU CÓ -->
                                    <?php $i = @array_search($log['id_rework'], array_keys($order['job'][$log['id_job']]['rework'])) + 1; ?>
                                    <b><?= $log['id_rework'] > 0 ? $i : '' ?></b>

                                    <!-- IMAGE NẾU CÓ -->
                                    <?php $i = array_search($log['id_job'], array_keys($order['job'])) + 1; ?>
                                    <?= $log['id_job'] > 0 ? ' của <b>IMAGE ' . $i . ' (' . $order['job'][$log['id_job']]['type_service'] . ')</b>' : '' ?>

                                    <!-- CUSTOM PRICE USER NẾU CÓ -->
                                    <b><?= $log['id_user'] > 0 ? $log['username'] : '' ?></b>

                                    <!-- CŨ -->
                                    <?php if ($log['old'] != '') { ?>
                                        từ
                                        <?php $sub_old = substr($log['old'], 0, 50); ?>
                                        <b><?= strlen($log['old']) > strlen($sub_old) ? $sub_old . '...' : $sub_old ?></b>
                                    <?php } ?>

                                    <!-- MỚI -->
                                    <?php if ($log['new'] != '') { ?>
                                        <span style="color: red">→</span>
                                        <?php $sub_new = substr($log['new'], 0, 50); ?>
                                        <b><?= strlen($log['new']) > strlen($sub_new) ? $sub_new . '...' : $sub_new ?></b>
                                    <?php } ?>

                                </h3>
                                <div class="timeline-body" style="display: none;">
                                    <div class="d-flex align-items-center" style="gap:10px; ">
                                        <!-- DỮ LIỆU CŨ -->
                                        <?php if ($log['old'] != '') { ?>
                                            <?php if (stringIsImage($log['old'])) { ?>
                                                <img src="<?= url_image($log['old'], $FDR_ORDER) ?>" alt="" width="150" onclick="downloadURI('<?= url_image($log['old'], $FDR_ORDER) ?>', '<?= $log['old'] ?>')" style="cursor: pointer" title="Bấm vào để tải xuống">
                                            <?php } else if (stringIsFile($log['old'])) { ?>
                                                <div class="rounded border p-2 text-truncate shadow" style="width: 150px; line-break: anywhere; text-align:center; cursor: pointer;" onclick="downloadURI('<?= url_image($log['old'], $FDR_ORDER) ?>', '<?= $log['old'] ?>')" title="Bấm vào để tải xuống">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                                                    <span style="font-size:12px;"><?= $log['old'] ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <b><?= $log['old'] ?></b>
                                            <?php } ?>
                                        <?php } ?>
                                        <!-- DỮ LIỆU MỚI -->
                                        <?php if ($log['new'] != '') { ?>
                                            <div> → </div>
                                            <?php if (stringIsImage($log['new'])) { ?>
                                                <img src="<?= url_image($log['new'], $FDR_ORDER) ?>" alt="" width="150" onclick="downloadURI('<?= url_image($log['new'], $FDR_ORDER) ?>', '<?= $log['new'] ?>')" style="cursor: pointer" title="Bấm vào để tải xuống">
                                            <?php } else if (stringIsFile($log['new'])) { ?>
                                                <div class="rounded border p-2 text-truncate shadow" style="width: 150px; line-break: anywhere; text-align:center; cursor: pointer;" onclick="downloadURI('<?= url_image($log['new'], $FDR_ORDER) ?>', '<?= $log['new'] ?>')" title="Bấm vào để tải xuống">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                                                    <span style="font-size:12px;"><?= $log['new'] ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <b><?= $log['new'] ?></b>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function open_close_lich_su() {
        $('#box_lich_su').slideToggle('fast', 'swing');
        // $('#small_lich_su').toggleClass('d-none');
    }
</script>