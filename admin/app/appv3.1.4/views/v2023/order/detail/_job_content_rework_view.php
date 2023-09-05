<!-- LIST REWORK ĐÃ TẠO -->
<?php $i = 1 ?>
<?php foreach ($job['rework'] as $id_rework => $rework) { ?>
    <div class="card card-primary shadow collapsed-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                    <div>REWORK <?= $i++ ?></div>
                </h3>
                <div class="card-tools m-0 d-flex">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div>
                <div id="list_complete_rework_<?= $id_rework ?>" data-id-rework="<?= $id_rework ?>" class="d-flex flex-wrap" style="gap: 10px;">
                    <?php foreach ($rework['file_complete'] as $key => $file) { ?>
                        <div class="position-relative image-hover" style="width: 48%;" id="file_complete_rework_<?= $key ?>">

                            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete_rework" data-target="#img_complete_rework_<?= $key ?>">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($file, 'uploads/images/' . $job['year'] . '/' . $job['month'] . '/') ?>', '<?= $file ?>')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete_rework(this, <?= $id_rework ?>,<?= $key ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <img id="img_complete_rework_<?= $key ?>" data-id-rework="<?= $id_rework ?>" data-id-complete-rework="<?= $key ?>" src="<?= url_image($file, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
                        </div>
                    <?php } ?>
                </div>
                <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete_rework" data-target="#list_complete_rework_<?= $id_rework ?>"> <i class="fas fa-upload"></i> Upload file rework</button>
            </div>
            <div class="mt-2">
                <b>Attach Reference Files</b>
                <div id="list_attach_rework_<?= $id_rework ?>" data-id-rework="<?= $id_rework ?>" class="mt-2 d-flex flex-wrap" style="gap:7px">
                    <?php foreach ($rework['attach'] as $id_attach => $url_attach) { ?>
                        <div class="position-relative image-hover" style="width: 30%;" id="file_attach_<?= $id_attach ?>">

                            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_attach_rework" data-target="#img_attach_<?= $id_attach ?>">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= $url_attach ?>', '<?= $url_attach ?>')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_attach_rework(this, <?= $id_rework ?>, <?= $id_attach ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <img id="img_attach_<?= $id_attach ?>" data-id-rework="<?= $id_rework ?>" data-id-attach="<?= $id_attach ?>" src="<?= url_image($url_attach, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
                        </div>
                    <?php } ?>
                </div>

                <div class="mt-2 d-flex flex-wrap" style="gap:7px">
                    <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_rework" data-target="#list_attach_rework_<?= $id_rework ?>"> <i class="fas fa-paperclip"></i> Upload attach file</button>
                </div>
            </div>

            <div class="mt-2">
                <b>Requirements</b>
                <textarea class="form-control" id="requirement_rework_<?= $id_rework ?>" rows="5"><?= $rework['note'] ?></textarea>
            </div>
            <button class="btn btn-sm btn-warning mt-2" onclick="ajax_update_requirement_rework(this, <?= $id_rework ?>)" style="width: 100px;">Save</button>
        </div>
    </div>
<?php } ?>

<div class="card card-danger shadow d-none" id="card_new_rework_<?= $id_job ?>">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div><i class="fas fa-plus"></i> NEW REWORK</div>
            </h3>
        </div>

    </div>
    <div class="card-body">
        <div class="mt-2">
            <b>Attach Reference Files</b>
            <div id="list_attach_rework_<?= $id_job ?>" class="mt-1 d-flex flex-wrap" style="gap:7px"></div>
            <div class="mt-1 d-flex flex-wrap" style="gap:7px">
                <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_new_rework" data-target="#list_attach_rework_<?= $id_job ?>"> <i class="fas fa-paperclip"></i> Upload attach file</button>
            </div>
        </div>
        <div class="mt-2">
            <b>Requirements</b>
            <textarea class="txt_note_rework_add form-control" rows="5"></textarea>
        </div>
        <div class="mt-2 text-center">
            <button class="btn btn-warning btn-sm mt-2" onclick="ajax_add_rework(this, '<?= $id_job ?>')" style="width: 100px;">save</button>
            <button class="btn btn-info btn-sm mt-2" style="width: 100px;" onclick="$('#card_new_rework_<?= $id_job ?>').addClass('d-none');">close</button>
        </div>
    </div>
</div>

<!-- END CARD REWORK LIST-->
<div class="mt-2">
    <button class="btn btn-warning w-100 mt-2" onclick="$('#card_new_rework_<?= $id_job ?>').removeClass('d-none');"> <i class="fas fa-plus"></i> New Rework</button>
</div>

<script>
    $(document).ready(function() {

    })

    function ajax_add_rework(btn, id_job) {
        let note = $(`#card_new_rework_${id_job} .txt_note_rework_add`).val();
        let attach = [];
        $(`#card_new_rework_${id_job} .img_attach`).each(function(index) {
            let src = $(this).attr('src');
            attach.push(src);
        });

        if (note == '') {
            alert('Hãy nhập mô tả');
            $(`#card_new_rework_${id_job} .txt_note_rework_add`).focus();
            return false;
        }

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_add_rework/${id_job}`,
            type: "POST",
            data: {
                note,
                attach
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                $(btn).prop("disabled", false).html('save');
                if (kq.status) {
                    toasts_success();
                    location.reload();
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

    function cb_upload_add_file_attach_new_rework(url_image, target, file_name) {
        let id_attach = Date.now();

        let html = `
        <div class="position-relative image-hover" style="width: 30%;" id="file_attach_${id_attach}">
            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                <button class="btn btn-sm btn-warning" onclick="remove_attach('#file_attach_${id_attach}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <img id="img_attach_${id_attach}" src="${url_image}" class="img_attach" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
        </div>`;
        $(target).append(html)
    }

    // ===========
    function ajax_update_requirement_rework(btn, id_rework) {

        let requirement = $('#requirement_rework_' + id_rework).val();

        if (requirement.length == 0) {
            alert('Dữ liệu không được bỏ trống');
            return;
        }

        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_update_requirement_rework`,
            type: "POST",
            data: {
                id_rework,
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

    // COMPLETE REWORK
    function cb_upload_add_file_complete_rework(url_image, target, file_name) {

        let id_rework = $(target).data('id-rework');
        $.ajax({
            url: `order/ajax_add_file_complete_rework`,
            type: "POST",
            data: {
                id_rework,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let id_file_complete = kq.data;
                    let html = `
                <div class="position-relative image-hover" style="width: 48%;" id="file_complete_rework_${id_file_complete}">
                    <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                        <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete_rework" data-target="#img_complete_rework_${id_file_complete}">
                            <i class="fas fa-upload"></i>
                        </button>

                        <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')">
                            <i class="fas fa-download"></i>
                        </button>

                        <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete_rework(this, ${id_rework}, ${id_file_complete})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <img id="img_complete_rework_${id_file_complete}" data-id-rework="${id_rework}" data-id-complete-rework="${id_file_complete}" src="${url_image}" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
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

    function cb_upload_edit_file_complete_rework(url_image, target, file_name) {

        let id_rework = $(target).data('id-rework');
        let id_complete_rework = $(target).data('id-complete-rework');
        $.ajax({
            url: `order/ajax_edit_file_complete_rework`,
            type: "POST",
            data: {
                id_rework,
                id_complete_rework,
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

    function ajax_delete_file_complete_rework(btn, id_rework, id_complete) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_delete_file_complete_rework`,
            type: "POST",
            data: {
                id_rework,
                id_complete
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(`#file_complete_rework_${id_complete}`).remove();
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    // END COMPLETE REWORK

    // ATTACH REWORK

    function cb_upload_add_file_attach_rework(url_image, target, file_name) {

        let id_rework = $(target).data('id-rework');
        $.ajax({
            url: `order/ajax_add_file_attach_rework`,
            type: "POST",
            data: {
                id_rework,
                url_image
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let id_attach = kq.data;
                    let html = `
                        <div class="position-relative image-hover" style="width: 30%;" id="file_attach_${id_attach}">
                            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_attach_rework" data-target="#img_attach_${id_attach}">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_attach_rework(this, ${id_rework}, ${id_attach})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <img id="img_attach_${id_attach}" data-id-rework="${id_rework}" data-id-attach="${id_attach}" src="${url_image}" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
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

    function cb_upload_edit_file_attach_rework(url_image, target, file_name) {

        let id_attach = $(target).data('id-attach');
        let id_rework = $(target).data('id-rework');
        $.ajax({
            url: `order/ajax_edit_file_attach_rework`,
            type: "POST",
            data: {
                id_attach,
                id_rework,
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

    function ajax_delete_file_complete_rework(btn, id_rework, id_complete) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_delete_file_complete_rework`,
            type: "POST",
            data: {
                id_rework,
                id_complete
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(`#file_complete_rework_${id_complete}`).remove();
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ajax_delete_file_attach_rework(btn, id_rework, id_attach) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `order/ajax_delete_file_attach_rework`,
            type: "POST",
            data: {
                id_rework,
                id_attach
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                } else {
                    toasts_danger(kq.error);
                }
                $(`#file_attach_${id_attach}`).remove();
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function remove_attach(e) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(e).remove();
        }
    }
    // END ATTACH REWORK
</script>