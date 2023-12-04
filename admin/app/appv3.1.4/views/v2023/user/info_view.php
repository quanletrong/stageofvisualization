<div class="container py-5" id="step-1">
    <form id="frm_user" method="post" action="<?= site_url('user') ?>">
        <input type="hidden" name="action" value="">
        <input type="hidden" name="id_user" value="">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">

                    <div class="form-group">
                        <label for="name"> <span class="text-red">*</span> Username</label>
                        <input type="text" class="form-control" id="username" value="<?= $info['username'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label> <span class="text-red">*</span> Role</label>
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

                    <div class="form-group">
                        <label for="name">Code user</label>
                        <input type="text" class="form-control" id="code_user" placeholder="Nhập code user" value="<?= $info['code_user'] ?>" disabled>
                    </div>

                    <div class="mb-1">
                        <label>Trạng thái tài khoản <small>ON đang hoạt động - OFF ngừng hoạt động</small></label>
                    </div>
                    <div class="form-group d-flex" style="gap:20px">
                        <input type="checkbox" id="status" data-bootstrap-switch data-off-color="danger" data-on-color="success" <?= $info['status'] ? 'checked' : '' ?> disabled>
                    </div>

                </div>
                <div class="col-12 col-lg-6">

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

                    <div class="form-group">
                        <label for="name"> <span class="text-red">*</span> Password</label> <br>
                        <small>Mật khẩu tối thiểu 8 ký tự, bao gồm số, chữ thường, chữ in hoa và ký tự đặc biệt !@#$%^&*</small>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
                    </div>

                    <div class="form-group">
                        <label>Ảnh đại diện</label>
                        <div style="display: flex; justify-content: center;">
                            <div class="w-25" style="position: relative; cursor: pointer;">
                                <img src="<?= $info['avatar'] ?>" id="imgAccountAvatar" class="w-100 rounded-circle border" style="aspect-ratio: 1; object-fit: cover;">
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#type').select2({});
        $('#user_service').select2({});
    });

    function cb_upload_image_ava(link, target, name) {
        $("#imgAccountAvatar").attr('src', link);
        $("#hdd_avatar").val(link);
    }
</script>