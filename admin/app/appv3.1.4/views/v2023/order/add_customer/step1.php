<div class="container pb-5" id="step-1">
    <div class="fw-semibold fs-5 mb-3 d-flex">
        <div class="step-1-active" style="width: 33%;text-align: center;background: #007bff;color: white;">LỰA CHỌN KHÁCH HÀNG</div>
        <div style="width: 34%;text-align: center;background: #bbbbbb;color: white;">NỘI DUNG ĐƠN HÀNG</div>
        <div style="width: 33%;text-align: center;background: #bbbbbb;color: white;">THANH TOÁN</div>
    </div>

    <div class="border p-4 step-1-box shadow">
        <strong>Chọn khách hàng</strong>
        <select id="list_customer" class="mb-3" style="width: 100%" onchange="ajax_load_info_user_create_order(this.value)">
            <option value="">-Chọn-</option>
            <?php foreach ($list_customer as $id_user => $user) { ?>
                <option value="<?= $id_user ?>"><?= $user['username'] ?></option>
            <?php } ?>
        </select>

        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="lastname" class="form-label">Full Name</label>
                    <input type="text" id="user_fullname" class="form-control" value="" disabled>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Username</label>
                    <input type="text" id="user_username" class="form-control" value="" disabled>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">CID</label>
                    <textarea type="tel" id="cid" class="form-control" value="" disabled></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="user_email" class="form-control" value="" disabled>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number (10 Digits)</label>
                    <input type="tel" id="user_phone" class="form-control" value="" disabled>
                </div>
                <div class="mb-3 text-center">
                    <img src="" id="user_avatar" class="img-circle" width="100" height="100" style="background-color: #eee;">
                </div>
            </div>
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