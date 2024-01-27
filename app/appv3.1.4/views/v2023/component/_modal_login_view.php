<style>
    .input-group-ct {
        display: block;
        background-color: #fff;
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .line-or-ct {
        display: flex;
        -webkit-box-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        align-items: center;
        position: relative;
        width: 100%;
        height: 30px;
    }

    .line-ct {
        width: 100%;
        height: 1px;
        background-color: rgb(242, 242, 242);
    }

    .or-ct {
        position: absolute;
        top: 0px;
        left: calc(50% - 24px);
        height: 24px;
        width: 49px;
        padding: 4px 8px;
        background-color: rgb(255, 255, 255);
    }

    .or-ct div {
        font-size: 13px;
        line-height: 20px;
        font-weight: 400;
        color: rgb(153, 153, 153);
    }

    .btn-or-ct {
        display: inline-block;
        border-radius: 8px;
        cursor: pointer;
        white-space: nowrap;
        width: fit-content;
        padding: 14px 0;
        width: 100%;
        opacity: 1;
    }
</style>

<!-- Modal login -->
<div class="modal fade" id="modal_login" tabindex="-1" aria-labelledby="modal_login" aria-hidden="true">
    <div class="modal-dialog modal-md modal-fullscreen-md-down">
        <div class="modal-content" style="background-color: white;">
            <div class="modal-body">
                <form action="<?php echo site_url('login/ajax_auth') ?>" method="post" id="form_login">

                    <input type="hidden" name="url" value="<?=current_url()?>">

                    <h4 class="text-center text-danger">Đăng nhập stageofvisualization</h4>

                    <div class="alert alert-danger login-alert" role="alert" style="display: none;"></div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-user"></i></div>
                        </div>
                        <input type="text" class="form-control " name="username" placeholder="Nhập tên đăng nhập">
                    </div>



                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-lock"></i></div>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu">
                    </div>
                    <input type="submit" class="btn btn-danger w-100" value="Đăng nhập">

                    <div class="line-or-ct mt-2">
                        <div class="line-ct"></div>
                        <div class="or-ct">
                            <div type="tertiary" class="sc-crrsfI fmnTOX">Hoặc</div>
                        </div>
                    </div>

                    <!-- TODO: bổ sung thêm chức năng này -->
                    <div class="d-flex justify-content-around mt-1 mb-3">
                        <a href="#" onclick="alert('chức năng đang phát triển')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-primary btn-or-ct"><i class="fa-brands fa-facebook"></i> Facebook</button>
                        </a>
                        <a href="#<?php echo @$loginUrlgg; ?>" onclick="alert('chức năng đang phát triển')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-danger btn-or-ct"><i class="fa-brands fa-google"></i> Google</button>
                        </a>
                    </div>

                    <div class="p-3 ps-4 d-flex justify-content-between">
                        <small>
                            <a href="<?= site_url() ?>" style="text-decoration: none;">
                                ← Trang chủ
                            </a>
                        </small>
                        <!-- <small><a href="" style="text-decoration: none;">Bạn quên mật khẩu?</a></small> -->
                        <!-- TODO: chưa làm quên mật khẩu -->
                        <small><a href="#" style="text-decoration: none;" onclick="alert('chức năng đang phát triển')">Bạn quên mật khẩu?</a></small>
                        <small>
                            <a href="<?= LINK_USER_REGISTER ?>" style="text-decoration: none;">
                                Đăng ký →
                            </a>
                        </small>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Modal login -->

<script>
    $(document).ready(function() {
        $("#form_login").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(),
                success: function(data) {

                    try {
                        let res = JSON.parse(data);

                        if (res.status) {
                            window.location.href = res.data;
                        } else {
                            $('#form_login .login-alert').text(res.error).show()
                        }
                    } catch (error) {
                        $('#form_login .login-alert').text('Vui lòng thử lại!').show()
                    }
                }
            });

        });
    })
</script>