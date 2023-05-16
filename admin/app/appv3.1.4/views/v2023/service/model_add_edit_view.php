<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-service" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_service" method="post" action="<?= site_url('service') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_service" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên dịch vụ</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên dịch vụ">
                                </div>

                                <div class="form-group">
                                    <label for="name">Giá dịch vụ</label>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="Ví dụ $39 Per Photo">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Mô tả dịch vụ</label>
                                    <textarea class="form-control" id="sapo" name="sapo" placeholder="Nhập mô tả dịch vụ" rows="4"></textarea>
                                    <small>Phần mô tả sẽ sử dụng trong phần tạo đơn</small>
                                </div>

                                <div class="mb-1">
                                    <label>Có hiển thị ra ngoài trang người dùng không?</label>
                                </div>

                                <div class="form-group d-flex" style="gap:20px">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>Ảnh trước và sau khi thiết kế</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image" class="btn btn-primary iframe-btn">Chọn ảnh</a>
                                        </div>
                                        <input type="text" class="form-control" id="image" name="image" readonly>
                                    </div>
                                    <small>Ảnh sẽ dùng trong phần tạo đơn và bảng giá.</small>
                                    <img src="" id="image_pre" class="rounded img-fluid w-100 shadow mb-3" />
                                </div>
                            </div>

                            <!-- Loại phòng -->
                            <div class="w-100">
                                <input type="hidden" name="room" id="room" />
                                <div id="room_div">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Loại phòng thuộc nhóm dịch</h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table" id="table_add_room">
                                                <thead>
                                                    <tr>
                                                        <th class="w-50">Tên phòng</th>
                                                        <th class="text-center" width="150"></th>
                                                        <th class=""></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="add_room()" class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Loại phòng -->
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
    var ROOM = {};
    $(function() {

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

        $('#frm_service').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                form.submit();
            },
            rules: {
                name: {
                    required: true,
                    minlength: 5,
                    maxlength: 256
                },
                sapo: {
                    required: true,
                    minlength: 5
                },
                price: {
                    required: true,
                    maxlength: 256
                },
                image: {
                    required: true
                }

            },
            messages: {
                name: {
                    required: 'Không được bỏ trống',
                    minlength: 'Tối thiểu 5 ký tự',
                    maxlength: 'Tối đa 256 ký tự',
                },
                sapo: {
                    required: 'Không được bỏ trống',
                    minlength: 'Tối thiểu 5 ký tự',
                    maxlength: 'Tối đa 256 ký tự',
                },
                price: {
                    required: 'Không được bỏ trống',
                    maxlength: 'Tối đa 256 ký tự',
                },
                image: {
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


        $('#modal-service').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);
            if (type == 'edit') {
                var service = button.data('service');
                $('#frm_service input[name=action]').val('edit');
                $('#frm_service input[name=id_service]').val(service.id_service);
                modal.find('.modal-title').text(`Sửa thông tin - ${service.name}`);
                modal.find('.modal-body #name').val(service.name);
                modal.find('.modal-body #sapo').val(service.sapo);
                modal.find('.modal-body #price').val(service.price);
                modal.find('.modal-body #image').val(service.image);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(service.status));
                modal.find('.modal-body #image_pre').attr('src', service.image_path);

                try {
                    ROOM = JSON.parse(service.room);
                    render_room();
                    modal.find('.modal-body #room').val(service.room);
                } catch (error) {
                    ROOM = {};
                }

            } else {
                $('#frm_service input[name=action]').val('add');
                $('#frm_service input[name=id_service]').val('');
                modal.find('.modal-title').text(`Thêm dịch vụ`);
                modal.find('.modal-body #name').val('');
                modal.find('.modal-body #sapo').val('');
                modal.find('.modal-body #price').val('');
                modal.find('.modal-body #image').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', true);
                modal.find('.modal-body #image_pre').attr('src', '');

                ROOM = {}
                $('#table_add_room tbody').html('');
                modal.find('.modal-body #room').val('');
            }
        });
    });

    function responsive_filemanager_callback(field_id) {
        var url = jQuery('#' + field_id).val();
        $(`#${field_id}_pre`).attr('src', url).show();
    }

    // <!-- xu lý thêm phong -->
    function add_room() {
        let row_last = $('#table_add_room tbody tr').last();
        if (row_last.find('input').val() != '' && row_last.find('img').attr('src') != '') {
            let room_id = Date.now();

            ROOM[room_id] = {
                'name': '',
                'image': ''
            }

            let row_new = `<tr id='${room_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0" value="" onChange="ROOM[${room_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="" alt="" class="img-fluid" id="image_${room_id}_pre">
                    <input type="hidden" id="image_${room_id}" onChange="ROOM[${room_id}].image = this.value">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_${room_id}" class="btn btn-warning iframe-btn"><i class="fas fa-upload"></i></i></a>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete ROOM[${room_id}]; $('#${room_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_room tbody').append(row_new);
            $('#table_add_room tbody tr').last().find('input').focus();
        } else {
            $('#table_add_room tbody tr').last().find('input').focus();
        }

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
    }

    function render_room() {
        for (const room_id in ROOM) {

            let row_new = `<tr id='${room_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0" value="${ROOM[room_id].name}" onChange="ROOM[${room_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="${ROOM[room_id].image}" alt="" class="img-fluid" id="image_${room_id}_pre">
                    <input type="hidden" id="image_${room_id}" onChange="ROOM[${room_id}].image = this.value">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_${room_id}" class="btn btn-warning iframe-btn"><i class="fas fa-upload"></i></i></a>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete ROOM[${room_id}]; $('#${room_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_room tbody').append(row_new);
        }
    }
</script>