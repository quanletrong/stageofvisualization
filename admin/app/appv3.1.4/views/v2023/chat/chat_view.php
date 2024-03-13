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
                <div class="col-md-4" id="chat-left">
                    <div style="overflow-x: hidden; overflow-y: auto; height: 93vh; background: white; border-radius: 5px; padding: 5px;">
                        <div style="display: flex; justify-content: space-between;">
                            <h3>Đoạn chat</h3>
                            <div id="btn-add-group" title="Thêm nhóm" data-toggle="modal" data-target="#modal-add-group"><i class="fas fa-plus-circle"></i></div>
                        </div>

                        <div class="mb-2">
                            <input type="text" placeholder="Tìm kiếm thành viên" class="form-control">
                        </div>
                        <div class="list-group">
                            <?php foreach ($list_group['list'] as $id_group => $group) { ?>
                                <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $id_group ?>" onclick="ajax_list_msg('<?= $id_group ?>')">
                                    <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;">
                                    
                                        <?php $num_member = count($list_group['member'][$id_group]); ?>
                                        <?php $lst_member = $list_group['member'][$id_group]; ?>

                                        <?php $index = 1; ?>
                                        <?php foreach ($lst_member as $user) { ?>
                                            <?php if ($index <= 4) { ?>
                                                <img src="<?= $user['avatar_url'] ?>" class="img-circle elevation-2 avatar" alt="<?= $user['fullname'] ?>" style="width: <?= $num_member == 1 ? '100%' : '50%' ?>; object-fit: cover; aspect-ratio: 1;">
                                                <?php $index++; ?>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                    <div style="width: 85%; position: relative;">
                                        <div style="width: 100%; font-weight: 500;" class="fullname text-truncate">
                                            <?= $group['name'] ?>
                                        </div>
                                        <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                                            <?php
                                            $da_xem = '300';
                                            $msg_newest = [];
                                            if (isset($list_group['msg_newest'][$id_group])) {
                                                $da_xem = $list_group['msg_newest'][$id_group]['da_xem'] ? '300' : '600';
                                                $msg_newest = $list_group['msg_newest'][$id_group];
                                            }
                                            ?>
                                            <?php if (count($msg_newest)) { ?>
                                                <div class="text-truncate content" style="width: 80%; font-weight: <?= $da_xem ?>;"><?= $msg_newest['content'] ?></div>
                                                <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;"><?= timeSince($msg_newest['create_time']) ?></div>
                                            <?php } ?>
                                        </div>

                                        <div style="position: absolute;right: 0px;top: 11px;color: red; display: none;" class="delete" onclick="ajax_delete_chat_user('<?= $id_group ?>')">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if (count($list_group['list']) == 0) { ?>
                                <div class="mt-3 text-center alert_empty_chat" style="display: block;">Không có đoạn chat nào</div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-add-group" style="display: none" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Thêm đoạn chat</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="frm_user" method="post" action="chat/ajax_add_group">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Tên gợi nhớ đoạn chat</label>
                                                <input type="text" class="form-control name_group" name="name_group" placeholder="Tên gợi nhớ">
                                            </div>

                                            <div class="form-group" data-select2-id="16">
                                                <label for="sapo">Thành viên trong đoạn chat</label>
                                                <div>
                                                    <select class="form-control select2 member_group" multiple="multiple" name="member_group[]" style="width: 100%;">
                                                        <?php foreach ($all_member as $id_user => $user) { ?>
                                                            <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="name">Lời nhắn đầu tiên tới đoạn chat</label>
                                                <input type="text" class="form-control msg_newest" name="msg_newest" placeholder="Lời nhắn đầu tiên" value="Xin chào mọi người">
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer d-flex justify-content-center">
                                            <button type="submit" class="btn btn-lg btn-danger">Lưu lại</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->

                        <script>
                            $(function() {
                                $('#modal-add-group .member_group').select2({});
                            })
                        </script>
                    </div>
                </div>
                <div class="col-md-8" id="chat-right">
                    <?php $this->load->view('v2023/chat/_chat_right_view.php'); ?>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(document).ready(function() {

        // Sắp xếp nhóm có tin nhắn mới nhất lên đầu
        let group_id_sort = <?= json_encode(array_keys($list_group['msg_newest'])) ?>;
        group_id_sort.reverse();
        group_id_sort.map(function(value, index, array) {
            $(`#${value}`).parent().prepend($(`#${value}`));
        });
        // END sắp xếp nhóm có tin nhắn mới nhất lên đầu

        let item_group_active = $('.item-chat').first();

        <?php if ($chat_user != '') { ?>
            item_group_active = $('#<?= $chat_user ?>');
        <?php } ?>

        item_group_active.addClass('active')
        let id_group = item_group_active.attr('id');

        let fullname = item_group_active.find('.fullname').text();
        let avatar = item_group_active.find('.div-avatar').html();

        $('#chat_right .fullname').text(fullname)
        $('#chat_right .div-avatar').html(avatar)
        if (id_group != '' && id_group !== undefined) {
            ajax_list_msg_by_group(id_group);
        }
    })

    function ajax_list_msg(id_group) {

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

                        // xóa bên trái
                        $(`[id='${chat_user}']`).remove();

                        // hiển thị empty nếu có
                        if ($('.item-chat').length == 0) {
                            $('#chat-left .alert_empty_chat').show();
                            $('#chat_right').hide();
                        }

                        // xóa bên phải
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

    function ajax_list_gchat_left() {

    }
    // hứng chat của khách
    socket.on('update-chat-tong', data => {

        var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
        audio.play();

        let id_gchat = data.id_gchat;
        let el_gchat_left = $(`[id='${data.id_gchat}']`);

        // đoạn chat cũ
        if (el_gchat_left.length) {
            el_gchat_left.find(`.content`).text(data.content);
            el_gchat_left.find(`.content`).css('font-weight', 600)
            el_gchat_left.find(`.time`).text(moment(data.create_time).fromNow());

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
            }
        }

        // đoạn chat mới nội dung bên trái
        else {
            let html_new =
                `<div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="${id_gchat}" onclick="ajax_list_msg('${id_gchat}')">
                <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;">
                    <img src="${data.avatar_url}" class="img-circle elevation-2 avatar" alt="User Image" style="width: 100%;object-fit: cover; aspect-ratio: 1;">
                </div>
                <div style="width: 85%; position: relative;">
                    <div style="width: 100%; font-weight: 500;" class="fullname">
                        ${_.isIPv4(data.id_gchat) ? `(Vãng lai - ${data.id_gchat})` : data.fullname_user}
                    </div>
                    <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                        <div class="text-truncate content" style="width: 80%; font-weight: 600;">${data.content}</div>
                        <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;">
                            ${moment(data.create_time).fromNow()}
                        </div>
                    </div>

                    <div style="position: absolute;right: -17px;top: 11px;color: red; display: none;" class="delete" onclick="ajax_delete_chat_user('${id_gchat}')">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>`

            $('#chat-left .list-group').prepend(html_new);
            $('#chat-left .alert_empty_chat').hide();
        }
    })
</script>