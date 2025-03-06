<div class="modal fade" id="modal-edit-room" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin khách hàng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column flex-md-row" style="gap:20px">
                    <div style="display: flex; gap:20px; flex-direction: column;">
                        <div style="display: flex; gap: 20px; align-items: center;">
                            <div style="width: 150px; font-weight: bold;">Tài khoản</div>
                            <div class="username"></div>
                        </div>

                        <div style="display: flex; gap: 20px; align-items: center;">
                            <div style="width: 150px;font-weight: bold">Tên khách hàng</div>
                            <div class="fullname"></div>
                        </div>

                        <div style="display: flex; gap: 20px; align-items: center;">
                            <div style="width: 150px;font-weight: bold">Phone number</div>
                            <div class="phone"></div>
                        </div>

                        <div style="display: flex; gap: 20px; align-items: center;">
                            <div style="width: 150px;font-weight: bold">Email</div>
                            <div class="email"></div>
                        </div>
                    </div>
                    <div style="text-align: center; width: 100%;">
                        <img src="" class="img-circle avatar" style="object-fit: cover; aspect-ratio: 1; width: 150px; border: 2px solid #F5F5F5;">
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <script>
        $(function() {
            $('#modal-edit-room .member_room').select2({});

            $('#modal-edit-room').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var room = button.data('room');

                $("#modal-edit-room .username").html(room.username);
                $("#modal-edit-room .fullname").html(room.fullname);
                $("#modal-edit-room .email").html(room.email);
                $("#modal-edit-room .phone").html(room.phone);
                $("#modal-edit-room .avatar").attr('src', room.avatar_url);
            })

            $("#frm_edit_room").submit(function(e) {

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
                            $("#modal-edit-room").modal("hide");
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