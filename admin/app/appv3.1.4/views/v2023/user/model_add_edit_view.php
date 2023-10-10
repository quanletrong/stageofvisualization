<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-user" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_user" method="post" action="<?= site_url('user') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_user" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">

                                <div class="form-group">
                                    <label for="name">Code user</label>
                                    <input type="text" class="form-control" id="code_user" name="code_user" placeholder="Nhập code user">
                                </div>

                                <div class="form-group">
                                    <label for="name"> <span class="text-red">*</span> Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên tài khoản">
                                </div>

                                <div class="form-group">
                                    <label for="name"> <span class="text-red">*</span> Fullname</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập Fullname">
                                </div>

                                <div class="form-group">
                                    <label for="name"> <span class="text-red">*</span> Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập Phone">
                                </div>

                                <div class="form-group">
                                    <label for="name"> <span class="text-red">*</span> Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Nhập Email">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">

                                <div class="mb-1">
                                    <label>Trạng thái tài khoản <small>ON đang hoạt động - OFF ngừng hoạt động</small></label>

                                </div>
                                <div class="form-group d-flex" style="gap:20px">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>

                                <div class="form-group">
                                    <label for="sapo"> <span class="text-red">*</span> Role</label>
                                    <div>
                                        <select class="form-control select2" id="role" name="role" style="width: 100%;">
                                            <option value="<?= ADMIN ?>">ADMIN</option>
                                            <option value="<?= SALE ?>">SALE</option>
                                            <option value="<?= QC ?>">QC</option>
                                            <option value="<?= EDITOR ?>">EDITOR</option>
                                            <option value="<?= CUSTOMER ?>">CUSTOMER</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="sapo">Cấp quyền làm đơn</label>
                                    <div>
                                        <select class="form-control select2" id="user_service" multiple="multiple" name="user_service[]" style="width: 100%;">
                                            <?php foreach ($list_service as $id_service => $service) { ?>
                                                <option value="<?= $id_service ?>"><?= $service['type_service'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="chk_all_user_service">
                                            <label class="form-check-label" for="chk_all_user_service">
                                            Select All
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-center" id="btn_save_voucher">
                        <button type="submit" class="btn btn-lg btn-danger">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    var ROOM = {}; // các hạng mục thiết kế thuộc dịch vụ
    $(function() {

        $('#role').select2({});
        $('#user_service').select2({});

        $("#chk_all_user_service").click(function() {
            if ($("#chk_all_user_service").is(':checked')) {
                $("#user_service > option").prop("selected", "selected");
                $("#user_service").trigger("change");
            } else {
                $("#user_service > option").prop("selected", "");
                $("#user_service").trigger("change");
            }
        });

        $('#frm_voucher').validate({
            submitHandler: function(form) {

                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                // $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                form.submit();

                // if ($(form).find('input[name="image"]') == '') {
                //     $('#image-error').show();
                //     $([document.documentElement, document.body]).animate({
                //         scrollTop: $("#image-error").offset().top
                //     }, 2000);

                //     $(form).find('button[type="submit"]').attr('disabled', false);

                // } else {

                //     $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                //     // $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                //     form.submit();
                // }
            },
            // rules: {
            //     name: {
            //         required: true,
            //         minlength: 5,
            //         maxlength: 256
            //     },
            //     type_voucher: {
            //         required: true,
            //         minlength: 2,
            //         maxlength: 256
            //     },
            //     sapo: {
            //         required: true,
            //         minlength: 5
            //     },
            //     price: {
            //         required: true,
            //         maxlength: 256
            //     },
            //     image: {
            //         required: true
            //     }

            // },
            // messages: {
            //     name: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 5 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     type_voucher: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 2 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     sapo: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 5 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     price: {
            //         required: 'Không được bỏ trống',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     image: {
            //         required: 'Không được bỏ trống'
            //     }
            // },
            // errorElement: 'span',
            // errorPlacement: function(error, element) {
            //     error.addClass('invalid-feedback');
            //     element.closest('.form-group, .input-group').append(error);
            // },
            // highlight: function(element, errorClass, validClass) {
            //     $(element).addClass('is-invalid');
            // },
            // unhighlight: function(element, errorClass, validClass) {
            //     $(element).removeClass('is-invalid');
            // }
        });


        $('#modal-user').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);

            if (type == 'edit') {
                var user = button.data('user');

                $('#frm_user input[name=action]').val('edit');
                $('#frm_user input[name=id_user]').val(user.id_user);

                let user_service = [];
                for (const [key, value] of Object.entries(user.user_service)) {
                    user_service.push(key.toString());
                }

                //left
                modal.find('.modal-title').text(`Sửa tài khoản - ${user.username}`);
                modal.find('.modal-body #code_user').val(user.code_user);
                modal.find('.modal-body #username').val(user.username);
                modal.find('.modal-body #fullname').val(user.fullname);
                modal.find('.modal-body #phone').val(user.phone);
                modal.find('.modal-body #email').val(user.email);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(user.status));

                //right
                modal.find('.modal-body #role').val(user.role);
                modal.find('.modal-body #user_service').val(user_service);
            } else {
                $('#frm_user input[name=action]').val('add');
                $('#frm_user input[name=id_user]').val('');

                //left
                modal.find('.modal-title').text(`Thêm tài khoản`);
                modal.find('.modal-body #code_user').val('');
                modal.find('.modal-body #username').val('');
                modal.find('.modal-body #fullname').val('');
                modal.find('.modal-body #phone').val('');
                modal.find('.modal-body #email').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', true);

                //right
                modal.find('.modal-body #role').val('');
                modal.find('.modal-body #user_service').val([]);
            }

            $("#role").trigger("change");
            $("#user_service").trigger("change");
        });
    });
</script>