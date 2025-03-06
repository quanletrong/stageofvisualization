<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- css bootstrap/5.3.3 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- font-awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link href="<?php echo $base_url . "skins/" . ltrim(URI_PATH . '/', '/') . $template_f; ?>all.css" rel="stylesheet" type="text/css" />

<!-- js jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- lazy -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<!-- fancybox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancyapps-ui/5.0.36/fancybox/fancybox.umd.min.js" integrity="sha512-VNk0UJk87TUyZyWXUFuTk6rUADFyTsVpVGaaFQQIgbEXAMAdGpYaFWmguyQzEQ2cAjCEJxR2C++nSm0r2kOsyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancyapps-ui/5.0.36/fancybox/fancybox.min.css" integrity="sha512-s4DOVHc73MjMnsueMjvJSnYucSU3E7WF0UVGRQFd/QDzeAx0D0BNuAX9fbZSLkrYW7V2Ly0/BKHSER04bCJgtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- bs5dialog https://ymlluo.github.io/bs5dialog/dist/index.html -->
<link rel="stylesheet" href="skins/v2023/bs5dialog.css" />
<script src="js/v2023/bs5dialog.js"></script>
<script>
    bs5dialog.startup();
</script>


<!-- common js -->
<script src="js/v2023/common.js?v=1"></script>

<script src="js/v2023/func.js?v=1"></script>

<!-- SOCKET -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io('<?= SOCKET_SERVICES ?>', {
        transports: ['websocket'],
        withCredentials: true,
        extraHeaders: {
            "my-custom-header": "abcd"
        },
        reconnection: true,
        auth: {
            'token': '<?php echo EncryptData::Websocket_token_create($userid); ?>',
            'access_token': ''
        },
    });
</script>
<!-- END SOCKET -->