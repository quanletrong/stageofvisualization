<script>
    // chi tiết 1 JOB
    let JOB = {};
    JOB.image = '';
    JOB.room = '';
    JOB.service = '';
    JOB.price = '';
    JOB.requirement = '';
    JOB.attach = {};

    let STATE = {};
    // step 1
    STATE.for_user = '';

    // step 2
    STATE.job = {};
    STATE.style = '';
    
</script>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- PAGE HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?=$title?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?=$title?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- JOB CONTENT / TEAM ACTION -->
        <section>
            <form id="form_order">

                <!-- STEP 1 -->
                <?php $this->load->view(VERSION . '/order/add_customer/step1.php'); ?>
                <!-- STEP 2 -->
                <?php $this->load->view(VERSION . '/order/add_customer/step2.php'); ?>
                <!-- STEP 3 -->
                <?php $this->load->view(VERSION . '/order/add_customer/step3.php'); ?>
            </form>
        </section>
    </div>
</div>

<script>
    $("document").ready(function() {

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
            if($('#list_customer').val() == '') {
                alert ('Hãy chọn khách hàng cần tạo đơn!');
                return;
            }
            $("#step-1").addClass('d-none');
            $("#step-2").removeClass('d-none');
            $("#step-3").addClass('d-none');
            window.scrollTo(0, 0);
        });

        // step-2-next-3
        $("#step-2-next").click(function() {
            let error = '';
            for (const [id_job, job] of Object.entries(STATE.job)) {
                // room
                if (job.room == '') {
                    $(`#${id_job} .room`).addClass('is-invalid');
                    error = `#${id_job} .room`;
                } else {
                    $(`#${id_job} .room`).remove('is-invalid');
                }
                // service
                if (isEmpty(job.service)) {
                    $(`#${id_job} .service-error`).show();
                    error = `#${id_job} .service-error`;
                } else {
                    $(`#${id_job} .service-error`).hide();
                }
            }

            if (error === '') {
                $("#step-1").addClass('d-none');
                $("#step-2").addClass('d-none');
                $("#step-3").removeClass('d-none');
                window.scrollTo(0, 0);

                // render list price
                let index = 1;
                let price_html = '';
                let total_price = 0;
                for (const [id_job, job] of Object.entries(STATE.job)) {

                    let price = parseFloat(job.price);
                    price = isNaN(price) ? 0 : price;

                    price_html += `
                    <div class="mb-2 d-flex justify-content-between">
                        <div>Photo ${index++}:</div>
                        <div>$${price}</div>
                    </div>`;
                    total_price = total_price + price;
                }
                $('#list-price').html(price_html);
                $('#total_price').html(total_price);
            } else {
                scroll_to(error);
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

        // 
        $('#submit-order').click(function() {
            if (STATE.card_number != '' && STATE.card_mm != '' && STATE.card_yy != '' && STATE.card_cvv != '') {
                ajax_order();
            } else {
                valid_order.element(`*[name="card_number"]`)
                valid_order.element(`*[name="card_mm"]`);
                valid_order.element(`*[name="card_yy"]`);
                valid_order.element(`*[name="card_cvv"]`);
            }
        })

        // STEP 2
        $("#button_upload_image_step_2").click(function() {
            $('#step-2 .div_main_1').addClass('d-none');
            $('#step-2 .div_main_2').removeClass('d-none');
            $('#step-2 .div_main_3').removeClass('d-none');
            $('#step-2 .div_main_4').removeClass('d-none');
        })

        function ajax_order() {
            $.ajax({
                url: 'order/submit_add/customer',
                type: "POST",
                data: {
                    order: STATE
                },
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                    alert('Bạn đã tạo thành công đơn hàng.');
                    // window.location.href = '<?= site_url(LINK_ORDER) ?>';

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        }
    })

    // step2_remove_job
    function step2_remove_job(job_id) {

        if (confirm("Are you sure you want to delete this photo?") == true) {
            delete STATE.job[job_id];
            let count_job = Object.keys(STATE.job).length;

            $(`#${job_id}`).remove();
            if (count_job == 0) {
                $('#step-2 .div_main_1').removeClass('d-none');
                $('#step-2 .div_main_2').addClass('d-none');
                $('#step-2 .div_main_3').addClass('d-none');
                $('#step-2 .div_main_4').addClass('d-none');
            }
        }

    }

    // cb_upload_image_job
    function cb_upload_image_job(link, target, name) {
        let job_id = $(target).data('id');
        $(target + '_pre').attr('src', link);
        STATE.job[job_id].image = link;
    }

    // cb_upload_image_attach
    function cb_upload_attach(link, target, name) {
        let job_id = $(target).data('id');
        let attach_id = Date.now() + Object.keys(STATE.job[job_id].attach).length;
        let attach_html = `<div style="position:relative" class="mt-2">
            <img src="${link}"  style="width:50px;aspect-ratio: 1; object-fit: cover;" >
            <i class="fa-solid fa-xmark" style="position:absolute;right: 5px;top: 5px; cursor: pointer;" onclick="remove_attach(this, ${job_id}, ${attach_id})"></i>
        </div>`;
        $(`#${job_id}_attach_pre`).append(attach_html);
        STATE.job[job_id].attach[attach_id] = link;
    }

    function remove_attach(e, job_id, attach_id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            delete STATE.job[job_id].attach[attach_id];
            $(e).parent().remove();
        }
    }

    // add_job
    function add_job() {
        let job_id = Date.now();
        let job_new = `<div class="border p-4 shadow mb-2 div_main_2" id="${job_id}">
                <div class="position-relative">
                    <button type="button" class="btn_upload_image d-none" onclick="quanlt_upload(this);" data-callback="cb_upload_image_job" data-target="#image_${job_id}"></button>
                    <input type="hidden" id="image_${job_id}" data-id="${job_id}"/>
                    <img id="image_${job_id}_pre" class="img-fluid w-100" alt="">
                    <div class="position-absolute" style="top:10px; right: 10px; cursor: pointer;">
                        <i class="fa-solid fa-xmark fs-3" onclick="step2_remove_job(${job_id})"></i>
                    </div>
                </div>

                <small>Thumbnail shown. The full quality photo <span class="link-color" style="cursor: pointer;">(preview)</span> will be received when the order is placed.</small>

                <div class="my-3">
                    <label for="exampleFormControlInput1" class="form-label fw-bold">Room Type:</label>
                    <select title="Please select room type." class="form-control room" onchange="STATE.job[${job_id}].room = this.value">
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
                                type="radio"
                                id="flexCheckDefault_${job_id}_<?= $id ?>" 
                                onchange="add_or_remove_service(${job_id}, '<?= $id ?>', '<?= $sv['price'] ?>')"
                            >
                            <label class="form-check-label" for="flexCheckDefault_${job_id}_<?= $id ?>">
                                <?= $sv['name'] ?> - $<?= $sv['price'] ?> Per Photo
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
                    <textarea class="form-control" onchange="STATE.job[${job_id}].requirement = $(this).val()"></textarea>

                    <div class="mb-3 mt-3">
                        <button type="button" class="form-control form-control-sm" onclick="quanlt_upload(this);" data-callback="cb_upload_attach" data-target="#image_${job_id}" style="width: fit-content;">
                            <i class="fa-solid fa-paperclip"></i>
                            Attach Reference Files
                        </button>

                        <div id="${job_id}_attach_pre" class="d-flex" style="gap:10px"></div>

                    </div>
                </div>
            </div>`;

        $('#list_job').append(job_new);
        var JOB_COPY = JSON.parse(JSON.stringify(JOB))
        STATE.job[job_id] = {
            ...JOB_COPY
        };

        // upload image luôn
        $(`#${job_id} .btn_upload_image`).click();
    }

    // add_or_remove_service
    function add_or_remove_service(job_id, service_id, price) {
        // RADIO
        STATE.job[job_id].service = service_id;
        STATE.job[job_id].price = price;

        // UNCHECKED CÁC SERVICE KHÁC
        $(`#flexCheckDefault_${job_id}_${service_id}`)
            .parent()
            .siblings('.form-check')
            .find('.form-check-input')
            .prop('checked', false)
    }
</script>