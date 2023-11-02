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
                                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_main_file" data-target="#main_file_<?= $id_job ?>">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            <?php } ?>

                                            <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($job['image'], $FDR_ORDER) ?>', '<?= $job['image'] ?>')">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>

                                        <img src="<?= url_image($job['image'], $FDR_ORDER) ?>" class="img-order-all" alt="" width="100%" data-id="<?= $id_job ?>" id="main_file_<?= $id_job ?>">
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex align justify-content-between align-items-center">
                                            <b>Attach Reference Files</b>
                                            <div>
                                                <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                                    <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_add_attach_file" title="Thêm file đính kèm" data-job="<?= $id_job ?>">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                <?php } ?>

                                                <button class="btn btn-sm btn-warning" onclick="" title="Tải tất cả file đính kèm">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div style="display: flex; flex-wrap: wrap;" class="list_attach mt-2">
                                            <?php $list_attach = json_decode($job['attach'], true); ?>
                                            <?php foreach ($list_attach as $key => $item) { ?>
                                                <div class="position-relative image-hover w-25 p-1">
                                                    <div class="position-absolute d-none" style="right: 10px">
                                                        <i class="fas fa-times icon-delete-image"></i>
                                                    </div>

                                                    <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:5px">
                                                        <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($item, $FDR_ORDER) ?>', '<?= $item ?>')" style="font-size: 10px; padding: 3px 5px;">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                                            <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_attach_file" data-target="#attach_file_<?= $key ?>" style="font-size: 10px; padding: 3px 5px;">
                                                                <i class="fas fa-upload"></i>
                                                            </button>

                                                            <button class="btn btn-sm btn-warning" onclick="ajax_delete_attach_file(this, <?= $id_job ?>, '<?= $key ?>')" style="font-size: 10px; padding: 3px 5px;">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php } ?>
                                                    </div>

                                                    <img src="<?= url_image($item, $FDR_ORDER) ?>" alt="" data-id-job="<?= $id_job ?>" data-id-attach="<?= $key ?>" id="attach_file_<?= $key ?>" style="width: -webkit-fill-available; width: -moz-available;">
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
                                            <button class="btn btn-sm btn-warning mt-2" onclick="ajax_update_requirement(this, <?= $id_job ?>)" style="width: 150px;">Save Requirements</button>
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
                                    <div id="list_complete_<?= $id_job ?>" data-id-job="<?= $id_job ?>" class="d-flex flex-wrap" style="gap: 10px;">
                                        <?php foreach ($job['file_complete'] as $key => $file) { ?>
                                            <div class="position-relative image-hover" style="width: 48%;" id="file_complete_<?= $key ?>">

                                                <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                                    <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete" data-target="#img_complete_<?= $key ?>">
                                                        <i class="fas fa-upload"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($file, $FDR_ORDER) ?>', '<?= $file ?>')">
                                                        <i class="fas fa-download"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete(this, <?= $id_job ?>,<?= $key ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <img id="img_complete_<?= $key ?>" data-id-job="<?= $id_job ?>" data-id-complete="<?= $key ?>" src="<?= url_image($file, $FDR_ORDER) ?>" alt="" width="100%">
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <button class="btn btn-sm btn-warning w-100 mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete" data-target="#list_complete_<?= $id_job ?>"> <i class="fas fa-upload"></i> Upload file complete</button>
                                </div>
                            </div>

                            <!-- CARD REWORK LIST-->
                            <?php
                            $data['id_order']  = $order['id_order'];
                            $data['id_job']    = $id_job;
                            $data['job']       = $job;
                            $data['FDR_ORDER'] = $FDR_ORDER;
                            $this->load->view(TEMPLATE_FOLDER . 'order/detail/_job_content_rework_view.php', $data); ?>
                            <!-- CARD REWORK LIST-->
                        </div>
                    </div>
                </div>
                <?php $active = '' ?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    function cb_upload_edit_main_file(url_image, target, file_name) {

        let id_job = $(target).data('id');

        $(target).attr('src', url_image);

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

    function cb_upload_edit_attach_file(url_image, target, file_name) {

        let id_job = $(target).data('id-job');
        let id_attach = $(target).data('id-attach');

        $(target).attr('src', url_image);

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

    function cb_upload_add_attach_file(url_image, target, file_name, btn_upload) {

        let btn_upload_old = $(btn_upload).html();
        $(btn_upload).html(`<i class="fas fa-sync fa-spin"></i>`);

        let id_job = $(btn_upload).data('job');

        $(target).attr('src', url_image);

        $.ajax({
            url: `order/ajax_add_attach_file`,
            type: "POST",
            data: {
                id_job,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);
                let new_id_attach = kq.data;
                if (kq.status) {
                    let html = `<div class="position-relative image-hover w-25 p-1">
                        <div class="position-absolute d-none" style="right: 10px">
                            <i class="fas fa-times icon-delete-image"></i>
                        </div>

                        <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:5px">
                            <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')" style="font-size: 10px; padding: 3px 5px;">
                                <i class="fas fa-download"></i>
                            </button>
                            <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_attach_file" data-target="#attach_file_${new_id_attach}" style="font-size: 10px; padding: 3px 5px;">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_attach_file(this, ${id_job}, '${new_id_attach}')" style="font-size: 10px; padding: 3px 5px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php } ?>
                        </div>

                        <img src="${url_image}" alt="" width="100" data-id-job="${id_job}" data-id-attach="${new_id_attach}" id="attach_file_${new_id_attach}" style="width: -webkit-fill-available; width: -moz-available;">
                    </div>`;

                    $(`#tab_content_job_${id_job} .list_attach`).append(html);

                } else {
                    toasts_danger(kq.error);
                }

                $(btn_upload).html(btn_upload_old);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ajax_delete_attach_file(btn, id_job, id_attach) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_delete_attach_file`,
            type: "POST",
            data: {
                id_job,
                id_attach
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(`#attach_file_${id_attach}`).parent().remove();
                $(btn).prop("disabled", false);
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
                $(btn).html('Save Requirements');
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

    function cb_upload_add_file_complete(url_image, target, file_name) {

        let id_job = $(target).data('id-job');
        $.ajax({
            url: `order/ajax_add_file_complete`,
            type: "POST",
            data: {
                id_job,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let id_file_complete = kq.data;
                    let html = `
                        <div class="position-relative image-hover" style="width: 48%;" id="file_complete_${id_file_complete}">
                            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete" data-target="#img_complete_${id_file_complete}">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete(this, ${id_job}, ${id_file_complete})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <img id="img_complete_${id_file_complete}" data-id-job="${id_job}" data-id-complete="${id_file_complete}" src="${url_image}" alt="" width="100%">
                        </div>`;
                    $(target).append(html)
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

    function cb_upload_edit_file_complete(url_image, target, file_name) {

        let id_job = $(target).data('id-job');
        let id_complete = $(target).data('id-complete');
        console.log(id_job, id_complete)
        $.ajax({
            url: `order/ajax_edit_file_complete`,
            type: "POST",
            data: {
                id_job,
                id_complete,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    $(target).attr('src', url_image)
                    $(target)
                        .siblings('.position-btn')
                        .find('.fa-download')
                        .parent()
                        .attr('onclick', `downloadURI('${url_image}', '${file_name}')`)

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

    function ajax_delete_file_complete(btn, id_job, id_complete) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_delete_file_complete`,
            type: "POST",
            data: {
                id_job,
                id_complete
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(`#file_complete_${id_complete}`).remove();
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }
</script>