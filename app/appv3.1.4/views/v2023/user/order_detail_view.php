<style>
    #TabContentImage img {
        border-radius: 5px;
        box-shadow: 3px 2px 7px 0px #888888;
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
                            <div class="tab-pane fade <?= $active ?>" id="tab_content_job_<?= $id_job ?>" role="tabpanel" aria-labelledby="tab_job_<?= $id_job ?>" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="card shadow">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                        <div>ORIGINAL FILE(S)</div>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="position-relative image-hover">
                                                    <img src="<?= url_image($job['image'], "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" class="img-order-all" alt="" width="100%">
                                                </div>
                                                <div class="mt-3">
                                                    <b>Attach Reference Files</b>
                                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                        <?php $list_attach = json_decode($job['attach'], true); ?>
                                                        <?php foreach ($list_attach as $key => $item) { ?>
                                                            <div class="position-relative">
                                                                <img src="<?= url_image($item, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

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
                                                <?php if ($job['file_complete'] != '') { ?>
                                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                                        <?php $list_complete = json_decode($job['file_complete'], true); ?>
                                                        <?php foreach ($list_complete as $key => $file) { ?>
                                                            <div class="position-relative" style="width: 48%;">
                                                                <img src="<?= url_image($file, "uploads/images/" . $job['year'] . "/" . $job['month'] . "/") ?>" alt="" width="100%">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="alert alert-warning" role="alert">
                                                        Orders are being processed!
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>

                                        <!-- TODO: chưa làm -->
                                        <?php if ($order['status'] == ORDER_DELIVERED) { ?>
                                            <div class="mt-3">
                                                <button class="btn btn-warning w-100 shadow" onclick="alert('Chức năng đang phát triển')"><i class="fa-solid fa-plus"></i> ADD REWORK</button>
                                            </div>
                                        <?php } ?>

                                        <!-- CARD REWORK LIST-->
                                        <!-- TODO: chưa làm -->
                                        <div class="card shadow d-none">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                        <div>REWORK 1</div>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div>
                                                    <b>Requirements</b>
                                                    <textarea class="form-control " rows="5">Please use greyish wide plank hardwood flooring in rooms, dark gray tiles in bathrooms and light carpet in bedrooms.Use Scandinavian furniture design and white kitchen with dark gray countertops.</textarea>
                                                </div>
                                                <div class="mt-2">
                                                    <b>Attach Reference Files</b>
                                                    <div class="d-flex flex-wrap" style="gap:7px">
                                                        <div class="position-relative">
                                                            <img src="https://picsum.photos/320/180" alt="" width="100">
                                                        </div>

                                                        <div class="position-relative">
                                                            <img src="https://picsum.photos/320/180" alt="" width="100">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-2">
                                                    <b>File rework complete</b>
                                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                                        <div class="position-relative" style="width: 48%;">
                                                            <img src="https://picsum.photos/320/180" alt="" width="100%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            <div class="card card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                            <div>ACTION</div>
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mt-3">
                        <?php
                        if ($order['status'] == ORDER_PENDING) {
                            echo '<button class=" btn w-100" style="color:white;background-color: deeppink" >PENDING</button>';
                            echo '<p>Đơn hàng của bạn đang đợi duyệt!</p>';
                        } else if ($order['status'] == ORDER_DELIVERED) {
                            echo '<button class=" btn btn-info w-100">DELIVERED</button>';
                            echo '<p>Đơn hàng của bạn đã được giao. Vui lòng kiểm tra lại!</p>';
                        } else if ($order['status'] == ORDER_COMPLETE) {
                            echo '<button class="btn btn-success w-100">COMPLETE</button>';
                            echo '<p>Bạn đã nhận được đơn hàng!</p>';
                        } else if ($order['status'] == ORDER_CANCLE) {
                            echo '<button class="btn btn-danger w-100">CANCLE</button>';
                            echo '<p>Đơn hàng của bạn đã bị xóa!</p>';
                        } else {
                            echo '<button class="btn btn-warning w-100">IN PROGRESS</button>';
                            echo '<p>Đơn hàng của bạn đang được xử lý.</p>';
                        }
                        ?>
                    </div>

                    <div class="mt-3">
                        <p><b style="color: orange;">ID Customer: CID<?= $order['id_user'] ?></b></p>
                        <p><b style="color: orange;">ID Order: OID<?= $order['id_order'] ?></b></p>

                        <?php foreach ($list_type_service as $type => $val) { ?>
                            <p><b style="color: orange;"><?= $type ?>: <?= count($val) ?></b></p>
                        <?php } ?>

                        <p><b style="color: orange;">TOTAL: [<?= count($list_job) ?>]</b></p>
                    </div>

                    <?php if($order['status'] == ORDER_DELIVERED) {?>
                    <div class="mt-3">
                        <p>If you agree with this file, then click COMPLETE!</p>
                        <button class=" btn btn-success w-100">COMPLETE</button>
                    </div>
                    <?php } ?>
                    <!-- <div class="mt-3" onclick="alert('Chức năng đang phát triển')">
                        <img src="images/chat.png" class="w-100" style="border:  1px solid #eee;">
                    </div> -->
                </div>
            </div>
        </div>

    </div>



</div>