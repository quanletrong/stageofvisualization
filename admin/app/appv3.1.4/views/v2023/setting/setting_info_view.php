<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cài đặt trang chủ</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Cài đặt trang chủ</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form id="form_info" method="post" action="setting/info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin website</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-3">

                                <div class="text-right">
                                    <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone" value="<?= htmlentities(@$setting['phone']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" value="<?= htmlentities(@$setting['email']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" value="<?= htmlentities(@$setting['address']) ?>">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="link_facebook">Link facebook</label>
                                    <input type="text" class="form-control" name="link_facebook" value="<?= htmlentities(@$setting['link_facebook']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="link_youtube">Link youtube</label>
                                    <input type="text" class="form-control" name="link_youtube" value="<?= htmlentities(@$setting['link_youtube']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="link_instagram">Link instagram</label>
                                    <input type="text" class="form-control" name="link_instagram" value="<?= htmlentities(@$setting['link_instagram']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="link_linkedin">Linkedin</label>
                                    <input type="text" class="form-control" name="link_linkedin" value="<?= htmlentities(@$setting['link_linkedin']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin website</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-3">

                                <div class="text-right">
                                    <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
                                </div>
                                <div class="form-group">
                                    <label for="name">Logo ngang</label>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="quanlt_upload(this)" data-callback="cb_upload_logo_ngang" data-target="#logo_ngang">
                                        <i class="fas fa-upload"></i> Upload
                                    </button>
                                    <input type="hidden" id="logo_ngang" name="logo_ngang" value="<?= htmlentities(@$setting['logo_ngang_path']) ?>" required>
                                    <img src="<?= @$setting['logo_ngang_path'] ?>" id="logo_ngang_pre" style="max-width: 300px;">
                                </div>

                                <div class="form-group">
                                    <label for="name">Logo vuông</label>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="quanlt_upload(this)" data-callback="cb_upload_logo_vuong" data-target="#logo_vuong">
                                        <i class="fas fa-upload"></i> Upload
                                    </button>
                                    <input type="hidden" id="logo_vuong" name="logo_vuong" value="<?= htmlentities(@$setting['logo_vuong_path']) ?>" required>
                                    <img src="<?= @$setting['logo_vuong_path'] ?>" id="logo_vuong_pre" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(function() {
        $('#form_info').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                form.submit();
            },
            ignore: {},
            rules: {},
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
    })

    function cb_upload_logo_ngang(link, target, name) {
        $(`${target}_pre`).attr('src', link);
    }

    function cb_upload_logo_vuong(link, target, name) {
        $(`${target}_pre`).attr('src', link);
    }
</script>