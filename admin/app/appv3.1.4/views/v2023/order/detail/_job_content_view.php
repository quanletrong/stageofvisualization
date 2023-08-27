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

    .btn-upfile,
    .btn-download {
        padding: 5px;
        border-style: dotted;
        cursor: pointer;
        max-width: 200px;
        height: fit-content;
        background: aliceblue;
    }

    .btn-upfile:hover,
    .btn-download:hover {
        background-color: white;
    }

    .image-hover:hover .position-btn {
        display: flex !important;
        justify-content: center;
        align-items: center;
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
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="position-relative image-hover">

                                        <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                            <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                                <div class="btn-upfile rounded border shadow" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_main_file" data-target="#main_file_<?= $id_job ?>">
                                                    <i class="fas fa-upload"></i>
                                                </div>
                                            <?php } ?>

                                            <div class="btn-download rounded border shadow" onclick="downloadURI('<?= url_image($job['image'], "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>', '<?= $job['image'] ?>')">
                                                <i class="fas fa-download"></i>
                                            </div>
                                        </div>



                                        <img src="<?= url_image($job['image'], "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" class="img-order-all" alt="" width="100%" data-id="<?= $id_job ?>" id="main_file_<?= $id_job ?>">
                                    </div>
                                    <div class="mt-3">
                                        <b>Attach Reference Files</b>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <?php $list_attach = json_decode($job['attach'], true); ?>
                                            <?php foreach ($list_attach as $key => $item) { ?>
                                                <div class="position-relative image-hover">
                                                    <div class="position-absolute d-none" style="right: 10px">
                                                        <i class="fas fa-times icon-delete-image"></i>
                                                    </div>

                                                    <div class="position-btn" style="position: absolute; display: none; top: 20%; width:100%; gap:10px">
                                                        <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                                            <div class="btn-upfile rounded border shadow" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_attach_file" data-target="#attach_file_<?= $key ?>">
                                                                <i class="fas fa-upload"></i>
                                                            </div>
                                                        <?php } ?>

                                                        <div class="btn-download rounded border shadow" onclick="downloadURI('<?= url_image($item, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>', '<?= $item ?>')">
                                                            <i class="fas fa-download"></i>
                                                        </div>
                                                    </div>

                                                    <img src="<?= url_image($item, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100" data-id-job="<?= $id_job ?>" data-id-attach="<?= $key ?>" id="attach_file_<?= $key ?>" style="aspect-ratio: 4/3; object-fit: cover;">
                                                </div>
                                            <?php } ?>
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
                                        <textarea class="form-control requirement_job" rows="5" id="requirement_job_<?= $id_job ?>"><?= $job['requirement'] ?></textarea>
                                        <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                            <button class="btn btn-sm btn-danger mt-2" onclick="ajax_update_requirement(this, <?= $id_job ?>)" style="width: 100px;">Save</button>
                                        <?php } ?>
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
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- <?php //if($job['file_complete'] != '' && $job['file_complete'] != null ){?> -->
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
                                        </div>
                                    </div>
                                    <!-- <?php //} ?> -->
                                    <button class="btn btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_file_complete" data-target="#file_comptele_<?= $id_job ?>">Upload file complete</button>
                                </div>
                            </div>

                            <!-- CARD REWORK LIST-->
                            <!-- TODO: TẠM ẨN -->
                            <div class="card card-primary shadow d-none">
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

<script>
    function cb_upload_edit_main_file(link, target, file_name) {

        let id_job = $(target).data('id');

        $(target).attr('src', link);
        ajax_edit_main_file(id_job, link)
    }

    function ajax_edit_main_file(id_job, url_image) {

        $.ajax({
            url: `order/ajax_edit_main_file`,
            type: "POST",
            data: {
                id_job,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

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

    function cb_upload_edit_attach_file(link, target, file_name) {

        let id_job = $(target).data('id-job');
        let id_attach = $(target).data('id-attach');

        $(target).attr('src', link);
        ajax_edit_attach_file(id_job, id_attach, link)
    }

    function ajax_edit_attach_file(id_job, id_attach, url_image) {

        $.ajax({
            url: `order/ajax_edit_attach_file`,
            type: "POST",
            data: {
                id_job,
                id_attach,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

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

    function ajax_update_requirement(btn, id_job) {

        let requirement = $('#requirement_job_' + id_job).val();

        if (requirement.length == 0) {
            alert('Dữ liệu không được bỏ trống');
            return;
        }

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_update_requirement`,
            type: "POST",
            data: {
                id_job,
                requirement
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(btn).html('Save');
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
</script>