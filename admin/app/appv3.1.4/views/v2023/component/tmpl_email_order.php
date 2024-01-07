<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="width:800px; padding: 10px; margin:0 auto">
        <div style="text-align: center; margin-top: 15px; margin-bottom: 30px;">
            <img src="https://stageofvisualization.com/images/logo-21.png" alt="" width="200">
        </div>

        <p>Xin chào,</p>
        <p>
            Hệ thống xin thông báo, đơn hàng <a href="<?= site_url('order/detail/' . $id_order) ?>">#<?=$id_order?></a> có thay đổi như sau:
        </p>

        <p>
            <b>
                <?= $body ?>
            </b>
        </p>

        <p>Chúc bạn một ngày làm việc hiệu quả.</p>

        <a href="<?= site_url('order/detail/' . $id_order) ?>" style="display: block; text-align: center; width: 100%;  background-color: #fff0f0; border:1px solid red;border-radius: 5px; padding:8px 0; text-decoration: none; color: red; font-weight: bold;">XEM ĐƠN HÀNG</a>

        <div style="text-align: center; font-size: 12px; margin-top: 20px;">
            © <?= date("Y") ?> stageofvisualization.com
        </div>
    </div>
</body>

</html>