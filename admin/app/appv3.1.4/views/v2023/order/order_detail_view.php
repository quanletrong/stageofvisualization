<style>
    img {
        border-radius: 5px;
        box-shadow: 3px 2px 7px 0px #888888;
    }

    .card-header {
        padding: 0.3rem 0.8rem;
    }

    #list-image-order .card-body {
        padding: 0.5rem 0.8rem;
    }

    .btn-upfile {
        display: flex;
        justify-items: center;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5px;
        border-style: dotted;
        cursor: pointer;
        max-width: 200px;
        height: fit-content;
    }

    .btn-upfile div {
        text-align: center;
        font-size: 0.8rem;
    }

    .btn-upfile:hover {
        background-color: aliceblue;
    }

    .image-hover:hover .btn-upfile {
        display: flex !important;
        background-color: aliceblue;
    }

    .position-relative:hover i {
        cursor: pointer;
        color: red;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chi tiết đơn hàng</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('order') ?>">Danh sách đơn hàng</a></li>
                        <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- IMAGE -->
                <div class="col-12 col-lg-9" id="list-image-order">
                    <!-- <div class="card card-primary  card-tabs"> -->
                    <div class="card card-tabs card-primary">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="tab_job" role="tablist">
                                <?php $active = 'active' ?>
                                <?php $index = 1 ?>
                                <?php foreach ($list_job as $id_job => $job) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $active; ?>" id="tab_job_<?= $id_job ?>" data-toggle="pill" href="#tab_content_job_<?= $id_job ?>" role="tab" aria-controls="tab_content_job_<?= $id_job ?>" aria-selected="false">IMAGE <?= $index++ ?> (<?= $job['type_service'] ?>)</a>
                                    </li>
                                    <?php $active = '' ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="tab_content_job">
                                <?php $active = 'active show' ?>
                                <?php foreach ($list_job as $id_job => $job) { ?>
                                    <div class="tab-pane fade <?= $active; ?>" id="tab_content_job_<?= $id_job ?>" role="tabpanel" aria-labelledby="tab_job_<?= $id_job ?>">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <div class="card card-primary shadow ">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                                <div>ORIGINAL FILE(S)</div>
                                                                <button class="btn btn-danger btn-sm">SAVE</button>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="position-relative image-hover">
                                                            <div class="btn-upfile rounded border shadow" style="position: absolute; display: none; left: 45%; top: 45%;">
                                                                <i class="fas fa-upload"></i>
                                                                <div>Upload file other</div>
                                                            </div>
                                                            <img src="<?= url_image($job['image'], 'uploads/images/2023/07/') ?>" class="img-order-all" alt="" width="100%">
                                                        </div>
                                                        <div class="mt-3">
                                                            <b>Attach Reference Files</b>
                                                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                                <?php $list_attach = json_decode($job['attach'], true); ?>
                                                                <?php foreach ($list_attach as $key => $item) { ?>
                                                                    <div class="position-relative">
                                                                        <div class="position-absolute" style="right: 10px">
                                                                            <i class="fas fa-times icon-delete-image"></i>
                                                                        </div>
                                                                        <img src="<?= url_image($item, 'uploads/images/2023/07/') ?>" alt="" width="100">
                                                                    </div>
                                                                <?php } ?>
                                                                
                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>attach reference</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3">
                                                            <div class="d-flex">
                                                                <div style="min-width: 130px; font-weight: bold;">Room Type</div>
                                                                <div><?=$job['room']?></div>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div style="min-width: 130px; font-weight: bold;">Services</div>
                                                                <div><?=$job['service']?> (<?=$job['type_service']?>)</div>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div style="min-width: 130px; font-weight: bold;">Design Style</div>
                                                                <div><?=$job['style']?></div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3">
                                                            <b>Requirements:</b>
                                                            <textarea class="form-control" rows="5"><?=$job['requirement']?></textarea>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-12 col-lg-6">
                                                <!-- CARD COMPLETED FILE-->
                                                <div class="card card-primary shadow">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                                <div>COMPLETED FILE(S)</div>
                                                                <button class="btn btn-danger btn-sm">SAVE</button>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div>
                                                            <!-- <b>File</b> -->
                                                            <div class="d-flex flex-wrap" style="gap: 10px;">

                                                                <div class="position-relative" style="width: 48%;">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100%">
                                                                </div>

                                                                <div class="position-relative" style="width: 48%;">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100%">
                                                                </div>
                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file complete</div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- CARD REWORK LIST-->
                                                <div class="card card-primary shadow">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                                <div>REWORK 1</div>
                                                                <button class="btn btn-danger btn-sm">SAVE</button>
                                                            </h3>
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
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100">
                                                                </div>

                                                                <div class="position-relative">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100">
                                                                </div>

                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>attach reference</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-2">
                                                            <b>File rework complete</b>
                                                            <div class="d-flex flex-wrap" style="gap: 10px;">
                                                                <div class="position-relative" style="width: 48%;">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100%">
                                                                </div>
                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>rework complete</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card card-primary shadow">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                                                <div>REWORK 1</div>
                                                                <button class="btn btn-danger btn-sm">SAVE</button>
                                                            </h3>
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
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100">
                                                                </div>

                                                                <div class="position-relative">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100">
                                                                </div>

                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>attach reference</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-2">
                                                            <b>File rework complete</b>
                                                            <div class="d-flex flex-wrap" style="gap: 10px;">
                                                                <div class="position-relative" style="width: 48%;">
                                                                    <div class="position-absolute" style="right: 10px">
                                                                        <i class="fas fa-times icon-delete-image"></i>
                                                                    </div>
                                                                    <img src="https://picsum.photos/320/180" alt="" width="100%">
                                                                </div>
                                                                <div class="btn-upfile rounded border shadow">
                                                                    <i class="fas fa-upload"></i>
                                                                    <div>Upload file <br>rework complete</div>
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
                <!-- TEAM ACTION -->
                <div class="col-12 col-lg-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                                    <div>TEAM ACTION</div>
                                    <button class="btn btn-danger btn-sm">SAVE</button>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <b>Assign</b>
                                <p>
                                    <select class="select2" id="tag" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                        <option value="design1" selected>design1</option>
                                        <option value="design2" selected>design2</option>
                                        <option value="design2" selected>design3</option>
                                    </select>
                                </p>

                            </div>

                            <div class="mt-3">
                                <b>Countdown time</b>
                                <p>
                                <div style=" border: 1px solid #ddd; padding: 3px 10px; border-radius: 4px; text-align: center; background: #eee; font-weight: bold;">00:02:57:00</div>
                                </p>
                            </div>

                            <div class="mt-3">
                                <b>Custom time (thêm thời gian cho đơn)</b>
                                <p>
                                    <input type="text" class="form-control" style="text-align: center; font-weight: bold;" value="00:02:57:00">
                                </p>
                            </div>

                            <div class="mt-3">
                                <b>Job status</b>
                                <div style=" border: 1px solid #ddd; padding: 3px 10px; border-radius: 4px; text-align: center; background: #eee; font-weight: bold; color: green;">Complete</div>
                            </div>

                            <div class="mt-3">
                                <p><b style="color: orange;">CID: C123456</b></p>
                                <p><b style="color: orange;">JID: J123456</b></p>
                            </div>
                            <hr>
                            <div class="mt-3">
                                <p><b style="color: orange;">3D Floor Plan: 1</b></p>
                                <p><b style="color: orange;">VS: 2</b></p>
                                <p><b style="color: orange;">VR: 1</b></p>
                                <p><b style="color: orange;">TOTAL: [4]</b></p>
                            </div>
                            <hr>
                            <div class="mt-3">
                                <b>WORKING DESIGNER</b>
                                <div class="mt-1">
                                    <div class="d-flex">
                                        <div class="w-50" style="color: red;">IMAGE 1 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="design1" selected>design1</option>
                                            <option value="design2">design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 2 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="design1" selected>design1</option>
                                            <option value="design2">design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 3 (VS)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="design1">design1</option>
                                            <option value="design2" selected>design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 4 (3D)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="design1">design1</option>
                                            <option value="design2" selected>design2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <b>WORKING QC</b>
                                <div class="mt-1">
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 1 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="QC1" selected>QC1</option>
                                            <option value="QC2">QC2</option>
                                            <option value="QC3">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 2 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="QC1" selected>QC1</option>
                                            <option value="QC2">QC2</option>
                                            <option value="QC3">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 3 (VS)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="QC1">QC1</option>
                                            <option value="QC2" selected>QC2</option>
                                            <option value="QC3">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">IMAGE 4 (3D)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="QC1">QC1</option>
                                            <option value="QC2" selected>QC2</option>
                                            <option value="QC3">QC3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <b>GIÁ CUSTOM (cộng thêm tiền cho đơn)</b>
                                <div class="mt-1">
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">Tổng custom</div>
                                        <input class="form-control" value="1">
                                    </div>

                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">QC1</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">QC2</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">design1</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">design2</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red;">design3</div>
                                        <input class="form-control" value="2">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button class="w-100 btn btn-outline btn-success">ACCEPT</button>
                            <button class="w-100 btn btn-outline btn-danger mt-3">DOES NOT ACCEPT</button>
                            <b style="color: red;">Reason for not accepting?</b>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card direct-chat direct-chat-primary" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title">CHAT WITH CUSTOMER</h3>
                            <div class="card-tools">
                                <span title="3 New Messages" class="badge badge-primary">3</span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="direct-chat-messages">

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        Is this template really for free? That's unbelievable!
                                    </div>

                                </div>


                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        You better believe it!
                                    </div>

                                </div>


                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        Working with AdminLTE on a great new app! Wanna join?
                                    </div>

                                </div>


                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>

                                </div>

                            </div>


                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Count Dracula
                                                    <small class="contacts-list-date float-right">2/28/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Sarah Doe
                                                    <small class="contacts-list-date float-right">2/23/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I will be waiting for...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nadia Jolie
                                                    <small class="contacts-list-date float-right">2/20/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I'll call you back at...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nora S. Vans
                                                    <small class="contacts-list-date float-right">2/10/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Where is your new...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    John K.
                                                    <small class="contacts-list-date float-right">1/27/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Can I take a look at...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Kenneth M.
                                                    <small class="contacts-list-date float-right">1/4/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Never mind I found...</span>
                                            </div>

                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </div>

                        <div class="card-footer">
                            <form action="#" method="post">
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-primary">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card direct-chat direct-chat-primary" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title">CHAT WITH QC AND DESIGN</h3>
                            <div class="card-tools">
                                <span title="3 New Messages" class="badge badge-primary">3</span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="direct-chat-messages">

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        Is this template really for free? That's unbelievable!
                                    </div>

                                </div>


                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        You better believe it!
                                    </div>

                                </div>


                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        Working with AdminLTE on a great new app! Wanna join?
                                    </div>

                                </div>


                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                                    </div>

                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>

                                </div>

                            </div>


                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Count Dracula
                                                    <small class="contacts-list-date float-right">2/28/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Sarah Doe
                                                    <small class="contacts-list-date float-right">2/23/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I will be waiting for...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nadia Jolie
                                                    <small class="contacts-list-date float-right">2/20/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I'll call you back at...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nora S. Vans
                                                    <small class="contacts-list-date float-right">2/10/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Where is your new...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    John K.
                                                    <small class="contacts-list-date float-right">1/27/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Can I take a look at...</span>
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Kenneth M.
                                                    <small class="contacts-list-date float-right">1/4/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Never mind I found...</span>
                                            </div>

                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </div>

                        <div class="card-footer">
                            <form action="#" method="post">
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-primary">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">LỊCH SỬ</h3>
                            </div>

                        </div>
                        <div class="card-body">

                            <div class="timeline">

                                <!-- <div class="time-label">
                                    <span class="bg-red">10 Feb. 2014</span>
                                </div> -->

                                <div>
                                    <i class="fas fa-cart-plus bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">C123456</a> đã tạo thành công đơn hàng</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 13:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">saler_1</a> thay đổi trạng thái từ <b>Pending</b> thành <b>QC CHECK</b></h3>
                                    </div>
                                </div>


                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 14:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">QC_1</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 15:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">QC_2</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 16:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">QC_1</a> thay đổi trạng thái từ <b>QC CHECK</b> thành <b>Avaiabel</b></h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 17:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 18:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 19:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đang xử lý Image 1(VR)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 20:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đang xử lý Image 2(VR)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 21:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đang xử lý Image 3(VS)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 22:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đang xử lý Image 4(3D)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 23:05 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> thay đổi trạng thái từ <b>IN PROCESS</b> thành <b>DONE</b>
                                            <h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 23:45 30/07/2023</span>
                                        <h3 class="timeline-header"><a href="#">QC1</a> thay đổi trạng thái từ <b>DONE</b> thành <b>DELIVERY</b>
                                            <h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-check-circle bg-success"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 07:05 31/07/2023</span>
                                        <h3 class="timeline-header">Hoàn thành đơn<h3>
                                    </div>
                                </div>

                                <div class="d-none">
                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #4 Cập nhật bởi <span style="color: red;">design1</span> lúc <span style="color: red;">15:15 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>Avaiabel</b> thành <b>In Progress</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #5 Cập nhật bởi <span style="color: red;">lequanltv</span> lúc <span style="color: red;">16:00 30-07-2023 </span>
                                        <div>Image 1(VR) upload file complete</div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #6 Cập nhật bởi <span style="color: red;">design1</span> lúc <span style="color: red;">16:15 30-07-2023 </span>
                                        <div>Image 2(VR) upload file complete</div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #7 Cập nhật bởi <span style="color: red;">design2</span> lúc <span style="color: red;">16:16 30-07-2023 </span>
                                        <div>Image 3(VS) upload file complete</div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #8 Cập nhật bởi <span style="color: red;">design3</span> lúc <span style="color: red;">16:17 30-07-2023 </span>
                                        <div>Image 4(3D) upload file complete</div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #9 Cập nhật bởi <span style="color: red;">design3</span> lúc <span style="color: red;">16:18 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>In Progress</b> thành <b>DONE</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #10 Cập nhật bởi <span style="color: red;">QC1</span> lúc <span style="color: red;">16:30 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>DONE</b> thành <b>Delivered</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #11 Cập nhật bởi <span style="color: red;">khach_hang_1</span> lúc <span style="color: red;">17:30 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>Delivered</b> thành <b>REWORK</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #12 Cập nhật bởi <span style="color: red;">sale1</span> lúc <span style="color: red;">17:35 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>REWORK</b> thành <b>QC CHECK</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #13 Cập nhật bởi <span style="color: red;">QC1</span> lúc <span style="color: red;">17:40 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>QC CHECK</b> thành <b>In Progress</b></div>
                                        <div>Đã thêm <b>design4</b></div>
                                        <div>Giá custom <b>design4</b> thay đổi từ 0 thành 1</div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #14 Cập nhật bởi <span style="color: red;">design4</span> lúc <span style="color: red;">18:45 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>In Progress</b> thành <b>DONE</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #15 Cập nhật bởi <span style="color: red;">QC1</span> lúc <span style="color: red;">19:30 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>DONE</b> thành <b>Delivered</b></div>
                                    </div>

                                    <div class="mb-1" style="border-bottom: 1px solid #dedede;">
                                        #16 Cập nhật bởi <span style="color: red;">khach_hang_1</span> lúc <span style="color: red;">20:30 30-07-2023 </span>
                                        <div>Trạng thái thay đổi từ <b>DONE</b> thành <b>COMPLETE</b></div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>