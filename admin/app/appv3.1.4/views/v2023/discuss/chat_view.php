<?php ?>
<script src="js/v2023/moment_2.29.4.min.js"></script>
<style>
    .item-chat:hover {
        background-color: #f0f0f0;
    }

    .item-chat.active {
        background-color: #f0f0f0;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Đoạn chat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Đoạn chat</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div style="overflow-x: hidden; overflow-y: auto; height: 80vh; background: white; border-radius: 5px; padding: 5px;">

                        <?php foreach ($list_user_chat as $chat) { ?>
                            <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $chat['id_user']?>" onclick="ajax_chat_list_by_user('<?= $chat['id_user'] ?>')">
                                <div style="width: 15%; max-width: 50px;">
                                    <img src="<?= $chat['avatar_url'] ?>" class="img-circle elevation-2 avatar" alt="User Image" style="width: 100%;">
                                </div>
                                <div style="width: 85%;">
                                    <div style="width: 100%; font-weight: 500;" class="fullname">
                                        <?= isIPV4($chat['id_user']) ?  '(Vãng lai - '.$chat['id_user'].')' : $chat['fullname_user'] ?>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;gap: 15px;width: 100%;">
                                        <div class="text-truncate content" style="width: 80%; font-weight: 300;"><?= $chat['content'] ?></div>
                                        <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;"><?= timeSince($chat['create_time']) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
                <div class="col-md-8" id="chat-right">
                    <?php $this->load->view('v2023/discuss/_chat_right_view.php'); ?>
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


        $('#discuss_khach .fullname').text(fullname)
        $('#discuss_khach .avatar').attr('src', avatar)
        if (id_user != '') {
            ajax_chat_list_by_user(id_user);
        }

        $('.item-chat').click(function() {
            $('.item-chat').removeClass('active');
            $(this).addClass('active')
            $(this).find('.content').css('font-weight', 300)

            let fullname = $(this).find('.fullname').text();
            let avatar = $(this).find('.avatar').attr('src');

            $('#discuss_khach .fullname').text(fullname)
            $('#discuss_khach .avatar').attr('src', avatar)
        })
    })

    // hứng chat của khách
    socket.on('update-chat-tong', data => {

        var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
        audio.play();

        let chat_user = data.id_user;

        $(`[id='${chat_user}'] .content`).text(data.content);
        $(`[id='${chat_user}'] .content`).css('font-weight', 600)

        $(`[id='${chat_user}'] .time`).text(moment(data.create_time).fromNow());

        let isActive = $(`[id='${chat_user}']`).hasClass('active');
        if (isActive) {

            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();

            let new_html = html_item_chat(data);
            $('#discuss_khach .list-chat')
                .append(new_html)
                .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

            $('#discuss_khach .content_discuss').val('').attr('rows', 2);
            $('#discuss_khach .chat_list_attach').html('');
            $('#discuss_khach .list-chat').scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

            tooltipTriggerList('#discuss_khach');
        }
    })
</script>