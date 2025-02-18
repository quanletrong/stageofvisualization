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

    .msg-item:hover .btn-xoa-msg,
    .msg-item:hover .btn-reply-msg {
        display: block !important;
    }
</style>
<div id="chat_right" class="card mb-0" style="width: 100%; display: none;">
    <div class="card-header text-white p-1">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="d-flex align-items-center w-75">
                <div class="d-sm-none" style=" color: black; width: 50px;" onclick="back_to_list_gchat()">
                    <i class="fas fa-chevron-left" style=" font-size: 2rem;"></i>
                </div>
                <div class="div-avatar" style="height:50px; width: 50px; display: flex; flex-wrap: wrap; align-content: center;">{{}}</div>
                <div style="font-weight: 500; color:black; margin-left:15px" class="fullname text-truncate">{{}}</div>
            </div>

            <div class="dropdown dropleft">{{}}</div>
        </div>

    </div>
    <div class="card-body bg-white p-1">
        <div style="display: flex; flex-direction: column; justify-content: flex-end;">
            <div class="list-chat" style="height: 81vh; overflow-y: auto; padding-right: 10px; display: flex;
    flex-direction: column-reverse;"></div>
            <div class="mt-2 nhap_du_lieu_chat">
                <div style="position:relative; margin:5px" class="rounded">
                    <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                    <div class="chat_reply d-flex flex-wrap"></div>
                    <div class="chat_list_attach d-flex flex-wrap"></div>

                    <textarea
                        name="message"
                        class="form-control content_chat bg-white"
                        style="padding-right: 33px; resize: none; overflow-y: auto;"
                        data-callback="cb_upload_add_file_attach_chat"
                        data-onbefore="onbefore_upload_add_file_attach_chat"
                        data-onprogress="onprogress_upload_add_file_attach_chat"
                        data-onsuccess="onsuccess_upload_add_file_attach_chat"
                        data-onerror="onerror_upload_add_file_attach_chat"
                        onpaste="quanlt_handle_paste_image(event)"
                        ondrop="quanlt_handle_drop_file(event)"></textarea>

                    <div style="height: fit-content; position: absolute; bottom: 10px; right:20px">
                        <button type="button" class="text-primary p-0 border-0 btn-send" style="background: none;" onclick="ajax_msg_add_to_group(this)"><i class="fas fa-paper-plane"></i></button>
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
    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    });

    $(document).ready(function() {

        // INIT LẦN ĐẦU
        // load msg
        let item_group_active = $('.item-chat').first();
        <?php if ($active_gchat != '') { ?>
            item_group_active = $('#<?= $active_gchat ?>');
        <?php } ?>
        item_group_active.addClass('active')
        let id_group = item_group_active.attr('id');
        let fullname = item_group_active.find('.fullname').text();
        let avatar = item_group_active.find('.div-avatar').html();
        let dropdown = item_group_active.find('.dropdown').html();

        $('#chat_right .fullname').text(fullname)
        $('#chat_right .div-avatar').html(avatar)
        $('#chat_right .dropdown').html(dropdown)
        if (id_group != '' && id_group !== undefined) {

            // reset ve 1
            page_msg = 1;
            on_load_page_msg = 1;
            ajax_list_msg_by_group(id_group);

            // check if mobile: an ben trai, hien ben phai
            _.isMobile() ? $('#chat_right').hide() : $('#chat_right').show();
            // end check if mobile

            // pushState
            window.history.pushState('chat', 'chat', `/admin/chat/index/${id_group}`);
        }

        // end

        // set height group
        set_vh_list_chat();

        $(window).resize(function() {
            set_vh_list_chat();
        });
        // end
        // INIT LẦN ĐẦU

        $(`#chat_right .content_chat`).on('keypress keyup', function(e) {

            let line = _.calculateNumLines(e.target.value, this);
            line = line < 2 ? 2 : line // tối thiểu 2 rows
            line = line > 5 ? 5 : line; // tối đa 10 rows
            $(this).attr('rows', line)
            set_vh_list_chat();
        })

        $("#chat_right .content_chat").keypress(function(e) {
            if (e.which == 13 && !e.shiftKey) {
                ajax_msg_add_to_group($(`#chat_right .btn-send`));
                return false;
            }
        });

    })

    function back_to_list_gchat() {

        $('#chat_right').hide();
        $('#chat-left').show();
    }

    function ajax_list_msg_by_group(id_group) {

        {
            // $('#chat_right').show();
            set_vh_list_chat();
        }

        // let old_msg = $('#chat_right .list-chat').html();
        $('#chat_right .list-chat').append(`<center><i class="fas fa-sync fa-spin"></i></center>`)

        $.ajax({
            url: `chat/ajax_list_msg_by_group/${id_group}`,
            type: "POST",
            data: {
                page_msg
            },

            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let {
                        list,
                        next_page
                    } = kq.data;

                    let new_msg = ``;
                    for (const [key, chat] of Object.entries(list)) {

                        let {
                            id_msg,
                            file_list,
                            id_user,
                            fullname_user,
                            content,
                            avatar_url,
                            create_time
                        } = chat;
                        new_msg += html_item_chat(id_msg, file_list, id_user, content, avatar_url, create_time, fullname_user)
                    }

                    // set next page
                    page_msg = next_page;

                    $('#chat_right .list-chat .fa-sync').parent().remove(); // xoa spin chat
                    $('#chat_right .list-chat').append(new_msg);
                    $('#chat_right .time:first').html('&nbsp');

                    gom_avatar_fullname_time_gan_nhau();

                    tooltipTriggerList('#chat_right');
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

    $("#chat_right .list-chat").scroll(function(e) {
        // console.log(e)

        let div = $(this).get(0);
        let scrollTop = Math.abs(div.scrollTop); // vị trí thanh cuộn so với top
        let scrollHeight = $(this)[0].scrollHeight; // chiều cao list chat
        let height = $(this).height(); // chiều cao khung chat list
        // da cuộn lên vị trí cao nhất
        if ((scrollTop + height) > (scrollHeight - 500)) {

            let id_gchat = $('.item-chat.active').attr('id');

            if (page_msg >= 1 && id_gchat !== undefined && on_load_page_msg != page_msg) {

                on_load_page_msg = page_msg;
                ajax_list_msg_by_group(id_gchat)
            }
        }


    });

    function gom_avatar_fullname_time_gan_nhau() {
        // ẩn avatar liên tiêp
        let before_time = 0;
        let before_msg = '';
        let before_user = '';
        $($('.msg-item').get().reverse()).each(function(i, obj) {

            let crr_user = $(this).data('by');
            let crr_msg = $(this).attr('id');
            let crr_time = new Date($(this).data('time')).getTime() / 1000;

            // chi show avatar hien tai, hide avatar trước
            if (crr_user == before_user) {
                $(`#${before_msg}`).find('.avatar').css('opacity', 0);
            }

            // chi show fullname truoc, hide fullname hien tai
            if (crr_user == before_user) {
                $(this).find('.fullname').hide();
                $(this).removeClass('mt-3').addClass('mt-1');
            }

            // if (time hien tai - thoi gian truoc) > 10p thi show hien tai, ẩn <= 10p
            if ((crr_time - before_time) > 600) {
                $(this).find('.time_msg').show();
            }

            before_user = crr_user;
            before_msg = crr_msg;
            before_time = crr_time;
        });
        // end ẩn avatar liên tiêp
    }

    function ajax_msg_add_to_group(btn) {

        let content = $('#chat_right .content_chat').val();
        let attach = [];
        $('#chat_right .chat_list_attach > div').each(function(index) {
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
        let id_gchat = $('.item-chat.active').attr('id');
        if (id_gchat === undefined) {
            return false;
        }
        // end chat user

        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        $.ajax({
            url: `chat/ajax_msg_add_to_group/${id_gchat}`,
            type: "POST",
            data: {
                'content': content,
                'attach': attach,
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                    // reset form nhập
                    $('#chat_right .content_chat').val('').attr('rows', 2);
                    $('#chat_right .chat_list_attach').html('');

                    // build html chat
                    socket.emit('add-msg-to-gchat', kq.data);

                    // build xong rồi scroll xuống dưới
                    set_vh_list_chat();

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

    function ajax_del_msg_group(id_msg) {
        if (confirm("Are you sure you want to delete this message?") == true) {
            $.ajax({
                url: `chat/ajax_del_msg_group/${id_msg}`,
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        $(`#msg_${id_msg} .rounded`).html(kq.data);
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
    }

    function html_item_chat(id_msg, file_list, id_user, content, avatar_url, create_time, fullname_user) {

        let timeSince = _.timeSince(create_time);
        let width_right = $('#chat_right .list-chat').width();

        let total_file = Object.keys(file_list).length;
        let max_width_image_pc = total_file == 1 ? '350px' : '250px';
        let max_width_image_mb = '100%';
        let max_width = _.isMobile() ? max_width_image_mb : max_width_image_pc;

        let ratio_imgae = total_file > 1 ? 'aspect-ratio: 1;object-fit: cover;' : '';

        // LIST FILE
        let list_file = ``;

        for (const [id_file, file] of Object.entries(file_list)) {

            let src_file = `<?= url_image('', FOLDER_CHAT_TONG) ?>${file}`;
            let src_file_thumb = `<?= url_image('', FOLDER_CHAT_TONG . 'thumb/') ?>${file}`;

            list_file +=
                `${
                    _.isImage(file)
                    ? 
                    `<a data-src="${src_file}" data-fancybox="gallery" data-caption="${timeSince}">
                        <img 
                            src="${src_file_thumb}" 
                            class="rounded border" 
                            style="cursor: pointer; max-width:${max_width}; ${ratio_imgae}"
                        >
                    </a>`
                    :
                    `<div 
                        onclick="_.downloadURI('${src_file}', '${file}')" 
                        class="rounded border p-2 text-truncate bg-light" 
                        style="cursor: pointer; width: 200px;line-break: anywhere; text-align:center;"
                    >
                        <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                        <span style="font-size:12px;">${file}</span>
                    </div>
                    `
                }`;
        }
        // END LIST FILE

        // NUT XÓA
        let xoa = '';
        <?php if ($role == ADMIN) { ?>
            xoa = `
                <div style="width:20px; cursor: pointer;" onclick="ajax_del_msg_group(${id_msg})">
                    <div class="btn-xoa-msg" style="display:none">
                        <i class="fas fa-trash" style="font-size: 0.75rem; color: gray"></i>
                    </div>
                </div>`;
        <?php } ?>
        // END NUT XÓA

        // NUT REPLY
        let reply = `
            <div style="width:20px; cursor: pointer;" onclick="reply_msg(${id_msg}, '${content.replace(/[\r\n'"“”‘’]+/g, " ")}', '${fullname_user}', '${id_user}')">
                <div class="btn-reply-msg" style="display:none">
                    <i class="fas fa-reply" style="font-size: 0.75rem; color: gray"></i>
                </div>
            </div>`;
        // END NUT REPLY

        let html = ``;
        if (<?= $cur_uid ?> == id_user) {
            html = `
            <div id="msg_${id_msg}" 
                class="mt-3 me-2 msg-item" 
                data-by="${id_user}" 
                data-time="${create_time}"
                title="${timeSince}"
            >
                <div class="time_msg" style="display:none; text-align:center">
                    <small style="color:#7c7c7c;">${timeSince}</small>
                </div>
                <div class="d-flex justify-content-end" style="gap:10px; margin-left: 40px;">
                
                    <div class="d-flex align-items-center gap-1">
                        ${xoa}
                        ${reply}
                    </div>                   

                    <div style="display: flex; flex-direction: column; align-items: flex-end;">

                        ${
                            list_file != ''
                            ?`<div class="rounded d-flex justify-content-end mb-1" style="flex-wrap: wrap; gap:5px;">${list_file}</div>`
                            : ``
                        }

                        ${
                            content != ''
                            ? `<div class="rounded mb-1" style="background: #e1f0ff; padding: 5px 10px; width: fit-content;">
                                <div style="white-space: pre-line;">${content}</div>
                            </div>`
                            : ``
                        }

                        <small style="" class="time" title="${create_time}"></small>
                    </div>
                    
                </div>
                
            </div>`;
        } else {
            html = `
            <div id="msg_${id_msg}" 
                class="mt-3 me-2 msg-item" 
                data-by="${id_user}" 
                data-time="${create_time}"
                title="${timeSince}"
            >
                <div class="time_msg" style="display:none; text-align:center">
                    <small style="color:#7c7c7c;">${timeSince}</small>
                </div>
                <div style="display:flex; justify-content: flex-start; gap:10px; align-items: flex-end;">
                    <img class="rounded-circle border avatar" style="width:30px; aspect-ratio: 1;object-fit: cover;height: 30px;" src="${avatar_url}">
                    <div>
                        <div class="fullname" style="display:block; display: flex; justify-content: space-between; gap:20px">
                            <small style="color:#7c7c7c;">${fullname_user}</small> 
                        </div>
                    
                        <div style="display:flex; gap:10px;">
                            <div>
                                ${
                                    list_file != ''
                                    ?`<div class="rounded d-flex mb-1" style="gap:5px; flex-wrap: wrap;">${list_file}</div>`
                                    : ``
                                }

                                ${
                                    content != ''
                                    ? `<div class="rounded mb-1" style="background: #f0f0f0;padding: 5px 10px; width: fit-content;">
                                        <div style="white-space: pre-line; word-break: break-word;"> ${content}</div>
                                    </div>`
                                    : ``
                                }
                                
                                <small style="color:#7c7c7c" class="time" title="${create_time}"></small>
                            </div>
                            
                            <div class="d-flex align-items-center gap-1">
                                ${reply}
                                ${xoa}
                            </div>  
                        </div>
                       
                    </div>
                </div>
            </div>`;
        }

        return html;
    }

    function remove_chat_attach(id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(id).remove();
            set_vh_list_chat();
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
                <img id="img_attach_${id_attach}" src="${res.thumb}" class="img_attach rounded shadow" alt="" width="100%" style="aspect-ratio: 1;object-fit: cover;">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" title="${res.name}" data-file="${res.link}">
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

        $('#chat_right .chat_list_attach').append(html);

        set_vh_list_chat();
    }

    function onbefore_upload_add_file_attach_chat(eventDrop, dropTo) {

        let html =
            `<div 
            class="position-relative image-hover p-2" 
            style="width:80px" 
            id="file_attach_${dropTo}" 
            title="" 
            data-file=""
        >
            <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_attach('#file_attach_${dropTo}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="file" style="display:none">
                <div width="100%" class="rounded border p-2 shadow" style="text-align:center; aspect-ratio: 1;object-fit: cover;">0%</div>
            </div>

            <div class="percent rounded border p-2 shadow" 
                width="100%" 
                style="display:block; text-align:center; aspect-ratio: 1;object-fit: cover;"
            >
                0%
            </div>
        </div>`;

        $('#chat_right .chat_list_attach').append(html);

        set_vh_list_chat();
    }

    function onprogress_upload_add_file_attach_chat(eventDrop, percent, dropTo) {
        $(`#file_attach_${dropTo} .percent`).text(`${percent} %`);
    }

    function onerror_upload_add_file_attach_chat(eventDrop, error, error_text, dropTo) {
        $(`#file_attach_${dropTo} .percent`).text(`Error`);
        alert(error_text);
    }

    function onsuccess_upload_add_file_attach_chat(eventDrop, success, dropTo) {
        let {
            link,
            name,
            thumb
        } = success;
        $(`#file_attach_${dropTo} .percent`).hide();
        $(`#file_attach_${dropTo} .file`).show();
        $(`#file_attach_${dropTo}`).data('file', link);

        let html = '';
        if (_.isImage(link)) {
            html = `<img width="100%" src="${thumb}" class="img_attach rounded shadow" alt="" style="aspect-ratio: 1;object-fit: cover;">`;
        } else {
            html =
                `<div width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center; aspect-ratio: 1;object-fit: cover;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${name}</span>
                </div>`
        }

        $(`#file_attach_${dropTo} .file`).html(html);
    }

    function set_vh_list_chat() {

        let windown_height = $(window).height();
        let card_header = $('#chat_right .card-header').outerHeight();
        let nhap_du_lieu_chat = $('#chat_right .nhap_du_lieu_chat').outerHeight();

        let new_height = windown_height - card_header - nhap_du_lieu_chat - 30;
        $('#chat_right .list-chat').css('height', new_height + 'px');
    }

    function reply_msg(id_msg, content, fullname_user,id_user) {
        
        content == '' ? content = 'Đính kèm' : '';
        content.length > 50 ? content = content.slice(0, 50) + "..." : '';

        id_user == '<?= $cur_uid ?>' ? fullname_user = `chính mình` : '';
        let html = `
        <div class="d-flex justify-content-between align-items-center rounded mb-1" style="background: #f0f0f0;padding: 5px 10px; width: 100%;">
            <div>
                <div class="fw-bold">Đang trả lời ${fullname_user}</div>
                <div>
                    <div style="white-space: pre-line; font-size:14px">"${content}"</div>
                </div>
            </div>
            <div style="cursor: pointer;" onclick="remove_reply()"><i class="fas fa-times"></i></div>
        </div>`;

        $('#chat_right .chat_reply').html(`${html}`);
        $('#chat_right .content_chat').focus();

        set_vh_list_chat();
    }

    function remove_reply(){
        $('#chat_right .chat_reply').html('');
        set_vh_list_chat();
    }
</script>