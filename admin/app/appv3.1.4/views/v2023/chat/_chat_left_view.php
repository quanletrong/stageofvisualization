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
            <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $id_group ?>" onclick="onclick_el_gchat('<?= $id_group ?>')">
                <div class="div-avatar" style="width: 15%; width: 50px; height:50px; display: flex; flex-wrap: wrap; align-content: center;">

                    <?php $num_member = count($list_group['member'][$id_group]); ?>
                    <?php $lst_member = $list_group['member'][$id_group]; ?>

                    <?php $index = 1; ?>
                    <?php foreach ($lst_member as $user) { ?>

                        <?php if ($index <= 3) { ?>
                            <img src="<?= $user['avatar_url'] ?>" class="img-circle elevation-2 avatar" alt="<?= $user['fullname'] ?>" style="width: <?= $num_member == 1 ? '100%' : '50%' ?>; object-fit: cover; aspect-ratio: 1;">
                        <?php } ?>
                        
                        <?php if ($index == 4) { ?>
                            <div style="border-radius: 25px;font-size: 0.8rem;box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23)!important;background: white;width: 50%;text-align: center;color: gray;">+<?php echo ($num_member - 3)?></div>
                        <?php } ?>
                        
                        <?php $index++; ?>

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

<!-- MODAL ADD GROUP -->
<?php $this->load->view('v2023/chat/_modal_add_group_view.php'); ?>

<script>
    $(document).ready(function() {

        // Sắp xếp nhóm có tin nhắn mới nhất lên đầu
        let group_id_sort = <?= json_encode(array_keys($list_group['msg_newest'])) ?>;
        group_id_sort.reverse();
        group_id_sort.map(function(value, index, array) {
            $(`#${value}`).parent().prepend($(`#${value}`));
        });
        // END sắp xếp nhóm có tin nhắn mới nhất lên đầu

    })
</script>