<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>KPI</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">KPI</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form method="GET" action="kpi" id="filter_form">
                <div class="rounded px-2 py-1 pb-0 mb-2" style="background-color: #dee2e6;">
                    <div class="row">
                        <!-- Lọc Khoảng ngày-->
                        <div class="col-md-3 mb-2">
                            <small>Khoảng ngày</small>
                            <div class="input-group">
                                <input type="text" class="form-control daterange-btn" placeholder="Nhập khoảng ngày" id="create_time" value="">
                                <input type="hidden" name="filter_fdate" value="<?= $filter_fdate ?>">
                                <input type="hidden" name="filter_tdate" value="<?= $filter_tdate ?>">
                                <div class="input-group-append daterange-btn" id="">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Lọc theo user -->
                        <div class="col-md-3 mb-2">
                            <small>Tìm theo tài khoản</small>
                            <select class="select2" name="filter_id_user" id="filter_id_user">
                                <option value=""></option>
                                <?php foreach ($all_user as $id => $it) { ?>
                                    <option value="<?= $id ?>" <?= $id == $filter_id_user ? 'selected' : '' ?>><?= $it['username'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Lọc theo user -->
                        <div class="col-md-2 mb-2">
                            <small>Role</small>
                            <select class="select2" name="filter_role" id="filter_role">
                                <option value=""></option>
                                <option value="<?= ADMIN ?>" <?= ADMIN == $filter_role ? 'selected' : '' ?>>ADMIN</option>
                                <option value="<?= SALE ?>" <?= SALE == $filter_role ? 'selected' : '' ?>>SALE</option>
                                <option value="<?= QC ?>" <?= QC == $filter_role ? 'selected' : '' ?>>QC</option>
                                <option value="<?= EDITOR ?>" <?= EDITOR == $filter_role ? 'selected' : '' ?>>EDITOR</option>
                            </select>
                        </div>

                        <!-- search -->
                        <div class="col-md-2 mb-2 ">
                            <small>&nbsp;</small>
                            <div class="d-flex" style="gap:5px; align-items: center;">
                                <button type="submit" class="btn btn-primary" title="Tìm kiếm"><i class="fas fa-search"></i> Tìm kiếm</button>
                                <a href="kpi" class="btn" title="Làm mới bộ lọc"><i class="fas fa-sync-alt"></i></a>
                            </div>

                        </div>

                        <!-- vị trí nút export table -->
                        <div class="col-md-2 mb-2 d-flex align-items-end justify-content-end"></div>
                    </div>
                </div>
            </form>
            <table id="example1" class="table table-bordered table-striped">
                <thead class="thead-danger">
                    <tr>
                        <th class="text-center" width="50">STT</th>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th class="text-center">Role</th>
                        <?php foreach ($list_service as $sv) { ?>
                            <th class="text-center" width="100"><?= $sv ?></th>
                        <?php } ?>
                        <th class="text-center" width="50">TOTAL</th>
                        <!-- <th class="text-center" width="50">[VIEW]</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($list_kpi as $id_user => $item) { ?>

                        <tr>
                            <td class="align-middle text-center"><?= $index++ ?></td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center" style="gap:10px">
                                    <img src="<?= $item['avatar_url'] ?>" alt="<?= $item['username'] ?>" class="img-circle shadow" style="margin-bottom: 5px; width: 36px; aspect-ratio: 1; object-fit: cover;">
                                    <span><?= $item['fullname'] ?></span>
                                </div>
                            </td>
                            <td class="align-middle"><?= $item['username'] ?></td>

                            <td class="align-middle text-center"><?= get_role_name($item['role']) ?></td>

                            <?php foreach ($list_service as $sv) { ?>
                                <td class="text-center align-middle" style="font-size: 1.25rem;">
                                    <?= @$item['list_service'][$sv]['total'] ?>
                                </td>
                            <?php } ?>

                            <td class="text-center align-middle font-weight-bold" style="font-size: 1.25rem;">
                                <?= $item['total'] ?>
                            </td>

                            <!-- <td class="align-middle text-center"> <a href="#"> [VIEW] </a> </td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
            placeholder: 'select..'
        });

        $("#example1").DataTable({
            "pageLength": 1000,
            "responsive": true,
            "autoWidth": false,
            "lengthChange": false,
            "searching": false,
            "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#filter_form .col-md-2:eq(-1)');

        // ngày tạo
        //Set mặc định ngày
        let startDate = moment().subtract(29, 'days');
        let endDate = moment();
        try {
            <?php if ($filter_fdate != '') { ?>
                startDate = moment('<?= $filter_fdate ?>');
            <?php } ?>

            <?php if ($filter_tdate != '') { ?>
                endDate = moment('<?= $filter_tdate ?>');
            <?php } ?>
        } catch (error) {
            console.log(error);
        }

        //Date range as a button
        $('.daterange-btn').daterangepicker({
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(6, 'days'), moment()],
                    '30 ngày trước': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                },
                startDate: startDate,
                endDate: endDate,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            },
            function(start, end) {
                $('input[name="filter_fdate"]').val(start.format('YYYY-MM-D'))
                $('input[name="filter_tdate"]').val(end.format('YYYY-MM-D'))
            }
        )
    });
</script>