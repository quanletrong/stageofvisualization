 <div class="container py-5" id="step-1">
     <div class="fw-semibold fs-5 mb-3">STEP 1 OF 3: CUSTOMER INFO</div>

     <div class="border p-4 step-1-box shadow">
         <div class="fw-bold">Already Have An Account? <span class="link-color"> Sign In</span>.
         </div>
         <div class="mb-3">
             <label for="name" class="form-label">First Name</label>
             <input type="text" class="form-control" id="name" name="name" required placeholder="First Name" onchange="STATE.name = $(this).val()">
         </div>
         <div class="mb-3">
             <label for="lastname" class="form-label">Last Name</label>
             <input type="text" class="form-control" id="lastname" name="lastname" required placeholder="Last Name" onchange="STATE.lastname = $(this).val()">
         </div>
         <div class="mb-3">
             <label for="email" class="form-label">Email</label>
             <input type="email" class="form-control" id="email" name="email" required placeholder="email@email.com" onchange="STATE.email = $(this).val()">
         </div>
         <div class="mb-3">
             <label for="phone" class="form-label">Phone Number (10 Digits)</label>
             <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10}" placeholder="Phone number" onchange="STATE.phone = $(this).val()">
         </div>

         <div class="mb-3">
             <button type="button" class="btn btn-lg btn-danger w-100" id="step-1-next">Continue</button>
         </div>
     </div>
 </div>