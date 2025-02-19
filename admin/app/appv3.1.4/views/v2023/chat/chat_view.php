<?php ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancyapps-ui/5.0.36/fancybox/fancybox.umd.min.js" integrity="sha512-VNk0UJk87TUyZyWXUFuTk6rUADFyTsVpVGaaFQQIgbEXAMAdGpYaFWmguyQzEQ2cAjCEJxR2C++nSm0r2kOsyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancyapps-ui/5.0.36/fancybox/fancybox.min.css" integrity="sha512-s4DOVHc73MjMnsueMjvJSnYucSU3E7WF0UVGRQFd/QDzeAx0D0BNuAX9fbZSLkrYW7V2Ly0/BKHSER04bCJgtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .main-footer {
        display: none;
    }

    .item-chat {
        transition: all 0.2s ease-in-out;
        border-radius: 10px;
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
        <div class="row">
            <div class="col-md-4">
                <?php $this->load->view('v2023/chat/_chat_left_view.php'); ?>
            </div>
            <div class="col-md-8">
                <?php $this->load->view('v2023/chat/_chat_right_view.php'); ?>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script>
    let page_msg = 1;
    let on_load_page_msg = 1;

    $(document).ready(function() {
        // ân header menu khi vao trang chat
        $('.main-header').hide()

        // setInterval thời gian gửi tin nhắn
        setInterval(() => {
            $('#chat-left .time, #chat_right .time:first').each(function(i, obj) {
                let datetime = $(this).attr('title');
                if (moment(datetime).fromNow() !== $(this).text()) {
                    $(this).html(moment(datetime).fromNow());
                }
            });

        }, 1000);

    })

    $(window).resize(function() {

        //  thay 
        if (_.isMobile()) {
            $('#chat_right').hide();
            $('#chat-left').show();
        } else {
            $('#chat_right').show();
            $('#chat-left').show();
        }
    });

    function onclick_el_gchat(id_group) {

        // ben trai
        let chat_item = $(`[id='${id_group}']`);
        $('.item-chat').removeClass('active');
        chat_item.addClass('active')
        chat_item.find('.content').css('font-weight', 300)

        // ben phai
        let fullname = chat_item.find('.fullname').text();
        let avatar = chat_item.find('.div-avatar').html();
        let dropdown = chat_item.find('.dropdown').html();
        $('#chat_right .fullname').text(fullname);
        $('#chat_right .div-avatar').html(avatar);
        $('#chat_right .dropdown').html(dropdown);
        $('#chat_right').show();

        // reset ve 1
        page_msg = 1;
        on_load_page_msg = 1;
        $('#chat_right .list-chat').html('');
        $('#chat_right .content_chat').val('');
        $('#chat_right .chat_reply').html('');
        $('#chat_right .chat_list_attach').html('');

        ajax_list_msg_by_group(id_group);

        // check if mobile: an ben trai, hien ben phai
        if (_.isMobile()) {
            $('#chat_right').show();
            $('#chat-left').hide();
        }

        // pushState
        window.history.pushState('chat', 'chat', `/admin/chat/index/${id_group}`);
    }

    function ajax_delete_gchat(id_gchat) {

        if (confirm('Are you sure you want to delete this chat?')) {

            // reset ve 1
            page_msg = 1;
            on_load_page_msg = 1;

            $.ajax({
                url: `chat/ajax_delete_gchat/${id_gchat}`,
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);

                    if (kq.status) {
                        socket.emit('delete-gchat', kq.data);
                        $('#chat-left').show();
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

        if (Object.keys(members).length > 1) {
            avatar = `<div style="min-width: 50px; width: 50px; aspect-ratio: 1; border-radius: 50%; border: 1px solid #dedede; display: flex; align-items: center; justify-content: center; color: white; background-color: #4caf50; font-size: 1.23rem;position: relative; overflow: hidden;">
                <div style="width: 4ch; overflow: hidden; white-space: nowrap; padding: 0 3px; text-align: center">G</div>
                <div style="font-size: 10px;position: absolute;bottom: 0;width: 100%;text-align: center;background: rgba(128, 128, 128, 0.7);">TEAM</div>
            </div>`
        } else {
            const [mem_id, mem] = Object.entries(members)[0];
            avatar =
                `<div style="min-width: 50px; width: 50px; aspect-ratio: 1; border-radius: 50%; border: 1px solid #dedede; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.23rem;">
                <img src="${mem.avatar_url}" class="img-circle avatar" alt="${mem.fullname}" style="object-fit: cover; aspect-ratio: 1; width: 100%">
            </div>`;
        }
        // end tạo avatar

        // tạo element gchat cột bên trái
        let html_new =
            `<div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="${id_gchat}">
                <div class="div-avatar" onclick="onclick_el_gchat('${id_gchat}')">
                    ${avatar}
                </div>
                <div style="flex: 1 1 0%; position: relative;">
                    <div style="font-weight: 500; display: grid;grid-template-columns: 1fr" onclick="onclick_el_gchat('${id_gchat}')">
                        <span class="fullname text-truncate">${name_gchat}</span>
                    </div>                    
                    <div style="display: grid;grid-template-columns: 4fr 1fr;gap: 15px;" onclick="onclick_el_gchat('${id_gchat}')">
                        <div class="text-truncate content" style="font-weight: 600;">${content}</div>
                        <div class="text-truncate time" style="font-weight: 300; font-size: 0.75rem; text-align: right;" title="${create_time}">&nbsp;</div>
                    </div>

                    <div style="position: absolute;right: 0px;top: 0px; display: none;" class="option">
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
            action_by,
            reply
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
            el_gchat_left.find(`.content`).css('font-weight', action_by == <?= $cur_uid ?> ? 300 : 600)
            el_gchat_left.find(`.time`).attr('title', create_time);
            el_gchat_left.parent().prepend(el_gchat_left);

            // update bên phải nếu nhóm đang active
            let isActiveRight = el_gchat_left.hasClass('active');
            if (isActiveRight) {
                let new_html = html_item_chat(id_gchat, id_msg, file_list, id_user, content, avatar_url, create_time, fullname, reply);
                $('#chat_right .list-chat').prepend(new_html);
                $('#chat_right .time').html('');
                $('#chat_right .time:first').html('vài giây trước');
                gom_avatar_fullname_time_gan_nhau();
                tooltipTriggerList('#chat_right');
                set_vh_list_chat();

                if (action_by == <?= $cur_uid ?>) {
                    let chat_right = $("#chat_right").outerHeight();
                    $('#chat_right .list-chat').scrollTop($('#chat_right .list-chat')[0].scrollHeight);
                }
            }
        }
    })
</script>