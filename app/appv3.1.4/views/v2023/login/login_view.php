<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>

    <!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <style>
        html,
        body {
            height: 100%;
        }

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
</head>

<body style="background-color: #eeeeee;">
    <div class="container" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);">
        <div class="card shadow mx-auto" style="width: 100%; max-width: 500px;">
            <div class="card-body">
                <form action="<?php echo site_url('login/auth?url=' . $currUrl) ?>" method="post">
                    <h4 class="text-center text-danger">Login stageofvisualization</h4>
                    <?php if ($login_fail != '') { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $login_fail ?>
                        </div>
                    <?php } ?>

                    <div class="input-group mt-3 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-user"></i></div>
                        </div>
                        <input type="text" class="form-control " name="username" placeholder="Enter username">
                    </div>



                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-ct"><i class="fa-solid fa-lock"></i></div>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="Enter password">
                    </div>
                    <input type="submit" class="btn btn-danger w-100" value="Login">

                    <div class="line-or-ct mt-2">
                        <div class="line-ct"></div>
                        <div class="or-ct">
                            <div type="tertiary" class="sc-crrsfI fmnTOX">Or</div>
                        </div>
                    </div>

                    <!-- TODO: bổ sung thêm chức năng này -->
                    <div class="d-flex justify-content-around mt-1 mb-3">
                        <a href="#" onclick="alert('developing')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-primary btn-or-ct"><i class="fa-brands fa-facebook"></i> Facebook</button>
                        </a>
                        <a href="#<?php echo $loginUrlgg; ?>" onclick="alert('developing')" style="width: 48%;">
                            <button type="button" class="btn btn-outline-danger btn-or-ct"><i class="fa-brands fa-google"></i> Google</button>
                        </a>
                    </div>

                    <div class="p-3 ps-4 d-flex justify-content-between">
                        <small>
                            <a href="<?= site_url() ?>" style="text-decoration: none;">
                                ← Home
                            </a>
                        </small>
                        <!-- <small><a href="" style="text-decoration: none;">Bạn quên mật khẩu?</a></small> -->
                        <!-- TODO: chưa làm quên mật khẩu -->
                        <small><a href="#" style="text-decoration: none;" onclick="alert('developing')">Bạn quên mật khẩu?</a></small>
                        <small>
                            <a href="<?= LINK_USER_REGISTER ?>" style="text-decoration: none;">
                                Register →
                            </a>
                        </small>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>