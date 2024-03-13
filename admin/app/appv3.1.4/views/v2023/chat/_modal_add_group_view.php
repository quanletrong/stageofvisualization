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
                        if(kq.status) {
                            socket.emit('add-gchat', kq.data);
                        } else {
                            alert(kq.error)
                        }
                    }
                });
            });
        })
    </script>
</div>