<section id="section_chat_order">
    <script src="js/v2023/moment_2.29.4.min.js"></script>
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
    </style>
    <div style="position: fixed; right: 10px; bottom: 0px;" class="small_chat">
        <div style="position: relative;">
            <button class="btn btn-sm btn-primary" onclick="CHAT.open_close_chat('#section_chat_order')" data-bs-toggle="tooltip" data-bs-placement="top" title="Bấm">
                <i class="fa-solid fa-comment"></i> CHAT ĐƠN HÀNG NÀY
            </button>
            <div class="tin-nhan-moi bg-danger rounded-circle" style="position: absolute;top: -10px;right: -8px;width: 20px;height: 20px;font-size: 0.7rem;text-align: center;line-height: 1.8;color: white;"></div>
        </div>

    </div>

    <div style="position: fixed; right:0; width: 100%;bottom: 0px; max-width:600px; display: none; z-index: 2;" class="box_chat">
        <!-- TRAO ĐỔI KHÁCH -->
        <div class="card card-primary chat">
            <div class="card-header bg-primary text-white" onclick="CHAT.open_close_chat('#section_chat_order')" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                        <div><i class="fa-solid fa-comment"></i> CHAT ĐƠN HÀNG NÀY</div>
                        <div>x</div>
                    </h6>
                </div>
            </div>
            <div class="card-body bg-white p-1">
                <div style="display: flex; flex-direction: column; height: 80vh; justify-content: flex-end;">
                    <div class="list-chat" style="height: auto; overflow-y: auto;">
                        <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>
                    </div>
                    <div class="mt-2 nhap_du_lieu_chat">
                        <div style="position:relative; margin: 5px" class="rounded">
                            <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                            <div class="chat_list_attach d-flex flex-wrap"></div>

                            <textarea name="message" class="form-control content_discuss bg-white" rows="2" style="padding-right: 33px; resize: none; overflow-y: auto;" data-callback="cb_upload_add_file_attach_chat_khach" onpaste="quanlt_handle_paste_image(event)" ondrop="quanlt_handle_drop_file(event)"></textarea>

                            <div style="height: fit-content; position: absolute; bottom: 10px; right:20px">
                                <button type="button" class="text-primary p-0 border-0 btn-send" style="background: none;" onclick="ajax_discuss_khach_add(this)"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>

                        <!-- NHẬP DỮ LIỆU -->
                        <div style="margin-top: 5px; display: flex; justify-content: space-between;">
                            <div>
                                <button type="button" class="border-0 mr-2" style="font-size: 0.875rem; background: none;">
                                    <i class="fa-solid fa-face-smile" style="color:#424242"></i>
                                </button>

                                <button type="button" class="border-0" style="font-size: 0.875rem; background: none;" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat_khach">
                                    <i class="fa fa-paperclip"></i>
                                </button>
                            </div>

                            <button type="button" class="border-0 ml-2" style="font-size: 0.875rem; background: none;">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            ajax_discuss_list();

            $(`#section_chat_order .chat .content_discuss`).on('keypress keyup', function(e) {

                let line = _.calculateNumLines(e.target.value, this);
                line = line < 2 ? 2 : line // tối thiểu 2 rows
                line = line > 5 ? 5 : line; // tối đa 10 rows
                $(this).attr('rows', line)
            })

            $(`#section_chat_order .chat .content_discuss`).keypress(function(e) {
                if (e.which == 13 && !e.shiftKey) {
                    ajax_discuss_khach_add($(`#section_chat_order .chat .btn-send`));
                    return false;
                }
            });

        })

        function ajax_discuss_list() {
            $.ajax({
                url: `discuss/ajax_discuss_list`,
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
                            html += html_item_chat(discuss)
                        }

                        $('#section_chat_order .chat .list-chat').html(html).scrollTop($('#section_chat_order .chat .list-chat')[0].scrollHeight);

                        tooltipTriggerList('#section_chat_order .chat');
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

        function ajax_discuss_khach_add(btn) {

            let content = $('#section_chat_order .chat .content_discuss').val();
            let attach = [];
            $('#section_chat_order .chat .chat_list_attach > div').each(function(index) {
                let file = $(this).data('file');
                attach.push(file);
            });

            // check empty
            content = $.trim(content);
            if (content.length === 0 && attach.length === 0) {
                return false;
            }
            // end check empty

            $(btn).html(' <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
            $(btn).prop("disabled", true);

            $.ajax({
                url: `discuss/ajax_discuss_add`,
                type: "POST",
                data: {
                    'id_order': <?= $order['id_order'] ?>,
                    'content': content,
                    'attach': attach,
                },
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        socket.emit('update-chat-khach', kq.data)
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
                    onclick="downloadURI('<?= url_image('', $FDR_ORDER) ?>${file}', '${file}')"
                    style="cursor: pointer; width:150px"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Bấm để tải xuống"
                >   ${
                        (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(file)
                        ? `<img src="<?= url_image('', $FDR_ORDER) ?>${file}" class="rounded border"  style="width:100%; aspect-ratio: 1;object-fit: cover;">`
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
            if (<?= $cur_uid ?> == discuss.id_user) {
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
                <img class="rounded-circle border" style="width:40px; aspect-ratio: 1;object-fit: cover;height: 40px;" src="${discuss.avatar_url}" alt="${discuss.fullname}" title="${discuss.fullname}">
                <div class="rounded" style="background: #f0f0f0;padding: 5px 10px;">
                    <div style="white-space: pre-line;">${discuss.content != '' ? `${discuss.content}` : ''}</div>
                    <div class="rounded d-flex" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                    <small style="color:#7c7c7c">${moment(discuss.create_time).fromNow()}</small>
                </div>
            </div>`;
            }

            return html;
        }

        function remove_chat_khach_attach(id) {
            if (confirm("Are you sure you want to delete this file?") == true) {
                $(id).remove();
            }
        }

        // tham số bắt buộc [link_file, target, file_name, btn]
        function cb_upload_add_file_attach_chat_khach(link_file, target, file_name, btn) {
            let id_attach = Date.now();
            let html = ``;
            if (_.isImage(link_file)) {
                html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_khach_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach rounded shadow" alt="" width="100%" style="aspect-ratio: 1;object-fit: cover;">
            </div>`;
            } else {
                html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_khach_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center;     aspect-ratio: 1;object-fit: cover;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${file_name}</span>
                </div>
            </div>`;
            }

            $('#section_chat_order .chat .chat_list_attach').append(html);
        }
    </script>

    <!-- SOCKET -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        const socket = io('<?= SOCKET_SERVICES ?>', {
            transports: ['websocket'],
            withCredentials: true,
            extraHeaders: {
                "my-custom-header": "abcd"
            }
        });

        socket.on('update-chat-khach', data => {
            if (data.id_order == <?= $order['id_order'] ?>) {

                var audio = new Audio('images/Tieng-ting-www_tiengdong_com.mp3');
                audio.play();

                let small_chat = $('#section_chat_order .small_chat');
                let chat = $('#section_chat_order .chat');
                let content_discuss = $('#section_chat_order .chat .content_discuss');
                let chat_list_attach = $('#section_chat_order .chat .chat_list_attach');
                let list_chat = $('#section_chat_order .chat .list-chat');

                let cur_number = parseInt(small_chat.find('.tin-nhan-moi').text());
                cur_number = isNaN(cur_number) ? 0 : cur_number;
                small_chat.find('.tin-nhan-moi').text(cur_number + 1).show();

                let new_html = html_item_chat(data);

                list_chat.append(new_html).scrollTop(list_chat[0].scrollHeight);
                content_discuss.attr('rows', 2).val('');
                chat_list_attach.html('');
                list_chat.scrollTop(list_chat[0].scrollHeight);

                tooltipTriggerList(chat);
            }
        })
    </script>
    <!-- END SOCKET -->
</section>