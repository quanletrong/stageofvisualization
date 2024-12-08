<div class="container py-5 d-none" id="step-3">
    <div class="fw-semibold fs-5 mb-3">STEP 3 OF 3: CUSTOMER INFO</div>
    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="border p-4 step-3-box shadow">
                <!-- TODO: bỏ nhập card  -->
                <div class="mb-3">
                    <label for="input_card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="input_card_number" name="card_number" placeholder="Card Number" required onchange="STATE.card_number = $(this).val()">
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="input_card_number" class="form-label">Card Expiration Date</label>
                        <div class="d-flex" style="gap:10px">
                            <div class="w-50">
                                <input type="number" class="form-control w-100 me-3" id="input_card_mm" name="card_mm" placeholder="MM" required onchange="STATE.card_mm = $(this).val()">
                            </div>
                            <div class="w-50">
                                <input type="number" class="form-control w-100" id="input_card_yy" name="card_yy" placeholder="YY" required onchange="STATE.card_yy = $(this).val()">
                            </div>


                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="input_card_number" class="form-label">Card Security Code (CVV/CVC)</label>
                        <input type="number" class="form-control w-50 me-3" id="input_card_cvv" name="card_cvv" placeholder="CVV" required onchange="STATE.card_cvv = $(this).val()">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-lg-6 mb-2">
                        <label for="input_coupon" class="form-label">Coupon Code (Optional)</label>
                        <input type="text" class="form-control me-3 text-danger fw-bold" id="coupon" name="coupon" onchange="STATE.coupon = $(this).val()" disabled>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column">
                            <label for="input_card_number" class="form-label d-none d-lg-block">&nbsp;</label>
                            <div>
                                <button type="button" class="w-50 btn btn-secondary" style="width: fit-content;"
                                    data-bs-toggle="modal" data-bs-target="#modal-voucher" onclick="ajax_voucher_customer()">Select coupon</button>
                                <button class="btn text-danger" type="button" id="btn_remove_voucher" onclick="remove_voucher()" style="display: none;">Bỏ</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex">
                        <button type="button" class="btn btn-secondary w-25 me-3" id="step-3-back">Back Step 2</button>
                        <button type="button" id="submit-order" class="btn btn-danger w-75">Place Order</button>
                    </div>
                </div>
                <small>By Placing an Order, You Agree to Stage of visualization
                    <a class="link-color" target="_blank" href="<?= LINK_TERMS ?>">Terms of Use</a> and
                    <a class="link-color" target="_blank" href="<?= LINK_POLICY ?>">Privacy Policy</a>
                </small>
            </div>
        </div>
        <div class="col-12 col-lg-5 mt-3 mt-lg-0">
            <div class="border p-2 shadow">
                <div class="fw-bold mb-2">Order Summary</div>

                <div id="list-price">
                </div>
                <div class="fw-bold d-flex justify-content-between">
                    <div>Subtotal:</div>
                    <div>$<span id="total_price"></span></div>
                </div>

                <hr class="mt-3 mb-3">

                <div id="button_voucher" style="display: none;">
                    <div class="fw-bold mt-2 mb-2 d-flex justify-content-between align-items-center">
                        <div style="display: flex; gap:10px">
                            <a class="btn btn-warning btn-sm" style="display: flex;align-items: center;gap: 10px">
                                <i class="fas fa-gift"></i>
                                <div class="code_voucher"></div>
                                <div style="color:red; margin-left: 30px;">-<span class="price_voucher"></span> <span class="price_unit"></span></div>
                            </a>
                            <button class="btn text-danger" type="button" onclick=remove_voucher()>Bỏ</button>
                        </div>

                        <div>Giảm <strong class="price_voucher"></strong> <strong class="price_unit"></strong></div>
                    </div>
                </div>

                <!-- THANH TOÁN -->
                <div class="mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <strong>Tổng thanh toán</strong>
                    <div style="color:red; font-size: xx-large;">$ <strong id="thanh_toan_price"></strong></div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-voucher">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fas fa-wallet"></i> Áp dụng mã giảm giá</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex">
                <table width="100%" class="table voucher">
                    <thead>
                        <tr class="table-secondary">
                            <th>Mã</th>
                            <th>Mô tả</th>
                            <th width="100" class="text-center">Giá trị</th>
                            <th width="100" class="text-center">Còn lại</th>
                            <th width="130"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="100%" class="text-center"><i class="fas fa-sync fa-spin"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function ajax_voucher_customer() {
        $.ajax({
            url: 'order/ajax_voucher_customer',
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                let kq = JSON.parse(data);
                if (kq.status) {
                    let voucher_html = ``;
                    Object.entries(kq.data).forEach((entry) => {
                        const [key, value] = entry;

                        // nếu voucher đã được chọn thì button màu xanh, chưa chọn màu đỏ
                        let button_ap_dung = `<button type="button" class="btn btn-danger btn-sm w-100" onclick="ap_dung_voucher(${key})">Áp dụng</button>`;
                        if (key == STATE.voucher) {
                            button_ap_dung = `<button type="button" class="btn btn-success btn-sm w-100" onclick="ap_dung_voucher(${key})">Đang áp dụng</button>`;
                        }

                        voucher_html += `
                        <tr>
                            <td>
                                <strong>${value.code}</strong> <br/>
                                <small class="text-secondary">HSD: ${value.expire_view}</small>
                            </td>
                            <td>${value.note}</td>
                            <td class="text-center"> ${value.price} ${unit_name(value.price_unit)} </td>
                            <td class="text-center">${value.limit - value.total} lượt</td>
                            <td>${button_ap_dung}</td>
                        </tr>`
                    });

                    $('#modal-voucher .modal-body .voucher tbody').html(voucher_html);

                    // lưu vào biến 
                    VOUCHER = kq.data;

                } else {
                    bs5dialog.alert(kq.error, {
                        type: 'danger',
                        title: "Không lấy được coupon",
                        backdrop: true
                    });
                    console.log(kq.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
            }
        });
    }

    function ap_dung_voucher(id_voucher) {

        let code = VOUCHER[id_voucher].code;
        let price_voucher = VOUCHER[id_voucher].price;
        let price_unit = VOUCHER[id_voucher].price_unit;

        price = parseFloat(price_voucher);
        price_unit = parseInt(price_unit);
        let total_price = parseFloat($('#total_price').text());

        $('#coupon').val(code);
        $('#button_voucher .code_voucher').text(code);
        $('#button_voucher .price_voucher').text(price_voucher);
        $('#button_voucher .price_unit').text(unit_name(price_unit));
        $('#button_voucher').show();

        let thanh_toan_price = 0;
        // giảm %
        if (price_unit == <?= VOUCHER_PERCENT ?>) {
            thanh_toan_price = total_price - (total_price * price_voucher / 100);
            thanh_toan_price = thanh_toan_price.toFixed(2);
        }
        // giảm $
        else if (price_unit == <?= VOUCHER_USD ?>) {
            if (total_price > price_voucher) {
                thanh_toan_price = total_price - price_voucher;
            } else {
                thanh_toan_price = 0;
            }
        }
        // khác báo lỗi
        else {
            alert('Chỉ áp dụng mã % hoặc USD vào lúc này!')
        }

        $('#thanh_toan_price').text(thanh_toan_price)

        STATE.voucher = id_voucher;

        $('#modal-voucher').modal('hide');
        $('#btn_remove_voucher').show();
    }

    function remove_voucher() {
        let total_price = $('#total_price').text();
        $('#thanh_toan_price').text(total_price)
        $('#button_voucher').hide();
        $('#coupon').val('')
        $('#btn_remove_voucher').hide();
        STATE.voucher = '';
    }

    function unit_name(key) {
        let unit = '';
        switch (parseInt(key)) {
            case <?= VOUCHER_PERCENT ?>:
                unit = '%'
                break;
            case <?= VOUCHER_VND ?>:
                unit = '₫'
                break;
            case <?= VOUCHER_USD ?>:
                unit = '$'
                break;
            case <?= VOUCHER_EUR ?>:
                unit = '€'
                break;
            default:
                break;
        }

        return unit;
    }
</script>