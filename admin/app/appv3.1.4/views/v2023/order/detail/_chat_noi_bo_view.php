<div id="discuss_noi_bo" style="display: flex; flex-direction: column; justify-content: space-between;">
    <div class="list-chat" style="height: auto; overflow-y: auto;">
        <i class="fas fa-sync fa-spin"></i>
    </div>
    <div class="nhap_du_lieu_chat">
        <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
        <div id="chat_list_attach" class="mt-1 d-flex flex-wrap"></div>

        <!-- NHẬP DỮ LIỆU -->
        <div class="mt-2 input-group">
            <span class="input-group-append">
                <button type="button" class="btn btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat_noi_bo">
                    <i class="fa fa-paperclip"></i>
                </button>
            </span>

            <textarea class="form-control content_discuss" name="message" placeholder="Type Message ..."></textarea>
            <input type="hidden" class="file_discuss">

            <span class="input-group-append">
                <button type="button" class="btn btn-primary" onclick="ajax_discuss_add(this)">Send</button>
            </span>
        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        ajax_discuss_list_noi_bo();
        setInterval(() => {
            if ($('#tab_chat_noi_bo').hasClass('active')) {
                // ajax_discuss_list_noi_bo();
            }
        }, 15000);

    })

    function onclick_tab_chat_noi_bo(tab) {

        scroll_to(tab, 0)

        set_height_chat_list_and_height_input(`#discuss_noi_bo`);


        // ajax_discuss_list_noi_bo();
    }

    function ajax_discuss_list_noi_bo() {
        $.ajax({
            url: `discuss/ajax_discuss_list_noi_bo`,
            type: "POST",
            data: {
                id_order: <?= $order['id_order'] ?>,
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let list_discuss = kq.data;

                    let html = ``;
                    for (const [key, discuss] of Object.entries(list_discuss)) {

                        let html_file = ``;
                        for (const [id_file, file] of Object.entries(discuss.file_list)) {
                            html_file += `
                                <div class="p-1 w-25 mb-2" 
                                    onclick="downloadURI('<?= url_image('', $FDR_ORDER) ?>${file}', '${file}')"
                                    style="cursor: pointer;" title="Bấm để tải xuống"
                                >   ${
                                        (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(file)
                                        ? `<img class="w-100" src="<?= url_image('', $FDR_ORDER) ?>${file}">`
                                        : `
                                        <div width="100%" class="rounded border p-2 text-truncate shadow bg-light" style="height: 100px;line-break: anywhere; text-align:center">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                                            <span style="font-size:12px;">${file}</span>
                                        </div>
                                        `
                                    }
                                </div>
                            `;
                        }
                        html += `
                        
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">${discuss.fullname}</span>
                                    <span class="direct-chat-timestamp float-right">${moment(discuss.create_time).format('HH:mm, [ngày] DD-MM-YYYY')}</span>
                                </div>

                                <img class="direct-chat-img" src="${discuss.avatar_url}" alt="message user image">

                                <div class="direct-chat-text p-2 ${<?= $curr_uid ?> == discuss.id_user ? 'bg-light' : '' }">
                                    
                                    <div class="d-flex" style="">
                                        ${html_file}
                                    </div>
                                    <p class="m-0 px-2 py-1 rounded">${discuss.content}</p>
                                    
                                </div>
                            </div> `;
                    }

                    $('#tab_chat_noi_bo .total').html(Object.keys(list_discuss).length);

                    $('#discuss_noi_bo .list-chat')
                        .html(html)
                        .scrollTop($('#discuss_noi_bo .list-chat')[0].scrollHeight);
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

    function ajax_discuss_add(btn) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        let content = $('#discuss_noi_bo .content_discuss').val();
        // let file = $('#discuss_noi_bo .file_discuss').val();
        let file = {};

        $.ajax({
            url: `discuss/ajax_discuss_noi_bo_add`,
            type: "POST",
            data: {
                'id_order': <?= $order['id_order'] ?>,
                'content': content,
                'file': file,
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let discuss = kq.data;

                    let new_html = `
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">${discuss.fullname}</span>
                                <span class="direct-chat-timestamp float-right">${moment(discuss.create_time).format('HH:mm, [ngày] DD-MM-YYYY')}</span>
                            </div>

                            <img class="direct-chat-img" src="${discuss.avatar_url}" alt="message user image">

                            <div class="direct-chat-text">
                                ${discuss.content}
                            </div>
                        </div>`;

                    $('#discuss_noi_bo .list-chat')
                        .append(new_html)
                        .scrollTop($('#discuss_noi_bo .list-chat')[0].scrollHeight);

                    $('#discuss_noi_bo .content_discuss').val('');
                    $('#discuss_noi_bo .file_discuss').val('');


                } else {
                    toasts_danger(kq.error);
                }

                $(btn).prop("disabled", false).text('Send');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function remove_chat_noi_bo_attach(id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(id).remove();
            set_height_chat_list_and_height_input(`#discuss_noi_bo`);
        }
    }

    // tham số bắt buộc [link_file, target, file_name, btn]
    function cb_upload_add_file_attach_chat_noi_bo(link_file, target, file_name, btn) {
        let id_attach = Date.now();

        let html = ``;
        if (isImage(link_file)) {
            html = `
            <div class="position-relative image-hover p-1" style="width:100px" id="file_attach_${id_attach}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%;">
                    <button class="btn btn-sm btn-warning" onclick="remove_chat_noi_bo_attach('#file_attach_${id_attach}')" style="font-size: 10px; padding: 3px 5px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach" alt="" width="100%">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover p-1" style="width:100px" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 20px; width:100%;">
                    <button class="btn btn-sm btn-warning" onclick="remove_chat_noi_bo_attach('#file_attach_${id_attach}')" style="font-size: 10px; padding: 3px 5px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="height: 100px; line-break: anywhere; text-align:center">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${file_name}</span>
                </div>
            </div>`;
        }

        $('#chat_list_attach').append(html);

        set_height_chat_list_and_height_input(`#discuss_noi_bo`);
    }

    function set_height_chat_list_and_height_input(parent) {
        setTimeout(() => {
            $(`${parent} .list-chat`).css('height', `auto`);
            $(`${parent} .nhap_du_lieu_chat`).css('height', `auto`);

            let height_chat_tab_content = $('#card_chat_lich_su .card-body .tab-content').height();
            let height_nhap_du_lieu_chat = $(`${parent} .nhap_du_lieu_chat`).height();

            let h_list_chat = height_chat_tab_content - height_nhap_du_lieu_chat;

            $(`${parent} .list-chat`).css('height', `${h_list_chat}px`);
            $(`${parent} .nhap_du_lieu_chat`).css('height', `${height_nhap_du_lieu_chat}px`);
        }, 100);
    }
</script>