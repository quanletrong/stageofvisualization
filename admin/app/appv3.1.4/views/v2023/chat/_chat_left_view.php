<style>
    .dropdown-toggle::after {
        content: none;
    }

    .dropleft .dropdown-toggle::before {
        content: none;
    }
</style>
<div id="chat-left" class="content" style="background: white; border-radius: 5px; padding: 5px;">
    <div class="header">
        <div style="display: flex; justify-content: space-between;">
            <h3>Đoạn chat</h3>
            <?php if ($role == ADMIN) { ?>
                <div id="btn-add-group" title="Thêm nhóm" data-toggle="modal" data-target="#modal-add-group"><i class="fas fa-plus-circle"></i></div>
            <?php } ?>
        </div>

        <div class="mb-2">
            <input type="text" placeholder="Tìm kiếm đoạn chat" class="form-control" id="searchInput">
        </div>
    </div>

    <div class="list-group" style="overflow-x: hidden; overflow-y: auto; padding-right:5px">
        <?php foreach ($list_group['list'] as $id_group => $group) { ?>
            <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-chat" id="<?= $id_group ?>">

                <?php $num_member = count($list_group['members'][$id_group]); ?>

                <div class="div-avatar" onclick="onclick_el_gchat('<?= $id_group ?>')">

                    <?php if ($num_member > 1) { ?>
                        <div style="min-width: 50px; width: 50px; aspect-ratio: 1; border-radius: 50%; border: 1px solid #dedede; display: flex; align-items: center; justify-content: center; color: white; background-color: #4caf50; font-size: 1.23rem; position: relative; overflow: hidden;">
                            <div style="width: 4ch; overflow: hidden; white-space: nowrap; padding: 0 3px; text-align: center"><?= get_short_name_group($group['name']) ?></div>
                            <div style="font-size: 10px;position: absolute;bottom: 0;width: 100%;text-align: center;background: rgba(128, 128, 128, 0.7);">TEAM</div>
                        </div>
                    <?php } else { ?>
                        <?php $user = array_shift($list_group['members'][$id_group]); ?>
                        <div style="min-width: 50px; width: 50px; aspect-ratio: 1; border-radius: 50%; border: 1px solid #dedede; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.23rem;">
                            <img src="<?= $user['avatar_url'] ?>" class="img-circle avatar" alt="<?= $user['fullname'] ?>" style="object-fit: cover; aspect-ratio: 1; width: 100%;">
                        </div>
                    <?php } ?>


                </div>
                <div style="flex: 1 1 0%; position: relative;">
                    <div style="font-weight: 500; display: grid;grid-template-columns: 1fr" onclick="onclick_el_gchat('<?= $id_group ?>')">
                        <span class="fullname text-truncate"><?= $group['name'] ?></span>
                    </div>
                    <div style="display: grid;grid-template-columns: 4fr 1fr;gap: 15px;" onclick="onclick_el_gchat('<?= $id_group ?>')">
                        <?php
                        $da_xem = '300';
                        $msg_newest = [];
                        if (isset($list_group['msg_newest'][$id_group])) {
                            $da_xem = $list_group['msg_newest'][$id_group]['seen_time'] != null ? '300' : '600';
                            $msg_newest = $list_group['msg_newest'][$id_group];
                        }
                        ?>
                        <?php if (count($msg_newest)) { ?>
                            <div class="text-truncate content" style="font-weight: <?= $da_xem ?>;">
                                <?= $msg_newest['content'] !== '' ? $msg_newest['content'] : '<i>File phương tiện</i>' ?>
                            </div>
                            <div class="text-truncate time" style="font-weight: 300; font-size: 0.75rem; text-align: right;" title="<?= $msg_newest['create_time'] ?>">&nbsp;</div>
                        <?php } ?>
                    </div>


                    <div style="position: absolute;right: 0px;top: 0px; display: none;" class="option">
                        <div class="dropdown dropleft">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 0 20px;">
                                <span class="text-secondary">
                                    <i class="fas fa-ellipsis-h" style="font-size: 1.5rem;"></i>
                                </span>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(33px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-edit-group" data-group="<?= $id_group ?>">
                                    <span class="text-secondary">Xem thông tin</span>
                                </button>
                                <?php if ($role == ADMIN) { ?>
                                    <button class="dropdown-item" type="button" onclick="ajax_delete_gchat('<?= $id_group ?>')">
                                        <span class="text-secondary">Xóa nhóm này</span>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="mt-3 text-center alert_empty_chat" style="display: <?= count($list_group['list']) ? 'none' : 'block' ?>;">Không có đoạn chat nào</div>
    </div>
    <div style="    width: 100%; height: 30px; display: flex; align-items: flex-end; justify-content: center;"><a href="">Back home</a></div>

</div>
<!-- MODAL ADD GROUP -->
<?php if ($role == ADMIN) {
    $this->load->view('v2023/chat/_modal_add_group_view.php');
} ?>

<!-- MODAL EDIT GROUP -->
<?php $this->load->view('v2023/chat/_modal_edit_group_view.php'); ?>

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

            let new_height = windown_height - header - 30 - 30;
            $('#chat-left .list-group').css('height', new_height + 'px');
        }
    })

    // Xử lý tìm kiếm đoạn chat
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase();
        var items = document.querySelectorAll('.list-group > .item-chat');

        items.forEach(function(item) {
            var fullname = item.querySelector('span.fullname').textContent.toLowerCase();
            item.style.display = fullname.includes(searchValue) ? 'flex' : 'none';
        });
    });
</script>
