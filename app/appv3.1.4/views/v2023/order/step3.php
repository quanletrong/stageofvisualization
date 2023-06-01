<div class="container py-5 d-none" id="step-3">
    <div class="fw-semibold fs-5 mb-3">STEP 3 OF 3: CUSTOMER INFO</div>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="border p-4 step-3-box shadow">
                <div class="mb-3">
                    <label for="input_card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="input_card_number" name="card_number" placeholder="Card Number" required>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <label for="input_card_number" class="form-label">Card Expiration Date</label>
                        <div class="d-flex">
                            <input type="number" class="form-control w-25 me-3" id="input_card_mm" name="card_mm" placeholder="MM" required>
                            <input type="number" class="form-control w-25" id="input_card_yy" name="card_yy" placeholder="YY" required>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="input_card_number" class="form-label">Card Security Code (CVV/CVC)</label>
                        <input type="number" class="form-control w-25 me-3" id="input_card_cvv" name="card_cvv" placeholder="CVV" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-lg-6 mb-2">
                        <label for="input_coupon" class="form-label">Coupon Code (Optional)</label>
                        <input type="text" class="form-control me-3" id="coupon" name="coupon" placeholder="Coupon Code (Optional)">
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column">
                            <label for="input_card_number" class="form-label d-none d-lg-block">&nbsp;</label>
                            <button type="button" class="btn btn-secondary" style="width: fit-content;">Apply Coupon</button>
                        </div>

                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex">
                        <button type="button" class="btn btn-lg btn-secondary w-25 me-3" id="step-3-back">Back Step 2</button>
                        <button type="button" id="submit-order" class="btn btn-lg btn-danger w-75">Place Order</button>
                    </div>
                </div>
                <small>By Placing an Order, You Agree to Stuccco's <span class="link-color">Terms of Use</span> and
                    <span class="link-color">Privacy Policy</span></small>
            </div>
        </div>
        <div class="col-12 col-lg-4 fs-5">
            <div class="border p-2 shadow">
                <div class="fw-bold mb-2">Order Summary</div>

                <div id="list-price">
                </div>
                <div class="fw-bold d-flex justify-content-between">
                    <div>Subtotal:</div>
                    <div>$<span id="total_price"></span></div>
                </div>

            </div>

        </div>
    </div>
</div>