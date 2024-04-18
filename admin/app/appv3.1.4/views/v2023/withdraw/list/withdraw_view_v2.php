<?php ?>
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- BẢNG DỮ LIỆU -->
            <table id="example1" class="table table-bordered table-striped">
                <thead class="thead-danger">
                    <tr>
                        <th class="text-center">DATE CREATED</th>
                        <th class="text-center">CODE USER</th>
                        <th class="text-left">FULLNAME</th>
                        <th class="text-center">ROLE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">TOTAL</th>
                        <th class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdraw as $id_withdraw => $item) { ?>
                        <tr class="text-default">
                            <td class="align-middle text-center"><?= date('d/m/Y - H:i ', strtotime($item['create_time'])) ?></td>
                            <td class="align-middle text-center"><?= $item['code_user'] == '' ? 'UID' . $item['id_user'] : $item['code_user'] ?></td>
                            <td class="align-middle text-left d-flex" style="gap:10px; align-items: center;">
                                <img src="<?= url_image($item['avatar'], FOLDER_AVATAR) ?>" class="img-circle shadow" style="width: 36px; aspect-ratio: 1; object-fit: cover;">
                                <div>
                                    <?= $item['fullname'] ?><br>
                                    <small><?= $item['username'] ?></small>
                                </div>
                            </td>
                            <td class="align-middle text-center"><?= role_name($item['role']) ?></td>
                            <td class="align-middle text-center">
                                <?= $item['status'] ? '<span class="badge bg-success">DONE</span>' : '<span class="badge bg-danger">PENDING</span>' ?>
                            </td>
                            <td class="align-middle text-center"><?= $item['custom'] ?></td>

                            <?php $params = $item['id_user'] ."?status=".$item['status']."&create_time=".$item['create_time']; ?>
                            <td class="align-middle text-center"><a href="withdraw/detail/<?= $params ?>">[DETAIL]</a></td>
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

<script>
    $(function() {

        $("#example1").DataTable({
            "pageLength": 1000,
            "responsive": true,
            "autoWidth": false,
            "lengthChange": false,
            "searching": true,
            "buttons": ["excel", "pdf"],
            "ordering": true,
            "order": []
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>