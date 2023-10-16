<script src="js/v2023/moment_2.29.4.min.js"></script>
<div class="row">
    <!-- TRAO ĐỔI KHÁCH -->
    <?php if ($role == ADMIN || $role == SALE) { ?>
        <div class="col-12 col-lg-6">
            <div id="discuss_khach" class="card direct-chat direct-chat-primary" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle">
                    <h3 class="card-title">TRAO ĐỔI VỚI KHÁCH</h3>
                    <div class="card-tools">
                        <span title="3 New Messages" class="badge badge-primary total-discuss">0</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="direct-chat-messages" style="max-height: 600px;">
                        <i class="fas fa-sync fa-spin"></i>
                    </div>
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
        </div>
    <?php } ?>
    <!-- END TRAO ĐÔI KHÁCH  -->

    <!-- TRAO DOI NOI BỘ -->
    <div class="col-12 col-lg-<?=$role == ADMIN || $role == SALE ? 6 : 12 ?>">
        <div id="discuss_noi_bo" class="card direct-chat direct-chat-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle">
                <h3 class="card-title">TRAO ĐỔI NỘI BỘ</h3>
                <div class="card-tools">
                    <span title="3 New Messages" class="badge badge-primary total-discuss">0</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="direct-chat-messages" style="max-height: 600px;">
                    <i class="fas fa-sync fa-spin"></i>
                </div>
            </div>

            <div class="card-footer">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control content_discuss">
                    <input type="hidden" class="file_discuss">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary" onclick="ajax_discuss_add(this)">Send</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        // trao đổi nội bộ
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

                    $('#discuss_noi_bo .total-discuss').html(Object.keys(list_discuss).length);
                    $('#discuss_noi_bo .direct-chat-messages')
                        .html(html)
                        .scrollTop($('#discuss_noi_bo .direct-chat-messages')[0].scrollHeight);
                    $('#discuss_noi_bo .direct-chat-messages')



                } else {
                    toasts_danger(kq.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
        //end trao đổi nội bộ

        // trao doi voi khach
        <?php if ($role == ADMIN || $role == SALE) { ?>
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

                        $('#discuss_khach .total-discuss').html(Object.keys(list_discuss).length);
                        $('#discuss_khach .direct-chat-messages')
                            .html(html)
                            .scrollTop($('#discuss_khach .direct-chat-messages')[0].scrollHeight);
                        $('#discuss_khach .direct-chat-messages')



                    } else {
                        toasts_danger(kq.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        <?php } ?>
        // END trao doi voi khach
    })

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

                    $('#discuss_noi_bo .card-body .direct-chat-messages')
                        .append(new_html)
                        .scrollTop($('#discuss_noi_bo .card-body .direct-chat-messages')[0].scrollHeight);

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

                    $('#discuss_khach .card-body .direct-chat-messages')
                        .append(new_html)
                        .scrollTop($('#discuss_khach .card-body .direct-chat-messages')[0].scrollHeight);

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