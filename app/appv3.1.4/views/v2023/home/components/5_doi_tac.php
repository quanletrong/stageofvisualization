<!-- doi tac -->
<div class="container mt-5">
    <center class="fw-bold" style="font-size: 2em;">Thousands of Photos Staged for Real Estate Pros Across the USA
    </center>
    <center class="fs-5 fw-light">Top Real Estate Professionals Rely on Stuccco to Meet Their Virtual Staging Needs.
    </center>

    <div class="row">
        <?php foreach ($setting['partner'] as $partner) { ?>
            <div class="col-6 col-lg-3 mt-3 mb-3 text-center">
                <img data-src="<?=$partner['image']?>" alt="<?=$partner['name']?>" title="<?=$partner['name']?>" class="img-fluid lazy" style="max-height: 90px;">
            </div>
        <?php } ?>
    </div>
</div>