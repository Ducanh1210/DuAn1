<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">
            Chi tiết đặt vé — <?= htmlspecialchars($date) ?>
        </h3>

        <a href="?act=thongke&from=<?= urlencode($date) ?>&to=<?= urlencode($date) ?>"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại thống kê
        </a>
    </div>

    <!-- Thông tin tổng quan -->
    <div class="card p-3 mb-4">
        <h5 class="fw-bold mb-3">Tổng quan</h5>

        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted">Số đơn</div>
                    <div class="fs-4 fw-bold"><?= count($bookingsList) ?></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted">Số vé</div>
                    <div class="fs-4 fw-bold">
                        <?php
                            $countTickets = 0;
                            foreach ($bookingsList as $b) {
                                $countTickets += (int)$b['total_tickets'];
                            }
                            echo $countTickets;
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted">Tổng tiền</div>
                    <div class="fs-4 fw-bold text-success">
                        <?php
                            $sum = 0;
                            foreach ($bookingsList as $b) {
                                $sum += (int)$b['total_amount'];
                            }
                            echo number_format($sum) . " đ";
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bảng chi tiết -->
    <div class="card">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Danh sách đặt vé</h5>

            <?php if (empty($bookingsList)): ?>
                <div class="alert alert-warning">
                    Không có đặt vé nào trong ngày này.
                </div>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Phim</th>
                                <th>Rạp / Phòng</th>
                                <th>Giờ chiếu</th>
                                <th>Ghế</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($bookingsList as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['code']) ?></td>
                                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                                    <td><?= htmlspecialchars($row['movie_name']) ?></td>
                                    <td><?= htmlspecialchars($row['cinema_name'] . ' / ' . $row['room_name']) ?></td>
                                    <td>
                                        <?= date("d/m/Y H:i", strtotime($row['showtime_start'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['seats']) ?></td>
                                    <td class="text-end">
                                        <?= number_format($row['total_amount']) ?> đ
                                    </td>
                                    <td>
                                        <?php
                                            $status = $row['status'];
                                            $color = 'secondary';
                                            if ($status == 'confirmed') $color = 'info';
                                            if ($status == 'completed') $color = 'success';
                                            if ($status == 'cancelled') $color = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $color ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td><?= date("d/m/Y", strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
