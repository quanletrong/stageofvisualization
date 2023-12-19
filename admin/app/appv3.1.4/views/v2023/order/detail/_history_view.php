<div style="position: fixed; right:0; width: 100%;bottom: 0px; max-width:1200px; display: none; z-index: 2;" id="box_lich_su">
    <div class="card card-primary mb-0">
        <div class="card-header text-white" onclick="open_close_lich_su()" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0" style="display: flex;justify-content: space-between;align-items: center;width:100%;">
                    <div><i class="fas fa-history"></i> LỊCH SỬ ĐƠN HÀNG</div>
                    <div>x</div>
                </h6>
            </div>
        </div>
        <div class="card-body bg-white p-1" style=" height: 80vh;"></div>
    </div>
</div>

<script>
    function open_close_lich_su() {
        $('#box_lich_su').slideToggle('fast', 'swing');
        // $('#small_lich_su').toggleClass('d-none');
        $('#small_lich_su .tin-nhan-moi').text(0).hide();
    }

    function ajax_log_list(id_order) {
        $.ajax({
            url: `order/ajax_log_list/${id_order}`,
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                $('#box_lich_su .card-body').html(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(data);
                alert('Error');
                // $('#team_action_overlay').toggleClass('d-none');
            }
        });
    }
</script>