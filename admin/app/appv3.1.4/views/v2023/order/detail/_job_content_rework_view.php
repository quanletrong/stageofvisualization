<!-- LIST REWORK ĐÃ TẠO -->
<?php foreach ($job['rework'] as $id_rework => $rework) { ?>
    <div class="card card-primary shadow">
        <!-- collapsed-card -->
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                    <div>REWORK</div>
                </h3>
                <div class="card-tools m-0 d-flex">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
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
                <b>Requirements</b>
                <textarea class="form-control " rows="5"><?= $rework['note'] ?></textarea>
            </div>
            <div class="mt-2">
                <div class="mt-2 d-flex flex-wrap" style="gap:7px">
                    <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete" data-target="#list_complete_13"> <i class="fas fa-paperclip"></i> Upload attach file</button>
                </div>
                <div class="mt-2 d-flex flex-wrap" style="gap:7px">
                    <?php foreach ($rework['attach'] as $id_attach => $attach) { ?>
                        <div class="position-relative image-hover" style="width: 30%;" id="file_complete_1693196254">

                            <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete" data-target="#img_complete_1693196254">
                                    <i class="fas fa-upload"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('http://stageofvisualization.local/uploads/images/2023/07/SSDHRNKQ8k-QmVnS-300x250.png', 'SSDHRNKQ8k-QmVnS-300x250.png')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete(this, 13,1693196254)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <img id="img_complete_1693196254" data-id-job="13" data-id-complete="1693196254" src="http://stageofvisualization.local/uploads/images/2023/07/w09YDMR0Ij-XUlB6-660X300.jpg" alt="" width="100%" style="aspect-ratio: 4/3; object-fit: cover;">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="mt-2 text-center">
                <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete" data-target="#list_complete_<?= $id_job ?>">Save
                    rework</button>
            </div>
        </div>
    </div>
<?php } ?>

<div class="card card-danger shadow d-none" id="card_new_rework_<?= $id_job ?>">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div><i class="fas fa-plus"></i> NEW REWORK</div>
            </h3>
            <!-- <div class="card-tools m-0 d-flex">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>

                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div> -->
        </div>

    </div>
    <div class="card-body">
        <div>
            <b>Requirements</b>
            <textarea class="txt_note_rework_add form-control" rows="5"></textarea>
        </div>
        <div class="mt-2 d-flex flex-wrap" style="gap:7px">
            <button class="btn btn-warning btn-sm mt-2" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete" data-target="#list_complete_13"> <i class="fas fa-paperclip"></i> Upload attach file</button>
        </div>
        <div class="attach_files d-flex" style="gap:10px">
            <div style="position:relative" class="mt-2">
                <img src="http://stageofvisualization.local/uploads/tmp/lFpTM-300x250-1.jpg" style="width:50px;aspect-ratio: 1; object-fit: cover;">
                <i class="fas fa-times" style="position:absolute;right: 5px;top: 5px; cursor: pointer;" onclick="remove_attach(this)"></i>
            </div>

            <div style="position:relative" class="mt-2">
                <img src="http://stageofvisualization.local/uploads/tmp/lFpTM-300x250-1.jpg" style="width:50px;aspect-ratio: 1; object-fit: cover;">
                <i class="fas fa-times" style="position:absolute;right: 5px;top: 5px; cursor: pointer;" onclick="remove_attach(this)"></i>
            </div>
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

    function remove_attach(e) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(e).parent().remove();
        }
    }

    function ajax_add_rework(btn, id_job) {
        let note = $(`#card_new_rework_${id_job} .txt_note_rework_add`).val();
        let attach = [];
        $(`#card_new_rework_${id_job} .attach_files div`).each(function(index) {
            let src = $(this).find('img').attr('src');
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
</script>