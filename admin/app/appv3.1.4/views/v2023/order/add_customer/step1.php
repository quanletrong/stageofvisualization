<div class="container py-5" id="step-1">
    <div class="fw-semibold fs-5 mb-3">STEP 1 OF 3: CUSTOMER INFO</div>

    <div class="border p-4 step-1-box shadow">
        <strong>Chọn khách hàng</strong>
        <select id="list_customer" class="mb-3" style="width: 100%">
            <option value="">-Chọn-</option>
            <?php foreach ($list_customer as $id_user => $user) { ?>
                <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
            <?php } ?>
        </select>

        <div class="d-none">
            <hr>
            <div class="mb-3">
                <label for="lastname" class="form-label">Full Name</label>
                <input type="text" class="form-control" value="" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" value="" disabled>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number (10 Digits)</label>
                <input type="tel" class="form-control" value="" disabled>
            </div>

        </div>

        <div class="mt-3 mb-3">
            <button type="button" class="btn btn-lg btn-danger w-100" id="step-1-next">Continue</button>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#list_customer').select2({});
    });
</script>