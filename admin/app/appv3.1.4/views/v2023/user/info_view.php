<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thông tin tài khoản</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Thông tin tài khoản</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <!-- THÔNG TIN KHÔNG THAY ĐỔI -->
        <div class="info-box">
            <div class="info-box-content" style="justify-content: flex-start;">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" id="username" value="<?= $info['username'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Quyền</label>
                            <input class="form-control" value="<?= get_role_name($info['role']) ?>" disabled />
                        </div>

                        <div class="form-group">
                            <label>Loại tài khoản</label>
                            <div>
                                <select class="form-control select2" id="type" style="width: 100%;" disabled>
                                    <option value="1" <?= $info['type'] == ED_NOI_BO ? 'selected' : '' ?>>Nội bộ</option>
                                    <option value="2" <?= $info['type'] == ED_CTV ? 'selected' : '' ?>>Vãng lai</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-lg-6">

                        <div class="form-group">
                            <label for="name">Code user</label>
                            <input type="text" class="form-control" id="code_user" placeholder="Nhập code user" value="<?= $info['code_user'] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Cấp quyền làm đơn</label>
                            <div>
                                <select class="form-control select2" id="user_service" multiple="multiple" style="width: 100%;" disabled>
                                    <?php foreach ($list_service as $id_service => $service) { ?>
                                        <option value="<?= $id_service ?>" <?= isset($info['user_service'][$id_service]) ? 'selected' : '' ?>>
                                            <?= $service['type_service'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- THÔNG TIN CÁ NHÂN -->
            <div class="col-12 col-lg-6">
                <div class="info-box h-100">
                    <div class="info-box-content h-100" style="justify-content: flex-start;">
                        <h5 class="text-center">THÔNG TIN TÀI KHOẢN</h5>

                        <div class="form-group">
                            <label>Ảnh đại diện</label>
                            <div style="display: flex; justify-content: center;">
                                <div class="w-25" style="position: relative; cursor: pointer;">
                                    <img src="<?= $info['avatar_url'] ?>" id="imgAccountAvatar" class="w-100 rounded-circle border" style="aspect-ratio: 1; object-fit: cover;">
                                    <div class="btn" style="position: absolute; bottom: 0px; right: 15%; font-size: 0.85rem; border-radius: 15px; background: gray; padding: 5px; line-height: 1; color: white;" onclick="quanlt_upload(this);" data-callback="cb_upload_image_ava" data-target="#hdd_avatar">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <input type="hidden" id="hdd_avatar" name="hdd_avatar" value="<?= $info['avatar'] ?>">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Fullname</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập Fullname" value="<?= $info['fullname'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập Phone" value="<?= $info['phone'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Nhập Email" value="<?= $info['email'] ?>">
                        </div>

                        <button type="button" class="btn btn-sm btn-lg btn-danger w-100" onclick="ajax_edit_info(this)">Lưu lại</button>
                    </div>
                </div>

            </div>
            <!-- THAY ĐỔI MẬT KHẨU -->
            <div class="col-12 col-lg-6">
                <div class="info-box h-100">
                    <div class="info-box-content" style="justify-content: flex-start;">
                        <h5 class="text-center">THAY ĐỔI MẬT KHẨU</h5>
                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Mật khẩu cũ</label>
                            <div style="position: relative;">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu cũ">
                                <i class="fas fa-eye-slash i-eye" style="position: absolute; top:10px; right:5px;"></i>
                            </div>
                            <small>Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*</small>
                        </div>
                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Mật khẩu mới</label>
                            <div style="position: relative;">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới">
                                <i class="fas fa-eye-slash i-eye" style="position: absolute; top:10px; right:5px;"></i>
                            </div>
                            <small>Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*</small>
                        </div>
                        <div class="form-group">
                            <label for="name"> <span class="text-red">*</span> Nhập lại mật khẩu mới </label>
                            <div style="position: relative;">
                                <input type="password" class="form-control" id="re_password" name="re_password" placeholder="Nhập lại mật khẩu mới">
                                <i class="fas fa-eye-slash i-eye" style="position: absolute; top:10px; right:5px;"></i>
                            </div>
                            <small>Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-lg btn-danger w-100" onclick="ajax_edit_password(this)">Lưu lại</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#type').select2({});
        $('#user_service').select2({});

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

    function ajax_edit_info(btn) {
        let fullname = $('#fullname').val();
        let phone = $('#phone').val();
        let email = $('#email').val();
        let hdd_avatar = $('#hdd_avatar').val();

        if (fullname == '' || phone == '' || email == '' || hdd_avatar == '') {
            toasts_danger('Chưa nhập đủ dữ liệu');
        } else {
            $(btn).html(' <i class="fas fa-sync fa-spin"></i> Lưu lại').prop("disabled", true);
            $.ajax({
                    type: 'POST',
                    url: 'user/ajax_edit_info',
                    data: {
                        fullname,
                        phone,
                        email,
                        hdd_avatar
                    }
                })
                .done((data) => {

                    try {
                        let kq = JSON.parse(data);
                        if (kq.status) {
                            toasts_success('Lưu thành công');
                        } else {
                            toasts_danger(kq.error);
                        }
                    } catch (error) {
                        toasts_danger('Có lỗi xảy ra vui lòng thử lại (ERR_001)');
                    }

                })
                .fail((err) => {
                    toasts_danger('Có lỗi xảy ra vui lòng thử lại (ERR_002)');
                })
                .always(() => {
                    $(btn).html('Lưu lại').prop("disabled", false);
                });
        }
    }

    function ajax_edit_password(btn) {
        let password = $('#password').val();
        let new_password = $('#new_password').val();
        let re_password = $('#re_password').val();

        if (password == '' || new_password == '' || re_password == '') {
            toasts_danger('Chưa nhập đủ dữ liệu');
        } else if (new_password != re_password) {
            toasts_danger('Mật khẩu nhập lại không khớp mật khẩu mới');
        } else {
            $(btn).html(' <i class="fas fa-sync fa-spin"></i> Lưu lại').prop("disabled", true);
            $.ajax({
                    type: 'POST',
                    url: 'user/ajax_edit_password',
                    data: {
                        password,
                        new_password,
                        re_password
                    }
                })
                .done((data) => {

                    try {
                        let kq = JSON.parse(data);
                        if (kq.status) {
                            toasts_success('Lưu thành công');
                        } else {
                            toasts_danger(kq.error);
                        }
                    } catch (error) {
                        console.log(error)
                        toasts_danger('Có lỗi xảy ra vui lòng thử lại (ERR_001)');
                    }

                })
                .fail((err) => {
                    toasts_danger('Có lỗi xảy ra vui lòng thử lại (ERR_002)');
                })
                .always(() => {
                    $(btn).html('Lưu lại').prop("disabled", false);
                });
        }
    }

    function cb_upload_image_ava(link, target, name) {
        $("#imgAccountAvatar").attr('src', link);
        $("#hdd_avatar").val(link);
    }
</script>