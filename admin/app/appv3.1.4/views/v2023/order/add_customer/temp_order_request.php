<div class="m-1 p-2 border rounded d-flex flex-column bg-white" style="background: white">
    <div style="text-align: center;"><strong><?=$title?></strong></div>

    <div class="d-flex justify-content-between mt-2 w-100">
        <div>Ngày tạo</div>
        <div><?=date('H:s | d-m-Y', strtotime($create_time))?></div>
    </div>

    <div class="d-flex justify-content-between mt-2 w-100">
        <div>Người tạo</div>
        <div style="display: flex; flex-direction: column;">
            <span><?=$by['username']?></span>
            <span><?=$by['phone']?></span>
            <span><?=$by['email']?></span>
        </div>
    </div>

    <?php if($price_vouchor >0) {?>
        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Số tiền đơn</div>
            <div class="text-danger" style="color:red; font-size: 1.25rem"><?=$price?>$</div>
        </div>

        <div class="d-flex justify-content-between mt-2 w-100">
            <div>Giảm giá</div>
            <div class="text-danger" style="color:red; font-size: 1.25rem">-<?=$price_vouchor?>$</div>
        </div>
    <?php } ?>

    <div class="d-flex justify-content-between mt-2 w-100">
        <div>Số tiền cần thanh toán</div>
        <div class="text-danger" style="color:red; font-size: 1.25rem"><?=$amount?>$</div>
    </div>
    <button class="btn btn-sm btn-success mt-2 w-100" onclick="alert('todo')">Thanh toán nhanh</button>
    <a class="btn btn-sm btn-danger mt-2 w-100" href="<?=ROOT_DOMAIN?>user/orderdetail/<?=$id_order?>" target="_blank">Xem đơn hàng</a>
</div>