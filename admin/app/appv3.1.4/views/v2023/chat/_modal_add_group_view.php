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
                <form id="frm_add_group" method="post" action="chat/ajax_add_group">
                    <div class="card-body">
                        <div class="form-group" data-select2-id="16">
                            <label for="sapo">Thành viên</label>
                            <div>
                                <select class="form-control select2 member_group" multiple="multiple" name="member_group[]" style="width: 100%;">
                                    <?php foreach ($all_member as $id_user => $user) { ?>
                                        <?php if ($id_user != $cur_uid) { ?>
                                            <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Tên đoạn chat</label>
                            <input type="text" class="form-control name_group" name="name_group" placeholder="Tên đoạn chat">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-lg btn-danger">Thêm đoạn chat</button>
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

            $('#modal-add-group').on('shown.bs.modal', function() {
                $('#modal-add-group .member_group ').val(null).trigger("change");
            })

            $("#frm_add_group").submit(function(e) {

                e.preventDefault(); // avoid to execute the actual submit of the form.

                var form = $(this);
                var actionUrl = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data) {
                        let kq = JSON.parse(data);
                        if (kq.status) {

                            let id_gchat = kq.data.id_gchat;

                            $("#modal-add-group").modal("hide");

                            socket.emit('add-gchat', kq.data);

                            // 500ms sau update trái phải
                            setTimeout(() => {
                                // update trái
                                $('.item-chat').removeClass('active');
                                $(`#${id_gchat}`).addClass('active');
                                $(`#${id_gchat}`).parent().prepend($(`#${id_gchat}`));
                                $(`#${id_gchat} .content`).css('font-weight', 300)

                                // update phải
                                let fullname = $(`#${id_gchat} .fullname`).text();
                                let avatar = $(`#${id_gchat} .div-avatar`).html();
                                $('#chat_right .fullname').text(fullname)
                                $('#chat_right .div-avatar').html(avatar)
                                $('#chat_right').show()
                                ajax_list_msg_by_group(id_gchat);

                                // check if mobile: an ben trai
                                _.isMobile() ? $('#chat-left').hide() : $('#chat-left').show();
                                // end check if mobile
                            }, 500);

                        } else {
                            alert(kq.error)
                        }
                    }
                });
            });
        })
    </script>
</div>