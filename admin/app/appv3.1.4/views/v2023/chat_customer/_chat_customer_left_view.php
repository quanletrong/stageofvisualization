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
            <h3>Đoạn chat với khách hàng</h3>
        </div>

        <div class="mb-2">
            <input type="text" placeholder="Tìm kiếm đoạn chat" class="form-control" id="searchInput">
        </div>
    </div>

    <div class="list-room" style="overflow-x: hidden; overflow-y: auto; padding-right:5px">
        <?php foreach ($room_list as $id_room => $room) { ?>
            <div style="display: flex;gap: 5px;width: 100%; cursor: pointer; align-items: center; padding:5px; margin-bottom: 2px;" class="item-room" id="<?= $id_room ?>">

                <div class="div-avatar" onclick="onclick_room('<?= $id_room ?>')">
                    <div style="min-width: 50px; width: 50px; aspect-ratio: 1; border-radius: 50%; border: 1px solid #dedede; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.23rem;">
                        <img src="<?= $room['avatar_url'] ?>" class="img-circle avatar" style="object-fit: cover; aspect-ratio: 1; width: 100%;">
                    </div>
                </div>

                <div style="flex: 1 1 0%; position: relative;">
                    <div style="font-weight: 500; display: grid;grid-template-columns: 1fr" onclick="onclick_room('<?= $id_room ?>')">
                        <span class="fullname text-truncate"><?= $room['fullname'] ?></span>
                    </div>
                    <div style="display: grid;grid-template-columns: 4fr 1fr;gap: 15px;" onclick="onclick_room('<?= $id_room ?>')">
                        <?php $da_xem = $room['id_user_seen'] === null ? '600' : '300'; ?>
                        <div class="text-truncate content" style="font-weight: <?= $da_xem ?>;">
                            <?= $room['newest_content'] !== '' ? $room['newest_content'] : '<i>File phương tiện</i>' ?>
                        </div>
                        <div class="text-truncate time" style="font-weight: 300; font-size: 0.75rem; text-align: right;" title="<?= $room['newst_created_at'] ?>">&nbsp;</div>
                    </div>

                    <div style="position: absolute;right: 0px;top: 0px; display: none;" class="option">
                        <div class="dropdown dropleft">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 0 20px;">
                                <span class="text-secondary">
                                    <i class="fas fa-ellipsis-h" style="font-size: 1.5rem;"></i>
                                </span>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(33px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <button
                                    type="button"
                                    class="dropdown-item"
                                    data-toggle="modal"
                                    data-target="#modal-edit-room"
                                    data-room="<?= htmlspecialchars(json_encode($room), ENT_QUOTES, 'UTF-8') ?>">
                                    <span class="text-secondary">Xem thông tin</span>
                                </button>
                                <button class="dropdown-item" type="button" onclick="ajax_delete_room('<?= $id_room ?>')">
                                    <span class="text-secondary">Xóa nhóm này</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="mt-3 text-center alert_empty_chat" style="display: <?= count($room_list) ? 'none' : 'block' ?>;">Không có đoạn chat nào</div>
    </div>
    <div style="width: 100%; height: 30px; display: flex; align-items: flex-end; justify-content: center;"><a href="">Back home</a></div>

</div>

<!-- MODAL EDIT room -->
<?php $this->load->view('v2023/chat_customer/_modal_edit_room_view.php'); ?>

<script>
    $(document).ready(function() {
        set_vh_list_room();

        $(window).resize(function() {
            set_vh_list_room();
        });

        function set_vh_list_room() {
            let windown_height = $(window).height();
            let header = $('#chat-left .header').outerHeight();

            let new_height = windown_height - header - 30 - 30;
            $('#chat-left .list-room').css('height', new_height + 'px');
        }
    })

    // Xử lý tìm kiếm đoạn chat
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase();
        var items = document.querySelectorAll('.list-room > .item-room');

        items.forEach(function(item) {
            var fullname = item.querySelector('span.fullname').textContent.toLowerCase();
            item.style.display = fullname.includes(searchValue) ? 'flex' : 'none';
        });
    });
</script>