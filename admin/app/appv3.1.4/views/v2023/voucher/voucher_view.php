<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách khuyến mãi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách khuyến mãi</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Danh sách dịch vụ</h3>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-voucher" data-type="add">
                                    Thêm mới
                                </button>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead class="thead-danger">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th style="min-width: 200px; width: 200px;">CODE</th>
                                        <th class="">Mô tả</th>
                                        <th class="text-right">Giảm giá</th>
                                        <th class="text-center">Hết hạn</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 1; ?>
                                    <?php foreach ($list as $id_voucher => $item) { ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $index++ ?></td>
                                            <td class="align-middle"><?= $item['code'] ?></td>
                                            <td class="align-middle"><?= $item['note'] ?></td>
                                            <td class="align-middle text-right">
                                                <?= voucher_value($item['price'], $item['price_unit']); ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <?= date('d/m/Y H:i:s ', strtotime($item['expire_date'])) ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <?php
                                                if ($item['status'] === '1') {
                                                    echo '<span class="badge bg-danger">OFF</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">ON</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" data-toggle="modal" data-target="#modal-voucher" data-type="edit" data-voucher="<?= htmlentities(json_encode($item)) ?>">
                                                    [VIEW]
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- load modal add edit -->
<?php $this->load->view('v2023/voucher/model_add_edit_view'); ?>

<script>
    $(function() {

        $("#example1").DataTable({
            "lengthChange": true,
            "pageLength": 100,
            "responsive": true,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>