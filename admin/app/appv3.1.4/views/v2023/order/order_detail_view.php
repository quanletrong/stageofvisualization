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
                                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false">IMAGE 1</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">IMAGE 2</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">IMAGE 3</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="true">IMAGE 4</a>
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
                                        <option value="design2">design2</option>
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
                                <b>WORKING USER</b>
                                <div class="mt-1">
                                    <div style="color: red; font-weight: bold;">lequanltv</div>
                                    <div class="d-flex">
                                        <div class="w-75">3D Floor Plan</div>
                                        <input class="w-25" type="number" min=1 value="1" style="font-weight: bold;">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-75">VS</div>
                                        <input class="w-25" type="number" min=1 value="1" style="font-weight: bold;">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div style="color: red; font-weight: bold;">design1</div>
                                    <div class="d-flex">
                                        <div class="w-75">VS</div>
                                        <input class="w-25" type="number" min=1 value="1" style="font-weight: bold;">
                                    </div>
                                    <div class="d-flex mt-1">
                                        <div class="w-75">VR</div>
                                        <input class="w-25" type="number" min=1 value="1" style="font-weight: bold;">
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