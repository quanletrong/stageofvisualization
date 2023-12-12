<div>
    <style>
        .evenlope-chat {
            border: 0.5rem solid transparent;
            background: linear-gradient(white, white) padding-box,
                repeating-linear-gradient(-45deg,
                    red 0, red 12.5%,
                    transparent 0, transparent 25%,
                    #58a 0, #58a 37.5%,
                    transparent 0, transparent 50%) 0 / 5em 5em;
        }
    </style>
    <div class="p-4 rounded d-flex flex-column evenlope-chat">
        <div style="text-align: center;"><strong><?= $title ?></strong></div>

        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Ngày tạo</div>
            <div><?= date('H:s | d-m-Y', strtotime($create_time)) ?></div>
        </div>

        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Người tạo</div>
            <div><?= $by ?></div>
        </div>

        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Tổng ảnh</div>
            <div><?= $total ?> ảnh</div>
        </div>

        <?php if ($price_vouchor > 0) { ?>
            <div class="d-flex justify-content-between mt-2 w-100">
                <div>Số tiền đơn</div>
                <div class="text-danger" style="color:red; font-size: 1.25rem"><?= $price ?>$</div>
            </div>

            <div class="d-flex justify-content-between mt-2 w-100">
                <div>Giảm giá</div>
                <div class="text-danger" style="color:red; font-size: 1.25rem">-<?= $price_vouchor ?>$</div>
            </div>
        <?php } ?>

        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Số tiền cần thanh toán</div>
            <div class="text-danger" style="color:red; font-size: 1.25rem; font-weight: bold;"><?= $amount ?>$</div>
        </div>
        <button class="btn btn-sm btn-success mt-2 w-100" onclick="common.open_popup_pay('<?= $id_order ?>')">Thanh toán nhanh</button>
        <a class="btn btn-sm btn-danger mt-2 w-100" href="<?= ROOT_DOMAIN ?>user/orderdetail/<?= $id_order ?>" target="_blank">Xem đơn hàng</a>
    </div>
</div>