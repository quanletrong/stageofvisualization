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
                        <div class="col-md-3 mb-2">
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
                        <div class="col-md-3 mb-2 ">
                            <small>&nbsp;</small>
                            <div class="d-flex" style="gap:5px; align-items: center;">
                                <button type="submit" class="btn btn-primary" title="Tìm kiếm"><i class="fas fa-search"></i> Tìm kiếm</button>
                                <a href="kpi" class="btn" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
                            </div>

                        </div>

                        <!-- vị trí nút export table -->
                        <div class="col-md-3 mb-2 d-flex align-items-end justify-content-end div-export-table"></div>
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
                        <th class="text-center" width="100"></th>
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
                                    <?= isset($item['list_service'][$sv]) ? $item['list_service'][$sv] : 0 ?>
                                </td>
                            <?php } ?>

                            <td class="text-center align-middle font-weight-bold" style="font-size: 1.25rem;">
                                <?= $item['total'] ?>
                            </td>

                            <td class="text-center align-middle font-weight-bold" style="font-size: 1.25rem;">
                                <button class="btn btn-sm btn-success" onclick="ajax_set_rut_tien_ho(this, '<?= $item['id_user'] ?>')"> <i class="fas fa-wallet"></i> RÚT TIỀN</button>
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
        }).buttons().container().appendTo('#filter_form .div-export-table');
    });

    function ajax_set_rut_tien_ho(btn, id_user) {
        if (confirm('Bạn muốn thực hiện yêu cầu rút tiền cho tài khoản khác?')) {
            let old_text = $(btn).html();

            $(btn).html('<i class="fas fa-sync fa-spin"></i>');
            $(btn).prop("disabled", true);

            $.ajax({
                url: `withdraw/ajax_set_rut_tien_ho`,
                data: {
                    id_user: id_user
                },
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    let kq = JSON.parse(data);
                    if (kq.status) {
                        toasts_success();
                    } else {
                        toasts_danger(kq.error);
                    }

                    $(btn).closest('tr').remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        }
    }
</script>