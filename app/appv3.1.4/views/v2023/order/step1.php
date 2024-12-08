<div class="container py-5" id="step-1">
    <div class="fw-semibold fs-5 mb-3">STEP 1 OF 3: CUSTOMER INFO</div>

    <div class="border p-4 step-1-box shadow">


        <?php if (empty($user_info)) { ?>
            <div class="fw-bold">
                Already Have An Account? 
                <a href="#" class="link-color"class="link-color" data-bs-toggle="modal" data-bs-target="#modal_login">Sign In</a> 
                or
                <a href="register?url=<?=site_url('order')?>" class="link-color"class="link-color">Register.</a>
            </div>
        <?php } else { ?>
            <div class="mb-3">
                <label for="lastname" class="form-label">Full Name</label>
                <!-- <input type="text" class="form-control" id="lastname" name="lastname" required placeholder="Last Name" onchange="STATE.lastname = $(this).val()"> -->
                <input type="text" class="form-control" value="<?=$user_info['fullname']?>" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <!-- <input type="email" class="form-control" id="email" name="email" required placeholder="email@email.com" onchange="STATE.email = $(this).val()"> -->
                <input type="email" class="form-control" value="<?=$user_info['email']?>" disabled>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number (10 Digits)</label>
                <!-- <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10}" placeholder="Phone number" onchange="STATE.phone = $(this).val()"> -->
                <input type="tel" class="form-control" value="<?=$user_info['phone']?>" disabled>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-danger w-100" id="step-1-next">Continue</button>
            </div>
        <?php } ?>

    </div>
</div>