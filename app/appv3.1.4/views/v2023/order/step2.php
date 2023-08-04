<div class="container py-5 d-none" id="step-2">
    <div class="fw-semibold fs-5 mb-3">STEP 2 OF 3: ADD PHOTOS </div>

    <div class="row">
        <div class="col-12 col-lg-7">
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
                            <button type="button" class="btn btn-lg btn-secondary w-25 me-3" id="step-2-back">Back Step 1</button>
                            <button type="button" class="btn btn-lg btn-danger w-75" id="step-2-next">Continue to Next Step</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- HELPFUL GUIDANCE -->
        <div class="col-12 col-lg-5">
            <div class="fw-semibold fs-5 mb-3 text-center">
                HELPFUL GUIDANCE
            </div>
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            How to Place a Virtual Staging Order
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="link-color">
                                Click here to watch a video that will walk you through how to place a virtual staging
                                order on
                                Stuccco.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                            Tips for Selecting Photos
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Photos should show the focal point of the room. For example, if the photo is of a bedroom,
                            it should
                            show where the bed's headboard will go. <br>

                            Photos should be of decent quality (i.e. not blurry). The photo does not have to be a
                            professional
                            photo, it just needs to be clear and have decent lighting. We will maintain the resolution
                            of your
                            photos. <br>

                            Photos should depict empty and/or ugly spaces. These photos are best-suited for virtual
                            staging and
                            will give you the biggest bang for your buck.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                            What Is the Cost?
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div>
                                <p class="p-focused">The cost is determined by the number of photos you want edited and
                                    the type
                                    and number of services you want performed to each photo. Below is a complete list of
                                    the
                                    available virtual staging services and prices:</p>
                                <p class="p-focused">
                                    Add furniture and decor - $29<br>
                                    Remove items and add furniture and decor - $39<br>
                                    Change Interior Wall, Trim or Cabinet Color - $15<br>
                                    Change Exterior Wall, Trim or Door Color - $39<br>
                                    Change Flooring - $15<br>
                                    Change Ceiling - $15<br>
                                    Change Landscaping - $39<br>
                                    Enhance Photo - $1.50<br>
                                    Day to Dusk - $39</p>
                                <p class="p-focused">Choose one or multiple services for each photo during the checkout
                                    process,
                                    which will calculate your order total on the fly.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                            What Is the Delivery Time?
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div>
                                Every order is delivered in 24 hours, 7 days a week. If you want your order delivered in
                                12 hours or less, you can opt for this upgrade during the checkout process.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="true" aria-controls="panelsStayOpen-collapseFive">
                            Can Photos of Furnished Rooms be Staged?
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div>
                                Yes. If you want a photo of a furnished room staged, choose the service, "Remove Items
                                and Add Furniture and Decor" for the photo.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="true" aria-controls="panelsStayOpen-collapseSix">
                            What if I Dislike My Staged Photos?
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div>
                                If for any reason you are not happy with your virtually staged photos, send us a message
                                at the bottom of your order's page with your revision requests. We will revise your
                                photos for free provided the room type does not change and we are working from the same
                                photo. If you wish to change the room type or have a different photo staged we will ask
                                you to place a new order to appropriately take into consideration your designer's need
                                to design the photo from scratch.
                            </div>
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