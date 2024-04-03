<div class="container pb-5" id="step-2">
    <div class="fw-semibold fs-5 mb-3 d-flex">
        <div class="step-2-active" style="width: 50%;text-align: center;background: #007bff;color: white;">NỘI DUNG ĐƠN HÀNG</div>
        <div style="width: 50%;text-align: center;background: #bbbbbb;color: white;">THANH TOÁN</div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="list_job" class="bg-white"></div>

            <!-- Want to Add More Photos to Your Order?  -->
            <div class="border p-4 mt-3 shadow div_main_3 bg-white">
                <label for="" class="form-label fw-bold">Want to Add More Photos to Your Order?</label>
                <div class="mb-3">
                    <button type="button" class="btn btn-lg btn-dark text-light w-100 " onclick="add_job()">Add More Photos to Order</button>
                </div>
            </div>

            <!-- Property Address:  Design Style:-->
            <div class="border p-4 mt-3 shadow div_main_4 bg-white">
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

                    <div class="mb-3 mt-3">
                        <label for="phone" class="form-label">SET JID</label>
                        <textarea type="tel" id="jid" class="form-control" onchange="STATE.jid = this.value" rows="4"></textarea>
                    </div>

                    <!-- submit -->
                    <div class="mt-3">
                        <div class="d-flex">
                            <button type="button" class="btn btn-lg btn-danger w-100" id="step-2-next">Next</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>