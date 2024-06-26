<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-style" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_style" method="post" action="<?= site_url('style') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_style" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên phong cách thiết kế</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên phong cách thiết kế">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Mô tả phong cách thiết kế</label>
                                    <textarea class="form-control" id="sapo" name="sapo" placeholder="Nhập mô tả phong cách thiết kế" rows="4"></textarea>
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
                                    <label>Ảnh chính</label>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-warning" onclick="quanlt_upload(this);" data-callback="cb_upload_image_service" data-target="#image">
                                            <i class="fas fa-upload"></i> Upload ảnh
                                        </button>
                                        <input type="hidden" name="image" id="image">
                                        <span id="image-error" class="invalid-feedback" style="font-size: 80%; color: red;">
                                            Tin này cần tối thiểu 1 ảnh.
                                        </span>
                                    </div>
                                    <small>Ảnh sẽ dùng trong phần tạo đơn và bảng giá.</small>
                                    <img src="" id="image_pre" class="rounded img-fluid w-100 shadow mb-3" />
                                </div>
                            </div>

                            <!-- Danh sách ảnh -->
                            <div class="w-100">
                                <input type="hidden" name="slide" id="slide" />
                                <div id="slide_div">
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
                                            <table class="table" id="table_add_slide">
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
                                                            <button type="button" onclick="add_slide()" class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
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

<!-- modal full image -->
<div class="modal fade" id="modal-full-image" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="" class="w-100">
            </div>
        </div>
    </div>
</div>

<script>
    var SLIDE = {};
    $(function() {

        $('#frm_style').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                $(form).find('input[name="slide"]').val(JSON.stringify(SLIDE))
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


        $('#modal-style').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);
            if (type == 'edit') {
                var style = button.data('style');
                $('#frm_style input[name=action]').val('edit');
                $('#frm_style input[name=id_style]').val(style.id_style);
                modal.find('.modal-title').text(`Sửa thông tin - ${style.name}`);
                modal.find('.modal-body #name').val(style.name);
                modal.find('.modal-body #sapo').val(style.sapo);
                modal.find('.modal-body #image').val(style.image);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(style.status));
                modal.find('.modal-body #image_pre').attr('src', style.image_path);

                try {
                    SLIDE = JSON.parse(style.slide);
                    SLIDE = isEmpty(SLIDE) ? {} : SLIDE;
                    render_slide();
                    modal.find('.modal-body #slide').val(style.slide);
                } catch (error) {
                    SLIDE = {};
                }

            } else {
                $('#frm_style input[name=action]').val('add');
                $('#frm_style input[name=id_style]').val('');
                modal.find('.modal-title').text(`Thêm phong cách thiết kế`);
                modal.find('.modal-body #name').val('');
                modal.find('.modal-body #sapo').val('');
                modal.find('.modal-body #image').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', true);
                modal.find('.modal-body #image_pre').attr('src', '');

                SLIDE = {}
                $('#table_add_slide tbody').html('');
                modal.find('.modal-body #slide').val('');
            }
        });

        $('#modal-full-image').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var src = button.data('src');
            if (src === undefined || src === '') {
                src = button.attr('src');
            }
            var modal = $(this);
            modal.find('.modal-body img').attr('src', src);
        })
    });

    function cb_upload_image_service(res, btn) {
        let target = $(btn).data('target');
        $(`${target}_pre`).attr('src', res.link);
        $(`${target}-error`).hide();
    }

    function cb_upload_image_slide(res, btn) {
        let target = $(btn).data('target');
        $(`${target}_pre`).attr('src', res.link);
        let slide_id = $(target).data('id');
        SLIDE[slide_id].image = res.link;

        $(`${target}_pre`).data('src', res.link);
        $(`#${slide_id} .btn-full-image`).data('src', res.link);
    }

    // <!-- xu lý thêm ảnh slide -->
    function add_slide() {
        let row_last = $('#table_add_slide tbody tr').last();
        if (row_last.find('input').val() != '' && row_last.find('img').attr('src') != '') {
            let slide_id = Date.now();

            SLIDE[slide_id] = {
                'name': '',
                'image': ''
            }

            $('#table_add_slide tbody').append(html_row_image(slide_id));
            $('#table_add_slide tbody tr').last().find('input').focus();
        } else {
            $('#table_add_slide tbody tr').last().find('input').focus();
        }
    }

    function html_row_image(slide_id) {
        let row_new = `<tr id='${slide_id}'>
            <td class="align-middle">
                <input name="" class="form-control border-0" value="${htmlEntities(SLIDE[slide_id].name)}" onChange="SLIDE[${slide_id}].name = this.value">
            </td>
            <td class="align-middle">
                <img src="${SLIDE[slide_id].image_path}" alt="" class="img-fluid" id="image_${slide_id}_pre"
                    data-toggle="modal" data-target="#modal-full-image" data-src="${SLIDE[slide_id].image_path}"
                >
                <input type="hidden" id="image_${slide_id}" data-id="${slide_id}">
            </td>
            <td class="text-right py-0 align-middle">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-warning" onclick="quanlt_upload(this)" data-callback="cb_upload_image_slide" data-target="#image_${slide_id}" >
                        <i class="fas fa-upload"></i>
                    </button>

                    <button type="button" class="btn btn-danger" onClick="delete SLIDE[${slide_id}]; $('#${slide_id}').remove()"><i class="fas fa-trash"></i></button>

                    <button type="button" class="btn btn-info btn-full-image" data-toggle="modal" data-target="#modal-full-image" data-src="${SLIDE[slide_id].image_path}"><i class="fas fa-eye"></i></button>
                </div>
            </td>
        </tr>`;
        return row_new;
    }

    function render_slide() {
        $('#table_add_slide tbody').html('');
        for (const slide_id in SLIDE) {
            $('#table_add_slide tbody').append(html_row_image(slide_id));
        }
    }
</script>