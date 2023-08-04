<style>
    .small-box {
        color: white;
    }
</style>

<div class="d-flex mb-3" style="justify-content: space-between;">
    <button class="btn btn-success"><i class="far fa-play-circle"></i> START</button>
    <button class="btn btn-success" data-toggle="modal" data-target="#modal-withdraw-balance"> <i class="fas fa-wallet"></i> WITHDRAW BALANCE</button>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>65</h3>
                <p>TOTAL EDIT IN PROGRESS</p>
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
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>EDIT OVERDUE</p>
            </div>
            <div class="icon">
                <i class="ion ion-alert-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>65</h3>
                <p>TOTAL REWORK IN PROGRESS</p>
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
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>REWORK OVERDUE</p>
            </div>
            <div class="icon">
                <i class="ion ion-alert-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-withdraw-balance">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title"><i class="fas fa-wallet"></i> WITHDRAW BALANCE</h4>
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