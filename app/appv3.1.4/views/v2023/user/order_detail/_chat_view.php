<script src="js/v2023/moment_2.29.4.min.js"></script>
<style>
    .direct-chat-contacts,
    .direct-chat-messages {
        transition: -webkit-transform .5s ease-in-out;
        transition: transform .5s ease-in-out;
        transition: transform .5s ease-in-out, -webkit-transform .5s ease-in-out;
    }

    .direct-chat-messages {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
        height: 250px;
        overflow: auto;
        padding: 10px;
    }

    .direct-chat-msg {
        margin-bottom: 10px;
    }

    .direct-chat-infos {
        display: block;
        font-size: .875rem;
        margin-bottom: 2px;
    }

    .direct-chat-name {
        font-weight: 600;
    }

    .float-left {
        float: left !important;
    }

    .direct-chat-timestamp {
        color: #697582;
    }

    .float-right {
        float: right !important;
    }

    .direct-chat-img {
        border-radius: 50%;
        float: left;
        height: 40px;
        width: 40px;
        box-shadow: 3px 2px 7px 0px #888888;
        vertical-align: middle;
        border-style: none;
    }

    .direct-chat-text {
        border-radius: 0.3rem;
        background-color: #d2d6de;
        border: 1px solid #d2d6de;
        color: #444;
        margin: 5px 0 0 50px;
        padding: 5px 10px;
        position: relative;
        display: block;
    }
</style>
<div class="row">
    <!-- TRAO ĐỔI KHÁCH -->
    <div class="col-12 col-lg-12">
        <div id="discuss_khach" class="card card-primary mt-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                        <div>TRAO ĐỔI VỚI SALE</div>
                    </h6>
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
                        <button type="button" class="btn btn-primary" onclick="ajax_discuss_add(this)">Send</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- END TRAO ĐÔI KHÁCH  -->
</div>

<script>
    $(document).ready(function() {
        // trao doi voi khach
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
        // END trao doi voi khach
    })

    function ajax_discuss_add(btn) {
        $(btn).html(' <i class="fas fa-sync fa-spin"></i>');
        $(btn).prop("disabled", true);

        let content = $('#discuss_khach .content_discuss').val();
        // let file = $('#discuss_noi_bo .file_discuss').val();
        let file = {};

        $.ajax({
            url: `discuss/ajax_discuss_add`,
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