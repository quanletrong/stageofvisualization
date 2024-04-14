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

    .item-chat:hover .option {
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

        ajax_list_msg_by_group(id_group);
        window.history.pushState('chat', 'chat', `/admin/chat/index/${id_group}`);
    }

    function ajax_delete_gchat(id_gchat) {

        if (confirm('Are you sure you want to delete this chat?')) {
            $.ajax({
                url: `chat/ajax_delete_gchat/${id_gchat}`,
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        socket.emit('delete-gchat', kq.data);
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
        let id_gchat = data.id_gchat;
        let name_gchat = data.name_gchat
        let members = data.members;
        let member_ids = data.member_ids;
        let content = data.content
        let create_time = data.create_time
        let action_by = data.action_by

        // bật thông báo
        if (action_by != <?= $cur_uid ?>) {
            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();
        }

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
            `<div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="${id_gchat}">
                <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;" onclick="onclick_el_gchat('${id_gchat}')">
                    ${avatar}
                </div>
                <div style="width: 85%; position: relative;">
                    <div style="width: 100%; font-weight: 500;" class="fullname text-truncate" onclick="onclick_el_gchat('${id_gchat}')">
                        ${name_gchat}
                    </div>
                    <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;" onclick="onclick_el_gchat('${id_gchat}')">
                        <div class="text-truncate content" style="width: 80%; font-weight: 600;">${content}</div>
                        <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;" title="${create_time}">&nbsp;</div>
                    </div>

                    <div style="position: absolute;right: 0px;top: 11px;color: red; display: none; background-color: #f0f0f0;" class="option">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 0 10px;">
                                <span class="text-secondary">
                                    <i class="fas fa-ellipsis-h" style="font-size: 1.5rem;"></i>
                                </span>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(33px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-edit-group" data-group="${id_gchat}">
                                    <span class="text-secondary">Xem thông tin</span>
                                </button>
                                <?php if ($role == ADMIN) { ?>
                                    <button class="dropdown-item" type="button" onclick="ajax_delete_gchat('${id_gchat}')">
                                        <span class="text-secondary">Xóa nhóm này</span>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>`
        // end

        $('#chat-left .list-group').prepend(html_new);
        $('#chat-left .alert_empty_chat').hide();
    })

    socket.on('delete-gchat', data => {
        let id_gchat = data.id_gchat;

        // ẩn bên phải trước
        if ($(`#${id_gchat}`).hasClass('active')) {
            $('#chat_right').hide();
        }

        // xóa bên trai sau
        $(`#${id_gchat}`).remove();
        if ($('.item-chat').length == 0) {
            $('#chat-left .alert_empty_chat').show();
        }
    })

    socket.on('update-name-gchat', data => {
        let name_gchat = data.name_gchat;
        let id_gchat = data.id_gchat;
        $(`#${id_gchat} .fullname `).html(name_gchat);
    })


    // lắng nghe sự kiện thêm msg mới
    socket.on('add-msg-to-gchat', data => {
        let {
            id_gchat,
            id_msg,
            file_list,
            id_user,
            fullname,
            content,
            avatar_url,
            create_time,
            action_by
        } = data;

        let el_gchat_left = $(`[id='${id_gchat}']`);

        if (action_by != <?= $cur_uid ?>) {
            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();
        }

        // nhóm đã tồn tại làm 2 việc chính sau:
        if (el_gchat_left.length) {
            // update bên trái
            el_gchat_left.find(`.content`).html(content != '' ? content : '<i>File phương tiện</i>');
            el_gchat_left.find(`.content`).css('font-weight', action_by == <?= $cur_uid ?> ? 300 : 600 )
            el_gchat_left.find(`.time`).attr('title', create_time);
            el_gchat_left.parent().prepend(el_gchat_left);

            // update bên phải nếu nhóm đang active
            let isActiveRight = el_gchat_left.hasClass('active');
            if (isActiveRight) {
                let new_html = html_item_chat(id_msg, file_list, id_user, content, avatar_url, create_time, fullname);
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