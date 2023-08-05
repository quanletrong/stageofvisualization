<div class="container-fluid">
    <h1 class="fs-4 mt-3">MY ORDERS AND LISTINGS</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=site_url('')?>"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item active">My Order</li>
        </ol>
    </nav>

    <table class="table border table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">DATE CREATE</th>
                <th scope="col">JOB TYPE</th>
                <th scope="col">TOTAL IMAGE</th>
                <th scope="col">STATUS</th>
                <th scope="col">[VIEW]</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list_order as $id_order => $order) { ?>
                <tr>
                    <th scope="row">OID<?= $id_order ?></th>
                    <td><?= $order['create_time'] ?></td>
                    <td>
                        <?php foreach ($order['type_service'] as $val) { ?>
                            <span class="badge text-bg-danger"><?= $val ?></span>
                        <?php } ?>
                    </td>
                    <td><?= $order['total_job'] ?></td>
                    <td>
                        <?php
                        if ($order['status'] == ORDER_DELIVERED) {
                            echo '<small class="badge bg-info">DELIVERED</small>';
                        } else if ($order['status'] == ORDER_COMPLETE) {
                            echo '<small class="badge bg-success">COMPLETE</small>';
                        } else if ($order['status'] == ORDER_CANCLE) {
                            echo '<small class="badge bg-danger">CANCLE</small>';
                        } else {
                            echo '<small class="badge bg-warning">IN PROGRESS</small>';
                        }
                        ?>
                    </td>
                    <td><a href="user/orderdetail/<?= $id_order ?>">[VIEW JOB]</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>