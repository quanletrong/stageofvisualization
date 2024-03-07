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
                    <h1>Trang chính sách bảo mật</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
                        <li class="breadcrumb-item active">Chính sách bảo mật</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"></h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">

                    <form method="post" action="setting/termsofuse">
                        <div style="display: flex; justify-content: flex-start;">
                            <button type="submit" class="btn btn-sm btn-danger m-1">Lưu lại</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="text-bold">Nhập nội dung</span>
                                <textarea id="termsofuse" name="termsofuse"><?= html_entity_decode(htmlspecialchars_decode($setting['termsofuse'])) ?></textarea>

                            </div>

                            <div class="col-md-6">
                                <span class="text-bold">Xem trước</span>
                                <div id="termsofuse_pre" class="border p-2"><?= html_entity_decode(htmlspecialchars_decode($setting['termsofuse'])) ?></div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-start;">
                            <button type="submit" class="btn btn-sm btn-danger m-1">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- /.card -->
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