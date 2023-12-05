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

            <!-- LỌC -->
            <form method="GET" action="user" id="filter_form">
                <div class="rounded px-2 py-1 pb-0 mb-2" style="background-color: #dee2e6;">
                    <div class="row">
                        <!-- Lọc theo username -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo user name</small>
                            <input type="text" class="form-control" name="filter_username" id="filter_username" value="<?= htmlentities($filter_username) ?>">
                        </div>

                        <!-- Lọc theo code -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo code user</small>
                            <input type="text" class="form-control" name="filter_code" id="filter_code" value="<?= htmlentities($filter_code) ?>">
                        </div>

                        <!-- Lọc theo user -->
                        <div class="col-md-3 mb-2">
                            <small>Role</small>
                            <select class="select2" name="filter_role" id="filter_role">
                                <option value=""></option>
                                <option value="<?= ADMIN ?>" <?= ADMIN == $filter_role ? 'selected' : '' ?>>ADMIN</option>
                                <option value="<?= SALE ?>" <?= SALE == $filter_role ? 'selected' : '' ?>>SALE</option>
                                <option value="<?= QC ?>" <?= QC == $filter_role ? 'selected' : '' ?>>QC</option>
                                <option value="<?= EDITOR ?>" <?= EDITOR == $filter_role ? 'selected' : '' ?>>EDITOR</option>
                                <option value="<?= CUSTOMER ?>" <?= CUSTOMER == $filter_role ? 'selected' : '' ?>>CUSTOMER</option>
                            </select>
                        </div>

                        <!-- search -->
                        <div class="col-md-3 mb-2 ">
                            <small>&nbsp;</small>
                            <div class="d-flex" style="gap:5px; align-items: center;">
                                <button type="submit" class="btn btn-primary" title="Tìm kiếm"><i class="fas fa-search"></i> Tìm kiếm</button>
                                <a href="user" class="btn" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
                                <button type="button" class="btn" title="Thêm option tìm kiếm" onclick="$('#filter-expand').slideToggle()"><i class="fas fa-sliders-h"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="filter-expand" style="display: none;">

                        <!-- Lọc theo fullname -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo fullname</small>
                            <input type="text" class="form-control" name="filter_fullname" id="filter_fullname" value="<?= htmlentities($filter_fullname) ?>">
                        </div>

                        <!-- Lọc theo phone -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo phone</small>
                            <input type="phone" class="form-control" name="filter_phone" id="filter_phone" value="<?= htmlentities($filter_phone) ?>">
                        </div>

                        <!-- Lọc theo email -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo email</small>
                            <input type="email" class="form-control" name="filter_email" id="filter_email" value="<?= htmlentities($filter_email) ?>">
                        </div>

                        <!-- Lọc theo status -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo trạng thái</small>
                            <select class="select2" name="filter_status" id="filter_status">
                                <option value=""></option>
                                <option value="1" <?= $filter_status == '1' ? 'selected' : '' ?>>HOẠT ĐỘNG (ON)</option>
                                <option value="0" <?= $filter_status == '0' ? 'selected' : '' ?>>ĐÃ KHÓA (OFF)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ACTION BUTTON -->
            <div class="d-flex mt-2" style="gap:10px">

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-user" data-type="add">
                    <i class="fas fa-address-card"></i> Thêm tài khoản
                </button>

                <div style="display: flex; justify-content: flex-end;" id="export"></div>
            </div>
            <!-- TABLE -->
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
                            <td class="align-middle">
                                <div class="d-flex align-items-center" style="gap:5px">
                                    <img src="<?= $item['avatar'] ?>" alt="<?= $item['fullname'] ?>" class="img-circle shadow" style="margin-bottom: 5px; width: 36px; aspect-ratio: 1; object-fit: cover;">
                                    <span><?= $item['fullname'] ?></span>
                                </div>
                            </td>
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

        //select 2
        $('#filter_form .select2').select2({
            closeOnSelect: true,
            allowClear: true,
            placeholder: 'select...'
        });

        $("#example1").DataTable({
            "lengthChange": false,
            "pageLength": 100,
            "responsive": true,
            "autoWidth": false,
            "searching": false,
            "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#export');
    });
</script>