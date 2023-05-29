<div class="container-fluid py-5" style="background-color: #f9f9f9;">
    <div class="container">
        <center class="fw-bold" style="font-size: 2em;">Explore Stuccco's Design Styles </center>
        <center class="fs-5 fw-light">When Placing an Order, You Will Be Able to Provide Design Instructions and Choose
            a Design Style for Your Photos.</center>
        <center class="fs-5 fw-light link-color">Explore Stuccco's Virtual Staging Library.</center>

        <div class="row">
            <?php foreach ($style as $id => $it) { ?>
                <div class="col-12 col-lg-4 mt-3">
                    <img src="<?= $it['image_path'] ?>" alt="" class="w-100 img-fluid border rounded-1">
                    <div class="mt-2 fw-bold"><?= $it['name'] ?></div>
                    <div>
                        <?= $it['sapo'] ?>
                    </div>
                    <div class="link-color">View Examples</div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>