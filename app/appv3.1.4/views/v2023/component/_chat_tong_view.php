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
<div style="position: fixed; right: 20px; bottom: 20px;" id="small_chat_tong" class="">
    <div style="position: relative;">
        <button class="btn btn-sm btn-primary rounded-circle" onclick="open_close_chat_tong()" data-bs-toggle="tooltip" data-bs-placement="top" title="Bấm" style="width: 3rem; height:3rem">
            <i class="fa-solid fa-comment" style="font-size: 1.5rem;"></i>
        </button>
        <div class="tin-nhan-moi bg-danger rounded-circle" style="position: absolute;top: -10px;right: -8px;width: 20px;height: 20px;font-size: 0.7rem;text-align: center;line-height: 1.8;color: white;"></div>
    </div>

</div>

<div style="position: fixed; right:0; width: 100%;bottom: 0px; max-width:600px; display: none; z-index: 2;" id="box_chat_tong">
    <!-- TRAO ĐỔI KHÁCH -->
    <div id="chat_tong" class="card card-primary ">
        <div class="card-header bg-primary text-white" onclick="open_close_chat_tong()" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                    <div><i class="fa-solid fa-comment"></i> Chat với Stage Of Visualization - Hỗ trợ mọi lúc mọi nơi</div>
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
                    <div style="position:relative" class="rounded border">
                        <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                        <div class="chat_list_attach d-flex flex-wrap"></div>

                        <!-- NHẬP DỮ LIỆU -->
                        <div style="height: fit-content; position: absolute; bottom: 10px;">
                            <button type="button" class="border-0" style="font-size: 0.875rem; background: none;" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat_tong">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div>

                        <textarea name="message" class="form-control content_discuss bg-white" style="padding-left:33px; padding-right: 33px; resize: none; overflow-y: auto;" data-callback="cb_upload_add_file_attach_chat_tong" onpaste="quanlt_handle_paste_image(event)" ondrop="quanlt_handle_drop_file(event)"></textarea>

                        <div style="height: fit-content; position: absolute; bottom: 10px; right:20px">
                            <button type="button" class="text-primary p-0 border-0 btn-send" style="background: none;" onclick="ajax_chat_tong_add(this)"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        ajax_chat_tong_list();

        var _buffer;
        function countLines(textarea) {
            if (_buffer == null) {
                _buffer = document.createElement('textarea');
                _buffer.style.border = 'none';
                _buffer.style.height = '0';
                _buffer.style.overflow = 'hidden';
                _buffer.style.padding = '0';
                _buffer.style.position = 'absolute';
                _buffer.style.left = '0';
                _buffer.style.top = '0';
                _buffer.style.zIndex = '-1';
                document.body.appendChild(_buffer);
            }

            var cs = window.getComputedStyle(textarea);
            var pl = parseInt(cs.paddingLeft);
            var pr = parseInt(cs.paddingRight);
            var lh = parseInt(cs.lineHeight);

            if (isNaN(lh)) lh = parseInt(cs.fontSize);
            _buffer.style.width = (textarea.clientWidth - pl - pr) + 'px';
            _buffer.style.font = cs.font;
            _buffer.style.letterSpacing = cs.letterSpacing;
            _buffer.style.whiteSpace = cs.whiteSpace;
            _buffer.style.wordBreak = cs.wordBreak;
            _buffer.style.wordSpacing = cs.wordSpacing;
            _buffer.style.wordWrap = cs.wordWrap;
            _buffer.value = textarea.value;

            var result = Math.floor(_buffer.scrollHeight / lh);
            if (result == 0) result = 1;
            return result;
        }


        $(`#chat_tong .content_discuss`).on('keypress keyup', function(e) {

            let line = countLines(e.target);

            line = line > 5 ? 5 : line; // tối đa 10 line

            if (line > 2) {
                $(this).height(line * 24)
            } else {
                $(this).height(48)
            }
        })

        $("#chat_tong .content_discuss").keypress(function(e) {
            if (e.which == 13 && !e.shiftKey) {
                ajax_chat_tong_add($(`#chat_tong .btn-send`));
                return false;
            }
        });

    })

    function ajax_chat_tong_list() {
        $.ajax({
            url: `discuss/ajax_chat_tong_list`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    let list_discuss = kq.data;

                    let html = ``;
                    for (const [key, discuss] of Object.entries(list_discuss)) {
                        html += html_item_chat(discuss)
                    }

                    // mặc định nếu không có data thì hiển thị mặc định hello
                    if (html == ``) {
                        let hello = {
                            "id_discuss": "",
                            "id_order": "",
                            "id_user": "",
                            "content": "Chúc Quý khách ngày an lành! Tôi là chuyên viên tư vấn thiết kế.<br/> Quý khách đang có nhu cầu thay đổi thiết kế, hãy gửi các hình ảnh thực tế và để lại lời nhắn để tôi hỗ trợ bạn nhé!",
                            "file": "{}",
                            "create_time": moment(),
                            "status": "1",
                            "type": "3",
                            "username": "Stage of visualization",
                            "role": "",
                            "fullname": "Stage of visualization",
                            "avatar": "user-default.png",
                            "avatar_url": "http://stageofvisualization.local/uploads/avatar/user-default.png",
                            "file_list": {}
                        };

                        html += html_item_chat(hello)
                    }
                    // end mặc định 

                    $('#chat_tong .list-chat').html(html).scrollTop($('#chat_tong .list-chat')[0].scrollHeight);

                    tooltipTriggerList('#chat_tong');
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

    function ajax_chat_tong_add(btn) {

        let content = $('#chat_tong .content_discuss').val();
        let attach = [];
        $('#chat_tong .chat_list_attach > div').each(function(index) {
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
                'content': content,
                'attach': attach,
            },
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);

                if (kq.status) {
                    socket.emit('update-chat-tong', kq.data)
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
                    onclick="downloadURI('<?= url_image('', FDR_CHAT_TONG) ?>${file}', '${file}')"
                    style="cursor: pointer; width:150px"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Bấm để tải xuống"
                >   ${
                        (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(file)
                        ? `<img src="<?= url_image('', FDR_CHAT_TONG) ?>${file}" class="rounded border"  style="width:100%; aspect-ratio: 1;object-fit: cover;">`
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

    function remove_chat_tong_attach(id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(id).remove();
        }
    }

    // tham số bắt buộc [link_file, target, file_name, btn]
    function cb_upload_add_file_attach_chat_tong(link_file, target, file_name, btn) {
        let id_attach = Date.now();
        let html = ``;
        if (isImage(link_file)) {
            html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_tong_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach rounded shadow" alt="" width="100%" style="aspect-ratio: 1;object-fit: cover;">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover p-2" style="width:80px" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_tong_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center;     aspect-ratio: 1;object-fit: cover;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${file_name}</span>
                </div>
            </div>`;
        }

        $('#chat_tong .chat_list_attach').append(html);
    }

    //TODO:
    function isImage(url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    }

    function open_close_chat_tong() {
        $('#box_chat_tong').slideToggle('fast', 'swing');
        $('#small_chat_tong').toggleClass('d-none');
        $('#chat_tong .content_discuss').focus();
        $('#chat_tong .list-chat').scrollTop($('#chat_tong .list-chat')[0].scrollHeight);

        $('#small_chat_tong .tin-nhan-moi').text(0).hide();
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

    socket.on('update-chat-tong', data => {
        let check_username = data.username == '<?= $username ?>';
        let check_ip = data.ip == '<?= ip_address() ?>';
        
        if (check_username || check_ip) {

            var audio = new Audio('images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();

            let tin_nhan_moi = parseInt($('#small_chat_tong .tin-nhan-moi').text());
            tin_nhan_moi = isNaN(tin_nhan_moi) ? 0 : tin_nhan_moi;
            $('#small_chat_tong .tin-nhan-moi').text(tin_nhan_moi + 1).show();

            let new_html = html_item_chat(data);
            $('#chat_tong .list-chat')
                .append(new_html)
                .scrollTop($('#chat_tong .list-chat')[0].scrollHeight);

            $('#chat_tong .content_discuss').val('').height(48);
            $('#chat_tong .chat_list_attach').html('');
            $('#chat_tong .list-chat').scrollTop($('#chat_tong .list-chat')[0].scrollHeight);

            tooltipTriggerList('#chat_tong');
        }
    })
</script>
<!-- END SOCKET -->