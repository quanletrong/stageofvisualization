<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<link href="<?php echo $base_url . "skins/" . ltrim(URI_PATH . '/', '/') . $template_f; ?>all.css" rel="stylesheet" type="text/css" />



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<!-- TODO: tam thoi comment laij -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script> -->

<!-- lazy -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<!-- bs5dialog https://ymlluo.github.io/bs5dialog/dist/index.html -->
<link rel="stylesheet" href="skins/v2023/bs5dialog.css" />
<script src="js/v2023/bs5dialog.js"></script>
<script>
    bs5dialog.startup();
</script>


<!-- common js -->
<script src="js/v2023/common.js?v=1"></script>

<script src="js/v2023/func.js"></script>

<!-- SOCKET -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io('<?= SOCKET_SERVICES ?>', {
        transports: ['websocket'],
        withCredentials: true,
        extraHeaders: {
            "my-custom-header": "abcd"
        }
    });
</script>
<!-- END SOCKET -->