<?php ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Điều kiện JOIN đơn mới</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Điều kiện JOIN đơn mới</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Điều kiện JOIN đơn mới đối với EDITOR</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">

                    <form method="post" action="setting/max_order_working">
                        
                        <div class="d-flex" style="align-items: center;">
                            <div class="mr-2">Số lượng đơn chưa hoàn thành không được vượt quá</div>
                            <input id="max_order_working" name="max_order_working" class="form-control mr-2" value="<?= $setting['max_order_working'] ?>" style="width: 50px;">
                            <div>đơn.</div>
                        </div>

                        <div style="display: flex; justify-content: flex-start;">
                            <button type="submit" class="btn btn-sm btn-danger m-1">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(function() {

    })
</script>