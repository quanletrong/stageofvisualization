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

    STATE.cid = '';
    STATE.jid = '';

    // step 2
    STATE.job = {};
    STATE.style = '';
</script>

<style>
    .step-2-active:after {
        content: "";
        display: inline-block;
        width: 0;
        height: 0;
        border-top: 14px solid #bbbbbb;
        border-bottom: 14px solid #bbbbbb;
        border-left: 14px solid #007bff;
        float: inline-end;
    }

    .step-3-active:after {
        content: "";
        display: inline-block;
        width: 0;
        height: 0;
        border-top: 14px solid #007bff;
        border-bottom: 14px solid #007bff;
        border-left: 14px solid #bbbbbb;
        float: inline-start;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid pt-3">
        <!-- JOB CONTENT / TEAM ACTION -->
        <section>
            <form id="form_order" action="order/submit_add_private">
                <!-- STEP 2 -->
                <?php $this->load->view(VERSION . '/order/add_private/step2.php'); ?>
                <!-- STEP 3 -->
                <?php $this->load->view(VERSION . '/order/add_private/step3.php'); ?>
            </form>
        </section>
    </div>
</div>


<script>
    $("document").ready(function() {

        // init add_job
        add_job();

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
            $(this).html(' <i class="fas fa-sync fa-spin"></i>');
            $(this).prop("disabled", true);
            $.ajax({
                url: 'order/submit_add/private',
                type: "POST",
                data: {
                    order: STATE
                },
                success: function(data, textStatus, jqXHR) {
                    let res = JSON.parse(data);
                    alert('Bạn đã tạo thành công đơn hàng.');
                    window.location.href = 'order/detail/' + res.data.new_id_order;

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    alert('Error');
                }
            });
        })
    })

    // step2_remove_job
    function step2_remove_job(job_id) {
        let count_job = Object.keys(STATE.job).length;
        if (count_job == 1) {
            alert('Không được xóa hết ảnh');
        } else {
            if (confirm("Are you sure you want to delete this photo?") == true) {
                delete STATE.job[job_id];
                $(`#${job_id}`).remove();
            }
        }
    }

    // cb_upload_image_job
    function cb_upload_image_job(link, target, name, btn) {

        let job_id = $(target).data('id');

        if (_.isImage(link)) {
            $(btn).html(`<img class="img-fluid w-50" alt="${name}" src="${link}" data-bs-toggle="tooltip" data-bs-placement="top" title="Bấm vào để thay thế file" ondragover="$(this).hide()">`);
        } else {
            $(btn).html(
                `<div ondragover="$(this).hide()">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <p style="font-size:12px" class="text-truncate">${name}</p>
                </div>`
            );
        }
        STATE.job[job_id].image = link;
        tooltipTriggerList('body');
    }

    // cb_upload_image_attach
    function cb_upload_attach(link, target, name, btn) {
        let job_id = $(target).data('id');
        let attach_id = Date.now() + Object.keys(STATE.job[job_id].attach).length;
        let attach_html = `<div style="position:relative" class="m-2">
            <img src="${link}"  style="width:50px;aspect-ratio: 1; object-fit: cover;" class="shadow" >
            <i class="fas fa-times" style="position:absolute;right:-10px;top: -10px; color:red; cursor: pointer;" onclick="remove_attach(this, ${job_id}, ${attach_id})"></i>
        </div>`;
        $(`#${job_id}_attach_pre`).append(attach_html);

        $(btn).html(`<i class="fas fa-paperclip"></i> Attach Reference Files`);

        STATE.job[job_id].attach[attach_id] = link;
    }

    function remove_attach(e, job_id, attach_id) {
        if (confirm("Are you sure you want to delete this file?") == true) {
            delete STATE.job[job_id].attach[attach_id];
            $(e).parent().remove();
        }
    }

    function add_job() {
        let job_id = Date.now();
        let job_new = `
            <div class="position-relative border p-4 shadow mb-4 div_main_2" id="${job_id}">

                <div class="position-absolute" style="top: -15px;right: -15px;cursor: pointer;">
                    <btn class="btn btn-sm btn-danger rounded-circle shadow" onclick="step2_remove_job(${job_id})" style="width:30px; height:30px"> <i class="fas fa-times"></i></btn>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="">
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
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div>
                            <label for="exampleFormControlInput1" class="form-label fw-bold">File main:</label>
                            <input type="hidden" id="image_${job_id}" data-id="${job_id}"/>
                            <button 
                                type="button"
                                class="btn_upload_image w-100 d-flex p-3 rounded bg-light" 
                                style="min-height:150px; justify-content: center;align-items: center; border: red 1px dotted;" 
                                onclick="quanlt_upload(this);" 
                                ondrop="quanlt_handle_drop_file(event)"
                                ondragover="event.preventDefault();"
                                data-callback="cb_upload_image_job" 
                                data-target="#image_${job_id}"

                                data-onbefore="onbefore_upload_image_job"
                                data-onprogress="onprogress_upload_image_job"
                                data-onsuccess="onsuccess_upload_image_job"
                                data-onerror="onerror_upload_image_job"
                                data-job="${job_id}"

                            >
                                Kéo hình ảnh vào đây hoặc <span class="text-primary">Tải tệp lên</span>
                            </button>

                            <small>Thumbnail shown. The full quality photo <span class="link-color" style="cursor: pointer;">(preview)</span> will be received when the order is placed.</small>
                        </div>

                        <div class="mt-3">
                            <label for="exampleFormControlInput1" class="form-label fw-bold">Attach Reference Files:</label>
                            <button 
                                type="button" 
                                class="form-control form-control-sm" 
                                onclick="quanlt_upload(this);" 
                                ondrop="quanlt_handle_drop_file(event)"
                                ondragover="event.preventDefault();"
                                data-callback="cb_upload_attach" 
                                data-target="#image_${job_id}" 

                                data-onbefore="onbefore_upload_image_attach"
                                data-onprogress="onprogress_upload_image_attach"
                                data-onsuccess="onsuccess_upload_image_attach"
                                data-onerror="onerror_upload_image_attach"
                                data-job="${job_id}"

                                style="width: fit-content;"
                            >
                                <i class="fas fa-paperclip"></i> Attach Reference Files
                            </button>

                            <div id="${job_id}_attach_pre" class="d-flex flex-wrap bg-white mt-2" style="gap:10px"></div>

                        </div>
                    </div>
                </div>
            </div>`;

        $('#list_job').append(job_new);
        var JOB_COPY = JSON.parse(JSON.stringify(JOB))
        STATE.job[job_id] = {
            ...JOB_COPY
        };
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

    // DROP IMAGE JOB
    function onbefore_upload_image_job(eventDrop, dropTo) {
        let {
            target
        } = eventDrop;
        let {
            job
        } = target.dataset;
        $(target).html('');
        STATE.job[job].image = '';
    }

    function onprogress_upload_image_job(eventDrop, percent, dropTo) {
        let {
            target
        } = eventDrop;
        $(target).html(`${percent} %`);
    }

    function onerror_upload_image_job(eventDrop, error, error_text, dropTo) {
        let {
            target
        } = eventDrop;
        $(target).html(`Error`);
        console.log(error)
        alert(error_text);
    }

    function onsuccess_upload_image_job(eventDrop, success, dropTo) {

        let {
            target
        } = eventDrop;
        let job = eventDrop.target.dataset.job;
        let {
            link,
            name
        } = success;

        if (_.isImage(link)) {
            $(target).html(`<img class="img-fluid w-50" alt="${name}" src="${link}" data-bs-toggle="tooltip" data-bs-placement="top" title="Bấm vào để thay thế file" ondragover="$(this).hide()">`);
        } else {
            $(target).html(
                `<div ondragover="$(this).hide()">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <p style="font-size:12px" class="text-truncate">${name}</p>
                </div>`
            );
        }
        STATE.job[job].image = link;
        tooltipTriggerList('body');
    }
    // END DROP IMAGE JOB


    // DROP IMAGE ATTACH
    function onbefore_upload_image_attach(eventDrop, dropTo) {

        let job = eventDrop.target.dataset.job;
        
        let html =
            `<div 
            class="position-relative image-hover p-2" 
            style="width:80px" 
            id="file_attach_${dropTo}" 
            title="" 
            data-file=""
        >
            <div class="position-btn" style="position: absolute; display: block; top: 0; right:0" onclick="remove_attach(this, ${job}, ${dropTo})">
                <button class="btn btn-sm btn-warning rounded-circle">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="file" style="display:none">
                <div width="100%" class="rounded border p-2 shadow" style="text-align:center; aspect-ratio: 1;object-fit: cover;">0%</div>
            </div>

            <div class="percent rounded border p-2 shadow" 
                width="100%" 
                style="display:block; text-align:center; aspect-ratio: 1;object-fit: cover;"
            >
                0%
            </div>
        </div>`;

        $(`#${job}_attach_pre`).append(html);
    }

    function onprogress_upload_image_attach(eventDrop, percent, dropTo) {
        $(`#file_attach_${dropTo} .percent`).text(`${percent} %`);
    }

    function onerror_upload_image_attach(eventDrop, error, error_text, dropTo) {
        $(`#file_attach_${dropTo} .percent`).text(`Error`);
        alert(error_text);
    }

    function onsuccess_upload_image_attach(eventDrop, success, dropTo) {
        let job = eventDrop.target.dataset.job;
        let {
            link,
            name
        } = success;
        $(`#file_attach_${dropTo} .percent`).hide();
        $(`#file_attach_${dropTo} .file`).show();
        $(`#file_attach_${dropTo}`).data('file', link);

        let html = '';
        if (_.isImage(link)) {
            html = `<img width="100%" src="${link}" class="img_attach rounded shadow" alt="" style="aspect-ratio: 1;object-fit: cover;">`;
        } else {
            html =
                `<div width="100%" class="rounded border p-2 text-truncate shadow" style="line-break: anywhere; text-align:center; aspect-ratio: 1;object-fit: cover;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i> <br>
                    <span style="font-size:12px;">${name}</span>
                </div>`
        }

        $(`#file_attach_${dropTo} .file`).html(html);

        let attach = dropTo + Object.keys(STATE.job[job].attach).length;
        STATE.job[job].attach[attach] = link;
    }
    // END DROP IMAGE ATTACH
</script>