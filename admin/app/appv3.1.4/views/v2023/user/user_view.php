<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách tài khoản</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách tài khoản</li>
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
                                <h3 class="card-title">Danh sách tài khoản</h3>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-user" data-type="add">
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
                                        <th class="">Username</th>
                                        <th class="">Fullname</th>
                                        <th class="">Phone</th>
                                        <th class="">Email</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 1; ?>
                                    <?php foreach ($list as $id_user => $item) { ?>

                                        <?php $da_khoa = !$item['status']; ?>
                                        <tr <?= $da_khoa ? 'style="text-decoration-line: line-through"' : '' ?>>
                                            <td class="align-middle text-center"><?= $index++ ?></td>
                                            <td class="align-middle"><?= $item['code_user'] ?></td>
                                            <td class="align-middle"><?= $item['username'] ?></td>
                                            <td class="align-middle"><?= $item['fullname'] ?></td>
                                            <td class="align-middle"><?= $item['phone'] ?></td>
                                            <td class="align-middle"><?= $item['email'] ?></td>
                                            <td class="align-middle text-center">
                                                <?php if ($item['role'] == ADMIN) echo 'ADMIN' ?>
                                                <?php if ($item['role'] == SALE) echo 'SALE' ?>
                                                <?php if ($item['role'] == QC) echo 'QC' ?>
                                                <?php if ($item['role'] == EDITOR) echo 'EDITOR' ?>
                                                <?php if ($item['role'] == CUSTOMER) echo 'CUSTOMER' ?>
                                            </td>
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
                                                <?= date('d/m/Y H:i:s ', strtotime($item['create_time'])) ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <a href="#" data-toggle="modal" data-target="#modal-user" data-type="edit" data-user="<?= htmlentities(json_encode($item)) ?>">
                                                    [EDIT]
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
<?php $this->load->view('v2023/user/model_add_edit_view'); 
?>

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