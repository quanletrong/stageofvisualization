<div class="container py-5 d-none" id="step-3">
    <div class="fw-semibold fs-5 mb-3">STEP 3 OF 3: CUSTOMER INFO</div>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="border p-4 step-3-box shadow">

                <div class="fw-bold mb-2"><strong>Order Summary</strong></div>

                <div id="list-price">
                </div>
                <div class="fw-bold d-flex justify-content-between">
                    <div>Subtotal:</div>
                    <div>$<span id="total_price"></span></div>
                </div>

                <div class="mb-3">
                    <div class="d-flex">
                        <button type="button" class="btn btn-lg btn-secondary w-25 me-3" id="step-3-back">Back Step 2</button>
                        <button type="button" id="submit-order" class="btn btn-lg btn-danger w-75">Place Order</button>
                    </div>
                </div>
                <small>By Placing an Order, You Agree to Stuccco's
                    <a class="link-color" target="_blank" href="<?= LINK_TERMS ?>">Terms of Use</a> and
                    <a class="link-color" target="_blank" href="<?= LINK_POLICY ?>">Privacy Policy</a>
                </small>
            </div>
        </div>
        <div class="col-12 col-lg-4 fs-5">


        </div>
    </div>
</div>