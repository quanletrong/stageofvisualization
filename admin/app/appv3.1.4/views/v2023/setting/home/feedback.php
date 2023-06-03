<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Đánh giá của người dùng</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-2">
        <form id="form_feedback" method="post" action="setting/submit_home/feedback">
            <input type="hidden" name="feedback" />
            <div style="display: flex; justify-content: flex-end; gap:10px">
                <button type="button" onclick="add_feedback()" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
            </div>

            <div id="list_feedback">
            </div>
        </form>
    </div>
</div>

<script>
    var FEEDBACK = <?= $setting['feedback']  ?>;
    render_feedback();
    $(function() {
        $('#form_feedback').validate({
            submitHandler: function(form) {

                if ($(form).find('input[name="feedback"]') == '') {

                    alert('Hãy nhập đây đủ dữ liệu');
                    $(form).find('button[type="submit"]').attr('disabled', false);

                } else {

                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    $(form).find('input[name="feedback"]').val(JSON.stringify(FEEDBACK))
                    form.submit();
                }
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

    function add_feedback() {
        let row_last = $('#list_feedback .form-group').last();
        if (row_last.find('input').val() != '' && row_last.find('textarea').val() != '') {
            let feedback_id = Date.now();

            FEEDBACK[feedback_id] = {
                'user': '',
                'content': ''
            }

            render_feedback();

            $('#list_feedback  .form-group').last().find('input').focus();
        } else {
            $('#list_feedback .form-group').last().find('input').focus();
        }
    }

    function render_feedback() {
        $('#list_feedback').html('');
        for (const feedback_id in FEEDBACK) {

            let user = FEEDBACK[feedback_id].user;
            let content = FEEDBACK[feedback_id].content;
            let row_new = `
        <div class="form-group" id="${feedback_id}">
            <label>Đánh giá</label>
            <span class="text-danger" style="cursor:pointer" onclick="delete FEEDBACK[${feedback_id}]; render_feedback()">Xóa đánh giá</span>
            <input type="text" class="form-control mb-2" placeholder="Tên người đánh giá" value="${htmlEntities(user)}" onchange="FEEDBACK[${feedback_id}].user = $(this).val()">
            <textarea rows=3 class="form-control" placeholder="Nội dung đánh giá" onchange="FEEDBACK[${feedback_id}].content = $(this).val()">${htmlEntities(content)}</textarea>
        </div>`;

            $('#list_feedback').append(row_new);
        }
    }
</script>