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
                        <li class="breadcrumb-item active">Cài đặt trang chủ</li>
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
                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/slide.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/why_virtually_stage_view.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/why_stuccco_virtual_staging.php') ?>
                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/frequently_asked_questions.php') ?>
                </div>
                <div class="col-md-6">
                   
                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/happiness_guaranteed.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/feedback.php') ?>

                    <?php $this->load->view(TEMPLATE_FOLDER . 'setting/home/inc/partner.php') ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>