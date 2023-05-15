<?php ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách phòng</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Danh sách phòng</li>
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
                                <h3 class="card-title">Danh sách phòng</h3>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-room" data-type="add">
                                    Thêm mới
                                </button>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width: 50px; width: 50px;">STT</th>
                                        <th style="min-width: 100px; width: 100px;">Tên phòng</th>
                                        <th class="" style="min-width: 150px; width: 150px;">Dịch vụ</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Trước</th>
                                        <th class="text-center" style="min-width: 70px; width: 70px;">Sau</th>
                                        <th style="min-width: 150px; width: 150px;">Bởi</th>
                                        <th class="text-center" style="min-width: 80px; width: 80px;">Trạng thái</th>
                                        <th style="min-width: 70px; width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $index = 1; ?>
                                    <?php foreach ($list as $id_room => $item) { ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $index++ ?></td>
                                            <td class="align-middle"><?= $item['name'] ?></td>
                                            <td class="align-middle"><?= @$item['service_name'] ?></td>
                                            <td class="align-middle text-center"><img src='<?= $item['image_before_path'] ?>' width="100" class="rounded"></td>
                                            <td class="align-middle text-center"><img src='<?= $item['image_after_path'] ?>' width="100" class="rounded"></td>

                                            <td class="align-middle">
                                                <strong><?= $item['username'] ?></strong><br>
                                                Tạo: <?= date('H:i d/m/Y', strtotime($item['create_time'])) ?>
                                                <?= strtotime($item['update_time']) > 0 ? 'Sửa:' . date('H:i d/m/Y', strtotime($item['update_time'])) : '' ?>

                                            </td>
                                            <td class="align-middle text-center">
                                                <?php
                                                if ($item['status'] === '1') {
                                                    echo '<span class="badge bg-primary">Hiển thị</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">Ngừng hiển thị</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-sm btn-primary w-100" data-toggle="modal" data-target="#modal-room" data-type="edit" data-room="<?= htmlentities(json_encode($item)) ?>">
                                                    Sửa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th>Tên phòng</th>
                                        <th class="" style="min-width: 150px; width: 150px;">Dịch vụ</th>
                                        <th class="text-center">Ảnh trước</th>
                                        <th class="text-center">Ảnh sau</th>
                                        <th>Bởi</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th>Action</th>
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

<!-- modal edit -->
<div class="modal fade" id="modal-room" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_room" method="post" action="<?= site_url('room') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_room" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>Loại dịch vụ</label>
                                    <select class="select2" style="width: 100%;" name="id_service">
                                        <option value="0">Chọn</option>
                                        <?php foreach ($list_service as $id_service => $service) { ?>
                                            <option value="<?= $id_service ?>"><?= $service['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="name">Tên phòng</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên phòng">
                                </div>

                                <div class="mb-1">
                                    <label>Có hiển thị ra ngoài trang người dùng không?</label>
                                </div>
                                <div class="form-group d-flex" style="gap:20px">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="status_1" name="status" value="1">
                                        <label for="status_1" class="custom-control-label">Hiển thị</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="status_0" name="status" value="0">
                                        <label for="status_0" class="custom-control-label">Ngừng hiển thị</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>Ảnh trước khi thiết kế</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_before" class="btn btn-primary iframe-btn">Chọn ảnh</a>
                                        </div>
                                        <input type="text" class="form-control" id="image_before" name="image_before" readonly>
                                    </div>
                                    <small>Ảnh sẽ dùng trong phần tạo đơn và bảng giá. Kích thước ảnh nên dùng 4x3</small>
                                    <img src="" id="image_before_pre" class="rounded img-fluid w-100 shadow mb-3" />

                                    <label>Ảnh sau khi thiết kế</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_after" class="btn btn-primary iframe-btn">Chọn ảnh</a>
                                        </div>
                                        <input type="text" class="form-control" id="image_after" name="image_after" readonly>
                                    </div>
                                    <small>Ảnh sẽ dùng trong phần tạo đơn và bảng giá. Kích thước ảnh nên dùng 4x3</small>
                                    <img src="" id="image_after_pre" class="rounded img-fluid w-100 shadow" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-lg btn-danger">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function() {
        $('.select2').select2();
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('.iframe-btn').fancybox({
            'type': 'iframe',
            'autoScale': true,
            'iframe': {
                'css': {
                    'width': '1024px',
                    'height': '800px'
                }
            }
        });

        $('#frm_room').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                form.submit();
            },
            rules: {
                name: {
                    required: true,
                    minlength: 5,
                    maxlength: 256
                },
                image_before: {
                    required: true
                },
                image_after: {
                    required: true
                }

            },
            messages: {
                name: {
                    required: 'Không được bỏ trống',
                    minlength: 'Tối thiểu 5 ký tự',
                    maxlength: 'Tối đa 256 ký tự',
                },
                image_before: {
                    required: 'Không được bỏ trống'
                },
                image_after: {
                    required: 'Không được bỏ trống'
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group, .input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        $('#modal-room').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);
            if (type == 'edit') {
                var room = button.data('room');
                $('#frm_room input[name=action]').val('edit');
                $('#frm_room input[name=id_room]').val(room.id_room);
                modal.find('.modal-title').text(`Sửa thông tin - ${room.name}`);
                modal.find('.modal-body #name').val(room.name);
                modal.find('.modal-body #image_before').val(room.image_before);

                modal.find('.modal-body #image_after').val(room.image_after);
                modal.find(`.modal-body select[name=id_service][value=${room.id_service}]`).prop('selected', true);
                modal.find(`.modal-body input:radio[name=status][value=${room.status}]`).prop('checked', true);
                modal.find('.modal-body #image_before_pre').attr('src', room.image_before_path);
                modal.find('.modal-body #image_after_pre').attr('src', room.image_after_path);
            } else {
                $('#frm_room input[name=action]').val('add');
                $('#frm_room input[name=id_room]').val('');
                modal.find('.modal-title').text(`Thêm phòng`);
                modal.find('.modal-body #name').val('');
                modal.find('.modal-body #image_before').val('');
                modal.find('.modal-body #image_after').val('');
                modal.find(`.modal-body input:radio[name=status][value=1]`).prop('checked', true);
                modal.find('.modal-body #image_before_pre').attr('src', '');
                modal.find('.modal-body #image_after_pre').attr('src', '');
            }

        })

    });

    function responsive_filemanager_callback(field_id) {
        var url = jQuery('#' + field_id).val();
        $(`#${field_id}_pre`).attr('src', url).show();
    }
</script>