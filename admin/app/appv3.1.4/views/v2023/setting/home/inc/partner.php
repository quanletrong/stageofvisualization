<div class="card card-info collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Đối tác của chúng tôi</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <form id="form_partner" method="post" action="setting/submit_home/partner">
            <div class="form-group">
                <label for="name">Tiêu đề hiển thị</label>
                <input type="text" class="form-control title" placeholder="Nhập tiêu đề hiển thị" onchange="PARTNER.title = $(this).val()">
            </div>
            <div class="form-group">
                <label for="name">Mô tả ngắn</label>
                <input type="text" class="form-control sapo" placeholder="Nhập tiêu đề hiển thị" onchange="PARTNER.sapo = $(this).val()">
            </div>
            <input type="hidden" name="partner" />
            <table class="table" id="table_add_partner">
                <thead>
                    <tr>
                        <th class="w-50">Danh sách ảnh đối tác</th>
                        <th class="text-center" width="150"></th>
                        <th class="text-right">
                            <button type="button" onclick="add_partner()" class="btn btn-sm btn-primary w-25"><i class="fas fa-plus"></i></button>
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
    var PARTNER = <?= $setting['partner'] ?>;
    $("#form_partner .title").val(PARTNER.title);
    $("#form_partner .sapo").val(PARTNER.sapo);
    render_partner();
    $(function() {
        $('#form_partner').validate({
            submitHandler: function(form) {

                if ($(form).find('input[name="partner"]') == '') {
                    alert('Hãy nhập đầy đủ dữ liệu');

                    $(form).find('button[type="submit"]').attr('disabled', false);

                } else {

                    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
                    $(form).find('input[name="partner"]').val(JSON.stringify(PARTNER))
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


    function cb_upload_image_partner(res, btn) {
        let target = $(btn).data('target');
        $(`${target}_pre`).attr('src', res.link);
        let partner_id = $(target).data('id');
        PARTNER.images[partner_id].image = res.link;
        PARTNER.images[partner_id].name = res.name;

        $(`#${partner_id} .input-name`).val(res.name);

    }

    // <!-- xu lý thêm phong -->
    function add_partner() {
        let row_last = $('#table_add_partner tbody tr').last();
        if (row_last.find('input').val() != '' && row_last.find('img').attr('src') != '') {
            let partner_id = Date.now();

            PARTNER.images[partner_id] = {
                'name': '',
                'image': ''
            }

            render_partner();
            $(`#${partner_id} .button-upload`).click(); // upload ảnh luôn

            $('#table_add_partner tbody tr').last().find('input').focus();
        } else {
            $('#table_add_partner tbody tr').last().find('input').focus();
        }
    }

    function render_partner() {
        $('#table_add_partner tbody').html('');

        for (const partner_id in PARTNER.images) {

            let row_new = `
            <tr id='${partner_id}'>
                <td class="align-middle">
                    <input name="" class="form-control border-0 input-name" value="${htmlEntities(PARTNER.images[partner_id].name)}" onChange="PARTNER.images[${partner_id}].name = this.value">
                </td>
                <td class="align-middle">
                    <img src="${PARTNER.images[partner_id].image}" alt="" class="img-fluid" id="image_${partner_id}_pre">
                    <input type="hidden" id="image_${partner_id}" data-id="${partner_id}">
                </td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-warning button-upload" onclick="quanlt_upload(this)" data-callback="cb_upload_image_partner" data-target="#image_${partner_id}" >
                            <i class="fas fa-upload"></i>
                        </button>
                        <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-danger" onClick="delete PARTNER.images[${partner_id}]; $('#${partner_id}').remove()"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;

            $('#table_add_partner tbody').append(row_new);
        }
    }
</script>