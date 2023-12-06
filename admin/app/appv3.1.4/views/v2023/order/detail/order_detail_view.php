<script src="js/v2023/moment_2.29.4.min.js"></script>
<style>
    /* ẩn thanh cuộn */
    #tab_content_job textarea {
        overflow: hidden;
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- PAGE HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi tiết đơn hàng</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('order') ?>">Danh sách đơn hàng</a></li>
                            <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- JOB CONTENT / TEAM ACTION -->
        <section>
            <div class="row">
                <div class="col-12 col-lg-8" id="list-image-order">
                    <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_job_content_view.php'); ?>
                </div>
                <div class="col-12 col-lg-4">
                    <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_team_action_view.php'); ?>
                </div>
            </div>
        </section>
    </div>
</div>


<!--  box chat khach -->
<div>
    <?php if ($role == ADMIN || $role == SALE) {
        $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_khach_view.php', [
            'order' => $order,
            'role' => $role
        ]);
    }
    ?>
</div>


<!-- box chat noi bo -->
<div>
    <?php
    $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_noi_bo_view.php', [
        'order' => $order,
        'role' => $role
    ]);
    ?>
</div>


<!-- box lịch sử -->
<div>
    <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_history_view.php'); ?>
</div>



<script>
    function isImage(url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    }

    $(document).ready(function() {

        // TODO: có vấn đề
        $('#tab_content_job textarea').on('keyup', function() {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        })

        // TODO: có vấn đề
        $("#tab_content_job textarea").each(function(textarea) {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        });
    })

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
</script>