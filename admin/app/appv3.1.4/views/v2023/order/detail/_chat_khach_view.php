<div id="discuss_khach">
    <!-- <div class="card-header ui-sortable-handle">
        <h3 class="card-title">TRAO ĐỔI VỚI KHÁCH</h3>
        <div class="card-tools">
            <span title="3 New Messages" class="badge badge-primary total-discuss">0</span>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div> -->

    <div class="list-chat" style="height: auto;max-height: 1000px; overflow-y: auto;">
        <i class="fas fa-sync fa-spin"></i>
    </div>

    <div class="card-footer">
        <div class="input-group">
            <input type="text" name="message" placeholder="Type Message ..." class="form-control content_discuss">
            <span class="input-group-append">
                <button type="button" class="btn btn-primary" onclick="ajax_discuss_khach_add(this)">Send</button>
            </span>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        ajax_discuss_list_khach();
        setInterval(() => {
            if($('#tab_chat_khach').hasClass('active')) {
                ajax_discuss_list_khach();
            }
        }, 15000);
    })

    function onclick_tab_chat_khach(tab) {
        scroll_to(tab, 0)

        ajax_discuss_list_khach();
    }

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

                        html += `
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">${discuss.fullname}</span>
                                    <span class="direct-chat-timestamp float-right">${moment(discuss.create_time).format('HH:mm, [ngày] DD-MM-YYYY')}</span>
                                </div>

                                <img class="direct-chat-img" src="${discuss.avatar_url}" alt="message user image">

                                <div class="direct-chat-text">
                                    ${discuss.content}
                                </div>
                            </div> `;
                    }

                    $('#tab_chat_khach .total').html(Object.keys(list_discuss).length);

                    $('#discuss_khach .list-chat')
                        .html(html)
                        .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

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

    function ajax_discuss_khach_add(btn) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        let content = $('#discuss_khach .content_discuss').val();
        // let file = $('#discuss_noi_bo .file_discuss').val();
        let file = {};

        $.ajax({
            url: `discuss/ajax_discuss_khach_add`,
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

                    $('#discuss_khach .list-chat')
                        .append(new_html)
                        .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

                    $('#discuss_khach .content_discuss').val('');
                    $('#discuss_khach .file_discuss').val('');


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
</script>