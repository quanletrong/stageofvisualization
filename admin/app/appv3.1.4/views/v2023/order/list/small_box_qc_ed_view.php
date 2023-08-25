<style>
    .small-box {
        color: white;
    }
</style>

<div class="d-flex mb-3" style="justify-content: space-between;">
    <?php if ($role == EDITOR) { ?>
        <button class="btn btn-success" data-toggle="modal" data-target="#modal-start" onclick="ajax_find_order()"> <i class="fas fa-wallet"></i> START</button>
    <?php } ?>
    <button class="btn btn-success" data-toggle="modal" data-target="#modal-withdraw-balance"> <i class="fas fa-wallet"></i> WITHDRAW BALANCE</button>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL IMAGE AVAIABLE</span>
                <span class="info-box-number"><?= isset($box['image_avaiable']) ? $box['image_avaiable'] : 0 ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL PROGRESSING</span>
                <span class="info-box-number"><?= isset($box['progress']) ? $box['progress'] : 0 ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL OVERDUE</span>
                <span class="info-box-number"><?= isset($box['late']) ? $box['late'] : 0 ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL REWORK</span>
                <span class="info-box-number"><?= isset($box['rework']) ? $box['rework'] : 0 ?></span>
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
            <div class="modal-body">
                <div class="d-flex mb-3" style="font-weight: bold;">
                    <div class="w-50">VS</div>
                    <div>5</div>
                </div>
                <div class="d-flex mb-3" style="font-weight: bold;">
                    <div class="w-50">VR</div>
                    <div>5</div>
                </div>
                <div class="d-flex mb-3" style="font-weight: bold;">
                    <div class="w-50">3D</div>
                    <div>5</div>
                </div>
                <div class="d-flex mb-3" style="font-weight: bold;">
                    <div class="w-50">CUSTOM</div>
                    <div>5</div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">WITHDRAW</button>
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
</script>