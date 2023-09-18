<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách dịch vụ</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách dịch vụ</li>
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-service" data-type="add">
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
                                        <th style="min-width: 200px; width: 200px;">Dịch vụ</th>
                                        <th class="">Mô tả dịch vụ</th>
                                        <th class="text-center">Mã</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Trước/Sau</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Giá/ảnh $</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 1; ?>
                                    <?php foreach ($list as $id_service => $item) { ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $index++ ?></td>
                                            <td class="align-middle"><?= $item['name'] ?></td>
                                            <td class="align-middle"><?= $item['sapo'] ?></td>
                                            <td class="align-middle text-center"><?= $item['type_service'] ?></td>
                                            <td class="align-middle text-center"><img src='<?= $item['image_path'] ?>' width="100" class="rounded"></td>
                                            <td class="align-middle text-center"><?= $item['price'] ?>$</td>

                                            <td class="align-middle text-center">
                                                <?php
                                                if ($item['status'] === '1') {
                                                    echo '<span class="badge bg-success">ON</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">OFF</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#modal-service" data-type="edit" data-service="<?= htmlentities(json_encode($item)) ?>">
                                                    Sửa
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
<?php $this->load->view('v2023/service/model_add_edit_view');?>

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