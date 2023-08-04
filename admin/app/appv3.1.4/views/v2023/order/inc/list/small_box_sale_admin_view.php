<div class="row">
    <style>
        .small-box {
            color: white;
        }
    </style>

    <div class="col-lg-2 col-6">
        <div class="small-box" style="background-color: blueviolet;">
            <div class="inner">
                <h3>0</h3>
                <p>URGENT</p>
            </div>
            <div class="icon">
                <i class="ion ion-flash"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box" style="background-color: deeppink;">
            <div class="inner">
                <h3><?=isset($box['pending']) ? $box['pending'] : 0 ?></h3>
                <p>TOTAL PENDING</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-6">

        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?=isset($box['late']) ? $box['late'] : 0 ?></h3>
                <p>TOTAL OVERDUE</p>
            </div>
            <div class="icon">
                <i class="ion ion-alert-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?=isset($box['rework']) ? $box['rework'] : 0 ?></h3>
                <p>TOTAL REWORK</p>
            </div>
            <div class="icon">
                <i class="ion ion-refresh"></i>

            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-6">

        <div class="small-box bg-success">
            <div class="inner">
                <h3><?=isset($box['complete']) ? $box['complete'] : 0 ?></h3>
                <p>TOTAL COMPLETE</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3><?=isset($box['progress']) ? $box['progress'] : 0 ?></h3>
                <p>TOTAL PROGRESSING</p>
            </div>
            <div class="icon">
                <i class="ion ion-settings"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

</div>