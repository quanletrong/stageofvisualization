<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-voucher" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_voucher" method="post" action="<?= site_url('voucher') ?>">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="id_voucher" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="name">CODE</label>
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Nhập CODE giảm giá">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Mô tả</label>
                                    <textarea class="form-control" id="note" name="note" placeholder="Nhập mô tả" rows="4"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Hết hạn</label>
                                    <input type="text" class="form-control" id="expire" name="expire">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Giảm giá</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control w-75" id="value" name="value">
                                        <select class="form-control w-25" id="value_unit" name="value_unit">
                                            <option value="1">%</option>
                                            <option value="2">VNĐ</option>
                                            <option value="3">$</option>
                                            <option value="4">EUR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-1">
                                    <label>Trạng thái dịch vụ <small>ON đang cung cấp - OFF ngừng cung cấp</small></label>

                                </div>

                                <div class="form-group d-flex" style="gap:20px">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="sapo">Cấp mã cho tài khoản</label>
                                    <div>
                                        <select class="form-control select2" id="id_assign" name="id_assign" style="width: 100%;">
                                            <?php foreach ($list_sale as $id_sale => $sale) { ?>
                                                <option value="<?= $id_sale ?>"><?= $sale['username'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="sapo">Mã giảm giá đã được sử dụng <small id="used_time"></small></label>
                                    <div class="d-flex" style="gap:10px;">
                                        <div class="w-50">
                                            <small>ID đơn hàng</small>
                                            <input type="text" class="form-control " id="id_order" disabled>
                                        </div>
                                        <div class="w-50">
                                            <small>CODE đơn hàng</small>
                                            <input type="text" class="form-control" id="code_order" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex" style="gap:10px">
                                        <div class="w-50">
                                            <small>Tài khoản khách hàng</small>
                                            <input type="text" class="form-control" id="used" disabled>
                                        </div>
                                        <div class="w-50">
                                            <small>CODE khách hàng</small>
                                            <input type="text" class="form-control" id="code_user_used" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">

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
    var ROOM = {}; // các hạng mục thiết kế thuộc dịch vụ
    $(function() {

        $('#id_assign').select2({
            minimumResultsForSearch: -1
        });

        $('#frm_voucher').validate({
            submitHandler: function(form) {

                if ($(form).find('input[name="image"]') == '') {
                    $('#image-error').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#image-error").offset().top
                    }, 2000);

                    $(form).find('button[type="submit"]').attr('disabled', false);

                } else {

                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                    form.submit();
                }
            },
            rules: {
                name: {
                    required: true,
                    minlength: 5,
                    maxlength: 256
                },
                type_voucher: {
                    required: true,
                    minlength: 2,
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
                type_voucher: {
                    required: 'Không được bỏ trống',
                    minlength: 'Tối thiểu 2 ký tự',
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


        $('#modal-voucher').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);
            if (type == 'edit') {
                var voucher = button.data('voucher');
                $('#frm_voucher input[name=action]').val('edit');
                $('#frm_voucher input[name=id_voucher]').val(voucher.id_voucher);
                //left
                modal.find('.modal-title').text(`Sửa mã - ${voucher.code}`);
                modal.find('.modal-body #code').val(voucher.code);
                modal.find('.modal-body #note').val(voucher.note);
                modal.find('.modal-body #expire').val(voucher.expire);
                modal.find('.modal-body #value_unit').val(voucher.value_unit).change();
                modal.find('.modal-body #value').val(voucher.value);
                modal.find('.modal-body #status').bootstrapSwitch('state', !parseInt(voucher.status));

                //right
                modal.find('.modal-body #used_time').text(`[${voucher.used_time}]`);
                modal.find('.modal-body #id_assign').val(`${voucher.id_assign}`);
                modal.find('.modal-body #id_order').val(`#OID${voucher.id_order}`);
                modal.find('.modal-body #code_order').val(`${voucher.code_order}`);
                modal.find('.modal-body #used').val(`${voucher.used}`);
                modal.find('.modal-body #code_user_used').val(`${voucher.code_user_used}`);

            } else {
                $('#frm_voucher input[name=action]').val('add');
                $('#frm_voucher input[name=id_voucher]').val('');
                
                //left
                modal.find('.modal-title').text(`Thêm mã giảm giá`);
                modal.find('.modal-body #code').val('');
                modal.find('.modal-body #note').val('');
                modal.find('.modal-body #expire').val('');
                modal.find('.modal-body #value').val('');
                modal.find('.modal-body #status').bootstrapSwitch('state', false);

                //right
                modal.find('.modal-body #used_time').text(``);
                modal.find('.modal-body #id_order').val(``);
                modal.find('.modal-body #code_order').val(``);
                modal.find('.modal-body #used').val(``);
                modal.find('.modal-body #code_user_used').val(``);
            }

            $('#id_assign').select2({
                minimumResultsForSearch: -1
            });
        });
    });
</script>