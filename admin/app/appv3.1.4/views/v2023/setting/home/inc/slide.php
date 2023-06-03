
<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Thay đổi slide trang chủ</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <form id="form_home_slide" method="post" action="setting/submit_home/slide">
            <input type="hidden" name="slide"/>
            <table class="table" id="table_add_slide">
                <thead>
                    <tr>
                        <th class="w-50">Tên slide</th>
                        <th class="text-center" width="150"></th>
                        <th class="text-right">
                            <button type="button" onclick="add_slide()" class="btn btn-sm btn-primary w-25"><i class="fas fa-plus"></i></button>
                            <button type="submit" class="btn btn-sm btn-danger">Lưu lại</button>
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-center">

                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
<script>
    var SLIDE = <?=$setting['home_slide']?>;
    render_slide();
    $(function() {
        $('#form_home_slide').validate({
            submitHandler: function(form) {

                if ($(form).find('input[name="slide"]') == '') {
                    alert('Hãy nhập đầy đủ dữ liệu');

                    $(form).find('button[type="submit"]').attr('disabled', false);

                } else {

                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    $(form).find('input[name="slide"]').val(JSON.stringify(SLIDE))
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


    function cb_upload_image_slide(link, target, name) {
        $(`${target}_pre`).attr('src', link);
        let slide_id = $(target).data('id');
        SLIDE[slide_id].image = link;
        SLIDE[slide_id].name = name;
        $(`#${slide_id} .input-name`).val(name);
    }

    // <!-- xu lý thêm phong -->
    function add_slide() {
        let row_last = $('#table_add_slide tbody tr').last();
        if (row_last.find('input').val() != '' && row_last.find('img').attr('src') != '') {
            let slide_id = Date.now();

            SLIDE[slide_id] = {
                'name': '',
                'image': ''
            }

            render_slide();
            $(`#${partner_id} .button-upload`).click(); // upload ảnh luôn

            $('#table_add_slide tbody tr').last().find('input').focus();
        } else {
            $('#table_add_slide tbody tr').last().find('input').focus();
        }
    }

    function render_slide() {
        $('#table_add_slide tbody').html('');

        for (const slide_id in SLIDE) {

            let row_new = `<tr id='${slide_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0 input-name" value="${htmlEntities(SLIDE[slide_id].name)}" onChange="SLIDE[${slide_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="${SLIDE[slide_id].image}" alt="" class="img-fluid" id="image_${slide_id}_pre">
                    <input type="hidden" id="image_${slide_id}" data-id="${slide_id}">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-warning button-upload" onclick="quanlt_upload(this)" data-callback="cb_upload_image_slide" data-target="#image_${slide_id}" >
                            <i class="fas fa-upload"></i>
                        </button>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete SLIDE[${slide_id}]; $('#${slide_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_slide tbody').append(row_new);
        }
    }
</script>