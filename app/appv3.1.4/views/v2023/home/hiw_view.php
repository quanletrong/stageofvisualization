<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section id="hiw-content">
    <?= $html_hiw ?>
</section>
<?php if ($role == ADMIN) { ?>
    <div style="text-align: center; margin: 20px;">
        <button class="btn btn-danger w-25" onclick="ajax_save_hiw()"><i class="fa-solid fa-pen-to-square"></i> Lưu lại</button>
    </div>

    <script>
        $("document").ready(function() {

            bs5dialog.alert("Bấm chuột phải vào vùng cần thay đổi nội dung.", {
                type: 'warning',
                title: "Hướng dẫn chỉnh sửa nội dung page",
                backdrop: true,
                onOk: () => {}
            });

            $(document).on("contextmenu", ".edit_text", function(e) {
                let text = prompt('Nhập nội dung thay đổi:', $(this).html());

                if (text == null || text == "") {
                    text = $(this).html()
                }

                $(this).html(text);
                return false;
            });

            $(document).on("contextmenu", ".edit_image", function(e) {
                $(this).data('callback', 'cb_upload_img');
                quanlt_upload(this)
            });
        })

        function cb_upload_img(link_file, target, file_name, el) {
            $(el).attr('src', link_file)
        }

        function ajax_save_hiw() {

            let html_hiw = $('#hiw-content').html();
            $.ajax({
                url: 'home/ajax_save_hiw',
                type: "POST",
                data: {
                    html_hiw: html_hiw
                },
                success: function(data, textStatus, jqXHR) {

                    let res = JSON.parse(data);

                    if (res.status) {
                        bs5dialog.alert("Nội dung thay đổi đã được lưu thành công", {
                            type: 'success',
                            title: "Lưu thành công",
                            backdrop: true,
                            onOk: () => {}
                        });
                    } else {
                        bs5dialog.alert("Có lỗi xảy ra vui lòng thử lại!", {
                            type: 'danger',
                            title: "Thất bại",
                            backdrop: true,
                            onOk: () => {}
                        });
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(data);
                    bs5dialog.alert("Có lỗi xảy ra vui lòng thử lại!", {
                        type: 'danger',
                        title: "Thất bại",
                        backdrop: true,
                        onOk: () => {}
                    });
                }
            });
        }
    </script>
<?php } ?>