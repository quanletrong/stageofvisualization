<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách đơn hàng</h1>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Danh sách đơn hàng</h3>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">JID</th>
                                        <th class="text-center">CID</th>
                                        <th class="text-center">DATE</th>
                                        <th class="text-center">JOB TYPE</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">TIME</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php $index = 1; ?>
                                    <?php foreach ($list_order as $id_order => $order) { ?>
                                        <tr>
                                            <td class="align-middle text-center">OID<?=$order['id_order']?></td>
                                            <td class="align-middle text-center">UID<?=$order['id_user']?></td>
                                            <td class="align-middle text-center"><span title="<?=$order['create_time']?>"><?=timeSince($order['create_time'])?> trước</span></td>
                                            <td class="align-middle text-center">3D Floor Plan</td>
                                            <td class="align-middle text-center"><?=$order['total']?></td>
                                            <td class="align-middle text-center">Pending</td>
                                            <td class="align-middle text-center">3h:15p</td>
                                            <td class="align-middle text-center"><a href="order/detail/<?=$id_order?>">[VIEW JOB]</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">JID</th>
                                        <th class="text-center">CID</th>
                                        <th class="text-center">DATE</th>
                                        <th class="text-center">JOB TYPE</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">TIME</th>
                                        <th class="text-center">ACTION</th>
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

<script>
    $(function() {

        $("#example1").DataTable({
            "lengthChange": true,
            "pageLength": 50,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>