<div class="container py-5" id="step-1">
    <div class="fw-semibold fs-5 mb-3">LỰA CHỌN KHÁCH HÀNG CẦN TẠO ĐƠN</div>

    <div class="border p-4 step-1-box shadow">
        <strong>Chọn khách hàng</strong>
        <select id="list_customer" class="mb-3" style="width: 100%" onchange="ajax_load_info_user_create_order(this.value)">
            <option value="">-Chọn-</option>
            <?php foreach ($list_customer as $id_user => $user) { ?>
                <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
            <?php } ?>
        </select>

        <hr>
        <div class="mb-3 text-center">
            <img src="" id="user_avatar" class="img-circle" width="100" height="100" style="background-color: #eee;">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Full Name</label>
            <input type="text" id="user_fullname" class="form-control" value="" disabled>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Username</label>
            <input type="text" id="user_username" class="form-control" value="" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="user_email" class="form-control" value="" disabled>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number (10 Digits)</label>
            <input type="tel" id="user_phone" class="form-control" value="" disabled>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Set CID</label>
            <textarea type="tel" id="cid" class="form-control" onchange="STATE.cid = this.value"></textarea>
        </div>

        <div class="mt-3 mb-3">
            <button type="button" class="btn btn-lg btn-danger w-100" id="step-1-next">Continue</button>
        </div>


    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#list_customer').select2({});
    });

    function ajax_load_info_user_create_order(id_user) {

        STATE.for_user = id_user; 

        if (id_user != '') {
            $.ajax({
                url: 'user/ajax_load_info_user_create_order/' + id_user,
                success: function(jdata, textStatus, jqXHR) {
                    try {
                        let result = JSON.parse(jdata);
                        let data = result.data;
                        
                        $('#user_avatar').attr('src', data.avatar_url);
                        $('#user_fullname').val(data.fullname);
                        $('#user_username').val(data.username);
                        $('#user_email').val(data.email);
                        $('#user_phone').val(data.phone);
                        $('#cid').val(data.code);
                    } catch (error) {
                        console.log(error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jdata);
                    alert('Error');
                }
            });
        } else {
            $('#user_avatar').attr('src', '');
            $('#user_fullname').val('');
            $('#user_username').val('');
            $('#user_email').val('');
            $('#user_phone').val('');
            $('#cid').val('');
        }
    }
</script>