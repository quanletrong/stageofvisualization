<?php ?>
<style>
    #termsofuse_pre img,
    #termsofuse_pre iframe,
    #termsofuse_pre table {
        width: 100% !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trang How It Works</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">How It Works</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Title, desc -->
            <div class="container my-5 d-flex flex-column align-items-center">
                <div class="" style="font-size: 2.7em; font-weight: bold;"> Get Photos Virtually Staged in 24 Hours or Less
                </div>
                <div class="mt-2 fs-5">Quickly and Easily Maximize the Appeal and Value of Your Listings in Three Simple Steps
                </div>
            </div>
            <!-- END Title, desc -->

            <!-- STEP 1 -->
            <div class="row my-5">
                <div class="col-12 col-lg-6">
                    <div class="text-color fw-bold fs-5">
                        <input type="text" class="form-control" value="Step 1">
                    </div>
                    <div class="fw-bold fs-2 mt-2">
                        <input type="text" class="form-control" value="Upload Your Photos">
                    </div>
                    <div class="mt-2 fs-5">
                        <input type="text" class="form-control" value="Upload the photos you want staged.">
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div style="display: flex; gap:20px">
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Icon</span>
                                <button type="button" class="btn btn-warning button-upload btn-sm" onclick="quanlt_upload(this)" data-callback="cb_upload_image_slide" data-target="#image_1707207906343">
                                    <i class="fas fa-upload"></i> Upload ảnh
                                </button>
                            </div>
                            <img src="http://stageofvisualization.local/images/virtual-staging-how-it-works-step-1.jpg" class="rounded shadow" alt="">
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Ảnh mô tả</span>
                                <button type="button" class="btn btn-warning button-upload btn-sm" onclick="quanlt_upload(this)" data-callback="cb_upload_image_slide" data-target="#image_1707207906343">
                                    <i class="fas fa-upload"></i> Upload ảnh
                                </button>
                            </div>
                            <img src="http://stageofvisualization.local/images/virtual-staging-how-it-works-step-1.jpg" class="rounded shadow" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <!-- END STEP 1 -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(function() {
        tinymce.init({
            selector: '#termsofuse',
            height: "800",
            relative_urls: false,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'help', 'link', 'responsivefilemanager'
            ],
            toolbar: 'responsivefilemanager | undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            menubar: 'favs file edit view insert format tools table help',
            external_filemanager_path: "<?= ROOT_DOMAIN ?>filemanager/filemanager/",
            filemanager_title: "Thư viện ảnh",
            external_plugins: {
                // "responsivefilemanager": "<?= ROOT_DOMAIN ?>filemanager/filemanager/plugin.min.js",
                "filemanager": "<?= ROOT_DOMAIN ?>filemanager/filemanager/plugin.min.js"
            },
            setup: function(ed) {
                ed.on('change', function(e) {
                    $('#termsofuse').val(ed.getContent())
                    $('#termsofuse_pre').html(ed.getContent())
                });
            }
        });
    })
</script>