<?php ?>

<!-- modal edit -->
<div class="modal fade" id="modal-voucher-order" style="display: none" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Số đơn đã sử dụng mã</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="example2" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID ORDER</th>
                            <th>CODE ORDER</th>
                            <th>Đơn của <br>khách hàng</th>
                            <th>Giá trước <br>khuyến mại</th>
                            <th>Số tiền <br>khuyến mại</th>
                            <th>Giá sau <br>khuyến mại</th>
                            <th>Sử dụng bởi</th>
                            <th>Ngày sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        $('#modal-voucher-order').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            var order = button.data('order');
            var price = button.data('price');
            var unit = button.data('price-unit');

            modal.find('.modal-body table tbody').html('');

            let html = ``;
            for (const [order_id, item] of Object.entries(order)) {
                let price_voucher = 0;

                if (unit == 1) {
                    price_voucher = (price * item.price)/100
                } else {
                    price_voucher = price;
                }

                html += `
                <tr>
                    <td><a href="order/detail/${item.id_order}">OID${item.id_order}</a></td>
                    <td>${item.code_order}</td>
                    <td>${item.username}</td>
                    <td>${item.price}$</td>
                    <td>${price_voucher} $</td>
                    <td>${item.price - price_voucher} $</td>
                    <td>${item.create_by_user}</td>
                    <td>${item.create_date}</td>
                </tr>
                `;
            }

            modal.find('.modal-body table tbody').html(html);
        })
    });
</script>