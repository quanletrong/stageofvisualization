<script>
    // chi tiết 1 order
    let ORDER = {};
    ORDER.image = '';
    ORDER.room = '';
    ORDER.service = {};
    ORDER.requirement = '';
    ORDER.attach = '';

    let STATE = {};
    // step 1
    STATE.name = '';
    STATE.lastname = '';
    STATE.email = '';
    STATE.phone = '';

    // step 2
    STATE.order = {};
    STATE.style = '';

    // step 3
    STATE.card_number = '';
    STATE.card_mm = '';
    STATE.card_yy = '';
    STATE.card_cvv = '';
    STATE.coupon = '';
</script>
<div class="container-fluid" style="background-color: #fafafa;">

    <form id="form_order">
        <!-- STEP 1 -->
        <?php $this->load->view(VERSION . '/order/step1.php'); ?>
        <!-- STEP 2 -->
        <?php $this->load->view(VERSION . '/order/step2.php'); ?>
        <!-- STEP 3 -->
        <?php $this->load->view(VERSION . '/order/step3.php'); ?>
    </form>
</div>

<script>
    $("document").ready(function() {

        if ($(window).width() < 992) {
            $('.step-1-box').removeClass('w-50')
        } else {
            $('.step-1-box').addClass('w-50')
        }

        $(window).resize(function() {
            if ($(window).width() < 992) {
                $('.step-1-box').removeClass('w-50')
            } else {
                $('.step-1-box').addClass('w-50')
            }
        });

        // valid_order
        var valid_order = $('#form_order').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                form.submit();
            },
            rules: {
                'style': {
                    required: true
                }
            },
            messages: {},
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.parent().append(error);

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');

            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        // step-1-next-2
        $("#step-1-next").click(function() {
            if (STATE.name != '' && STATE.lastname != '' && STATE.email != '' && STATE.phone != '') {
                $("#step-1").addClass('d-none');
                $("#step-2").removeClass('d-none');
                $("#step-3").addClass('d-none');
                window.scrollTo(0, 0);
            } else {
                valid_order.element(`*[name="name"]`)
                valid_order.element(`*[name="lastname"]`);
                valid_order.element(`*[name="phone"]`);
                valid_order.element(`*[name="email"]`);
            }
        });

        // step-2-next-3
        $("#step-2-next").click(function() {
            let error = '';
            for (const [order_id, order_data] of Object.entries(STATE.order)) {
                // room
                if (order_data.room == '') {
                    $(`#${order_id} .room`).addClass('is-invalid');
                    error = `#${order_id} .room`;
                } else {
                    $(`#${order_id} .room`).remove('is-invalid');
                }
                // service
                if (isEmpty(order_data.service)) {
                    $(`#${order_id} .service-error`).show();
                    error = `#${order_id} .service-error`;
                } else {
                    $(`#${order_id} .service-error`).hide();
                }
            }

            if (error === '') {
                $("#step-1").addClass('d-none');
                $("#step-2").addClass('d-none');
                $("#step-3").removeClass('d-none');
                window.scrollTo(0, 0);
            } else {
                scrollTo(error);
            }
        });

        // step-2-back-1
        $("#step-2-back").click(function() {
            $("#step-1").removeClass('d-none');
            $("#step-2").addClass('d-none');
            $("#step-3").addClass('d-none');
            window.scrollTo(0, 0);
        });

        // step-3-back-2
        $("#step-3-back").click(function() {
            $("#step-1").addClass('d-none');
            $("#step-2").removeClass('d-none');
            $("#step-3").addClass('d-none');
            window.scrollTo(0, 0);
        });

        // STEP 2
        $("#button_upload_image_step_2").click(function() {
            $('#step-2 .div_main_1').addClass('d-none');
            $('#step-2 .div_main_2').removeClass('d-none');
            $('#step-2 .div_main_3').removeClass('d-none');
            $('#step-2 .div_main_4').removeClass('d-none');
        })
    })

    // step2_remove_order
    function step2_remove_order(order_id) {

        delete STATE.order[order_id];
        let count_order = Object.keys(STATE.order).length;

        $(`#${order_id}`).remove();
        if (count_order == 0) {
            $('#step-2 .div_main_1').removeClass('d-none');
            $('#step-2 .div_main_2').addClass('d-none');
            $('#step-2 .div_main_3').addClass('d-none');
            $('#step-2 .div_main_4').addClass('d-none');
        }
    }

    // cb_upload_image_order
    function cb_upload_image_order(link, target, name) {
        let order_id = $(target).data('id');
        $(target + '_pre').attr('src', link);
        STATE.order[order_id].image = link;
    }

    // add_order
    function add_order() {
        let order_id = Date.now();
        let order_new = `<div class="border p-4 shadow div_main_2" id="${order_id}">
                <div class="position-relative">
                    <button type="button" class="btn_upload_image d-none" onclick="quanlt_upload(this);" data-callback="cb_upload_image_order" data-target="#image_${order_id}"></button>
                    <input type="hidden" id="image_${order_id}" data-id="${order_id}"/>
                    <img id="image_${order_id}_pre" class="img-fluid w-100" alt="">
                    <div class="position-absolute" style="top:10px; right: 10px; cursor: pointer;">
                        <i class="fa-solid fa-xmark fs-3" onclick="step2_remove_order(${order_id})"></i>
                    </div>
                </div>

                <small>Thumbnail shown. The full quality photo <span class="link-color" style="cursor: pointer;">(preview)</span> will be received when the order is placed.</small>

                <div class="my-3">
                    <label for="exampleFormControlInput1" class="form-label fw-bold">Room Type:</label>
                    <select title="Please select room type." class="form-control room" onchange="STATE.order[${order_id}].room = this.value">
                        <option value="">Select Room Type</option>
                        <?php foreach ($list_room as $id => $rm) { ?>
                            <option value="<?= $id ?>"><?= $rm['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="my-3">
                    <label for="exampleFormControlInput1" class="form-label fw-bold">Select Services (Select All
                        That Apply):
                    </label>
                    <span class="error invalid-feedback service-error" style="">This field is required.</span>

                    <?php foreach ($list_service as $id => $sv) { ?>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="service[]" 
                                id="flexCheckDefault_${order_id}_<?= $id ?>" 
                                onchange="add_or_remove_service(${order_id}, '<?= $id ?>', '<?= $sv['price'] ?>')"
                            >
                            <label class="form-check-label" for="flexCheckDefault_${order_id}_<?= $id ?>">
                                <?= $sv['name'] ?> - <?= $sv['price'] ?>
                            </label>
                            <i class="fa-solid fa-circle-info text-secondary" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModalServices" data-name="<?= $sv['name'] ?>" data-src="<?= $sv['image_path'] ?>" data-sapo="<?= $sv['sapo'] ?>"></i>
                        </div>
                    <?php } ?>

                </div>

                <div class="my-3">
                    <label for="exampleFormControlInput1" class="form-label fw-bold">Your Design Requirements
                        (Optional):</label><br>
                    <small>For example, your desired Collection ID from the <span class="link-color">Library</span>
                        (e.g.
                        CCBR10), your vision, etc.</small>
                    <textarea class="form-control" onchange="STATE.order[${order_id}].requirement = $(this).val()"></textarea>

                    <div class="mb-3 mt-3">
                        <label for="formFileSm" class="form-label"> Attach Reference Files</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file" placeholder=" Attach Reference Files">
                    </div>
                </div>
            </div>`;

        $('#list_order').append(order_new);
        var ORDER_COPY = JSON.parse(JSON.stringify(ORDER))
        STATE.order[order_id] = {
            ...ORDER_COPY
        };

        // upload image luôn
        $(`#${order_id} .btn_upload_image`).click();
    }

    // add_or_remove_service
    function add_or_remove_service(order_id, service_id, price) {
        let service = STATE.order[order_id].service;
        if (isEmpty(service[service_id])) {
            service[service_id] = price;
            $(`#${order_id} .service-error`).hide();
        } else {
            delete service[service_id];
            if (isEmpty(service)) {
                $(`#${order_id} .service-error`).show();
            }
        }
    }
</script>