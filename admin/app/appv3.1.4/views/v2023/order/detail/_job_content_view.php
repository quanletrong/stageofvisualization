<style>
    img {
        border-radius: 5px;
        box-shadow: 3px 2px 7px 0px #888888;
    }

    .card-header {
        padding: 0.3rem 0.8rem;
    }

    #list-image-order .card-body {
        padding: 0.5rem 0.8rem;
    }

    .btn-upfile {
        display: flex;
        justify-items: center;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5px;
        border-style: dotted;
        cursor: pointer;
        max-width: 200px;
        height: fit-content;
    }

    .btn-upfile div {
        text-align: center;
        font-size: 0.8rem;
    }

    .btn-upfile:hover {
        background-color: aliceblue;
    }

    .image-hover:hover .btn-upfile {
        display: flex !important;
        background-color: aliceblue;
    }

    .position-relative:hover i {
        cursor: pointer;
        color: red;
    }
</style>

<div class="card card-tabs card-primary">
    <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="tab_job" role="tablist">
            <?php $active = 'active' ?>
            <?php $index = 1 ?>
            <?php foreach ($order['job'] as $id_job => $job) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active; ?>" id="tab_job_<?= $id_job ?>" data-toggle="pill" href="#tab_content_job_<?= $id_job ?>" role="tab" aria-controls="tab_content_job_<?= $id_job ?>" aria-selected="false">IMAGE <?= $index++ ?> (<?= $job['type_service'] ?>)</a>
                </li>
                <?php $active = '' ?>
            <?php } ?>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="tab_content_job">
            <?php $active = 'active show' ?>
            <?php foreach ($order['job'] as $id_job => $job) { ?>
                <div class="tab-pane fade <?= $active; ?>" id="tab_content_job_<?= $id_job ?>" role="tabpanel" aria-labelledby="tab_job_<?= $id_job ?>">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="card card-primary shadow ">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                            <div>ORIGINAL FILE(S)</div>
                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <button class="btn btn-danger btn-sm">SAVE</button> -->
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="position-relative image-hover">
                                        <!-- TODO: TẠM ẨN -->
                                        <!-- <div class="btn-upfile rounded border shadow" style="position: absolute; display: none; left: 45%; top: 45%;">
                                                                <i class="fas fa-upload"></i>
                                                                <div>Upload file other</div>
                                                            </div> -->
                                        <img src="<?= url_image($job['image'], "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" class="img-order-all" alt="" width="100%">
                                    </div>
                                    <div class="mt-3">
                                        <b>Attach Reference Files</b>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <?php $list_attach = json_decode($job['attach'], true); ?>
                                            <?php foreach ($list_attach as $key => $item) { ?>
                                                <div class="position-relative">
                                                    <div class="position-absolute" style="right: 10px">
                                                        <i class="fas fa-times icon-delete-image"></i>
                                                    </div>
                                                    <img src="<?= url_image($item, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100">
                                                </div>
                                            <?php } ?>

                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>attach reference</div>
                                                                </div> -->
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="d-flex">
                                            <div style="min-width: 130px; font-weight: bold;">Room Type</div>
                                            <div><?= $job['room'] ?></div>
                                        </div>
                                        <div class="d-flex">
                                            <div style="min-width: 130px; font-weight: bold;">Services</div>
                                            <div><?= $job['service'] ?> (<?= $job['type_service'] ?>)</div>
                                        </div>
                                        <div class="d-flex">
                                            <div style="min-width: 130px; font-weight: bold;">Design Style</div>
                                            <div><?= $job['style'] ?></div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <b>Requirements:</b>
                                        <textarea class="form-control" rows="5"><?= $job['requirement'] ?></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <!-- CARD COMPLETED FILE-->
                            <div class="card card-primary shadow">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                            <div>COMPLETED FILE(S)</div>
                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <button class="btn btn-danger btn-sm">SAVE</button> -->
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <!-- <b>File</b> -->
                                        <div class="d-flex flex-wrap" style="gap: 10px;">

                                            <div class="position-relative" style="width: 48%;">
                                                <div class="position-absolute" style="right: 10px">
                                                    <i class="fas fa-times icon-delete-image"></i>
                                                </div>
                                                <img src="https://picsum.photos/320/180" alt="" width="100%">
                                            </div>

                                            <div class="position-relative" style="width: 48%;">
                                                <div class="position-absolute" style="right: 10px">
                                                    <i class="fas fa-times icon-delete-image"></i>
                                                </div>
                                                <img src="https://picsum.photos/320/180" alt="" width="100%">
                                            </div>
                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file complete</div>
                                                                </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD REWORK LIST-->
                            <div class="card card-primary shadow">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                            <div>REWORK 1</div>
                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <button class="btn btn-danger btn-sm">SAVE</button> -->
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <b>Requirements</b>
                                        <textarea class="form-control " rows="5">Please use greyish wide plank hardwood flooring in rooms, dark gray tiles in bathrooms and light carpet in bedrooms.Use Scandinavian furniture design and white kitchen with dark gray countertops.</textarea>
                                    </div>
                                    <div class="mt-2">
                                        <b>Attach Reference Files</b>
                                        <div class="d-flex flex-wrap" style="gap:7px">
                                            <div class="position-relative">
                                                <div class="position-absolute" style="right: 10px">
                                                    <i class="fas fa-times icon-delete-image"></i>
                                                </div>
                                                <img src="https://picsum.photos/320/180" alt="" width="100">
                                            </div>

                                            <div class="position-relative">
                                                <div class="position-absolute" style="right: 10px">
                                                    <i class="fas fa-times icon-delete-image"></i>
                                                </div>
                                                <img src="https://picsum.photos/320/180" alt="" width="100">
                                            </div>

                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>attach reference</div>
                                                                </div> -->
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <b>File rework complete</b>
                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                            <div class="position-relative" style="width: 48%;">
                                                <div class="position-absolute" style="right: 10px">
                                                    <i class="fas fa-times icon-delete-image"></i>
                                                </div>
                                                <img src="https://picsum.photos/320/180" alt="" width="100%">
                                            </div>
                                            <!-- TODO: TẠM ẨN -->
                                            <!-- <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>rework complete</div>
                                                                </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END CARD REWORK LIST-->
                        </div>
                    </div>
                </div>
                <?php $active = '' ?>
            <?php } ?>
        </div>
    </div>
</div>