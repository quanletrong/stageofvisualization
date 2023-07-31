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
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false">IMAGE 1 (VR)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">IMAGE 2(VR)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">IMAGE 3(VS)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="true">IMAGE 4(3D)</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
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
                                                        <img src="https://picsum.photos/320/180" class="img-order-all" alt="" width="100%">
                                                    </div>
                                                    <div class="mt-3">
                                                        <b>Attach Reference Files</b>
                                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
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

                                                    <div class="mt-3">
                                                        <div class="d-flex">
                                                            <div style="width: 130px; font-weight: bold;">Room Type</div>
                                                            <div>Phòng Khách</div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div style="width: 130px; font-weight: bold;">Services</div>
                                                            <div>Thêm nội thất và trang trí (VR)</div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div style="width: 130px; font-weight: bold;">Design Style</div>
                                                            <div>Hiện đại</div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <b>Requirements:</b>
                                                        <textarea class="form-control" rows="5">Please use greyish wide plank hardwood flooring in rooms, dark gray tiles in bathrooms and light carpet in bedrooms.Use Scandinavian furniture design and white kitchen with dark gray countertops.</textarea>
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
                                <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                    Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                                    Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                                    Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                                </div>
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
                                        <option value="lequanltv" selected>lequanltv</option>
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
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 1 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv" selected>lequanltv</option>
                                            <option value="design1" selected>design1</option>
                                            <option value="design2">design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 2 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv">lequanltv</option>
                                            <option value="design1" selected>design1</option>
                                            <option value="design2">design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 3 (VS)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv">lequanltv</option>
                                            <option value="design1">design1</option>
                                            <option value="design2" selected>design2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 4 (3D)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv">lequanltv</option>
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
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 1 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv" selected>QC1</option>
                                            <option value="design1">QC2</option>
                                            <option value="design2">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 2 (VR)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv" selected>QC1</option>
                                            <option value="design1">QC2</option>
                                            <option value="design2">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 3 (VS)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv">QC1</option>
                                            <option value="design1" selected>QC2</option>
                                            <option value="design2">QC3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">IMAGE 4 (3D)</div>
                                        <select class="select2" id="" name="tag[]" multiple="multiple" data-placeholder="Select design" style="width: 100%">
                                            <option value="lequanltv">QC1</option>
                                            <option value="design1" selected>QC2</option>
                                            <option value="design2">QC3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <b>GIÁ CUSTOM (cộng thêm tiền cho đơn)</b>
                                <div class="mt-1">
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">QC1</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">QC2</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">lequanltv</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">design1</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">design2</div>
                                        <input class="form-control" value="1">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-50" style="color: red; font-weight: bold;">design3</div>
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
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">CHAT TO CUSTOMER</h3>
                            </div>

                        </div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">CHAT TO CHECKER AND DESIGNER</h3>
                            </div>

                        </div>
                        <div class="card-body">
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
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">saler_1</a> thay đổi trạng thái từ <b>Pending</b> thành <b>QC CHECK</b></h3>
                                    </div>
                                </div>


                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">QC_1</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">QC_2</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">QC_1</a> thay đổi trạng thái từ <b>QC CHECK</b> thành <b>Avaiabel</b></h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">QC_1</a> thay đổi từ <b>Avaiabel</b> thành <b>In Progress</b></h3>
                                    </div>
                                </div>                                

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design3</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đã tham gia job</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đang xử lý Image 1(VR)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design1</a> đang xử lý Image 2(VR)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đang xử lý Image 3(VS)</h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-user bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">design2</a> đang xử lý Image 3(VS)</h3>
                                    </div>
                                </div>



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

                                <div>
                                    <i class="fas fa-user bg-yellow"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span>
                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                                        <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-warning btn-sm">View comment</a>
                                        </div>
                                    </div>
                                </div>


                                <div class="time-label">
                                    <span class="bg-green">3 Jan. 2014</span>
                                </div>


                                <div>
                                    <i class="fa fa-camera bg-purple"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 2 days ago</span>
                                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
                                        <div class="timeline-body">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                        </div>
                                    </div>
                                </div>


                                <div>
                                    <i class="fas fa-video bg-maroon"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 5 days ago</span>
                                        <h3 class="timeline-header"><a href="#">Mr. Doe</a> shared a video</h3>
                                        <div class="timeline-body">

                                        </div>
                                        <div class="timeline-footer">
                                            <a href="#" class="btn btn-sm bg-maroon">See comments</a>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
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