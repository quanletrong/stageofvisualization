<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="max-width:800px; padding: 10px; margin:0 auto">
        <div style="text-align: center; margin-top: 15px; margin-bottom: 30px;">
            <img src="https://stageofvisualization.com/images/logo-21.png" alt="" width="200">
        </div>

        <p>Hi,</p>
        <p>
            Tài khoản <strong><?= $fullname ?></strong> có yêu cầu rút tiền mới.
        </p>

        <p>Thời gian tạo: <?= date('d/m/y H:i:s', strtotime($create_time)) ?></p>

        <strong>Số lượng:</strong>
        <table border="0" cellspacing="0" cellpadding="0">
            <?php foreach ($service_request as $service => $number) { ?>
                <tr>
                    <td width=120><?= $service ?></td>
                    <td><?= $number ?></td>
                </tr>
            <?php } ?>
        </table>

        <p>Chúc bạn một ngày làm việc hiệu quả.</p>

        <div style="text-align: center; font-size: 12px; margin-top: 20px;">
            © <?= date("Y") ?> stageofvisualization.com
        </div>
    </div>
</body>

</html>