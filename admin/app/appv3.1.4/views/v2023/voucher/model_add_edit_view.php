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
                                <div class="mb-1">
                                    <label>Trạng thái của mã <small>ON đang hoạt động - OFF ngừng hoạt động</small></label>

                                </div>

                                <div class="form-group d-flex" style="gap:20px">
                                    <input type="checkbox" id="status" name="status" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                </div>

                                <div class="form-group">
                                    <label for="name">CODE</label>
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Nhập CODE giảm giá">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Mô tả</label>
                                    <textarea class="form-control" id="note" name="note" placeholder="Nhập mô tả" rows="8"></textarea>
                                </div>

                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="sapo">Cấp mã cho Sale</label>
                                    <div>
                                        <select class="form-control select2" id="voucher_user_sale" multiple="multiple" name="voucher_user_sale[]" style="width: 100%;">
                                            <?php foreach ($list_sale as $id_user => $user) { ?>
                                                <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="sapo">Cấp mã cho Khách hàng</label>
                                    <div>
                                        <select class="form-control select2" id="voucher_user_khach" multiple="multiple" name="voucher_user_khach[]" style="width: 100%;">
                                            <?php foreach ($list_khach as $id_user => $user) { ?>
                                                <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="sapo">Giảm giá</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control w-75" id="price" name="price">
                                        <select class="form-control w-25" id="price_unit" name="price_unit">
                                            <option value="1">%</option>
                                            <option value="2">VNĐ</option>
                                            <option value="3">$</option>
                                            <option value="4">EUR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Giới hạn lần sử dụng</label>
                                    <input type="text" class="form-control" id="limit" name="limit">
                                </div>

                                <div class="form-group">
                                    <label for="sapo">Hết hạn</label>
                                    <input type="text" class="form-control" id="expire_date" name="expire_date">
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

        $('#voucher_user_sale').select2({});
        $('#voucher_user_khach').select2({});

        $('#frm_voucher').validate({
            submitHandler: function(form) {

                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                // $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                form.submit();

                // if ($(form).find('input[name="image"]') == '') {
                //     $('#image-error').show();
                //     $([document.documentElement, document.body]).animate({
                //         scrollTop: $("#image-error").offset().top
                //     }, 2000);

                //     $(form).find('button[type="submit"]').attr('disabled', false);

                // } else {

                //     $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                //     // $(form).find('input[name="room"]').val(JSON.stringify(ROOM))
                //     form.submit();
                // }
            },
            // rules: {
            //     name: {
            //         required: true,
            //         minlength: 5,
            //         maxlength: 256
            //     },
            //     type_voucher: {
            //         required: true,
            //         minlength: 2,
            //         maxlength: 256
            //     },
            //     sapo: {
            //         required: true,
            //         minlength: 5
            //     },
            //     price: {
            //         required: true,
            //         maxlength: 256
            //     },
            //     image: {
            //         required: true
            //     }

            // },
            // messages: {
            //     name: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 5 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     type_voucher: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 2 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     sapo: {
            //         required: 'Không được bỏ trống',
            //         minlength: 'Tối thiểu 5 ký tự',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     price: {
            //         required: 'Không được bỏ trống',
            //         maxlength: 'Tối đa 256 ký tự',
            //     },
            //     image: {
            //         required: 'Không được bỏ trống'
            //     }
            // },
            // errorElement: 'span',
            // errorPlacement: function(error, element) {
            //     error.addClass('invalid-feedback');
            //     element.closest('.form-group, .input-group').append(error);
            // },
            // highlight: function(element, errorClass, validClass) {
            //     $(element).addClass('is-invalid');
            // },
            // unhighlight: function(element, errorClass, validClass) {
            //     $(element).removeClass('is-invalid');
            // }
        });


        $('#modal-voucher').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var type = button.data('type');
            var modal = $(this);

            if (type == 'edit') {
                var voucher = button.data('voucher');
                $('#frm_voucher input[name=action]').val('edit');
                $('#frm_voucher input[name=id_voucher]').val(voucher.id_voucher);

                let voucher_user_sale_selected = [];
                let voucher_user_khach_selected = [];
                for (const [user_id, value] of Object.entries(voucher.voucher_user)) {
                    if (value.role == <?= SALE ?>) {
                        voucher_user_sale_selected.push(user_id.toString());
                    }
                    if (value.role == <?= CUSTOMER ?>) {
                        voucher_user_khach_selected.push(user_id.toString());
                    }
                }

                //left
                modal.find('.modal-title').text(`Sửa mã - ${voucher.code}`);
                modal.find('.modal-body #code').val(voucher.code);
                modal.find('.modal-body #note').val(voucher.note);
                modal.find('.modal-body #expire_date').val(voucher.expire_date);
                modal.find('.modal-body #price_unit').val(voucher.price_unit).change();
                modal.find('.modal-body #price').val(voucher.price);
                modal.find('.modal-body #limit').val(voucher.limit);
                modal.find('.modal-body #status').bootstrapSwitch('state', !parseInt(voucher.status));

                //right
                modal.find('.modal-body #used_time').text(`[${voucher.used_time}]`);
                modal.find('.modal-body #voucher_user_sale').val(voucher_user_sale_selected);
                modal.find('.modal-body #voucher_user_khach').val(voucher_user_khach_selected);

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
                modal.find('.modal-body #expire_date').val('');
                modal.find('.modal-body #price').val('');
                modal.find('.modal-body #limit').val(1);
                modal.find('.modal-body #status').bootstrapSwitch('state', false);

                //right
                modal.find('.modal-body #used_time').text(``);
                modal.find('.modal-body #voucher_user_sale').val([]);
                modal.find('.modal-body #voucher_user_khach').val([]);
                modal.find('.modal-body #id_order').val(``);
                modal.find('.modal-body #code_order').val(``);
                modal.find('.modal-body #used').val(``);
                modal.find('.modal-body #code_user_used').val(``);
            }

            $('#voucher_user_sale').select2({});
            $('#voucher_user_khach').select2({});
        });
    });
</script>