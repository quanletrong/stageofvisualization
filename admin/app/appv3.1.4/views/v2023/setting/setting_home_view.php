<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cài đặt trang chủ</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/slide.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/why_virtually_stage_view.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/why_stuccco_virtual_staging.php') ?>
                </div>
                <div class="col-md-6">

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/frequently_asked_questions.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/feedback.php') ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>