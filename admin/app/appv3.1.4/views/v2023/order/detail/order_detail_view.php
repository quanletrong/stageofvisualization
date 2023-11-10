<script src="js/v2023/moment_2.29.4.min.js"></script>
<style>

    /* ẩn thanh cuộn */
    textarea {
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

        <!-- CHAT BOX -->
        <section>
            <div class="card card-tabs card-primary" style="height: 90vh;" id="card_chat_lich_su">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php if ($role == ADMIN || $role == SALE) { ?>
                            <li class="nav-item" onclick="onclick_tab_chat_khach(this)">
                                <a class="nav-link active" id="tab_chat_khach" data-toggle="pill" href="#tab_panel_chat_khach" role="tab" aria-controls="tab_panel_chat_khach" aria-selected="true">
                                    TRAO ĐỔI VỚI KHÁCH (<span class="total">0</span>)
                                </a>
                            </li>
                        <?php } ?>
                        <li class="nav-item" onclick="onclick_tab_chat_noi_bo(this)">
                            <a class="nav-link" id="tab_chat_noi_bo" data-toggle="pill" href="#tab_panel_chat_noi_bo" role="tab" aria-controls="tab_panel_chat_noi_bo" aria-selected="false">
                                TRAO ĐỔI NỘI BỘ (<span class="total">0</span>)
                            </a>
                        </li>
                        <li class="nav-item" onclick="onclick_tab_lich_su(this)">
                            <a class="nav-link" id="tab_lich_su" data-toggle="pill" href="#tab_panel_lich_su" role="tab" aria-controls="tab_panel_lich_su" aria-selected="false">
                                LỊCH SỬ (<?= count($logs) ?>)
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content h-100">

                        <?php if ($role == ADMIN || $role == SALE) { ?>
                            <div class="tab-pane fade active show" id="tab_panel_chat_khach" role="tabpanel" aria-labelledby="tab_chat_khach">
                                <?php
                                $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_khach_view.php', [
                                    'order' => $order,
                                    'role' => $role
                                ]);
                                ?>
                            </div>
                        <?php } ?>

                        <div class="tab-pane fade" id="tab_panel_chat_noi_bo" role="tabpanel" aria-labelledby="tab_chat_noi_bo">
                            <?php
                            $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_noi_bo_view.php', [
                                'order' => $order,
                                'role' => $role
                            ]);
                            ?>
                        </div>

                        <!-- PANEL HISTORY -->
                        <div class="tab-pane fade h-100" id="tab_panel_lich_su" role="tabpanel" aria-labelledby="tab_lich_su">
                            <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_history_view.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function isImage(url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    }

    $(document).ready(function() {

        // TODO: có vấn đề
        $('textarea').on('keyup', function() {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        })

        // TODO: có vấn đề
        $("textarea").each(function(textarea) {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        });
    })
</script>