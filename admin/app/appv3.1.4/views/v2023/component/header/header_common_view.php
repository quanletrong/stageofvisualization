<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- Icheck -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<!-- Toast -->
<link rel="stylesheet" href="skins/v2023/jquery.toast.css">

<!-- all CSS -->
<link rel="stylesheet" href="skins/v2023/all.css?v=1">



<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- lazy -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<!-- confirm -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<!-- TOAST  -->
<script src="js/v2023/jquery.toast.js"></script>

<!-- moment -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/moment/locale/vi.js"></script>
<script>moment.locale('vi')</script>

<!-- InputMask (moment phai import truoc) -->
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.js"></script>

<!-- daterange picker -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- <link rel="stylesheet" href="skins/v2023/bs5dialog.css" /> -->
<!-- <script src="js/v2023/bs5dialog.js"></script> -->
<script>
    // bs5dialog.startup();
</script>


<!-- common js -->
<script src="js/v2023/common.js?v=4"></script>
<script src="js/v2023/func.js?v=3"></script>

<!-- SOCKET_SERVICES -->
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