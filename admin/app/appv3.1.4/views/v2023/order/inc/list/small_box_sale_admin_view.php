<div class="row">
    <style>
        .small-box {
            color: white;
        }
    </style>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL URGENT</span>
                <span class="info-box-number">0</span>
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
                <span class="info-box-text">TOTAL OVERDUE</span>
                <span class="info-box-number"><?= isset($box['late']) ? $box['late'] : 0 ?></span>
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
                <span class="info-box-text">TOTAL PROGRESSING</span>
                <span class="info-box-number"><?= isset($box['progress']) ? $box['progress'] : 0 ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-text">TOTAL COMPLETE</span>
                <span class="info-box-number"><?= isset($box['complete']) ? $box['complete'] : 0 ?></span>
            </div>
        </div>
    </div>
</div>