<!-- doi tac -->
<div class="container mt-5">
    <center class="fw-bold" style="font-size: 2em;"><?=$partner['title']?></center>
    <center class="fs-5 fw-light"><?=$partner['sapo']?></center>

    <div class="row">
        <?php foreach ($partner['images'] as $partner) { ?>
            <div class="col-6 col-lg-3 mt-3 mb-3 text-center">
                <img data-src="<?=$partner['image']?>" alt="<?=$partner['name']?>" title="<?=$partner['name']?>" class="img-fluid lazy" style="max-height: 90px;">
            </div>
        <?php } ?>
    </div>
</div>