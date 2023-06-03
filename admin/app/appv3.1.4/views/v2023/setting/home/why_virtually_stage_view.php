
<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Why Virtually Stage?</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        
        <form id="form_home_slide" method="post" action="setting/submit_home/why_virtually_stage">
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-sm btn-danger m-1">Lưu lại</button>
            </div>
            <textarea id="content_why_virtually_stage" name="content_why_virtually_stage"><?=html_entity_decode(htmlspecialchars_decode($setting['why_virtually_stage']))?></textarea>
        </form>
    </div>
</div>

<script>
    var SLIDE = <?= $setting['home_slide'] ?>;
    render_slide();
    $(function() {
        tinymce.init({
            selector: '#content_why_virtually_stage',
            height: "800",
            relative_urls: false,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'help', 'link', 'responsivefilemanager'
            ],
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            menubar: 'favs file edit view insert format tools table help',

            setup: function(ed) {
                ed.on('change', function(e) {
                    $('#content_why_virtually_stage').val(ed.getContent())
                });
            }
        });
    })
</script>