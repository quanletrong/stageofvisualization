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
            <?php
            $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_box_view.php', [
                'order' => $order,
                'role' => $role
            ]);
            ?>
        </section>

        <!-- HISTORY -->
        <section>
            <?php //$this->load->view(TEMPLATE_FOLDER . 'order/detail/_history_view.php'); 
            ?>
        </section>
    </div>
</div>