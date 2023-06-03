<!-- review -->
<div class="container mt-5">
    <div class=row>
        <?php $feedback = json_decode($setting['feedback'], true); ?>
        <?php foreach ($feedback as $id => $it) { ?>
            <div class="col-md-4">
                <div class="d-flex justify-content-between mt-3">
                    <img src="images/5-stars.webp" alt="" height="20">
                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        Verified Purchase
                    </div>
                </div>
                <p class="mt-3"><?= $it['content'] ?></p>
                <div><?= $it['user'] ?></div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="my-5 d-flex flex-column align-items-center">
    <a href="<?= site_url(LINK_ORDER) ?>" class="btn btn-danger btn-lg mt-2 px-4 text-white">Place order</a>
    <div class="mt-2">
        Questions? Call
        <a href="tel: <?=$setting['phone']?>"><span class="link-color"><?=$setting['phone']?></span></a>
    </div>
</div>