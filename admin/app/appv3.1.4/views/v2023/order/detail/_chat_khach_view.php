<div style="position: fixed;right: 10px;bottom: 0px; width: 60%; max-width:800px; display: none; z-index: 2;" id="box_trao_doi_sale">
    <!-- <h4 class="mt-5">TRAO ĐỔI VỚI KHÁCH</h4> -->
    <div class="row">
        <!-- TRAO ĐỔI KHÁCH -->
        <div class="col-12 col-lg-12">
            <div id="discuss_khach" class="card card-danger mb-0">
                <div class="card-header bg-danger text-white" onclick="open_close_chat_khach()" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                            <div><i class="fas fa-comment"></i> TRAO ĐỔI VỚI KHÁCH</div>
                            <div>x</div>
                        </h6>
                    </div>
                </div>
                <div class="card-body bg-white">
                    <div style="display: flex; flex-direction: column; height: 80vh; justify-content: space-between;">
                        <div class="list-chat" style="height: auto; overflow-y: auto;">
                            <i class="fas fa-sync fa-spin"></i>
                        </div>
                        <div class="mt-2 nhap_du_lieu_chat">
                            <div style="position:relative" class="rounded border">
                                <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                                <div class="chat_list_attach d-flex flex-wrap"></div>

                                <!-- NHẬP DỮ LIỆU -->
                                <div style="height: fit-content; position: absolute; bottom: 10px; left:10px">
                                    <button type="button" class="btn" style="width: 80px; font-size: 0.875rem;" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat_khach">
                                        <i class="fa fa-paperclip"></i>
                                    </button>
                                </div>

                                <textarea name="message" class="form-control content_discuss bg-white" style="padding-left:100px; padding-right: 100px; resize: none; overflow-y: auto;" placeholder="Type Message ..." data-callback="cb_upload_add_file_attach_chat_khach" onpaste="quanlt_handle_paste_image(event)" ondrop="quanlt_handle_drop_file(event)"></textarea>

                                <div style="height: fit-content; position: absolute; bottom: 10px; right:10px">
                                    <button type="button" class="btn btn-primary" onclick="ajax_discuss_khach_add(this)"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        ajax_discuss_list_khach();

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


        $(`#discuss_khach .content_discuss`).on('keypress keyup', function(e) {

            let line = countLines(e.target);

            line = line > 5 ? 5 : line; // tối đa 10 line

            if (line > 2) {
                $(this).height(line * 24)
            } else {
                $(this).height(48)
            }
        })

    })

    function ajax_discuss_list_khach() {
        $.ajax({
            url: `discuss/ajax_discuss_list_khach`,
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

                    $('#tab_chat_khach .total').html(Object.keys(list_discuss).length);

                    $('#discuss_khach .list-chat').html(html).scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

                    // TODO:
                    // var audio = new Audio('images/Tieng-ting-www_tiengdong_com.mp3');
                    // audio.play();

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

    function ajax_discuss_khach_add(btn) {
        $(btn).html('<i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        let content = $('#discuss_khach .content_discuss').val();
        let attach = [];
        $('#discuss_khach .chat_list_attach > div').each(function(index) {
            let file = $(this).data('file');
            attach.push(file);
        });

        $.ajax({
            url: `discuss/ajax_discuss_khach_add`,
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
        if (<?= $curr_uid ?> == discuss.id_user) {
            html = `
            <div class="mb-2 me-2 d-flex justify-content-end" style="margin-left:50px; margin-right:15px" title="${moment(discuss.create_time).fromNow()}">
                <div class="rounded" style="background: #f0f0f0;padding: 10px; text-align: end;">
                    <div style="white-space: pre-line;">${discuss.content != '' ? `${discuss.content}` : ''}</div>
                    <div class="d-flex justify-content-end" style="flex-wrap: wrap; gap:5px">${list_file}</div>
                </div>
            </div>`;
        } else {
            html = `
            <div class="mb-2 me-2 d-flex" style="gap:10px" title="${moment(discuss.create_time).fromNow()}">
                <img class="rounded-circle border" style="width:40px; aspect-ratio: 1;object-fit: cover;height: 40px;" src="${discuss.avatar_url}" alt="${discuss.fullname}" title="${discuss.fullname}">
                <div class="rounded" style="background: #f0f0f0;padding: 10px;">
                    <div style="white-space: pre-line;">${discuss.content != '' ? `${discuss.content}` : ''}</div>
                    <div class="rounded d-flex" style="flex-wrap: wrap; gap:5px">${list_file}</div>
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
        if (isImage(link_file)) {
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

        $('#discuss_khach .chat_list_attach').append(html);
    }

    function open_close_chat_khach() {
        $('#box_trao_doi_sale').slideToggle('fast', 'swing');
        // $('#small_trao_doi_sale').toggleClass('d-none');
        $('#discuss_khach .content_discuss').focus();
        $('#discuss_khach .list-chat').scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);
    }
</script>