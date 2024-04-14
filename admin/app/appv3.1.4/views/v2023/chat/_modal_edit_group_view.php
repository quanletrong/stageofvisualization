<div class="modal fade" id="modal-edit-group" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin đoạn chat</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_edit_group" method="post" action="chat/ajax_edit_group">
                    <div class="card-body">
                        <div class="form-group" data-select2-id="16">
                            <label for="sapo">Thành viên</label>
                            <div>
                                <select class="form-control select2 member_group" multiple="multiple" name="member_group[]" style="width: 100%;" <?=$role == ADMIN ? '' : 'disabled' ?> >
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
                            <input type="text" class="form-control name_group" name="name_group" placeholder="Tên đoạn chat" <?=$role == ADMIN ? '' : 'disabled' ?>>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <?php if($role == ADMIN) { ?>
                        <div class="card-footer d-flex justify-content-center">
                            <input type="hidden" name="id_group" class="id_group" value="">
                            <button type="submit" class="btn btn-lg btn-danger">Cập nhật</button>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <script>
        $(function() {
            $('#modal-edit-group .member_group').select2({});

            $('#modal-edit-group').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var id_group = button.data('group');

                $.ajax({
                    url: `chat/ajax_modal_group_info/${id_group}`,
                    success: function(data) {
                        let kq = JSON.parse(data);
                        if (kq.status) {

                            let members = kq.data.members;
                            let name = kq.data.name;

                            $('#frm_edit_group .member_group').val(members);
                            $('#frm_edit_group .member_group').trigger('change');

                            $('#frm_edit_group .name_group').val(name);
                            $('#frm_edit_group .id_group').val(id_group);
                        } else {
                            alert('Lỗi:' + kq.error)
                        }
                    }
                });
            })

            $("#frm_edit_group").submit(function(e) {

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
                            $("#modal-edit-group").modal("hide");
                            socket.emit('edit-gchat', kq.data);
                        } else {
                            alert(kq.error)
                        }
                    }
                });
            });
        })
    </script>
</div>