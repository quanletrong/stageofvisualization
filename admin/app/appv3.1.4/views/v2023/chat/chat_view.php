<?php ?>
<script src="js/v2023/moment_2.29.4.min.js"></script>
<style>
    .main-footer {
        display: none;
    }

    .item-chat:hover {
        background-color: #f0f0f0;
    }

    .item-chat.active {
        background-color: #f0f0f0;
    }

    .item-chat:hover .delete {
        display: block !important;
    }

    /* group */

    #btn-add-group {
        font-size: 1.5rem;
        color: gray;
        cursor: pointer;
    }

    #btn-add-group:hover {
        color: black;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <?php $this->load->view('v2023/chat/_chat_left_view.php'); ?>
                </div>
                <div class="col-md-8">
                    <?php $this->load->view('v2023/chat/_chat_right_view.php'); ?>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    setInterval(() => {
        let time_chat = []
        $('.time').each(function(i, obj) {
            datetime = $(this).attr('title')
            if (datetime != undefined || datetime == '') {
                let seconds = moment(Date.now()).unix() - moment(datetime).unix();
                let interval = Math.floor(seconds / 31536000);
                if (interval >= 1) {
                    $(this).html(interval + " năm");
                    return
                }
                interval = Math.floor(seconds / 2592000);
                if (interval >= 1) {
                    $(this).html(interval + " tháng");
                    return
                }
                interval = Math.floor(seconds / 86400);
                if (interval >= 1) {
                    $(this).html(interval + " ngày");
                    return
                }
                interval = Math.floor(seconds / 3600);
                if (interval >= 1) {
                    $(this).html(interval + " giờ");
                    return
                }
                interval = Math.floor(seconds / 60);
                if (interval >= 1) {
                    $(this).html(interval + " phút");
                    return
                }
                $(this).html('Vừa xong');
                return
            }
        });
    }, 1000);


    $(document).ready(function() {
        $('.main-header').hide()
    })

    function onclick_el_gchat(id_group) {

        let chat_item = $(`[id='${id_group}']`);

        $('.item-chat').removeClass('active');
        chat_item.addClass('active')
        chat_item.find('.content').css('font-weight', 300)

        let fullname = chat_item.find('.fullname').text();
        let avatar = chat_item.find('.div-avatar').html();

        $('#chat_right .fullname').text(fullname)
        $('#chat_right .div-avatar').html(avatar)

        ajax_list_msg_by_group(id_group)
    }

    function ajax_delete_chat_user(chat_user) {

        if (confirm('Bạn muốn xóa đoạn chat này?')) {
            $.ajax({
                url: `chat/ajax_delete_chat_user/${chat_user}`,
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {

                        // update trái
                        $(`[id='${chat_user}']`).remove();

                        if ($('.item-chat').length == 0) {
                            $('#chat-left .alert_empty_chat').show();
                            $('#chat_right').hide();
                        }

                        // update phải
                        let is_active = $(`[id='${chat_user}']`).hasClass('active');
                        if (is_active) {

                            let item_group_active = $('.item-chat').first();
                            let id_group = item_group_active.attr('id');
                            let fullname = item_group_active.find('.fullname').text();
                            let avatar = item_group_active.find('.div-avatar').html();

                            item_group_active.addClass('active')

                            $('#chat_right .fullname').text(fullname)
                            $('#chat_right .div-avatar').html(avatar)
                            ajax_list_msg_by_group(id_group);
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
    }

    // lắng nghe sự kiên thêm nhóm chat
    socket.on('add-gchat', data => {
        let id_gchat = data.info.id_gchat;
        let members = data.member;
        let member_ids = data.member_ids;
        let msg = data.msg_newest
        let action_by = data.action_by

        // bật thông báo
        if (action_by != <?= $cur_uid ?>) {
            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();
        }

        // nếu user hiện tại includes trong member_ids thì mới hiển thị
        if (member_ids.length && member_ids.includes('<?= $cur_uid ?>')) {

            // tạo avatar cho el gchat
            let index = 1;
            let avatar = '';
            for (var mem_id in members) {
                if (index <= 4) {
                    let mem = members[mem_id];
                    avatar += `<img src = "${mem.avatar_url}"
                    class = "img-circle elevation-2 avatar"
                    alt = "${mem.fullname}"
                    style = "width: ${member_ids.length == 2 ? '100%' : '50%'}; object-fit: cover; aspect-ratio: 1;" >`;
                    index++;
                }
            }
            // end tạo avatar

            // tạo element gchat cột bên trái
            let html_new =
                `<div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="${id_gchat}" onclick="onclick_el_gchat('${id_gchat}')">
                <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;">
                    ${avatar}
                </div>
                <div style="width: 85%; position: relative;">
                    <div style="width: 100%; font-weight: 500;" class="fullname text-truncate">
                        ${data.info.name}
                    </div>
                    <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                        <div class="text-truncate content" style="width: 80%; font-weight: 600;">${isEmpty(msg) ? '' : msg.content}</div>
                        <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;" title="${isEmpty(msg) ? '' : msg.create_time}">&nbsp;</div>
                    </div>

                    <div style="position: absolute;right: 0px;top: 11px;color: red; display: none;" class="delete" onclick="ajax_delete_chat_user('${id_gchat}')">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>`
            // end

            $('#chat-left .list-group').prepend(html_new);
            $('#chat-left .alert_empty_chat').hide();
        }
    })

    // lắng nghe sự kiện thêm msg mới
    socket.on('add-msg-to-group', data => {
        let id_gchat = data.id_gchat;
        let action_by = data.action_by;
        let el_gchat_left = $(`[id='${data.id_gchat}']`);

        if (action_by != <?= $cur_uid ?>) {
            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();
        }

        // nhóm đã tồn tại làm 2 việc chính sau:
        if (el_gchat_left.length) {
            // update bên trái
            el_gchat_left.find(`.content`).text(data.content);
            el_gchat_left.find(`.content`).css('font-weight', 600)
            el_gchat_left.find(`.time`).attr('title', data.create_time);
            el_gchat_left.parent().prepend(el_gchat_left);

            // update bên phải nếu nhóm đang active
            let isActiveRight = el_gchat_left.hasClass('active');
            if (isActiveRight) {
                let new_html = html_item_chat(data);
                $('#chat_right .list-chat')
                    .append(new_html)
                    .scrollTop($('#chat_right .list-chat')[0].scrollHeight);

                $('#chat_right .content_chat').val('').attr('rows', 2);
                $('#chat_right .chat_list_attach').html('');
                $('#chat_right .list-chat').scrollTop($('#chat_right .list-chat')[0].scrollHeight);

                tooltipTriggerList('#chat_right');
                set_vh_list_chat();
            }
        }
    })
</script>