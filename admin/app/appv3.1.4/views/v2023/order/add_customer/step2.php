<div class="container py-5 d-none" id="step-2">
    <div class="fw-semibold fs-5 mb-3">NỘI DUNG ĐƠN HÀNG </div>

    <div class="row">
        <div class="col-12">
            <div class="border p-4 shadow div_main_1">
                <div class="mb-3">
                    <button type="button" class="btn btn-lg btn-danger w-100" id="button_upload_image_step_2" onclick="add_job()">Click or Drag and Drop Photos</button>
                </div>
            </div>

            <div id="list_job"></div>

            <!-- Want to Add More Photos to Your Order?  -->
            <div class="border p-4 mt-3 shadow d-none div_main_3">
                <label for="" class="form-label fw-bold">Want to Add More Photos to Your Order?</label>
                <div class="mb-3">
                    <button type="button" class="btn btn-lg btn-dark text-light w-100 " onclick="add_job()">Add More Photos to Order</button>
                </div>
            </div>

            <!-- Property Address:  Design Style:-->
            <div class="border p-4 mt-3 shadow d-none div_main_4">
                <label for="" class="form-label fw-bold">Design Style:</label>
                <div>
                    <div class="row">
                        <?php foreach ($list_style as $id => $st) { ?>
                            <div class="col-12 col-md-6">
                                <div class="form-check mt-2 ps-0 ms-5">
                                    <div class="mt-2">
                                        <input class="form-check-input" type="radio" name="style" value="<?= $id ?>" id="radio_design_style_<?= $id ?>" onchange="STATE.style = this.value">
                                        <label class="form-check-label link-color" for="radio_design_style_<?= $id ?>"><?= $st['name'] ?></label>
                                    </div>
                                    <div class="text-decoration-underline" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModalDesignStyle" data-name="<?= $st['name'] ?>" data-slide="<?= htmlentities($st['slide']) ?>">View Examples</div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="radio" name="style" checked value="" id="radio_im_unsure" onchange="STATE.style = ''">
                        <label class="form-check-label" for="radio_im_unsure">
                            I'm unsure, let my designer choose a style for me.
                        </label>
                    </div>

                    <!-- submit -->
                    <div class="mt-3">
                        <div class="d-flex">
                            <button type="button" class="btn btn-lg btn-secondary w-25 me-3" id="step-2-back">Back</button>
                            <button type="button" class="btn btn-lg btn-danger w-75" id="step-2-next">NEXT</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


<!-- Modal SERVICE -->
<div class="modal fade" id="exampleModalServices" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Furniture and Decor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="position-relative">
                    <img src="" class="image" style="width: 100%; height: 100%; object-fit: cover;">
                    <div class="position-absolute p-2 text-bg-dark shadow" style="top:10px; left: 10px;">BEFORE</div>
                    <div class="position-absolute p-2 text-bg-dark shadow" style="top:10px; right: 10px;">AFTER</div>
                </div>
                <p class="mt-3">
                    <strong class="name">Add Furniture and Decor</strong> - <span class="sapo">Add furniture and decor to a space. This is typically
                        used on photos of vacant spaces. It can also be used if you would like to keep existing furniture
                        and add additional furniture and decor around it. This service type does not include item removal.</span>
                </p>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#exampleModalServices').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var src = button.data('src');
                var name = button.data('name');
                var sapo = button.data('sapo');
                var modal = $(this);

                modal.find('.modal-header .modal-title').text(name);
                modal.find('.modal-body .image').attr('src', src);
                modal.find('.modal-body .name').text(name);
                modal.find('.modal-body .sapo').text(sapo);
            })
        });
    </script>
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