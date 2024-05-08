<style>
    /* ẩn thanh cuộn */
    #tab_content_job textarea {
        overflow: hidden;
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- PAGE HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi tiết đơn hàng <span style="color: red">#<?= $order['id_order'] ?></span></h1>
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

        <!-- JOB CONTENT / TEAM ACTION -->
        <section>
            <div class="row">
                <div class="col-12 col-lg-7" id="list-image-order">
                    <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_job_content_view.php'); ?>
                </div>
                <div class="col-12 col-lg-5">
                    <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_team_action_view.php'); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<section>
    <div style="position: fixed; right: 10px; bottom: 0; z-index:3">
        <div class="d-flex justify-content-end" style="gap:10px; ">
            <?php if ($role == ADMIN || $role == SALE) { ?>
                <div id="small_trao_doi_sale" class="">
                    <div style="position: relative;">
                        <button class="btn btn-sm btn-primary" onclick="open_close_chat_khach()" data-bs-toggle="tooltip" data-bs-placement="top" title="Mở">
                            <i class="fas fa-comment"></i> KHÁCH HÀNG
                        </button>

                        <div class="tin-nhan-moi bg-danger rounded-circle" style="position: absolute;top: -10px;right: -8px;width: 20px;height: 20px;font-size: 0.7rem;text-align: center;line-height: 1.8;color: white;"></div>
                    </div>
                </div>
            <?php } ?>

            <div id="small_trao_doi_noi_bo" class="">
                <div style="position: relative;">
                    <button class="btn btn-sm btn-primary" onclick="open_close_chat_noi_bo()" data-bs-toggle="tooltip" data-bs-placement="top" title="Mở">
                        <i class="fas fa-comment"></i> NỘI BỘ
                    </button>
                    <div class="tin-nhan-moi bg-danger rounded-circle" style="position: absolute;top: -10px;right: -8px;width: 20px;height: 20px;font-size: 0.7rem;text-align: center;line-height: 1.8;color: white;"></div>
                </div>
            </div>
            <div id="small_lich_su" class="">
                <div style="position: relative;">
                    <button class="btn btn-sm btn-primary" onclick="open_close_lich_su(); ajax_log_list(<?= $order['id_order'] ?>)" data-bs-toggle="tooltip" data-bs-placement="top" title="Mở">
                        <i class="fas fa-history"></i> LỊCH SỬ
                    </button>

                    <div class="tin-nhan-moi bg-danger rounded-circle" style="position: absolute;top: -10px;right: -8px;width: 20px;height: 20px;font-size: 0.7rem;text-align: center;line-height: 1.8;color: white;"></div>
                </div>
            </div>


        </div>
    </div>

    <!--  box chat khach -->
    <div>
        <?php if ($role == ADMIN || $role == SALE) {
            $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_khach_view.php', [
                'order' => $order,
                'role' => $role
            ]);
        }
        ?>
    </div>

    <!-- box chat noi bo -->
    <div>
        <?php
        $this->load->view(TEMPLATE_FOLDER . 'order/detail/_chat_noi_bo_view.php', [
            'order' => $order,
            'role' => $role
        ]);
        ?>
    </div>


    <!-- box lịch sử -->
    <div>
        <?php $this->load->view(TEMPLATE_FOLDER . 'order/detail/_history_view.php'); ?>
    </div>

    <!-- modal -->
    <!-- modal full image -->
    <div class="modal fade" id="modal-full-image" style="display: none" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="" alt="" class="w-100">
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#modal-full-image').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var image = button.data('src');
                    if (image === undefined || image === '') {
                        image = button.attr('src');
                    }
                    var modal = $(this);
                    modal.find('.modal-body img').attr('src', image);
                })
            })
        </script>
    </div>
</section>
<script>
    function isImage(url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    }

    $(document).ready(function() {

        // update rows của textarea tab đang active
        $("#tab_content_job .active textarea").each(function() {
            // console.log(getStyle(this, 'line-height'))
            // let scrollHeight = $(this).prop("scrollHeight") + 20;
            // $(this).css('height', scrollHeight + 'px')

            let line = _.calculateNumLines($(this).val(), this);
            line = line < 2 ? 2 : line // tối thiểu 2 rows
            $(this).attr('rows', line)
        });

        // click tab sẽ update rows của textarea trong tab đo
        $('#tab_job .nav-link').click(function() {
            setTimeout(() => {
                $("#tab_content_job .active textarea").each(function() {
                    // let scrollHeight = $(this).prop("scrollHeight") + 20;
                    // console.log(scrollHeight)
                    // $(this).css('height', scrollHeight + 'px')

                    let line = _.calculateNumLines($(this).val(), this);
                    line = line < 2 ? 2 : line // tối thiểu 2 rows
                    $(this).attr('rows', line)
                });
            }, 100);
        })

        // sự kiện nhập textarea sẽ update rows của tab
        $('#tab_content_job textarea').on('input', function(e) {
            let line = _.calculateNumLines(e.target.value, this);
            line = line < 2 ? 2 : line // tối thiểu 2 rows
            $(this).attr('rows', line)
        })
    })

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }
</script>

<!-- SOCKET -->
<script>
    var GLOBAL_SETTIMEOUT_CHAT_NOI_BO;

    socket.on('update-chat-noi-bo', data => {
        if (data.id_order == <?= $order['id_order'] ?>) {

            // update tin nhan moi
            let tin_nhan_moi = parseInt($('#small_trao_doi_noi_bo .tin-nhan-moi').text());
            tin_nhan_moi = isNaN(tin_nhan_moi) ? 0 : tin_nhan_moi;
            $('#small_trao_doi_noi_bo .tin-nhan-moi').text(tin_nhan_moi + 1).show();

            let new_html = html_item_chat_noi_bo(data);
            $('#discuss_noi_bo .list-chat')
                .append(new_html)
                .scrollTop($('#discuss_noi_bo .list-chat')[0].scrollHeight);

            $('#discuss_noi_bo .content_discuss').val('').attr('rows', 2);
            $('#discuss_noi_bo .chat_list_attach').html('');
            $('#discuss_noi_bo .list-chat').scrollTop($('#discuss_noi_bo .list-chat')[0].scrollHeight);


            // update title, chuông noti
            if (data.id_user != <?= $curr_uid ?>) {

                // chuông noti
                var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
                audio.play();

                // update title
                clearTimeout(GLOBAL_SETTIMEOUT_CHAT_NOI_BO);
                if ($('#box_trao_doi_noi_bo').css('display') == 'none') {
                    (function titleScroller(text) {
                        document.title = `(${tin_nhan_moi + 1}) ${text}`;
                        GLOBAL_SETTIMEOUT_CHAT_NOI_BO = setTimeout(function() {
                            titleScroller(text.substr(1) + text.substr(0, 1));
                        }, 500);
                    }(`Tin nhắn nội bộ. `));
                }
            }

        }
    })

    socket.on('update-chat-khach', data => {
        if (data.id_order == <?= $order['id_order'] ?>) {

            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            audio.play();

            let tin_nhan_moi = parseInt($('#small_trao_doi_sale .tin-nhan-moi').text());
            tin_nhan_moi = isNaN(tin_nhan_moi) ? 0 : tin_nhan_moi;
            $('#small_trao_doi_sale .tin-nhan-moi').text(tin_nhan_moi + 1).show();

            let new_html = html_item_chat_khach(data);
            $('#discuss_khach .list-chat')
                .append(new_html)
                .scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

            $('#discuss_khach .content_discuss').val('').attr('rows', 2);
            $('#discuss_khach .chat_list_attach').html('');
            $('#discuss_khach .list-chat').scrollTop($('#discuss_khach .list-chat')[0].scrollHeight);

            tooltipTriggerList('#discuss_khach');
        }
    })

    socket.on('refresh', data => {
        if (data.id_order == <?= $order['id_order'] ?> && data.au != <?= $curr_uid ?>) {

            var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
            // audio.play();

            let content =
                `<p><strong>${data.content}</strong></p>
            <small>Bấm cập nhật để làm mới hoặc đóng lại nếu chưa muốn cập nhật</small>`;


            let tin_nhan_moi = parseInt($('#small_lich_su .tin-nhan-moi').text());
            tin_nhan_moi = isNaN(tin_nhan_moi) ? 0 : tin_nhan_moi;
            $('#small_lich_su .tin-nhan-moi').text(tin_nhan_moi + 1).show();


            // $.confirm({
            //     title: 'Cập nhật dữ liệu mới!',
            //     content: content,
            //     type: 'red',
            //     typeAnimated: true,
            //     closeIcon: true,
            //     autoClose: 'refresh|30000',
            //     buttons: {
            //         refresh: {
            //             text: 'Cập nhật ngay',
            //             btnClass: 'btn-red',
            //             action: function() {
            //                 window.location.reload()
            //             }
            //         },
            //         close: function() {
            //             text: 'Cập nhật sau'
            //         }
            //     }
            // });
        }
    })
</script>
<!-- END SOCKET -->