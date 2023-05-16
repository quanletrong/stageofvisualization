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

                            <div class="w-100">
                                <label>Nhập ảnh và tên phòng thuộc nhóm dịch vụ này</label>
                                <textarea id="room" name="room"></textarea>
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
                tinymce.get("room").setContent(service.room);
                modal.find('.modal-body #status').bootstrapSwitch('state', parseInt(service.status));
                modal.find('.modal-body #image_pre').attr('src', service.image_path);
            } else {
                // $('#frm_service input[name=action]').val('add');
                // $('#frm_service input[name=id_service]').val('');
                // modal.find('.modal-title').text(`Thêm dịch vụ`);
                // modal.find('.modal-body #name').val('');
                // modal.find('.modal-body #sapo').val('');
                // modal.find('.modal-body #price').val('');
                // modal.find('.modal-body #image').val('');
                // tinymce.get("room").setContent('');
                // modal.find('.modal-body #status').bootstrapSwitch('state', true);
                // modal.find('.modal-body #image_pre').attr('src', '');
            }
        });

        tinymce.init({
            selector: '#room',
            height: "800",
            relative_urls: false,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'help', 'link', 'responsivefilemanager'
            ],
            toolbar: 'responsivefilemanager | undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            menubar: 'favs file edit view insert format tools table help',
            external_filemanager_path: "<?= ROOT_DOMAIN ?>filemanager/filemanager/",
            filemanager_title: "Thư viện ảnh",
            external_plugins: {
                // "responsivefilemanager": "<?= ROOT_DOMAIN ?>filemanager/filemanager/plugin.min.js",
                "filemanager": "<?= ROOT_DOMAIN ?>filemanager/filemanager/plugin.min.js"
            },
            setup: function(ed) {
                ed.on('change', function(e) {
                    $('#room').val(ed.getContent())
                });
            }
        });

    });

    function responsive_filemanager_callback(field_id) {
        var url = jQuery('#' + field_id).val();
        $(`#${field_id}_pre`).attr('src', url).show();
    }
</script>