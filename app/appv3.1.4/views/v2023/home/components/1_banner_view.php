<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="container-fluid g-0">
    <div id="owl-slide" class="owl-carousel owl-theme mt-3 ">
        <?php foreach ($setting['home_slide'] as $slide) { ?>
            <div class="position-relative" >
                <img src="<?= $slide['image'] ?>" alt="" class="w-100 img-fluid">
                <!-- <div></div> -->
                <div class="bg-dark p-2 position-absolute text-light fs-5" style="top: 50%; left: 1rem;">BEFORE</div>
                <div class="bg-dark p-2 position-absolute text-light fs-5" style="top: 50%; right: 1rem;">AFTER</div>
            </div>
        <?php } ?>

    </div>

</div>

<script>
    $(document).ready(function() {
        $("#owl-slide").owlCarousel({
            items: 1,
            autoplay: true,
            margin: 10,
            responsiveClass: true,
            nav: false,
            dots: true
        });
    })
</script>