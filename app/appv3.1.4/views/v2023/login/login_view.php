<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

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
    </style>
</head>

<body style="background-color: #eeeeee;">
    <div class="container h-50 d-flex justify-content-center">
        <div class="card shadow my-auto" style="width: 100%; max-width: 500px;">
            <div class="card-body">
                <form action="<?php echo site_url('login/auth?url=' . $currUrl) ?>" method="post">
                    <h4 class="text-center text-danger">Đăng nhập hệ thống</h4>
                    <?php if ($login_fail != '') { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $login_fail ?>
                        </div>
                    <?php } ?>
                    
                    <input type="text" class="form-control mb-3" name="username" placeholder="Nhập tên đăng nhập">
                    <input type="password" class="form-control mb-3" name="password" placeholder="Nhập mật khẩu">
                    <input type="submit" class="btn btn-danger" value="Đăng nhập">
                </form>
            </div>
        </div>
    </div>
</body>

</html>