<script src="js/v2023/moment_2.29.4.min.js"></script>

<div style="position: fixed; right: 10px; bottom: 0px;" id="small_trao_doi_sale" class="">
    <button class="btn btn-danger" onclick="open_close_chat()" data-bs-toggle="tooltip" data-bs-placement="top" title="Bấm">
        <i class="fa-solid fa-comment"></i> TRAO ĐỔI VỚI SALE
    </button>
</div>

<div style="position: fixed;right: 10px;bottom: 0px; width: 60%; max-width:800px; display: none;" id="box_trao_doi_sale">
    <!-- <h4 class="mt-5">TRAO ĐỔI VỚI SALE</h4> -->
    <div class="row">
        <!-- TRAO ĐỔI KHÁCH -->
        <div class="col-12 col-lg-12">
            <div id="discuss_khach" class="card card-primary ">
                <div class="card-header" onclick="open_close_chat()" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                            <div>TRAO ĐỔI VỚI SALE</div>
                            <div>x</div>
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <div id="discuss_khach" style="display: flex; flex-direction: column; height: 80vh; justify-content: space-between;">
                        <div class="list-chat" style="height: auto; overflow-y: auto;">
                            <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>
                        </div>
                        <div class="mt-2 nhap_du_lieu_chat">
                            <div style="position:relative" class="rounded border">
                                <!-- HIỂN THỊ FILE ĐÍNH KÈM -->
                                <div class="chat_list_attach d-flex flex-wrap"></div>

                                <!-- NHẬP DỮ LIỆU -->
                                <div style="height: fit-content; position: absolute; bottom: 10px; left:10px">
                                    <button type="button" class="btn btn-warning" style="width: 80px; font-size: 0.875rem;" onclick="quanlt_upload(this);" data-callback="cb_upload_add_file_attach_chat_khach">
                                        <i class="fa fa-paperclip"></i> File
                                    </button>
                                </div>

                                <textarea class="form-control content_discuss" name="message" placeholder="Type Message ..." onkeyup="set_height_chat_list_and_height_input(`#discuss_khach`)" style="padding-left:100px; padding-right: 100px; resize: none;"></textarea>

                                <div style="height: fit-content; position: absolute; bottom: 10px; right:10px">
                                    <button type="button" class="btn btn-primary" onclick="ajax_discuss_khach_add(this)">Send</button>
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
        ajax_discuss_list();
        setInterval(() => {
            // ajax_discuss_list();
        }, 15000);

        $(`textarea`).on('keyup', function() {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        })

    })

    function onclick_tab_chat_khach(tab) {

        scroll_to(tab, 0)

        set_height_chat_list_and_height_input(`#discuss_khach`);


        // ajax_discuss_list();
    }

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

                    $('#tab_chat_khach .total').html(Object.keys(list_discuss).length);

                    $('#discuss_khach .list-chat')
                        .html(html)
                        .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

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
        $(btn).html(' <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
        $(btn).prop("disabled", true);

        let content = $('#discuss_khach .content_discuss').val();
        let attach = [];
        $('#discuss_khach .chat_list_attach > div').each(function(index) {
            let file = $(this).data('file');
            attach.push(file);
        });

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
                    let discuss = kq.data;

                    let new_html = html_item_chat(discuss);

                    $('#discuss_khach .list-chat')
                        .append(new_html)
                        .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

                    $('#discuss_khach .content_discuss').val('').height(60);
                    $('#discuss_khach .chat_list_attach').html('');
                    set_height_chat_list_and_height_input(`#discuss_khach`);

                    tooltipTriggerList('#discuss_khach');
                } else {
                    alert(kq.error);
                }

                $(btn).prop("disabled", false).text('Send');
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
                <div class="p-1 mb-2" 
                    onclick="downloadURI('<?= url_image('', $FDR_ORDER) ?>${file}', '${file}')"
                    style="cursor: pointer; max-width:250px"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Bấm để tải xuống"
                >   ${
                        (/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(file)
                        ? `<img src="<?= url_image('', $FDR_ORDER) ?>${file}" height="150">`
                        : `
                        <div width="100%" class="rounded border p-2 text-truncate shadow bg-light" style="height: 100px;line-break: anywhere; text-align:center">
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <br />
                            <span style="font-size:12px;">${file}</span>
                        </div>
                        `
                    }
                </div>`;
        }

        let html = `
            <div class="d-flex" style="gap:15px" class="${<?= $cur_uid ?> == discuss.id_user ? '' : '' }">
                <img class="rounded-circle shadow" style="width:60px;aspect-ratio: 1;object-fit: cover;height: 60px;" src="${discuss.avatar_url}" alt="avatar">
                <div class="w-100">
                    <div class="d-flex justify-content-between w-100">
                        <div class="fw-bold fs-6">${discuss.fullname}</div>
                        <div class="" style="font-size: 0.875rem" data-bs-toggle="tooltip" data-bs-placement="top" title="${moment(discuss.create_time).format('HH:mm, [ngày] DD-MM-YYYY')}">${moment(discuss.create_time).fromNow()}</div>
                    </div>

                    <div class="" style="white-space: pre-line; ">${discuss.content != '' ? `${discuss.content}` : ''}</div>

                    <div class="d-flex" style="flex-wrap: wrap;">${list_file}</div>
                </div>
            </div>
            <hr>`;

        return html;
    }

    function remove_chat_khach_attach(id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            $(id).remove();
            set_height_chat_list_and_height_input(`#discuss_khach`);
        }
    }

    // tham số bắt buộc [link_file, target, file_name, btn]
    function cb_upload_add_file_attach_chat_khach(link_file, target, file_name, btn) {
        let id_attach = Date.now();

        let html = ``;
        if (isImage(link_file)) {
            html = `
            <div class="position-relative image-hover p-2" style="width:150px" id="file_attach_${id_attach}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_khach_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <img id="img_attach_${id_attach}" src="${link_file}" class="img_attach" alt="" width="100%">
            </div>`;
        } else {
            html = `
            <div class="position-relative image-hover p-2" style="width:150px" id="file_attach_${id_attach}" title="${file_name}" data-file="${link_file}">
                <div class="position-btn" style="position: absolute; display: none; top: 0; right:0">
                    <button class="btn btn-sm btn-warning rounded-circle" onclick="remove_chat_khach_attach('#file_attach_${id_attach}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div id="img_attach_${id_attach}" width="100%" class="rounded border p-2 text-truncate shadow" style="height: 100px; line-break: anywhere; text-align:center">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${file_name}</span>
                </div>
            </div>`;
        }

        $('#discuss_khach .chat_list_attach').append(html);

        set_height_chat_list_and_height_input(`#discuss_khach`);
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

            $(`${parent} .list-chat`).scrollTop($(`${parent} .list-chat`)[0].scrollHeight);
        }, 100);
    }


    function isImage(url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    }

    function open_close_chat() {
        $('#box_trao_doi_sale').slideToggle('fast', 'swing');
        $('#small_trao_doi_sale').toggleClass('d-none');
        set_height_chat_list_and_height_input(`#discuss_khach`);
    }
</script>