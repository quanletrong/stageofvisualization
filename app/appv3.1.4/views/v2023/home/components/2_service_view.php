<div class="container-fluid border-bottom" style="background-color: #f9f9f9;">
    <div class="container mt-4">

        <?php foreach ($service as $id => $it) { ?>
            <center class="fs-3 fw-bold"><?= $it['name'] ?></center>
            <center class="fs-5"><?= $it['price'] ?>. 12 to 24 Hour Delivery. </center>

            <?php $room = json_decode($it['room'], true) ?>
            <?php if (empty($room)) { ?>
                <img src="<?= $it['image_path'] ?>" alt="" class="w-100 img-fluid mt-3">
            <?php } else { ?>
                <?php foreach ($room as $it_room) { ?>
                    <img src="<?= $it_room['image_path'] ?>" alt="" class="w-100 img-fluid mt-3">
                    <div class="mt-2 fs-5"><?= $it_room['name'] ?></div>
                <?php } ?>
            <?php } ?>

            <div class="my-5 d-flex flex-column align-items-center">
                <a href="<?= site_url(LINK_ORDER) ?>" class="btn btn-danger btn-lg mt-2 px-4 text-white">Place order</a>
                <div class="mt-2">
                    Questions? Call
                    <a href="tel: 0987654321"><span class="link-color">1-833-788-2226</span></a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>