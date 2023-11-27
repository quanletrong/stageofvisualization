<style>
    .card-header {
        padding: 0.3rem 0.8rem;
    }

    #list-image-order .card-body {
        padding: 0.5rem 0.8rem;
    }

    .image-hover:hover .position-btn {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .position-relative:hover i {
        cursor: pointer;
        color: red;
    }
</style>
<div class="container-fluid">
    <h1 class="fs-4 mt-3">ORDERS DETAIL</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('') ?>"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="<?= LINK_USER_ORDER ?>">My Order</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Order</li>
        </ol>
    </nav>
    <div class="row">
        <!-- CỘT TRÁI -->
        <div class="col-12 col-lg-9">
            <div class="card card-tabs card-primary">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="TabImage" role="tablist">
                        <?php $active = 'active' ?>
                        <?php $index = 1 ?>
                        <?php foreach ($list_job as $id_job => $job) { ?>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $active ?>" id="tab_job_<?= $id_job ?>" data-bs-toggle="tab" data-bs-target="#tab_content_job_<?= $id_job ?>" type="button" role="tab" aria-controls="tab_content_job_<?= $id_job ?>" aria-selected="true">IMAGE <?= $index++ ?> (<?= $job['type_service'] ?>)</button>
                            </li>
                            <?php $active = '' ?>
                        <?php } ?>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="TabContentImage">
                        <?php $active = 'active show' ?>
                        <?php foreach ($list_job as $id_job => $job) { ?>
                            <div class="tab-pane <?= $active ?>" id="tab_content_job_<?= $id_job ?>" role="tabpanel" aria-labelledby="tab_job_<?= $id_job ?>" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="card shadow">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%; gap:5px">
                                                        <div>ORIGINAL FILE(S)</div>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="position-relative image-hover">
                                                    <b>Files main</b>
                                                    <?php if (stringIsImage($job['image'])) { ?>
                                                        <img src="<?= url_image($job['image'], $FDR_ORDER) ?>" class="mt-2 shadow" alt="<?= $job['image'] ?>" width="100%">
                                                    <?php } else { ?>
                                                        <div class="border shadow" style="text-align: center;width: 100%; padding: 30px 0;" title="<?= $job['image'] ?>">
                                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                            <div style="font-size:12px;"><?= $job['image'] ?></div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="mt-3">
                                                    <b>Attach Reference Files</b>
                                                    <div class="mt-2" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                        <?php $list_attach = json_decode($job['attach'], true); ?>
                                                        <?php foreach ($list_attach as $key => $item) { ?>

                                                            <div class="position-relative">
                                                                <?php if (stringIsImage($item)) { ?>
                                                                    <img src="<?= url_image($item, $FDR_ORDER) ?>" alt="<?= $item ?>" class="shadow" width="100">
                                                                <?php } else { ?>
                                                                    <div style="width:100px;aspect-ratio: 1; object-fit: cover; text-align:center" class="border p-2 pt-4 shadow" title="<?= $item ?>">
                                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                                        <p style="font-size:12px" class="text-truncate"><?= $item ?></p>
                                                                    </div>
                                                                <?php } ?>


                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mt-3">
                                                    <div class="d-flex">
                                                        <div style="min-width: 130px; font-weight: bold;">Room Type</div>
                                                        <div><?= $job['room'] ?></div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div style="min-width: 130px; font-weight: bold;">Services</div>
                                                        <div><?= $job['service'] ?> (<?= $job['type_service'] ?>)</div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div style="min-width: 130px; font-weight: bold;">Design Style</div>
                                                        <div><?= $job['style'] ?></div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mt-3">
                                                    <b>Requirements:</b>
                                                    <textarea class="form-control" rows="5" disabled><?= $job['requirement'] ?></textarea>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <!-- CARD COMPLETED FILE-->
                                        <div class="card shadow">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                        <div>COMPLETED FILE(S)</div>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <?php if (count($job['file_complete'])) { ?>
                                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                                        <?php foreach ($job['file_complete'] as $key => $file) { ?>
                                                            <div class="position-relative" style="width: 48%;">
                                                                <img src="<?= url_image($file, $FDR_ORDER) ?>" alt="" width="100%">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="alert alert-warning" role="alert">
                                                        Your orders are being processed!
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>

                                        <!-- CARD REWORK LIST-->
                                        <?php
                                        $data['id_order']  = $order['id_order'];
                                        $data['id_job']    = $id_job;
                                        $data['job']       = $job;
                                        $data['FDR_ORDER'] = $FDR_ORDER;
                                        $this->load->view(TEMPLATE_FOLDER . 'user/order_detail/_job_content_rework_view.php', $data);
                                        ?>
                                        <!-- END CARD REWORK LIST-->

                                    </div>
                                </div>
                            </div>

                            <?php $active = '' ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI -->
        <div class="col-12 col-lg-3">
            <div class="card card-primary" style="height: 100%;">
                <!-- <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                            <div>ACTION</div>
                        </h6>
                    </div>
                </div> -->
                <div class="card-body">
                    <div class="mt-3">
                        <?php
                        if ($order['status'] == ORDER_PENDING) {
                            echo '<button class=" btn w-100" style="color:white;background-color: deeppink" >PENDING</button>';
                            echo '<p>Đơn hàng của bạn đang chờ duyệt!</p>';
                        } else if ($order['status'] == ORDER_DELIVERED) {
                            echo '<button class=" btn btn-info w-100">DELIVERED</button>';
                            echo '<p>Đơn hàng của bạn đã được giao. Vui lòng kiểm tra lại!</p>';
                        } else if ($order['status'] == ORDER_COMPLETE) {
                            echo '<button class="btn btn-success w-100">COMPLETE</button>';
                            echo '<p>Đơn hàng này đã hoàn thành!</p>';
                        } else if ($order['status'] == ORDER_CANCLE) {
                            echo '<button class="btn btn-danger w-100">CANCLE</button>';
                            echo '<p>Đơn hàng của bạn đã hủy!</p>';
                        } else if ($order['status'] == ORDER_REWORK) {
                            echo '<button class="btn btn-danger w-100">REWORK</button>';
                            echo '<p>Đơn hàng của bạn đang được làm lại!</p>';
                        } else if ($order['status'] == ORDER_PAY_WAITING) {
                            echo '<button class="btn btn-danger w-100" onclick="open_popup_pay('.$order['id_order'].')">CLICK TO PAY!</button>';
                            echo '<p>Đơn hàng của bạn chưa thanh toán!</p>';
                        } else {
                            echo '<button class="btn btn-warning w-100">IN PROGRESS</button>';
                            echo '<p>Đơn hàng của bạn đang được xử lý.</p>';
                        }
                        ?>
                    </div>

                    <div class="mt-3">
                        <p><b style="color: orange;">ID Customer: <?= $order['code_user'] != '' ? htmlentities($order['code_user']) : '#CID' . $order['code_user'] ?></b></p>
                        <p><b style="color: orange;">ID Order: <?= $order['code_order'] != '' ? htmlentities($order['code_order']) : '#OID' . $order['id_order'] ?></b></p>

                        <?php foreach ($list_type_service as $type => $val) { ?>
                            <p><b style="color: orange;"><?= $type ?>: <?= count($val) ?></b></p>
                        <?php } ?>

                        <p><b style="color: orange;">TOTAL: [<?= count($list_job) ?>]</b></p>
                    </div>

                    <?php if ($order['status'] == ORDER_DELIVERED) { ?>
                        <div class="mt-3">
                            <p>If you agree with this file, then click COMPLETE!</p>
                            <button class=" btn btn-success w-100">COMPLETE</button>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-warning w-100 mt-2" onclick="$('#card_new_rework_<?= $id_job ?>').removeClass('d-none');"> <i class="fas fa-plus"></i> Add Rework</button>
                        </div>
                    <?php } ?>
                    <!-- <div class="mt-3" onclick="alert('Chức năng đang phát triển')">
                        <img src="images/chat.png" class="w-100" style="border:  1px solid #eee;">
                    </div> -->
                </div>
            </div>
        </div>

    </div>

    <!-- CHAT BOX -->
    <?php
    $this->load->view(TEMPLATE_FOLDER . 'user/order_detail/_chat_view.php', [
        'order' => $order
    ]);
    ?>
    <!-- END CHAT BOX -->


</div>

<script>
    $(document).ready(function() {
        // TODO: có vấn đề
        $("textarea").each(function(textarea) {
            $(this).height(60).height($(this)[0].scrollHeight < 60 ? 60 : $(this)[0].scrollHeight);
        });
    })

    function open_popup_pay(id_order) {
        
        let LOCAL_PAY = Date.now();
        localStorage.setItem(LOCAL_PAY, "RUNING")

        let url = `<?= site_url('order/ajax_popup_payment/') ?>/${id_order}?l=${LOCAL_PAY}`;
        newwindow = window.open(url, 'Payment Order', 'height=800,width=500');

        let kiem_tra_trang_thai_pay = setInterval(() => {
            var trang_thai_pay = localStorage.getItem(LOCAL_PAY);

            if (trang_thai_pay == "RUNING") {
                console.log(trang_thai_pay);
            } else {

                localStorage.removeItem(LOCAL_PAY);
                clearInterval(kiem_tra_trang_thai_pay)

                if (trang_thai_pay == '<?= PAY_HUY ?>') {
                    alert('Thanh toán không thành công');
                } else if (trang_thai_pay == '<?= PAY_HOAN_THANH ?>') {
                    alert('Thanh toán thành công');
                    window.location.reload();
                }
            }

        }, 2000);
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }
</script>