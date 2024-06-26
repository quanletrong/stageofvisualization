<style>
    .image-hover:hover .position-btn {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .position-relative:hover i {
        cursor: pointer;
        color: red;
    }

    .btn-setting-group {
        color: gray;
        cursor: pointer;
        margin-right: 10px;
    }

    .btn-setting-group:hover {
        color: black;
    }
</style>
<div id="chat_khach" class="card mb-0" style="width: 100%; display: none;">
    <div class="card-header text-white p-1">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="d-flex align-items-center">
                <div>
                    <img src="" class="img-circle elevation-2 avatar" alt="User Image" style="width: 40px; object-fit: cover; aspect-ratio: 1;">
                </div>
                <div style="font-weight: 500; color:black; margin-left:15px" class="fullname">...</div>
            </div>
            <div class="btn-setting-group"><i class="fas fa-ellipsis-h"></i></div>
        </div>

    </div>
    <div class="card-body bg-white p-1">
        <div style="display: flex; flex-direction: column; justify-content: flex-end;">
            <div class="list-chat" style="height: 76vh; overflow-y: auto;">
                <center><i class="fas fa-sync fa-spin"></i></center>
            </div>
            <div class="mt-2 nhap_du_lieu_chat">
                <div style="position:relative; margin:5px" class="rounded">
                    <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                    <div class="chat_list_attach d-flex flex-wrap"></div>

                    <textarea name="message" class="form-control content_chat bg-white" style="padding-right: 33px; resize: none; overflow-y: auto;" data-callback="cb_upload_add_file_attach_chat" onpaste="quanlt_handle_paste_image(event)" ondrop="quanlt_handle_drop_file(event)"></textarea>

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

        $(`#chat_khach .content_chat`).on('keypress keyup', function(e) {

            let line = _.calculateNumLines(e.target.value, this);
            line = line < 2 ? 2 : line // tối thiểu 2 rows
            line = line > 5 ? 5 : line; // tối đa 10 rows
            $(this).attr('rows', line)
        })

        $("#chat_khach .content_chat").keypress(function(e) {
            if (e.which == 13 && !e.shiftKey) {
                ajax_chat_add($(`#chat_khach .btn-send`));
                return false;
            }
        });

    })

    function ajax_chat_list_by_user(id_user) {
        $('#chat_khach').show();
        $('#chat_khach .list-chat').html('<center><i class="fas fa-sync fa-spin"></i></center>')

        $.ajax({
            url: `chat/ajax_chat_list_by_user/${id_user}`,

            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let list_chat = kq.data;

                    let html = ``;
                    for (const [key, chat] of Object.entries(list_chat)) {
                        html += html_item_chat(chat)
                    }

                    $('#chat_khach .list-chat').html(html).scrollTop($('#chat_khach .list-chat')[0].scrollHeight);

                    tooltipTriggerList('#chat_khach');
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

        let content = $('#chat_khach .content_chat').val();
        let attach = [];
        $('#chat_khach .chat_list_attach > div').each(function(index) {
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
        if (chat_user === undefined) {
            return false;
        }
        // end chat user

        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `chat/ajax_chat_add/${chat_user}`,
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

    function html_item_chat(chat) {

        let list_file = ``;
        for (const [id_file, file] of Object.entries(chat.file_list)) {
            list_file += `
            <div class="" 
                onclick="_.downloadURI('<?= url_image('', FOLDER_CHAT_TONG) ?>${file}', '${file}')"
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
        if (<?= $cur_uid ?> == chat.action_by) {
            html = `
        <div class="mb-2 me-2 d-flex justify-content-end" style="margin-left:50px; margin-right:15px" title="${chat.create_time}">
            <div class="rounded" style="background: #f0f0f0;padding: 5px 10px; text-align: end;">
                <div style="white-space: pre-line;">${chat.content != '' ? `${chat.content}` : ''}</div>
                <div class="d-flex justify-content-end" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                <small style="color:#7c7c7c">${moment(chat.create_time).fromNow()}</small>
            </div>
        </div>`;
        } else {
            html = `
        <div class="mb-2 me-2 d-flex" style="gap:10px" title="${chat.create_time}">
            <img class="rounded-circle border" style="width:40px; aspect-ratio: 1;object-fit: cover;height: 40px;" src="${chat.avatar_url}">
            <div class="rounded" style="background: #f0f0f0;padding: 5px 10px;">
                <div style="white-space: pre-line;">${chat.content != '' ? `${chat.content}` : ''}</div>
                <div class="rounded d-flex" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                <small style="color:#7c7c7c">${moment(chat.create_time).fromNow()}</small>
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

    function cb_upload_add_file_attach_chat(res, btn) {
        let id_attach = Date.now();
        let html = ``;
        if (res.thumb != '') {
            html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" data-file="${res.link}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${res.link}" class="img_attach rounded shadow" alt="" width="100%" style="aspect-ratio: 1;object-fit: cover;">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover p-2" style="width:80px" 
                id="file_attach_${id_attach}" title="${res.name}" data-file="${res.link}"
            >
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center;     aspect-ratio: 1;object-fit: cover;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${res.name}</span>
                </div>
            </div>`;
        }

        $('#chat_khach .chat_list_attach').append(html);
    }
</script>