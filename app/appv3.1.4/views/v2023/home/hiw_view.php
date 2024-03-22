<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
    function edit_text(el) {

        let text = prompt('Nhập nội dung thay đổi:', $(el).html());

        if (text == null || text == "") {
            text = $(el).html()
        }

        $(el).html(text);
    }

    function edit_image(el) {

        let text = prompt('Nhập nội dung thay đổi:', $(el).html());

        if (text == null || text == "") {
            text = $(el).html()
        }

        $(el).html(text);
    }

    function cb_upload_img(link_file, target, file_name, el) {
        $(el).attr('src', link_file)
    }

    $("document").ready(function() {
        $(document).on("contextmenu", ".edit_text", function(e) {
            edit_text(this)
            return false;
        });
    })
</script>
<div class="container my-5 d-flex flex-column align-items-center">
    <div class="edit_text" style="font-size: 2.7em; font-weight: bold;">Get Photos Virtually Staged in 24 Hours or Less</div>
    <div class="mt-2 fs-5 edit_text">Quickly and Easily Maximize the Appeal and Value of Your Listings in Three Simple Steps
    </div>
</div>

<div class="container">
    <div class="row my-5">
        <div class="col-12 col-lg-6">
            <div class="text-color fw-bold fs-5 edit_text">Step 1</div>
            <div class="fw-bold fs-2 mt-2 edit_text">Upload Your Photos</div>
            <div class="mt-2 fs-5 edit_text">
                Upload the photos you want staged.
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <img onclick="quanlt_upload(this);" data-callback="cb_upload_img" src="images/virtual-staging-how-it-works-step-1.jpg" class="rounded shadow img-fluid" alt="">
        </div>
    </div>

    <div class="row my-5">
        <div class="col-12 col-lg-6">
            <div class="text-color fw-bold fs-5 edit_text">Step 2</div>
            <div class="fw-bold fs-2 mt-2 edit_text">Submit Your Order</div>
            <div class="mt-2 fs-5 edit_text">
                Tell us how you want your photos staged and submit payment. If you're a first-time customer, you'll
                be
                prompted to create a free account.
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <img onclick="quanlt_upload(this);" data-callback="cb_upload_img" src="images/virtual-staging-how-it-works-step-2.jpg" class="rounded shadow img-fluid" alt="">
        </div>
    </div>

    <div class="row my-5">
        <div class="col-12 col-lg-6">
            <div class="text-color fw-bold fs-5 edit_text">Step 3</div>
            <div class="fw-bold fs-2 mt-2 edit_text">Get Your Staged Photos</div>
            <div class="mt-2 fs-5 edit_text">
                In 24 hours or less, you'll get an email with a link to view and download your staged photos.
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <img onclick="quanlt_upload(this);" data-callback="cb_upload_img" src="images/virtual-staging-how-it-works-step-3.jpg" class="rounded shadow img-fluid" alt="">
        </div>
    </div>

    <center>
        <a href="<?= LINK_ORDER ?>">
            <button class="btn btn-lg btn-danger mt-2 px-4 edit_text">Place order</button>
        </a>
        <div class="mt-2 edit_text">Questions? Call <a href="tel: <?= $setting['phone'] ?>"><span class="link-color"><?= $setting['phone'] ?></span></a></div>
    </center>

    <div class="row my-5">
        <div class="col-12 col-lg-6 text-center">
            <div class="fw-bold fs-4 mt-2 edit_text">Free Revisions Included
            </div>
            <div class="mt-2 fs-5 edit_text">
                Need a revision? Send your designer a message at the bottom of your order and your virtually staged
                photos will be revised free of charge ASAP.
            </div>
        </div>

        <div class="col-12 col-lg-6 text-center">
            <div class="fw-bold fs-4 mt-2 edit_text">Free Listing Included
            </div>
            <div class="mt-2 fs-5 edit_text">
                A listing is a publicly-visible landing page that showcases your property. You can share, customize
                or hide it with a single click.
            </div>
        </div>
    </div>
</div>

<!-- Your Happiness is Guaranteed -->
<div class="container-fluid py-5" style="background-color: #edf1ee;">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="fw-bold edit_text" style="font-size: 2em;">Your Happiness is Guaranteed</div>
                <div class="mt-2 fs-5 fw-light edit_text" style="font-weight: 300; line-height: 1.5em;">
                    If you want your virtually staged photos to be revised, send us a message at the bottom of your
                    virtual staging order within 60 days of purchase and we will gladly revise your virtually staged
                    photos free of charge until you are completely happy.
                </div>
            </div>
            <div class="col-12 col-lg-6 text-center">
                <img onclick="quanlt_upload(this);" data-callback="cb_upload_img" src="images/happiness-guarantee.svg" alt="" width="250" height="250">
            </div>
        </div>
    </div>
</div>