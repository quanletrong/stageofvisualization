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

    .msg-item:hover .btn-reaction-msg {
        display: block !important;
    }

    .btn-reaction-msg:hover .choose-reaction {
        display: flex !important;
    }

    .icon-reaction:hover {
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
        transform: scale(1.2);
    }

    #modal_show_all_reaction .reaction-item:hover {
        cursor: pointer;
        background: #f0f0f0;
        border-radius: 15px;
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
            <div class="list-chat" style="height: 81vh; overflow-y: auto; padding-right: 10px; padding-bottom: 10px; display: flex;
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
                    let list_id_msg = [];
                    for (const [key, chat] of Object.entries(list)) {

                        let {
                            id_msg,
                            file_list,
                            id_user,
                            fullname_user,
                            content,
                            avatar_url,
                            reply,
                            create_time
                        } = chat;
                        new_msg += html_item_chat(id_group, id_msg, file_list, id_user, content, avatar_url, create_time, fullname_user, reply)

                        list_id_msg.push(id_msg);
                    }

                    // set next page
                    page_msg = next_page;

                    $('#chat_right .list-chat .fa-sync').parent().remove(); // xoa spin chat
                    $('#chat_right .list-chat').append(new_msg);
                    $('#chat_right .time:first').html('&nbsp');

                    if (list_id_msg.length > 0) {
                        ajax_list_reaction_many_msg(list_id_msg.join());
                    }

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
                'id_msg_reply': $('#id_msg_reply').val()
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {

                    // reset form nhập
                    $('#chat_right .content_chat').val('').attr('rows', 2);
                    $('#chat_right .chat_list_attach').html('');
                    $('#chat_right .chat_reply').html('');

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

    function html_item_chat(id_group, id_msg, file_list, id_user, content, avatar_url, create_time, fullname_user, reply_db) {

        let timeSince = _.timeSince(create_time);
        let width_right = $('#chat_right .list-chat').width();

        let total_file = Object.keys(file_list).length;
        let max_width_image_pc = total_file == 1 ? '350px' : '250px';
        let max_width_image_mb = '100%';
        let max_width = _.isMobile() ? max_width_image_mb : max_width_image_pc;

        let ratio_imgae = total_file > 1 ? 'aspect-ratio: 1;object-fit: cover;' : '';
        let role = '<?= $role ?>';
        let admin = '<?= ADMIN ?>';
        let cur_uid = '<?= $cur_uid ?>';

        // HTML LIST FILE
        let html_list_file = render_files_msg(file_list, timeSince, max_width, ratio_imgae)

        // NUT XÓA
        let html_btn_xoa = role == admin ? render_xoa_msg(id_msg) : '';

        // NUT REPLY
        let html_btn_reply = render_btn_reply_msg(id_msg, content, fullname_user, id_user);

        // NUT REPLY
        let html_btn_reaction = render_btn_reaction(id_msg, cur_uid, id_user);

        // NUT REACTION
        let html_msg_reaction = render_msg_reaction([]);

        // HTML REPLY
        let html_has_reply = render_msg_has_reply(reply_db, cur_uid, id_user, id_group);

        let html = ``;
        if (cur_uid == id_user) {
            html = `
            <div id="msg_${id_msg}" class="mt-3 me-2 msg-item" data-by="${id_user}" data-time="${create_time}" title="${timeSince}">
                
                <div class="time_msg" style="display:none; text-align:center">
                    <small style="color:#7c7c7c;">${timeSince}</small>
                </div>

                ${html_has_reply}

                <div class="d-flex justify-content-end" style="gap:10px; margin-left: 40px;">
                    <div class="d-flex align-items-center" style="gap:8px">
                        ${html_btn_xoa}
                        ${html_btn_reply}
                        ${html_btn_reaction}
                    </div>                   

                    <div style="display: flex; flex-direction: column; align-items: flex-end;">

                        ${
                            html_list_file != ''
                            ?`<div class="rounded d-flex justify-content-end mb-1" style="flex-wrap: wrap; gap:5px;">${html_list_file}</div>`
                            : ``
                        }

                        ${
                            content != ''
                            ? `
                            <div class="rounded mb-1" style="background: #e1f0ff; padding: 5px 10px; width: fit-content;">
                                <div style="white-space: pre-line;">${content}</div>
                            </div>`
                            : ``
                        }
                            <div class="msg-reaction" onclick="show_all_reaction('${id_msg}')">${html_msg_reaction}</div>
                        <small style="" class="time" title="${create_time}"></small>
                    </div>                            
                </div>
            </div>`;
        } else {
            html = `
            <div id="msg_${id_msg}" class="mt-3 me-2 msg-item" data-by="${id_user}" data-time="${create_time}" title="${timeSince}">
                <div class="time_msg" style="display:none; text-align:center">
                    <small style="color:#7c7c7c;">${timeSince}</small>
                </div>

                <div style="display:flex; justify-content: flex-start; gap:10px; align-items: flex-end;">
                    <img class="rounded-circle border avatar" style="width:30px; aspect-ratio: 1;object-fit: cover;height: 30px;" src="${avatar_url}">
                    <div>
                        <div class="fullname" style="display:block; display: flex; justify-content: space-between; gap:20px">
                            <small style="color:#7c7c7c;">${fullname_user}</small> 
                        </div>

                        ${html_has_reply}
                    
                        <div style="display:flex; gap:10px;">
                            <div>
                                ${
                                    html_list_file != ''
                                    ?`<div class="rounded d-flex mb-1" style="gap:5px; flex-wrap: wrap;">${html_list_file}</div>`
                                    : ``
                                }

                                ${
                                    content != ''
                                    ? `
                                    <div class="rounded mb-1" style="background: #f0f0f0;padding: 5px 10px; width: fit-content;">
                                        <div style="white-space: pre-line; word-break: break-word;"> ${content}</div>
                                    </div>`
                                    : ``
                                }
                                <div class="msg-reaction" onclick="show_all_reaction('${id_msg}')">${html_msg_reaction}</div>
                                <small style="color:#7c7c7c" class="time" title="${create_time}"></small>
                            </div>
                            
                            <div class="d-flex align-items-center" style="gap:8px">
                                ${html_btn_reaction}
                                ${html_btn_reply}
                                ${html_btn_xoa}
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

    function click_reply_msg(id_msg, content, fullname_user, id_user) {

        content == '' ? content = 'Đính kèm' : '';
        content.length > 50 ? content = content.slice(0, 50) + "..." : '';

        id_user == '<?= $cur_uid ?>' ? fullname_user = `chính mình` : '';
        let html = `
        <input type="hidden" id="id_msg_reply" value="${id_msg}">
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

    function remove_reply() {
        $('#chat_right .chat_reply').html('');
        set_vh_list_chat();
    }

    function scroll_to_msg(id_msg, id_group) {
        // msg đã tìm thấy thì scroll đến luôn
        if ($(`#msg_${id_msg}`).length) {
            $(".list-chat").animate({
                scrollTop: $(".list-chat").scrollTop() + $(`#msg_${id_msg}`).position().top - 100
            }, 500);

            $(`#msg_${id_msg}`).css('background', '#f0f0f0');
            setTimeout(() => {
                $(`#msg_${id_msg}`).css('background', '');
            }, 3000);

        } else {

            // cuộn lên đâu list chat để load thêm msg
            $(".list-chat").animate({
                scrollTop: -$(".list-chat")[0].scrollHeight
            }, 500);

            // 1 giây sau gọi lại hàm scroll_to_msg để tìm msg
            setTimeout(() => {
                scroll_to_msg(id_msg, id_group);
            }, 1000);
        }
    }

    // render html nút xóa tin nhắn
    function render_xoa_msg(id_msg) {
        return `
            <div style="width:20px; cursor: pointer;" onclick="ajax_del_msg_group(${id_msg})">
                <div class="btn-xoa-msg" style="display:none">
                    <i class="fas fa-trash" style="font-size: 0.85rem; color: gray"></i>
                </div>
            </div>`;
    }

    // render html nút trả lời tin nhắn
    function render_btn_reply_msg(id_msg, content, fullname_user, id_user) {
        return `
            <div
                style="width:20px; cursor: pointer;" 
                onclick="click_reply_msg(${id_msg}, '${content.replace(/[\r\n'"“”‘’]+/g, " ")}', '${fullname_user}', '${id_user}')"
            >
                <div class="btn-reply-msg" style="display:none">
                    <i class="fas fa-reply" style="font-size: 0.85rem; color: gray"></i>
                </div>
            </div>`;
    }

    // render html nút reaction tin nhắn
    function render_btn_reaction(id_msg, cur_uid, id_user) {

        // tùy vào tin nhắn mà hiện vị trí nút reaction
        let position = ``;
        if (cur_uid == id_user) {
            position = 'position: absolute; right:0; flex-direction: row-reverse;';
        } else {
            position = 'position: absolute; left:0';
        }

        return `
            <div
                style="width:20px; cursor: pointer;" onclick=""
            >
                <div class="btn-reaction-msg position-relative" style="display:none">
                    <i class="fas fa-smile" style="font-size: 0.85rem; color: gray"></i>
                    <div class="choose-reaction" style="${position}; display: none; gap: 12px; background: #f0f0f0; padding: 2px 4px; border-radius:10px">

                    <?php foreach (REACTION as $key => $rat) { ?>
                        <div onclick="ajax_set_reaction(${id_msg}, '<?= $key ?>')" class="icon-reaction" title="<?= $rat['title'] ?>"><?= $rat['icon'] ?></div>
                    <?php } ?>
                    </div>
                </div>
            </div>`;
    }

    // render html danh sách reaction của tin nhắn
    function render_msg_reaction(reaction) {

        let reaction_cf = <?= json_encode(REACTION) ?>;
        let list_icon_reaction = Object.keys(reaction);
        let html_list_icon = ``;
        list_icon_reaction.forEach(icon => {
            html_list_icon += `<div>${reaction_cf[icon]['icon']}</div>`
        })

        if (list_icon_reaction.length == 0) {
            return '';
        } else {
            let total_reaction = Object.values(reaction).reduce((acc, arr) => acc + arr.length, 0);
            return `
            <div style="display: flex;justify-content: flex-end;gap: 5px;background: aliceblue;border-radius: 10px;padding: 0 10px; width: fit-content;">
                ${html_list_icon}
                <div>${total_reaction}</div>
            </div>`;
        }
    }

    // tin nhắn đang có reply
    function render_msg_has_reply(reply_db, cur_uid, id_user, id_group) {

        if (reply_db == '' || reply_db == null) {
            return '';
        }

        try {
            let {
                fullname,
                id_msg,
                content
            } = JSON.parse(reply_db);

            // reply right (chính mình)
            if (cur_uid == id_user) {
                let reply_for = reply_db.id_user == cur_uid ? 'chính mình' : fullname;
                return `
                <div>
                    <div class="d-flex justify-content-end" style="font-size: 12px; color: gray">Bạn đã trả lời tin nhắn của ${reply_for}</div>
                    <div class="d-flex justify-content-end">
                        <div 
                            onclick="scroll_to_msg(${id_msg}, ${id_group})"
                            style="font-size: 12px; padding: 5px 10px 10px 10px; margin-bottom:-10px; border-radius: 5px; background: #c9c9c9; cursor: pointer"
                        >
                            ${content == '' ? 'Đính kèm' : content}
                        </div>
                    </div>
                </div>`
            }
            // reply left (người khác)
            else {
                let reply_for = reply_db.id_user == cur_uid ? 'bạn' : fullname;
                return `
                <div>
                    <div class="d-flex" style="font-size: 12px; color: gray">Đã trả lời tin nhắn của ${reply_for}</div>
                    <div class="d-flex">
                        <div 
                            style="font-size: 12px; padding: 5px 10px 10px 10px; margin-bottom:-10px; border-radius: 5px; background: #c9c9c9; cursor: pointer"
                            onclick="scroll_to_msg(${id_msg}, ${id_group})"
                        >
                            ${content == '' ? 'Đính kèm' : content}
                        </div>
                    </div>
                </div>`;
            }

        } catch (error) {
            console.log(error.message);
        }
    }

    // render list file trong tin nhắn
    function render_files_msg(file_list, timeSince, max_width, ratio_imgae) {

        let html_list_file = '';
        for (const [id_file, file] of Object.entries(file_list)) {

            let src_file = `<?= url_image('', FOLDER_CHAT_TONG) ?>${file}`;
            let src_file_thumb = `<?= url_image('', FOLDER_CHAT_TONG . 'thumb/') ?>${file}`;

            html_list_file +=
                `
                ${_.isImage(file)
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

        return html_list_file;
    }

    // set reaction cho tin nhắn
    function ajax_set_reaction(id_msg, reaction) {
        $.ajax({
            url: `chat/ajax_set_reaction/${id_msg}`,
            type: "POST",
            data: {
                'reaction': reaction
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    $(`#msg_${id_msg} .msg-reaction`).html(render_msg_reaction(kq.data));
                    // build html chat
                    socket.emit('set-reaction', {
                        id_msg: id_msg,
                        reaction: kq.data
                    });
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

    // lấy list reaction theo list id_msg
    function ajax_list_reaction_many_msg(list_id_msg) {
        $.ajax({
            url: `chat/ajax_list_reaction_many_msg`,
            type: "POST",
            data: {
                'list_id_msg': list_id_msg
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    for (const [id_msg, reaction] of Object.entries(kq.data)) {
                        let html_icons = render_msg_reaction(reaction);
                        $(`#msg_${id_msg} .msg-reaction`).html(html_icons);
                    }
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

    // modal show all reaction
    function show_all_reaction(id_msg) {
        let html = `
        <div class="modal fade" id="modal_show_all_reaction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cảm xúc về tin nhắn</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="list_reaction modal-body">
                        <center><i class="fas fa-sync fa-spin"></i></center>
                    </div>
                </div>
            </div>
        </div>`;

        $('#modal_show_all_reaction').remove();
        $('body').append(html);

        $('#modal_show_all_reaction').modal('show');

        $.ajax({
            url: `chat/ajax_list_reaction_msg/${id_msg}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let reaction_cf = <?= json_encode(REACTION) ?>;
                    let html = ``;

                    kq.data.forEach(reaction => {
                        let id_raction = reaction['reaction'];
                        let fullname = reaction['fullname'];
                        let remove_reaction = reaction['id_user'] == '<?= $cur_uid ?>' ? `ajax_remove_reaction(this, '${id_msg}')` : '';
                        html += `
                        <div class="reaction-item d-flex justify-content-between p-2 my-2" onclick="${remove_reaction}">
                            <div>${fullname}</div>
                            <div style="display: flex;flex-direction: column;align-items: flex-end;">
                                <div>${reaction_cf[id_raction]['icon']}</div>
                                <div style="font-size:12px; color: gray">${reaction['id_user'] == '<?= $cur_uid ?>' ? `Bấm để gỡ` : ''}</div>
                            </div>
                        </div>`;
                    })

                    $('#modal_show_all_reaction .list_reaction').html(html);
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

    // xóa gỡ bỏ reaction tin nhắn
    function ajax_remove_reaction(el, id_msg) {

        $(el).remove();
        $.ajax({
            url: `chat/ajax_remove_reaction/${id_msg}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    $(`#msg_${id_msg} .msg-reaction`).html(render_msg_reaction(kq.data));
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
</script>