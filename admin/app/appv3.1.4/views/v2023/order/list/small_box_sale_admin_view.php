<style>
    .small-box {
        color: white;
    }
</style>

<div class="row">
    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL IMAGE AVAILABLE</span>
                <span class="info-box-number"><?= isset($box['image_avaiable']) ? $box['image_avaiable'] : 0 ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL QC CHECK</span>
                <span class="info-box-number"><?= isset($box['qc_check']) ? $box['qc_check'] : 0 ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL PENDING</span>
                <span class="info-box-number"><?= isset($box['pending']) ? $box['pending'] : 0 ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL DONE</span>
                <span class="info-box-number"><?= isset($box['done']) ? $box['done'] : 0 ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL REWORK</span>
                <span class="info-box-number"><?= isset($box['rework']) ? $box['rework'] : 0 ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL</span>
                <span class="info-box-number"><?= isset($box['all']) ? $box['all'] : 0 ?></span>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-withdraw-balance">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title"><i class="fas fa-wallet"></i> WITHDRAW BALANCE</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex">
                <div class="w-50 service">
                    <i class="fas fa-sync fa-spin"></i>
                </div>

                <div class="w-50 in_out">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ajax_set_rut_tien(this)">WITHDRAW</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-start">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title"><i class="far fa-play-circle"></i> FIND NEW ORDER</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <i class="fas fa-sync fa-spin"></i>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" onclick="ajax_find_order()">Try again!</button>
                <button class="btn btn-primary btn-link-join" href="" style="display: none;" onclick="ajax_ed_join_order()">JOIN</button>
            </div>
        </div>
    </div>
</div>

<script>
    function ajax_find_order() {
        $('#modal-start .modal-body').html(' <i class="fas fa-2x fa-sync fa-spin"></i>');

        setTimeout(() => {
            $.ajax({
                url: 'order/ajax_find_order',
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        $('#modal-start .modal-body').html('Đã tìm thấy đơn hàng mới.');
                        $('#modal-start .btn-link-join').attr('onclick', `ajax_ed_join_order(this, ${kq.msg})`)
                        $('#modal-start .btn-link-join').show();
                    } else {
                        $('#modal-start .modal-body').html('Không tìm thấy đơn hàng vào lúc này vui lòng bấm thử lại!');
                        $('#modal-start .btn-link-join').hide();

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        }, 2000);

    }

    function ajax_ed_join_order(btn, id_order) {
        let old_text = $(btn).html();

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);
        $.ajax({
            url: `order/ajax_ed_join_order/${id_order}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    toasts_success();
                    window.location.href = `order/detail/${id_order}`;
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

    function ajax_get_rut_tien() {
        $('#modal-withdraw-balance .modal-body .in_out').html('<i class="fas fa-sync fa-spin"></i>');
        $('#modal-withdraw-balance .modal-body .service').html('');

        $.ajax({
            url: `withdraw/ajax_get_rut_tien`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);
                if (kq.status) {
                    let in_out_html = ``;
                    let service_html = ``;
                    Object.entries(kq.data).forEach((entry) => {
                        const [key, value] = entry;

                        if (key == 'CHECK_IN' || key == 'CHECK_OUT') {
                            in_out_html += `
                            <div class="d-flex mb-3" style="font-weight: bold;">
                                <div class="w-50">${key}</div>
                                <div>${value}</div>
                            </div>`
                        } else {
                            service_html += `
                            <div class="d-flex mb-3" style="font-weight: bold;">
                                <div class="w-50">${key}</div>
                                <div>${value}</div>
                            </div>`
                        }

                    });

                    $('#modal-withdraw-balance .modal-body .in_out').html(in_out_html)
                    $('#modal-withdraw-balance .modal-body .service').html(service_html)

                } else {
                    toasts_danger(kq.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ajax_set_rut_tien(btn) {
        let old_text = $(btn).html();

        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `withdraw/ajax_set_rut_tien`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);
                if (kq.status) {
                    toasts_success();
                    $('#modal-withdraw-balance .modal-body').html('');

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
</script>