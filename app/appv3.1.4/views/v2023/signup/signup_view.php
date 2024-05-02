<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- css bootstrap/5.3.3 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://mdbcdn.b-cdn.net/wp-content/themes/mdbootstrap4/docs-app/css/dist/mdb5/standard/core.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- js bootstrap/5.3.3 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<style>
    .input-group-ct {
        background-color: #fff;
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        height: calc(2.66rem + 2px) !important;
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

    .i-eye {
        right: 10px;
        top: 15px;
        font-size: 14px;
        cursor: pointer;
    }

    .i-eye {
        right: 10px;
        top: 15px;
        font-size: 14px;
        cursor: pointer;
    }

    .i-circle-xmark {
        right: 10px;
        top: 15px;
        font-size: 14px;
        color: red;
    }
</style>

<body style="background-color: #eeeeee;">
    <div class="container" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);">
        <div class="card shadow mx-auto" style="width: 100%; max-width: 500px;">
            <div class="card-body ">
                <?php if ($info['success'] != "") { ?>
                    <?php if ($info['success'] == "1") { ?>
                        <div class="alert alert-success" role="alert">
                            You have successfully created an account, click <a href="<?php echo site_url(LINK_USER_LOGIN); ?>">Login</a>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            An error occurred, please recreate
                        </div>
                    <?php } ?>
                <?php } ?>
                <form id="frm-signup" method="post" action="<?php echo site_url(LINK_USER_REGISTER, $langcode) ?>">
                    <h4 class="text-center text-danger">Create Account</h4>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-file-signature"></i></div>
                        </div>
                        <div class="form-outline">
                            <input type="text" id="fullname" name="fullname" value="<?php echo $info["fullname"] ?>" class="form-control form-control-lg">
                            <label class="form-label" for="fullname" style="margin-left: 0px;">Fullname</label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 65px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-user"></i></div>
                        </div>
                        <div class="form-outline position-relative">
                            <input type="text" id="uname" name="uname" value="<?php echo $info["username"] ?>" class="form-control form-control-lg" required oninvalid="this.setCustomValidity('Please enter username')" oninput="this.setCustomValidity('')" />
                            <label class="form-label" for="uname" style="margin-left: 0px;">Username<span class="text-danger">*</span></label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 88px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-lock"></i></div>
                        </div>
                        <div class="form-outline position-relative">
                            <input type="password" id="pword" name="pword" class="form-control form-control-lg" required />
                            <label class="form-label" for="pword" style="margin-left: 0px;">Password<span class="text-danger">*</span></label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 68px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>
                            <i class="fa-solid position-absolute i-eye  fa-eye-slash"></i>
                        </div>
                    </div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-lock"></i> </div>
                        </div>
                        <div class="form-outline position-relative">
                            <input type="password" id="repword" name="repword" class="form-control form-control-lg" required oninvalid="this.setCustomValidity('Password incorrect')" oninput="this.setCustomValidity('')">
                            <label class="form-label" for="repword" style="margin-left: 0px;">Re-enter the password<span class="text-danger">*</span></label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 118px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>

                            <i class="fa-solid position-absolute i-eye  fa-eye-slash"></i>
                        </div>
                    </div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-envelope"></i></div>
                        </div>
                        <div class="form-outline">
                            <input type="email" id="email" name="email" value="<?php echo $info["email"] ?>" class="form-control form-control-lg" required oninvalid="this.setCustomValidity('Invalid email')" oninput="this.setCustomValidity('')" />
                            <label class="form-label" for="email" style="margin-left: 0px;">Email<span class="text-danger">*</span></label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 43px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-phone"></i></div>
                        </div>
                        <div class="form-outline">
                            <input type="text" pattern="(\+84|0)\d{9,10}" value="<?php echo $info["phone"] ?>" id="phone" name="phone" class="form-control form-control-lg" oninvalid="this.setCustomValidity('Invalid phone number')" oninput="this.setCustomValidity('')" required/>
                            <label class="form-label" for="phone" style="margin-left: 0px;">Phone number <span class="text-danger">*</span></label>
                            <div class="form-notch">
                                <div class="form-notch-leading" style="width: 9px;"></div>
                                <div class="form-notch-middle" style="width: 85px;"></div>
                                <div class="form-notch-trailing"></div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex justify-content-center mt-3">
                        <input type="submit" class="btn btn-danger w-100" value="Register">
                    </div>

                    <div class="line-or-ct mt-2">
                        <div class="line-ct"></div>
                        <div class="or-ct">
                            <div type="tertiary" class="sc-crrsfI fmnTOX">Or</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-around mt-1 mb-3">
                        <a href="#" onclick="alert('Developing')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-primary btn-or-ct"><i class="fa-brands fa-facebook"></i> Facebook</button>
                        </a>
                        <a href="#<?php echo $loginUrlgg; ?>" onclick="alert('Developing')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-danger btn-or-ct"><i class="fa-brands fa-google"></i> Google</button>
                        </a>
                    </div>

                    <div class="p-3 ps-4 d-flex justify-content-between">
                        <small>
                            <a href="<?= site_url() ?>" style="text-decoration: none;">
                                ← Trang chủ
                            </a>
                        </small>
                        <small>
                            <!-- TODO: chức năng quên mật khẩu -->
                            <a href="#<?= site_url('quen-mat-khau') ?>" style="text-decoration: none;" onclick="alert('Developing')">
                                Forgot password?
                            </a>
                        </small>
                        <small>
                            <a href="<?= site_url('login') ?>" style="text-decoration: none;">
                                Log in →
                            </a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    $(function() {
        $(".form-outline input").each(function() {
            if ($(this).val() != "") {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });

        $(".form-outline input").on("keyup", function() {
            if ($(this).val() != "") {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });

        $(".i-eye").on("click", function() {
            if ($(this).hasClass("fa-eye-slash")) {
                $(this).parent(".form-outline").find("input").attr("type", "text");
                $(this).removeClass("fa-eye-slash");
                $(this).addClass("fa-eye");
            } else {
                $(this).parent(".form-outline").find("input").attr("type", "password");
                $(this).addClass("fa-eye-slash");
                $(this).removeClass("fa-eye");
            }
        });
    });



    var password = document.getElementById("pword");
    var confirm_password = document.getElementById("repword");

    function validateRePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Password incorrect");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    function validatePassword() {
        var reg_pass = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (password.value == "") {
            password.setCustomValidity('Please enter your password');
        } else if (!reg_pass.test(password.value)) {
            password.setCustomValidity("Password must contain a minimum of 8 characters and at least 1 letter and 1 number");
        } else {
            password.setCustomValidity('');
        }
    }

    password.onkeyup = validatePassword;
    password.onfocus = validatePassword;
    confirm_password.onkeyup = validateRePassword;
</script>

</html>