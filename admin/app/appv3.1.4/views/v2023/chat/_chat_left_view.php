<style>
    .dropdown-toggle::after {
        content: none;
    }
</style>
<div id="chat-left" class="content" style="background: white; border-radius: 5px; padding: 5px;">
    <div class="header">
        <div style="display: flex; justify-content: space-between;">
            <h3>Đoạn chat</h3>
            <?php if($role == ADMIN) { ?>
                <div id="btn-add-group" title="Thêm nhóm" data-toggle="modal" data-target="#modal-add-group"><i class="fas fa-plus-circle"></i></div>
            <?php } ?>
        </div>

        <div class="mb-2">
            <input type="text" placeholder="Tìm kiếm thành viên" class="form-control">
        </div>
    </div>

    <div class="list-group" style="overflow-x: hidden; overflow-y: auto; padding-right:5px">
        <?php foreach ($list_group['list'] as $id_group => $group) { ?>
            <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $id_group ?>" onclick="onclick_el_gchat('<?= $id_group ?>')">
                <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;">

                    <?php $num_member = count($list_group['members'][$id_group]); ?>
                    <?php $lst_member = $list_group['members'][$id_group]; ?>

                    <?php $index = 1; ?>
                    <?php foreach ($lst_member as $user) { ?>

                        <?php if ($index <= 3) { ?>
                            <img src="<?= $user['avatar_url'] ?>" class="img-circle border avatar" alt="<?= $user['fullname'] ?>" style="width: <?= $num_member == 1 ? '100%' : '50%' ?>; object-fit: cover; aspect-ratio: 1;">
                        <?php } ?>

                        <?php if ($index == 4) { ?>
                            <div class="border" style="border-radius: 25px;font-size: 0.8rem;background: white;width: 50%;text-align: center;color: gray;">+<?php echo ($num_member - 3) ?></div>
                        <?php } ?>

                        <?php $index++; ?>

                    <?php } ?>

                </div>
                <div style="width: 85%; position: relative;">
                    <div style="width: 80%; font-weight: 500;" class="fullname text-truncate">
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
                            <div class="text-truncate content" style="width: 80%; font-weight: <?= $da_xem ?>;"><?= $msg_newest['content'] !== '' ? $msg_newest['content'] : '<i>File phương tiện</i>' ?></div>
                            <div class="time" style="width: 20%; font-weight: 300; font-size: 0.75rem; text-align: right;" title="<?= $msg_newest['create_time'] ?>">&nbsp;</div>
                        <?php } ?>
                    </div>

                    
                    <div style="position: absolute;right: 0px;top: 11px;color: red; display: none; background-color: #f0f0f0;" class="option">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 0 10px;">
                                <span class="text-secondary">
                                    <i class="fas fa-ellipsis-h" style="font-size: 1.5rem;"></i>
                                </span>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(33px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-edit-group" data-group="<?=$id_group?>">
                                    <span class="text-secondary">Xem thông tin</span>
                                </button>
                                <?php if($role == ADMIN) { ?>
                                    <button class="dropdown-item" type="button" onclick="ajax_delete_chat_user('<?= $id_group ?>')">
                                        <span class="text-secondary">Xóa nhóm này</span>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (count($list_group['list']) == 0) { ?>
            <div class="mt-3 text-center alert_empty_chat" style="display: block;">Không có đoạn chat nào</div>
        <?php } ?>
    </div>

</div>
<!-- MODAL ADD GROUP -->
<?php if($role == ADMIN) { $this->load->view('v2023/chat/_modal_add_group_view.php');}?>

<!-- MODAL EDIT GROUP -->
<?php  $this->load->view('v2023/chat/_modal_edit_group_view.php');?>

<script>
    $(document).ready(function() {

        // Sắp xếp nhóm có tin nhắn mới nhất lên đầu
        let group_id_sort = <?= json_encode(array_keys($list_group['msg_newest'])) ?>;
        group_id_sort.reverse();
        group_id_sort.map(function(value, index, array) {
            $(`#${value}`).parent().prepend($(`#${value}`));
        });
        // END sắp xếp nhóm có tin nhắn mới nhất lên đầu

        set_vh_list_group();

        $(window).resize(function() {
            set_vh_list_group();
        });

        function set_vh_list_group() {
            let windown_height = $(window).height();
            let header = $('#chat-left .header').outerHeight();

            let new_height = windown_height - header - 30;
            $('#chat-left .list-group').css('height', new_height + 'px');
        }
    })
</script>