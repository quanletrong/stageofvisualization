<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thư viện ảnh thiết kế</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Thư viện ảnh thiết kế</li>
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
                                <h3 class="card-title">Thư viện ảnh thiết kế</h3>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-library" data-type="add">
                                    Thêm mới ảnh
                                </button>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="GET" action="library">
                                <div class="row" style="background-color: #dee2e6; padding-top: 1rem; margin-bottom: 1rem;">

                                    <!-- Status -->
                                    <div class="col-md-3">
                                        <select class="select2" style="width: 100%;" name="sstatus" data-minimum-results-for-search="Infinity">
                                            <option value="">Tất trạng thái</option>
                                            <option value="1" <?= @$_GET['sstatus'] == '1' ? 'selected' : '' ?>>Hiển thị</option>
                                            <option value="0" <?= @$_GET['sstatus'] == '0' ? 'selected' : '' ?>>Không hiển thị</option>
                                        </select>
                                    </div>
                                    <!-- Phòng -->
                                    <div class="col-md-3">
                                        <select class="select2" style="width: 100%;" name="sid_room">
                                            <option value="">Tất loại phòng</option>
                                            <?php foreach ($list_room as $rm) { ?>
                                                <option value="<?= $rm['id_room'] ?>" <?= @$_GET['sid_room'] == $rm['id_room'] ? 'selected' : '' ?>><?= $rm['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- Phong cách -->
                                    <div class="col-md-3">

                                        <select class="select2" style="width: 100%;" name="sid_style">
                                            <option value="">Tất cả phong cách</option>
                                            <?php foreach ($list_style as $st) { ?>
                                                <option value="<?= $st['id_style'] ?>" <?= @$_GET['sid_style'] == $st['id_style'] ? 'selected' : '' ?>><?= $st['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- TÌM -->
                                    <div class="col-md-3 mb-3">
                                        <button type="submit" class="btn btn-primary w-50"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width=50>STT</th>
                                        <th style="min-width: 200px; width: 200px;">Tên</th>
                                        <th style="min-width: 200px; width: 200px;">Phòng</th>
                                        <th style="min-width: 200px; width: 200px;">Phong cách</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Trước/Sau</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 1; ?>
                                    <?php foreach ($list_library as $id_library => $item) { ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $index++ ?></td>
                                            <td class="align-middle"><?= $item['name'] ?></td>
                                            <td class="align-middle"><?= $item['room_name'] ?></td>
                                            <td class="align-middle"><?= $item['style_name'] ?></td>
                                            <td class="align-middle text-center"><img data-src='<?= $item['image_path'] ?>' width="100" class="rounded lazy"></td>

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
                                                <a href="#" class="btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#modal-library" data-type="edit" data-library="<?= htmlentities(json_encode($item)) ?>">
                                                    Sửa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th style="min-width: 200px; width: 200px;">Tên</th>
                                        <th style="min-width: 200px; width: 200px;">Phòng</th>
                                        <th style="min-width: 200px; width: 200px;">Phong cách</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Trước/Sau</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </tfoot>
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
<?php $this->load->view('v2023/library/model_add_edit_view'); ?>

<script>
    $(function() {

        $('.lazy').lazy();
        
        $("#example1").DataTable({
            "lengthChange": true,
            "pageLength": 50,
            "responsive": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('.select2').select2();
    });
</script>