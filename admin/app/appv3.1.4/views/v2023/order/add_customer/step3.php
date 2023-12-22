<div class="container py-5 d-none" id="step-3">
    <div class="fw-semibold fs-5 mb-3">THANH TOÁN</div>
    <div class="row">
        <div class="col-12">
            <div class="border p-4 step-3-box shadow bg-white">

                <!-- Order Summary -->
                <div class="fw-bold"><strong>Order Summary</strong></div>

                <div id="list-price">
                </div>
                <div class="d-flex justify-content-between" style="color: red; font-weight: bold;">
                    <div>Subtotal:</div>
                    <div>$<span id="total_price"></span></div>
                </div>

                <hr class="mt-3 mb-3">
                <!-- Voucher -->
                <div class="fw-bold"><strong>Voucher</strong>
                    <a href="#" data-toggle="modal" data-target="#modal-voucher" onclick="ajax_get_list_voucher_for_create_order_by_sale()">
                        [Chọn mã]
                    </a>
                </div>

                <div id="button_voucher" style="display: none;">
                    <div class="fw-bold mt-2 mb-2 d-flex justify-content-between">
                        <a class="btn btn-warning btn-sm" style="display: flex;align-items: center;gap: 10px">
                            <i class="fas fa-gift"></i>
                            <div class="code_voucher"></div>
                            <div style="color:red; margin-left: 30px;">$ <span class="price_voucher"></span></div>
                        </a>
                        <div>Giảm $ <strong class="price_voucher"></strong></div>
                    </div>
                </div>

                <hr class="mt-3 mb-3">
                <!-- THANH TOÁN -->
                <div class="mt-5 mb-5 d-flex justify-content-between align-items-center">
                    <strong>Tổng thanh toán</strong>
                    <div style="color:red; font-size: xx-large;">$ <strong id="thanh_toan_price"></strong></div>
                </div>

                <div class="mb-3">
                    <div class="d-flex" style="gap:10px">
                        <button type="button" class="btn btn-lg btn-secondary w-25 me-3" id="step-3-back">Back</button>
                        <button type="button" id="submit-order" class="btn btn-lg btn-danger w-75">Hoàn Thành</button>
                    </div>
                </div>
                <small>By Placing an Order, You Agree to Stuccco's
                    <a class="link-color" target="_blank" href="<?= LINK_TERMS ?>">Terms of Use</a> and
                    <a class="link-color" target="_blank" href="<?= LINK_POLICY ?>">Privacy Policy</a>
                </small>
            </div>
        </div>
        <div class="col-12 col-lg-4 fs-5">


        </div>
    </div>
</div>
<div class="modal fade" id="modal-voucher">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title"><i class="fas fa-wallet"></i> Mã giảm giá</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex">
                <table width="100%" class="table voucher">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Mô tả</th>
                            <th width="100" class="text-center">Giá trị $</th>
                            <th width="100" class="text-center">Còn lại</th>
                            <th width="100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="100%" class="text-center"><i class="fas fa-sync fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-payment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title"><i class="fas fa-wallet"></i> TẠO ĐƠN THÀNH CÔNG - GỬI YÊU CẦU THANH TOÁN</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="link-payment">Tài khoản khách hàng</label>
                    <input class="form-control username"  disabled style="font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="link-payment">Tên khách hàng</label>
                    <input class="form-control fullname" disabled style="font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="link-payment">Email khách hàng</label>
                    <input class="form-control email" disabled style="font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="link-payment">Số tiền cần thanh toán</label>
                    <input class="form-control money-payment"  value="" disabled style="font-size: 2rem;">
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="link-payment">Copy link đơn hàng gửi cho khách</label>
                        <button type="button" class="btn btn-warning btn-sm" onclick="copyToClipboard(this, $('#modal-payment .link-payment').val())">Copy</button>
                    </div>
                    
                    <textarea class="form-control link-payment mt-2"  placeholder="" disabled></textarea>
                </div>

            </div>
            <div class="modal-footer justify-content-center">
                <a class="btn btn-success back_to_order" href="">Xem chi tiết đơn hàng</a>
            </div>
        </div>
    </div>
</div>
<!-- js bước 3 -->
<script>
    function ajax_get_list_voucher_for_create_order_by_sale() {
        $.ajax({
            url: 'voucher/ajax_get_list_voucher_for_create_order_by_sale',
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);
                if (kq.status) {
                    let voucher_html = ``;
                    Object.entries(kq.data).forEach((entry) => {
                        const [key, value] = entry;

                        voucher_html += `
                        <tr>
                            <td>
                                <strong>${value.code}</strong> <br/>
                                <small>HSD: ${value.expire_view}</small>
                            </td>
                            <td>${value.note}</td>
                            <td class="text-center">${value.price} $</td>
                            <td class="text-center">${value.limit - value.total}</td>
                            <td><a class="btn btn-danger btn-sm" onclick="ap_dung_voucher(${key}, '${value.code}', '${value.price}')">Áp dụng</a></td>
                        </tr>`
                    });

                    $('#modal-voucher .modal-body .voucher tbody').html(voucher_html)

                } else {
                    toasts_danger(kq.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ap_dung_voucher(id_voucher, code, price_voucher) {

        price_voucher = parseFloat(price_voucher);
        let total_price = parseFloat($('#total_price').text());

        $('#button_voucher .code_voucher').text(code);
        $('#button_voucher .price_voucher').text(price_voucher);
        $('#button_voucher').show();

        let thanh_toan_price = 0;
        if (total_price > price_voucher) {
            thanh_toan_price = total_price - price_voucher;
        }

        $('#thanh_toan_price').text(thanh_toan_price)

        STATE.voucher = id_voucher;
    }

    function remove_voucher() {
        $('#button_voucher').hide();

        let total_price = $('#total_price').html(total_price);

        $('#thanh_toan_price').text(total_price)

        STATE.voucher = id_voucher;
    }
</script>

<!-- end js bước 3 -->