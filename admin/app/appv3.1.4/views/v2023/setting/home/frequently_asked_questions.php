<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Frequently Asked Questions</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-2">
        <form id="form_asked_question" method="post" action="setting/submit_home/asked_question">
            <input type="hidden" name="asked_question" />
            <div style="display: flex; justify-content: flex-end; gap:10px">
                <button type="button" onclick="add_asked_question()" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
            </div>

            <div id="list_asked_question">
            </div>
        </form>
    </div>
</div>

<script>
    var ASKED_QUESTION = <?= $setting['asked_question']  ?>;
    render_asked_question();
    $(function() {
        $('#form_asked_question').validate({
            submitHandler: function(form) {

                if ($(form).find('input[name="asked_question"]') == '') {

                    alert('Hãy nhập đây đủ dữ liệu');
                    $(form).find('button[type="submit"]').attr('disabled', false);

                } else {

                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    $(form).find('input[name="asked_question"]').val(JSON.stringify(ASKED_QUESTION))
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

    function add_asked_question() {
        let row_last = $('#list_asked_question .form-group').last();
        if (row_last.find('input').val() != '' && row_last.find('textarea').val() != '') {
            let asked_question_id = Date.now();

            ASKED_QUESTION[asked_question_id] = {
                'asked': '',
                'question': ''
            }

            render_asked_question();

            $('#list_asked_question  .form-group').last().find('input').focus();
        } else {
            $('#list_asked_question .form-group').last().find('input').focus();
        }
    }

    function render_asked_question() {
        $('#list_asked_question').html('');
        for (const asked_question_id in ASKED_QUESTION) {

            let asked = ASKED_QUESTION[asked_question_id].asked;
            let question = ASKED_QUESTION[asked_question_id].question;
            let row_new = `
        <div class="form-group" id="${asked_question_id}">
            <label>Câu hỏi</label>
            <span class="text-danger" style="cursor:pointer" onclick="delete ASKED_QUESTION[${asked_question_id}]; render_asked_question()">Xóa câu hỏi</span>
            <input type="text" class="form-control mb-2" placeholder="Nhập câu hỏi" value="${asked}" onchange="ASKED_QUESTION[${asked_question_id}].asked = $(this).val()">
            <textarea rows=3 class="form-control" placeholder="Nhập câu trả lời" onchange="ASKED_QUESTION[${asked_question_id}].question = $(this).val()">${question}</textarea>
        </div>`;

            $('#list_asked_question').append(row_new);
        }
    }
</script>