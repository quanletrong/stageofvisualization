<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Your Happiness is Guaranteed</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <form id="form_happy" method="post" action="setting/submit_home/happy_guaranteed">
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
            </div>
            <div class="form-group">
                <label for="name">Tiêu đề</label>
                <input type="text" class="form-control title" name="title_happy" value="<?=htmlentities(@$happy_guaranteed['title'])?>" required>
            </div>
            <div class="form-group">
                <label for="name">Mô tả ngắn</label>
                <textarea rows=3 class="form-control" name="sapo_happy" required><?=htmlentities(@$happy_guaranteed['sapo'])?></textarea>
            </div>
            <div class="form-group">
                <label for="name">Ảnh</label>
                <button type="button" class="btn btn-sm btn-warning" onclick="quanlt_upload(this)" data-callback="cb_upload_image_happy" data-target="#image_happy">
                    <i class="fas fa-upload"></i>  Upload ảnh
                </button>
                <input type="hidden" id="image_happy" name="image_happy" value="<?=htmlentities(@$happy_guaranteed['image_path'])?>" required>
                <img src="<?=@$happy_guaranteed['image_path']?>" id="image_happy_pre">
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $('#form_happy').validate({
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                form.submit();
            },
            rules: {},
            messages: {},
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group, .input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    })

    function cb_upload_image_happy(link, target, name) {
        $(`${target}_pre`).attr('src', link);
    }
</script>