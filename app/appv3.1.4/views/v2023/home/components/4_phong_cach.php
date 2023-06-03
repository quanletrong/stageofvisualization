<div class="container-fluid py-5">
    <div class="container">
        <center class="fw-bold" style="font-size: 2em;">Explore Design Styles </center>
        <center class="fs-5 fw-light">When Placing an Order, You Will Be Able to Provide Design Instructions and Choose
            a Design Style for Your Photos.</center>
        <center class="fs-5 fw-light link-color">Explore Virtual Staging Library.</center>

        <div class="row">
            <?php foreach ($style as $id => $it) { ?>
                <div class="col-12 col-lg-4 mt-3">
                    <img data-src="<?= $it['image_path'] ?>" alt="" class="w-100 img-fluid border rounded-1 lazy">
                    <div class="mt-2 fw-bold"><?= $it['name'] ?></div>
                    <div>
                        <?= $it['sapo'] ?>
                    </div>
                    <div class="link-color" data-bs-toggle="modal" data-bs-target="#exampleModalDesignStyle" data-name="<?= $it['name'] ?>" data-slide="<?= htmlentities($it['slide']) ?>">View Examples</div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal DESIGN STYLE -->
<div class="modal fade" id="exampleModalDesignStyle" tabindex="-1" aria-labelledby="exampleModalLabelDesignStyle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabelDesignStyle">Comfortable Contemporary</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="owl-design-style" class="owl-carousel owl-theme mt-3 ">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#exampleModalDesignStyle').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var name = button.data('name');
                var slide = button.data('slide');
                var modal = $(this);
                modal.find('.modal-header .modal-title').text(name);

                try {
                    let html = '';
                    for (const [key, value] of Object.entries(slide)) {
                        html += `<img src="${value.image_path}" class="img-fluid w-100" />`
                    }

                    $('#owl-design-style').html(html).hide();
                    $('#owl-design-style').trigger('destroy.owl.carousel');
                    $("#owl-design-style").owlCarousel({
                        items: 1,
                        autoplay: false,
                        margin: 10,
                        responsiveClass: true,
                        nav: false,
                        dots: true
                    });
                    $('#owl-design-style').show()
                } catch (error) {
                    console.log(error)
                }
            })
        });
    </script>
</div>