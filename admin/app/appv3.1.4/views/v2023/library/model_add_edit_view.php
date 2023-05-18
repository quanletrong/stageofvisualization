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
                                                        <th class="w-25 text-center"></th>
                                                        <th class="w-25"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            <label for="fileButton" class="btn btn-warning">
                                                                <i class="fas fa-upload"></i>
                                                            </label>
                                                        </td>
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

<!-- upload anh -->
<form id="frm_files" enctype="multipart/form-data" action="upload" method="post">
    <input type="file" id="fileButton" name="file[]" accept="image/*" multiple hidden />
    <script>
        let IMAGE_ACTIVE = 0;
        $(function() {
            $("#frm_files").on('change', '#fileButton', function(e) {
                e.preventDefault();
                var formData = new FormData($(this).parents('form')[0]);

                $.ajax({
                    url: 'upload',
                    type: 'POST',
                    xhr: function() {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    },
                    success: function(response) {
                        try {
                            response = JSON.parse(response);

                            if (response.status) {

                                if (Object.keys(response.data).length) {
                                    for (const [key, value] of Object.entries(response.data)) {
                                        if (value.status) {

                                            if (IMAGE_ACTIVE > 0) {

                                                SLIDE[IMAGE_ACTIVE] = {
                                                    'name': value.name,
                                                    'image': value.link,
                                                };
                                                IMAGE_ACTIVE = 0;
                                            } else {
                                                let image_id = makeid(5);
                                                SLIDE[image_id] = {
                                                    'name': value.name,
                                                    'image': value.link,
                                                };
                                            }

                                        } else {
                                            let error_text = '';
                                            for (const [key, error] of Object.entries(value.error)) {
                                                error_text += '- ' + error + '<br/>';
                                            }
                                            toasts_danger(`${error_text} Ảnh: ${value.name} `, 'Thất bại')
                                        }
                                    }

                                    render_image();

                                } else {
                                    toasts_danger('Xin lỗi, không lưu được ảnh', 'Thất bại')
                                }

                            } else {
                                toasts_danger(response.error, 'Thất bại')
                            }

                        } catch (error) {
                            console.log(error)
                            toasts_danger('Xin lỗi, upload ảnh đang gặp vấn đề!', 'Thất bại')
                        }
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            });
        })
    </script>
</form>

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
                    'name': library.name,
                    'image': library.image_path,
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

            $('#table_add_image tbody').append(html_row_image(image_id));
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

    function html_row_image(image_id) {
        let row_new = `<tr id='${image_id}'>
            <td class="align-middle">
                <input name="" class="form-control border-0" value="${SLIDE[image_id].name}" onChange="SLIDE[${image_id}].name = this.value">
            </td>
            <td class="align-middle text-center">
                <img src="${SLIDE[image_id].image}" alt="" class="img-fluid w-50" id="image_${image_id}_pre">
                <input type="hidden" id="image_${image_id}" onChange="SLIDE[${image_id}].image = this.value">
            </td>
            <td class="text-right py-0 align-middle">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-warning">
                        <label for="fileButton" onClick="IMAGE_ACTIVE=${image_id}">
                            <i class="fas fa-upload"></i>
                        </label>
                    </button>
                    
                    <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                    <button type="button" class="btn btn-danger" onClick="delete SLIDE[${image_id}]; $('#${image_id}').remove()"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`;

        return row_new;
    }

    function render_image() {
        $('#table_add_image tbody').html('');
        for (const image_id in SLIDE) {
            $('#table_add_image tbody').append(html_row_image(image_id));
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