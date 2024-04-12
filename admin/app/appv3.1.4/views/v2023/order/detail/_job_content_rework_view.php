<!-- LIST REWORK ĐÃ TẠO -->
<?php $i = 1 ?>
<?php foreach ($job['rework'] as $id_rework => $rework) { ?>
    <div class="card card-primary shadow">
        <div class="card-header" onclick="$(this).siblings('.card-body').slideToggle()" style="cursor: pointer;">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div>REWORK <?= $i ?></div>
                <small><?= $rework['create_time'] != null ? date('H:i d/m/Y', strtotime($rework['create_time'])) : ''; ?></small>
            </h3>
        </div>
        <div class="card-body" style="display: <?= $i < count($job['rework']) ? 'none' : 'block' ?>;">
            <div>
                <div class="d-flex align justify-content-between align-items-center">
                    <b>Complete file rework</b>
                    <div>
                        <button class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_complete_rework" data-target="#list_complete_rework_<?= $id_rework ?>" style="width: 80px;" title="Thêm file hoàn thành">
                            <i class="fas fa-upload"></i> Tải lên
                        </button>

                        <button class="btn btn-sm btn-warning" onclick="" title="Tải tất cả file hoàn thành" style="width: 100px;">
                            <i class="fas fa-download"></i> Tải xuống
                        </button>
                    </div>
                </div>
                <div id="list_complete_rework_<?= $id_rework ?>" data-id-rework="<?= $id_rework ?>" class="d-flex flex-wrap mt-2">
                    <?php foreach ($rework['file_complete'] as $key => $file) { ?>
                        <div class="position-relative image-hover w-50 p-1" id="file_complete_rework_<?= $key ?>">

                            <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:10px">
                                <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($file, $FDR_ORDER) ?>', '<?= $file ?>')">
                                    <i class="fas fa-download"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete_rework" data-target="#img_complete_rework_<?= $key ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete_rework(this, <?= $id_rework ?>,<?= $key ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <?php if (stringIsImage($file)) { ?>
                                <img id="img_complete_rework_<?= $key ?>" data-id-rework="<?= $id_rework ?>" data-id-complete-rework="<?= $key ?>" src="<?= url_image($file, $FDR_ORDER) ?>" alt="" width="100%">
                            <?php } else { ?>
                                <div id="img_complete_rework_<?= $key ?>" width="100%" class="rounded border p-2 text-truncate shadow" style="height: 100px; line-break: anywhere; text-align:center" data-id-rework="<?= $id_rework ?>" data-id-complete-rework="<?= $key ?>">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                                    <span style="font-size:12px;"><?= $file ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="mt-4">
                <div class="d-flex align justify-content-between align-items-center">
                    <b>Attach Reference Files</b>
                    <div>
                        <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                            <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_rework" data-target="#list_attach_rework_<?= $id_rework ?>" style="width: 80px;" title="Thêm file đính kèm">
                                <i class="fas fa-upload"></i> Tải lên
                            </button>
                        <?php } ?>

                        <a href="order/ajax_zip_attach_rework/<?= $id_rework ?>">
                            <button class="btn btn-sm btn-warning" style="width: 100px;" title="Tải tất cả file đính kèm">
                                <i class="fas fa-download"></i> Tải xuống
                            </button>
                        </a>
                    </div>
                </div>

                <div id="list_attach_rework_<?= $id_rework ?>" data-id-rework="<?= $id_rework ?>" class="mt-2 d-flex flex-wrap">
                    <?php foreach ($rework['attach'] as $id_attach => $url_attach) { ?>
                        <div class="position-relative image-hover w-25 p-1" id="file_attach_<?= $id_attach ?>">

                            <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:5px">

                                <button class="btn btn-sm btn-warning" onclick="downloadURI('<?= url_image($url_attach, $FDR_ORDER) ?>', '<?= $url_attach ?>')" style="font-size: 10px; padding: 3px 5px;">
                                    <i class="fas fa-download"></i>
                                </button>

                                <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                    <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_attach_rework" data-target="#img_attach_<?= $id_attach ?>" style="font-size: 10px; padding: 3px 5px;">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_attach_rework(this, <?= $id_rework ?>, <?= $id_attach ?>)" style="font-size: 10px; padding: 3px 5px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </div>

                            <?php if (stringIsImage($url_attach)) { ?>
                                <img id="img_attach_<?= $id_attach ?>" data-id-rework="<?= $id_rework ?>" data-id-attach="<?= $id_attach ?>" src="<?= url_image($url_attach, $FDR_ORDER) ?>" alt="" width="100%">
                            <?php } else { ?>
                                <div id="img_attach_<?= $id_attach ?>" width="100%" class="rounded border p-2 text-truncate shadow" style="height: 100px; line-break: anywhere; text-align:center" data-id-rework="<?= $id_rework ?>" data-id-attach="<?= $id_attach ?>">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                                    <span style="font-size:12px;"><?= $url_attach ?></span>
                                </div>
                            <?php } ?>

                        </div>
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="mt-4">
                <div class="d-flex align justify-content-between align-items-center">
                    <b>Requirements</b>
                    <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                        <button class="btn btn-sm btn-warning mt-2" onclick="ajax_update_requirement_rework(this, <?= $id_rework ?>)" style="width: 150px;">
                            Save Requirements</button>
                    <?php } ?>
                </div>

                <textarea class="form-control mt-2" id="requirement_rework_<?= $id_rework ?>" rows="5" cols="50" style="resize: none; overflow-y: scroll;"><?= $rework['note'] ?></textarea>
            </div>

        </div>
    </div>
    <?php $i++ ?>
<?php } ?>

<div class="card card-danger shadow" id="card_new_rework_<?= $id_job ?>" style="display: none;">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                <div><i class="fas fa-plus"></i> NEW REWORK</div>
            </h3>
        </div>

    </div>
    <div class="card-body">
        <div class="mt-3">
            <b>Attach Reference Files</b>
            <div id="list_attach_new_rework_<?= $id_job ?>" class="mt-1 d-flex flex-wrap" style="gap:7px"></div>
            <div class="mt-1 d-flex flex-wrap" style="gap:7px">
                <button class="mt-2 btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_new_rework" data-target="#list_attach_new_rework_<?= $id_job ?>"> <i class="fas fa-paperclip"></i> Upload attach file</button>
            </div>
        </div>
        <div class="mt-2">
            <b>Requirements</b>
            <textarea class="txt_note_rework_add form-control" cols="50" rows="5"></textarea>
        </div>
        <div class="mt-2 text-center">
            <button class="btn btn-warning btn-sm mt-2" onclick="ajax_add_rework(this, '<?= $id_job ?>')" style="width: 100px;">Save Rework</button>
            <button class="btn btn-sm mt-2" style="width: 100px;" onclick="$('#card_new_rework_<?= $id_job ?>').slideToggle();">Cancle</button>
        </div>
    </div>
</div>

<!-- END CARD REWORK LIST-->
<?php if (in_array($role, [ADMIN, SALE])) { ?>
    <div class="mt-2">
        <button class="btn btn-danger w-100 mt-2" onclick="$('#card_new_rework_<?= $id_job ?>').slideToggle();"> <i class="fas fa-plus"></i> New Rework</button>
    </div>
<?php } ?>

<script>
    function ajax_add_rework(btn, id_job) {
        let note = $(`#card_new_rework_${id_job} .txt_note_rework_add`).val();
        let attach = [];
        $(`#list_attach_new_rework_${id_job} > div`).each(function(index) {
            let file = $(this).data('file');
            attach.push(file);
        });

        if (note == '' && attach.length == 0) {
            $.toast({
                icon: 'warning',
                heading: 'Thiếu dữ liệu',
                text: 'Hãy nhập mô tả hoặc đính kèm file',
                hideAfter: 15000,
                position: 'top-right',
            })
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
                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Thêm REWORK'
                    })
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

    function cb_upload_add_file_attach_new_rework(link_file, target, file_name) {

        let id_attach = Date.now();

        let html = ``;
        if (isImage(link_file)) {
            html = `
            <div class="position-relative image-hover w-25 p-1" id="file_attach_${id_attach}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                    <button class="btn btn-sm btn-warning" onclick="remove_attach('#file_attach_${id_attach}')" style="font-size: 10px; padding: 3px 5px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach" alt="" width="100%">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover w-25 p-1" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 45%; width:100%; gap:10px">
                    <button class="btn btn-sm btn-warning" onclick="remove_attach('#file_attach_${id_attach}')" style="font-size: 10px; padding: 3px 5px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div 
                    id="img_attach_${id_attach}"
                    width="100%" 
                    class="rounded border p-2 text-truncate shadow" 
                    style="height: 100px; line-break: anywhere; text-align:center"
                > 
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br/>
                   <span style="font-size:12px;">${file_name}</span>
                </div>
            </div>`;
        }

        $(target).append(html)
    }

    // ===========
    function ajax_update_requirement_rework(btn, id_rework) {

        let requirement = $('#requirement_rework_' + id_rework).val();

        if (requirement.length == 0) {
            alert('Dữ liệu không được bỏ trống');
            return;
        }

        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
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
                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Sửa Requirements Rework'
                    })
                } else {
                    toasts_danger(kq.error);
                }
                $(btn).html('Save Requirements');
                $(btn).prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Vui lòng thử lại');
            }
        });
    }

    // COMPLETE REWORK
    function cb_upload_add_file_complete_rework(url_image, target, file_name, btn_upload) {

        let btn_upload_old = $(btn_upload).html();
        $(btn_upload).html(`99 %`);
        $(btn_upload).prop('disabled', true)

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

                    let html = ``;
                    let fileview = ``;

                    if (isImage(url_image)) {
                        fileview = `
                        <img id="img_complete_rework_${id_file_complete}" data-id-rework="${id_rework}" data-id-complete-rework="${id_file_complete}" src="${url_image}" alt="" width="100%">`;
                    } else {
                        fileview = `
                        <div 
                            id="img_complete_rework_${id_file_complete}" 
                            width="100%" 
                            class="rounded border p-2 text-truncate shadow" 
                            style="height: 100px; line-break: anywhere; text-align:center"
                            data-id-rework="${id_rework}"
                            data-id-complete-rework="${id_file_complete}"
                        > 
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br/>
                            <span style="font-size:12px;">${file_name}</span>
                        </div>`;
                    }

                    html = `<div class="position-relative image-hover w-50 p-1" id="file_complete_rework_${id_file_complete}">
                        <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:10px">
                            <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')">
                                <i class="fas fa-download"></i>
                            </button>

                            <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_complete_rework" data-target="#img_complete_rework_${id_file_complete}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_complete_rework(this, ${id_rework}, ${id_file_complete})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        ${fileview}

                    </div>`;

                    $(target).append(html)

                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Thêm Complete File Rework'
                    })
                } else {
                    toasts_danger(kq.error);
                }

                $(btn_upload).html(btn_upload_old);
                $(btn_upload).attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function cb_upload_edit_file_complete_rework(url_image, target, file_name, btn_upload) {

        let btn_upload_old = $(btn_upload).html();
        $(btn_upload).html(`99 %`);
        $(btn_upload).prop('disabled', true)

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
                    let fileview = ``;
                    if (isImage(url_image)) {
                        fileview = `
                        <img id="img_complete_rework_${id_complete_rework}" data-id-rework="${id_rework}" data-id-complete-rework="${id_complete_rework}" src="${url_image}" alt="" width="100%">`;
                    } else {
                        fileview = `
                        <div 
                            id="img_complete_rework_${id_complete_rework}" 
                            width="100%" 
                            class="rounded border p-2 text-truncate shadow" 
                            style="height: 100px; line-break: anywhere; text-align:center"
                            data-id-rework="${id_rework}"
                            data-id-complete-rework="${id_complete_rework}"
                        > 
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br/>
                            <span style="font-size:12px;">${file_name}</span>
                        </div>`;
                    }

                    $(target)
                        .siblings('.position-btn')
                        .find('.fa-download')
                        .parent()
                        .attr('onclick', `downloadURI('${url_image}', '${file_name}')`); // cập nhật file mới vào nút download

                    $(target).remove(); // xóa file cũ
                    $(`#file_complete_rework_${id_complete_rework}`).append(fileview); // thay bằng file mới

                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Sửa Complete File Rework'
                    })

                } else {
                    toasts_danger(kq.error);
                }
                $(btn_upload).html(btn_upload_old);
                $(btn_upload).attr('disabled', false);
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
                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Xóa Complete File Rework'
                    })
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

    function cb_upload_add_file_attach_rework(url_image, target, file_name, btn_upload) {

        $(btn_upload).html(`99 %`);
        $(btn_upload).prop('disabled', true)

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
                    let html_main = ``;
                    let fileview = ``;

                    if (isImage(url_image)) {
                        fileview = `
                        <img id="img_attach_${id_attach}" data-id-rework="${id_rework}" data-id-attach="${id_attach}" src="${url_image}" alt="" width="100%">`;
                    } else {
                        fileview = `
                        <div 
                            id="img_attach_${id_attach}" 
                            width="100%" 
                            class="rounded border p-2 text-truncate shadow" 
                            style="height: 100px; line-break: anywhere; text-align:center"
                            data-id-rework="${id_rework}"
                            data-id-attach="${id_attach}"
                        > 
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br/>
                        <span style="font-size:12px;">${file_name}</span>
                        </div>`;
                    }

                    html_main = `
                    <div class="position-relative image-hover w-25 p-1" id="file_attach_${id_attach}">
                        <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%; gap:5px">

                            <button class="btn btn-sm btn-warning" onclick="downloadURI('${url_image}', '${file_name}')" style="font-size: 10px; padding: 3px 5px;">
                                <i class="fas fa-download"></i>
                            </button>

                            <?php if (in_array($role, [ADMIN, SALE, QC])) { ?>
                                <button class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_edit_file_attach_rework" data-target="#img_attach_${id_attach}" style="font-size: 10px; padding: 3px 5px;">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" onclick="ajax_delete_file_attach_rework(this, ${id_rework}, ${id_attach})" style="font-size: 10px; padding: 3px 5px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php } ?>
                        </div>

                        ${fileview}

                    </div>`;

                    $(target).append(html_main)

                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Thêm Attach File Rework'
                    })
                } else {
                    toasts_danger(kq.error);
                }
                $(btn_upload).html('<i class="fas fa-upload"></i> Tải lên');
                $(btn_upload).attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(id_attach);
                alert('Error');
            }
        });
    }

    function cb_upload_edit_file_attach_rework(url_image, target, file_name, btn_upload) {

        let btn_upload_old = $(btn_upload).html();
        $(btn_upload).html(`99 %`);
        $(btn_upload).prop('disabled', true)

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

                    if (isImage(url_image)) {
                        fileview = `
                        <img id="img_attach_${id_attach}" data-id-rework="${id_rework}" data-id-attach="${id_attach}" src="${url_image}" alt="" width="100%">`;
                    } else {
                        fileview = `
                        <div 
                            id="img_attach_${id_attach}" 
                            width="100%" 
                            class="rounded border p-2 text-truncate shadow" 
                            style="height: 100px; line-break: anywhere; text-align:center"
                            data-id-rework="${id_rework}"
                            data-id-attach="${id_attach}"
                        > 
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br/>
                        <span style="font-size:12px;">${file_name}</span>
                        </div>`;
                    }

                    // cập nhật file mới vào nút download
                    $(target)
                        .siblings('.position-btn')
                        .find('.fa-download')
                        .parent()
                        .attr('onclick', `downloadURI('${url_image}', '${file_name}')`);

                    $(target).remove(); // xóa file cũ

                    $(`#file_attach_${id_attach}`).append(fileview); // thay bằng file mới

                    $(target).attr('src', url_image)
                    $(target)
                        .siblings('.position-btn')
                        .find('.fa-download')
                        .parent()
                        .attr('onclick', `downloadURI('${url_image}', '${file_name}')`)

                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Sửa Attach File Rework'
                    })

                } else {
                    toasts_danger(kq.error);
                }
                $(btn_upload).html(btn_upload_old);
                $(btn_upload).attr('disabled', false);
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
                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Xóa Complete File Rework'
                    })
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
                    socket.emit('refresh', {
                        id_order: <?= $order['id_order'] ?>,
                        'au': <?= $curr_uid ?>,
                        'content': 'Xóa Attach File Rework'
                    })
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