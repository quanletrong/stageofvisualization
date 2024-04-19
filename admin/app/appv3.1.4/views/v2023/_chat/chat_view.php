<?php ?>
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
                        <div class="list-chat-user">
                            <?php foreach ($list_user_chat as $chat) { ?>
                                <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $chat['id_user'] ?>" onclick="click_left_chat_user('<?= $chat['id_user'] ?>')">
                                    <div style="width: 15%; max-width: 50px;">
                                        <img src="<?= $chat['avatar_url'] ?>" class="img-circle elevation-2 avatar" alt="User Image" style="width: 100%;object-fit: cover; aspect-ratio: 1;">
                                    </div>
                                    <div style="width: 85%; position: relative;">
                                        <div style="width: 100%; font-weight: 500;" class="fullname">
                                            <?= isIPV4($chat['id_user']) ?  '(Vãng lai - ' . $chat['id_user'] . ')' : $chat['fullname_user'] ?>
                                        </div>
                                        <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                                            <div class="text-truncate content" style="width: 80%; font-weight: <?= $chat['da_xem'] ? 300 : 600 ?>;"><?= $chat['content'] ?></div>
                                            <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;"><?= timeSince($chat['create_time']) ?></div>
                                        </div>

                                        <div style="position: absolute;right: 0px;top: 11px;color: red; display: none;" class="delete" onclick="ajax_delete_chat_user('<?= $chat['id_user'] ?>')">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if (count($list_user_chat) == 0) { ?>
                                <div class="mt-3 text-center alert_empty_chat" style="display: block;">Không có đoạn chat nào</div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-add-group" style="display: none" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Thêm nhóm</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="frm_user" method="post" action="chat/ajax_add_group">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Tên nhóm</label>
                                                <input type="text" class="form-control name_group" name="name_group" placeholder="Nhập tên nhóm">
                                            </div>

                                            <div class="form-group" data-select2-id="16">
                                                <label for="sapo">Thành viên</label>
                                                <div>
                                                    <select class="form-control select2 member_group" multiple="multiple" name="member_group[]" style="width: 100%;">
                                                        <?php foreach ($all_member as $id_user => $user) { ?>
                                                            <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

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

        let item_chat_active = $('.item-chat').first();

        <?php if ($chat_user != '') { ?>
            item_chat_active = $('#<?= $chat_user ?>');
        <?php } ?>

        item_chat_active.addClass('active')
        let id_user = item_chat_active.attr('id');

        let fullname = item_chat_active.find('.fullname').text();
        let avatar = item_chat_active.find('.avatar').attr('src');


        $('#chat_khach .fullname').text(fullname)
        $('#chat_khach .avatar').attr('src', avatar)
        if (id_user != '' && id_user !== undefined) {
            ajax_chat_list_by_user(id_user);
        }
    })

    function click_left_chat_user(chat_user) {

        let chat_item = $(`[id='${chat_user}']`);

        $('.item-chat').removeClass('active');
        chat_item.addClass('active')
        chat_item.find('.content').css('font-weight', 300)

        let fullname = chat_item.find('.fullname').text();
        let avatar = chat_item.find('.avatar').attr('src');

        $('#chat_khach .fullname').text(fullname)
        $('#chat_khach .avatar').attr('src', avatar)

        ajax_chat_list_by_user(chat_user)
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
                            $('#chat_khach').hide();
                        }

                        // xóa bên phải
                        let is_active = $(`[id='${chat_user}']`).hasClass('active');
                        if (is_active) {

                            let item_chat_active = $('.item-chat').first();
                            let id_user = item_chat_active.attr('id');
                            let fullname = item_chat_active.find('.fullname').text();
                            let avatar = item_chat_active.find('.avatar').attr('src');

                            item_chat_active.addClass('active')

                            $('#chat_khach .fullname').text(fullname)
                            $('#chat_khach .avatar').attr('src', avatar)
                            ajax_chat_list_by_user(id_user);
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

    // hứng chat của khách
    socket.on('update-chat-tong', data => {

        var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
        audio.play();

        let id_user = data.id_user;
        let chat_user = $(`[id='${data.id_user}']`);

        // đoạn chat cũ
        if (chat_user.length) {

            chat_user.find(`.content`).text(data.content);
            chat_user.find(`.content`).css('font-weight', 600)
            chat_user.find(`.time`).text(moment(data.create_time).fromNow());

            let isActiveRight = chat_user.hasClass('active');
            if (isActiveRight) {
                let new_html = html_item_chat(data);
                $('#chat_khach .list-chat')
                    .append(new_html)
                    .scrollTop($('#chat_khach .list-chat')[0].scrollHeight);

                $('#chat_khach .content_chat').val('').attr('rows', 2);
                $('#chat_khach .chat_list_attach').html('');
                $('#chat_khach .list-chat').scrollTop($('#chat_khach .list-chat')[0].scrollHeight);

                tooltipTriggerList('#chat_khach');
            }
        }

        // đoạn chat mới nội dung bên trái
        else {
            let html_new =
                `<div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="${id_user}" onclick="click_left_chat_user('${id_user}')">
                <div style="width: 15%; max-width: 50px;">
                    <img src="${data.avatar_url}" class="img-circle elevation-2 avatar" alt="User Image" style="width: 100%;object-fit: cover; aspect-ratio: 1;">
                </div>
                <div style="width: 85%; position: relative;">
                    <div style="width: 100%; font-weight: 500;" class="fullname">
                        ${_.isIPv4(data.id_user) ? `(Vãng lai - ${data.id_user})` : data.fullname_user}
                    </div>
                    <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                        <div class="text-truncate content" style="width: 80%; font-weight: 600;">${data.content}</div>
                        <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;">
                            ${moment(data.create_time).fromNow()}
                        </div>
                    </div>

                    <div style="position: absolute;right: -17px;top: 11px;color: red; display: none;" class="delete" onclick="ajax_delete_chat_user('${id_user}')">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>`

            $('#chat-left .list-chat-user').prepend(html_new);
            $('#chat-left .alert_empty_chat').hide();
        }
    })
</script>