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
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= isset($box['progress']) ? $box['progress'] : 0 ?></h3>
                <p>TOTAL PROGRESSING</p>
            </div>
            <div class="icon">
                <i class="ion ion-settings"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= isset($box['late']) ? $box['late'] : 0 ?></h3>
                <p>TOTAL OVERDUE</p>
            </div>
            <div class="icon">
                <i class="ion ion-alert-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= isset($box['rework']) ? $box['rework'] : 0 ?></h3>
                <p>TOTAL REWORK</p>
            </div>
            <div class="icon">
                <i class="ion ion-refresh"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= isset($box['complete']) ? $box['complete'] : 0 ?></h3>
                <p>TOTAL COMPLETE</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                <a class="btn btn-primary btn-link-join" href="" style="display: none;" target="_blank">JOIN</a>
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
                        $('#modal-start .btn-link-join').attr('href', 'order/detail/' + kq.msg)
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
</script>