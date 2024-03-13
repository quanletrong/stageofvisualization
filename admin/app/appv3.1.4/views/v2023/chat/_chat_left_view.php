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

<script>

    
</script>