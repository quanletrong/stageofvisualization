<div class="container-fluid">
    <h1 class="fs-4 mt-3">MY ORDERS AND LISTINGS</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('') ?>"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item active">My Order</li>
        </ol>
    </nav>

    <table class="table border table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" width="150">DATE CREATE</th>
                <th scope="col">JOB TYPE</th>
                <th scope="col" class="text-center">TOTAL IMAGE</th>
                <th scope="col" class="text-center">STATUS</th>
                <th scope="col" class="text-center">[VIEW]</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list_order as $id_order => $order) { ?>
                <tr>
                    <th scope="row" class="text-center">OID<?= $id_order ?></th>
                    <td onclick="$('.time_since, .time_date').toggleClass('d-none');" style="cursor: pointer;">
                        <div class="time_since">
                            <?= timeSince($order['create_time']) ?> ago
                        </div>
                        <div class="time_date d-none">
                            <?= date('H:s | d-m-Y', strtotime($order['create_time'])) ?>
                        </div>
                    </td>
                    <td>
                        <?php foreach ($order['list_service'] as $val) { ?>
                            <span class="badge text-bg-danger"><?= $val ?></span>
                        <?php } ?>
                    </td>
                    <td class="text-center"><?= count($order['list_job']) ?></td>
                    <td class="text-center">
                        <?php
                        if ($order['status'] == ORDER_PENDING) {
                            echo '<small class="badge" style="color:white;background-color: deeppink" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đang chờ duyệt!">PENDING</small>';
                        } else if ($order['status'] == ORDER_DELIVERED) {
                            echo '<small class="badge bg-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đã được giao!">DELIVERED</small>';
                        } else if ($order['status'] == ORDER_COMPLETE) {
                            echo '<small class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đã hoàn thành!">COMPLETE</small>';
                        } else if ($order['status'] == ORDER_CANCLE) {
                            echo '<small class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đã hủy!">CANCLE</small>';
                        } else if ($order['status'] == ORDER_REWORK) {
                            echo '<small class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đang làm lại!">REWORK</small>';
                        } else if ($order['status'] == ORDER_PAY_WAITING) {
                            echo '<small class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng chờ thanh toán!">UNPAID</small>';
                        } else {
                            echo '<small class="badge bg-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Đơn hàng đang trong quá trình làm!">IN PROGRESS</small>';
                        }
                        ?>
                    </td>
                    <td class="text-center "><a href="user/orderdetail/<?= $id_order ?>" class="link-primary">[VIEW JOB]</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>