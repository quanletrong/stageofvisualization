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
                                    <label for="name"> <span class="text-red">*</span> Password</label> <br>
                                    <small>Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*</small>
                                    <div style="position: relative;">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" style="padding-right: 30px;">
                                        <i class="fas fa-eye-slash i-eye" style="position: absolute; top:10px; right:5px;"></i>
                                    </div>
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
                                    <label> <span class="text-red">*</span> Role</label>
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
                                    <label>Loại tài khoản</label>
                                    <div>
                                        <select class="form-control select2" id="type" name="type" style="width: 100%;">
                                            <option value="1">Nội bộ</option>
                                            <option value="2">Vãng lai</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Cấp quyền làm đơn</label>
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

                                <div class="form-group">
                                    <label>Ảnh đại diện</label>
                                    <div style="display: flex; justify-content: center;">
                                        <div class="w-25" style="position: relative; cursor: pointer;">
                                            <img src="" id="imgAccountAvatar" class="w-100 rounded-circle border" style="aspect-ratio: 1; object-fit: cover;">
                                            <div class="btn" style="position: absolute; bottom: 0px; right: 15%; font-size: 0.85rem; border-radius: 15px; background: gray; padding: 5px; line-height: 1; color: white;" onclick="quanlt_upload(this);" data-callback="cb_upload_image_ava" data-target="#hdd_avatar">
                                                <i class="fas fa-camera"></i>
                                            </div>
                                            <input type="hidden" id="hdd_avatar" name="hdd_avatar" value="">
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
    $(function() {
        $('#role').select2({})
        $('#type').select2({});
        $('#user_service').select2({});

        $('#role').on('change', function() {
            validobj.element(`select[name="role"]`);
        });

        $("#chk_all_user_service").click(function() {
            if ($("#chk_all_user_service").is(':checked')) {
                $("#user_service > option").prop("selected", "selected");
                $("#user_service").trigger("change");
            } else {
                $("#user_service > option").prop("selected", "");
                $("#user_service").trigger("change");
            }
        });

        var validobj = $('#frm_user').validate({
            submitHandler: function(form) {

                event.preventDefault();
                $(form).find('button[type="submit"]').attr('disabled', true);
                let action = $(form).find('[name="action"]').val();
                let url = action === 'add' ?
                    'user/ajax_add_user' :
                    'user/ajax_edit_user';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $(form).serialize(), // serializes the form's elements.
                    success: function(data) {
                        let kq = JSON.parse(data);
                        if (kq.status) {
                            $.toast({
                                icon: 'success',
                                heading: `<b>Thành công</b>`,
                                text: `Tự động tải lại sau 5s`,
                                hideAfter: 5000,
                                position: 'top-right',
                                afterHidden: function () {
                                    location.reload();
                                } 
                            })
                        } else {
                            $.toast({
                                icon: 'error',
                                heading: `Thất bại`,
                                text: kq.error,
                                hideAfter: 15000,
                                position: 'top-right',
                            })
                            $(form).find('button[type="submit"]').attr('disabled', false);
                        }
                    }
                });
            },
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                fullname: {
                    required: true
                },
                phone: {
                    required: true
                },
                email: {
                    required: true
                },
                role: "select_required"
            },
            messages: {},
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group, .input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
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
                modal.find('.modal-body #password').val(user.password);
                modal.find('.modal-body #fullname').val(user.fullname);
                modal.find('.modal-body #phone').val(user.phone);
                modal.find('.modal-body #email').val(user.email);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(user.status));

                //right
                modal.find('.modal-body #role').val(user.role);
                modal.find('.modal-body #type').val(user.type);
                modal.find('.modal-body #user_service').val(user_service);
                modal.find('.modal-body #hdd_avatar').val(user.avatar);
                modal.find('.modal-body #imgAccountAvatar').attr('src', user.avatar);
            } else {
                $('#frm_user input[name=action]').val('add');
                $('#frm_user input[name=id_user]').val('');

                //left
                modal.find('.modal-title').text(`Thêm tài khoản`);
                modal.find('.modal-body #code_user').val('');
                modal.find('.modal-body #username').val('');
                modal.find('.modal-body #password').val('');
                modal.find('.modal-body #fullname').val('');
                modal.find('.modal-body #phone').val('');
                modal.find('.modal-body #email').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', true);

                //right
                modal.find('.modal-body #role').val('');
                modal.find('.modal-body #type').val(1);
                modal.find('.modal-body #user_service').val([]);
                modal.find('.modal-body #hdd_avatar').val('');
                modal.find('.modal-body #imgAccountAvatar').attr('src', '<?= url_image(AVATAR_DEFAULT, FOLDER_AVATAR) ?>');
            }

            $("#role").trigger("change");
            $("#type").trigger("change");
            $("#user_service").trigger("change");
        });

        $(".i-eye").on("click", function() {
            if ($(this).hasClass("fa-eye-slash")) {
                $(this).siblings("input").prop("type", "text");
                $(this).removeClass("fa-eye-slash");
                $(this).addClass("fa-eye");
            } else {
                $(this).siblings("input").prop("type", "password");
                $(this).addClass("fa-eye-slash");
                $(this).removeClass("fa-eye");
            }
        });
    });

    function cb_upload_image_ava(link, target, name) {
        $("#imgAccountAvatar").attr('src', link);
        $("#hdd_avatar").val(link);
    }
</script>