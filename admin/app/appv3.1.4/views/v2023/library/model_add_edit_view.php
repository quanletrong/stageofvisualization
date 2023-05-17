<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-library" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_library" method="post" action="<?= site_url('library') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_library" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>Loại phòng</label>
                                    <select class="select2" style="width: 100%;" name="id_room">
                                        <?php foreach ($list_room as $id_room => $room) { ?>
                                            <option value="<?= $id_room ?>"><?= $room['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>Phong cách thiết kế</label>
                                    <select class="select2" style="width: 100%;" name="id_style">
                                        <?php foreach ($list_style as $id_style => $style) { ?>
                                            <option value="<?= $id_style ?>"><?= $style['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-1">
                                    <label>Có hiển thị ra ngoài trang người dùng không?</label>
                                </div>

                                <div class="form-group d-flex" style="gap:20px">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>
                            </div>

                            <!-- Danh sách ảnh -->
                            <div class="w-100">
                                <input type="hidden" name="image" id="image" />
                                <div id="image_div">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Danh sách ảnh</h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table" id="table_add_image">
                                                <thead>
                                                    <tr>
                                                        <th class="w-50">Tên ảnh</th>
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
                                                            <button type="button" onclick="add_image()" class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.Danh sách ảnh -->
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
    var SLIDE = {};
    $(function() {
        $('.select2').select2();
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

        $('#frm_library').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                $(form).find('input[name="image"]').val(JSON.stringify(SLIDE))
                form.submit();
            },
            rules: {},
            messages: {},
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


        $('#modal-library').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);
            if (type == 'edit') {
                var library = button.data('library');
                modal.find('.modal-title').text(`Sửa thông tin - ${library.name}`);
                modal.find('.modal-body input[name=action]').val('edit');
                modal.find('.modal-body input[name=id_room]').val(library.id_room);
                modal.find('.modal-body input[name=id_style]').val(library.id_style);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(library.status));
                modal.find('.modal-body #image').val(library.image);
                modal.find('.modal-body #image_pre').attr('src', library.image_path);

                let image_id = Date.now();
                SLIDE[image_id] = {
                    'name' : library.name,
                    'image' : library.image_path,
                };
                render_image();
                $('#table_add_image tfoot').hide();

            } else {
                modal.find('.modal-title').text(`Thêm ảnh vào thư viện`);
                modal.find('.modal-body input[name=action]').val('add');
                modal.find('.modal-body input[name=id_room]').val('');
                modal.find('.modal-body input[name=id_style]').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', true);
                modal.find('.modal-body #image').val('');
                modal.find('.modal-body #image_pre').attr('src', '');

                SLIDE = {}
                $('#table_add_image tbody').html('');
                modal.find('.modal-body #image').val(JSON.stringify(SLIDE));
               
                $('#table_add_image tfoot').show();
            }
        });
    });

    function responsive_filemanager_callback(field_id) {
        var url = jQuery('#' + field_id).val();
        $(`#${field_id}_pre`).attr('src', url).show();
    }

    // <!-- xu lý thêm ảnh image -->
    function add_image() {
        let row_last = $('#table_add_image tbody tr').last();
        if (row_last.find('input').val() != '' && row_last.find('img').attr('src') != '') {
            let image_id = Date.now();

            SLIDE[image_id] = {
                'name': '',
                'image': ''
            }

            let row_new = `<tr id='${image_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0" value="" onChange="SLIDE[${image_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="" alt="" class="img-fluid" id="image_${image_id}_pre">
                    <input type="hidden" id="image_${image_id}" onChange="SLIDE[${image_id}].image = this.value">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_${image_id}" class="btn btn-warning iframe-btn"><i class="fas fa-upload"></i></i></a>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete SLIDE[${image_id}]; $('#${image_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_image tbody').append(row_new);
            $('#table_add_image tbody tr').last().find('input').focus();
        } else {
            $('#table_add_image tbody tr').last().find('input').focus();
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

    function render_image() {
        console.log(SLIDE)
        for (const image_id in SLIDE) {

            let row_new = `<tr id='${image_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0" value="${SLIDE[image_id].name}" onChange="SLIDE[${image_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="${SLIDE[image_id].image}" alt="" class="img-fluid" id="image_${image_id}_pre">
                    <input type="hidden" id="image_${image_id}" onChange="SLIDE[${image_id}].image = this.value">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <a href="<?= ROOT_DOMAIN ?>/filemanager/filemanager/dialog.php?type=1&field_id=image_${image_id}" class="btn btn-warning iframe-btn"><i class="fas fa-upload"></i></i></a>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete SLIDE[${image_id}]; $('#${image_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_image tbody').append(row_new);
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
</script>