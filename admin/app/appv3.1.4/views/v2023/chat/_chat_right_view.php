<div id="discuss_khach" class="card mb-0" style="width: 100%;">
    <div class="card-header text-white p-1">
        <div class="d-flex align-items-center">
            <div>
                <img src="" class="img-circle elevation-2 avatar" alt="User Image" style="width: 40px; object-fit: cover; aspect-ratio: 1;">
            </div>
            <div style="font-weight: 500; color:black; margin-left:15px" class="fullname">...</div>
        </div>
    </div>
    <div class="card-body bg-white p-1">
        <div style="display: flex; flex-direction: column; justify-content: flex-end;">
            <div class="list-chat" style="height: 63vh; overflow-y: auto;">
                <center><i class="fas fa-sync fa-spin"></i></center>
            </div>
            <div class="mt-2 nhap_du_lieu_chat">
                <div style="position:relative; margin:5px" class="rounded">
                    <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                    <div class="chat_list_attach d-flex flex-wrap"></div>

                    <textarea name="message" class="form-control content_discuss bg-white" style="padding-right: 33px; resize: none; overflow-y: auto;" data-callback="cb_upload_add_file_attach_chat" onpaste="quanlt_handle_paste_image(event)" ondrop="quanlt_handle_drop_file(event)"></textarea>

                    <div style="height: fit-content; position: absolute; bottom: 10px; right:20px">
                        <button type="button" class="text-primary p-0 border-0 btn-send" style="background: none;" onclick="ajax_chat_add(this)"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>

                <!-- NHẬP DỮ LIỆU -->
                <div style="margin-top: 5px; display: flex; justify-content: space-between;">
                    <div>
                        <button type="button" class="border-0 mr-2" style="font-size: 0.875rem; background: none;">
                            <i class="fas fa-smile" style="color:#424242"></i>
                        </button>

                        <button type="button" class="border-0" style="font-size: 0.875rem; background: none;" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </div>

                    <button type="button" class="border-0 ml-2" style="font-size: 0.875rem; background: none;">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(`#discuss_khach .content_discuss`).on('keypress keyup', function(e) {

            let line = _.calculateNumLines(e.target.value, this);
            line = line < 2 ? 2 : line // tối thiểu 2 rows
            line = line > 5 ? 5 : line; // tối đa 10 rows
            $(this).attr('rows', line)
        })

        $("#discuss_khach .content_discuss").keypress(function(e) {
            if (e.which == 13 && !e.shiftKey) {
                ajax_chat_add($(`#discuss_khach .btn-send`));
                return false;
            }
        });

    })

    function ajax_chat_list_by_user(id_user) {

        $('#discuss_khach .list-chat').html('<center><i class="fas fa-sync fa-spin"></i></center>')
        
        $.ajax({
            url: `discuss/ajax_chat_list_by_user/${id_user}`,

            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let list_discuss = kq.data;

                    let html = ``;
                    for (const [key, discuss] of Object.entries(list_discuss)) {
                        html += html_item_chat(discuss)
                    }

                    $('#discuss_khach .list-chat').html(html).scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

                    tooltipTriggerList('#discuss_khach');
                } else {
                    alert(kq.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ajax_chat_add(btn) {

        let content = $('#discuss_khach .content_discuss').val();
        let attach = [];
        $('#discuss_khach .chat_list_attach > div').each(function(index) {
            let file = $(this).data('file');
            attach.push(file);
        });

        // check empty
        content = $.trim(content);
        if (content.length === 0 && attach.length === 0) {
            return false;
        }
        // end check empty

        // get chat user active
        let chat_user = $('.item-chat.active').attr('id');
        // end chat user

        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `discuss/ajax_chat_add/${chat_user}`,
            type: "POST",
            data: {
                'content': content,
                'attach': attach,
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    socket.emit('update-chat-tong', kq.data);
                } else {
                    alert(kq.error);
                }

                $(btn).prop("disabled", false).html('<i class="fas fa-paper-plane"></i>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function html_item_chat(discuss) {

        let list_file = ``;
        for (const [id_file, file] of Object.entries(discuss.file_list)) {
            list_file += `
            <div class="" 
                onclick="downloadURI('<?= url_image('', FOLDER_CHAT_TONG) ?>${file}', '${file}')"
                style="cursor: pointer; width:150px"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="Bấm để tải xuống"
            >   ${
                    (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(file)
                    ? `<img src="<?= url_image('', FOLDER_CHAT_TONG) ?>${file}" class="rounded border"  style="width:100%; aspect-ratio: 1;object-fit: cover;">`
                    : `
                    <div class="rounded border p-2 text-truncate bg-light" style="width: 100%;line-break: anywhere; text-align:center; aspect-ratio: 1;object-fit: cover;">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                        <span style="font-size:12px;">${file}</span>
                    </div>
                    `
                }
            </div>`;
        }

        let html = ``;
        if (<?= $cur_uid ?> == discuss.action_by) {
            html = `
        <div class="mb-2 me-2 d-flex justify-content-end" style="margin-left:50px; margin-right:15px" title="${discuss.create_time}">
            <div class="rounded" style="background: #f0f0f0;padding: 5px 10px; text-align: end;">
                <div style="white-space: pre-line;">${discuss.content != '' ? `${discuss.content}` : ''}</div>
                <div class="d-flex justify-content-end" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                <small style="color:#7c7c7c">${moment(discuss.create_time).fromNow()}</small>
            </div>
        </div>`;
        } else {
            html = `
        <div class="mb-2 me-2 d-flex" style="gap:10px" title="${discuss.create_time}">
            <img class="rounded-circle border" style="width:40px; aspect-ratio: 1;object-fit: cover;height: 40px;" src="${discuss.avatar_url}">
            <div class="rounded" style="background: #f0f0f0;padding: 5px 10px;">
                <div style="white-space: pre-line;">${discuss.content != '' ? `${discuss.content}` : ''}</div>
                <div class="rounded d-flex" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                <small style="color:#7c7c7c">${moment(discuss.create_time).fromNow()}</small>
            </div>
        </div>`;
        }

        return html;
    }

    function remove_chat_attach(id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(id).remove();
        }
    }

    // tham số bắt buộc [link_file, target, file_name, btn]
    function cb_upload_add_file_attach_chat(link_file, target, file_name, btn) {
        let id_attach = Date.now();

        let html = ``;
        if (isImage(link_file)) {
            html = `
        <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" data-file="${link_file}">
            <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_attach('#file_attach_${id_attach}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach rounded shadow" alt="" width="100%" style="aspect-ratio: 1;object-fit: cover;">
        </div>`;
        } else {
            html = `
        <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
            <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_attach('#file_attach_${id_attach}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center;     aspect-ratio: 1;object-fit: cover;">
                <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                <span style="font-size:12px;">${file_name}</span>
            </div>
        </div>`;
        }

        $('#discuss_khach .chat_list_attach').append(html);
    }
</script>