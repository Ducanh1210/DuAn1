<div class="container-fluid">
  <!-- Dashboard Header -->
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div>
      <h2 class="mb-1 d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2 text-primary"></i>
        <span>Dashboard</span>
      </h2>
      <p class="text-muted mb-0">Tổng quan hệ thống và thống kê</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary d-flex align-items-center gap-2" onclick="exportReport()">
        <i class="bi bi-download"></i>
        <span>Xuất báo cáo</span>
      </button>
      <button class="btn btn-outline-secondary d-flex align-items-center gap-2" onclick="shareReport()">
        <i class="bi bi-share"></i>
        <span>Chia sẻ</span>
      </button>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row g-4 mb-4">
    <!-- Card 1: Nhân viên -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #0d6efd !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #0d6efd !important; font-weight: 600;">NHÂN VIÊN</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalStaff) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(13, 110, 253, 0.1);">
              <i class="bi bi-people fs-4" style="color: #0d6efd;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary">Tổng số</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Xuất vé (Tickets sold) -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #198754 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #198754 !important; font-weight: 600;">XUẤT VÉ</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalTickets) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(25, 135, 84, 0.1);">
              <i class="bi bi-ticket-perforated fs-4" style="color: #198754;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success-subtle text-success">
              <i class="bi bi-calendar-day"></i> Hôm nay: <?= number_format($todayBookings) ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: Bán vé (Revenue) -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #ffc107 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #ffc107 !important; font-weight: 600;">BÁN VÉ</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalRevenue, 0, ',', '.') ?></h3>
              <small class="text-muted">VNĐ</small>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(255, 193, 7, 0.1);">
              <i class="bi bi-currency-exchange fs-4" style="color: #ffc107;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning-subtle text-warning">
              <i class="bi bi-calendar-day"></i> Hôm nay: <?= number_format($todayRevenue, 0, ',', '.') ?> VNĐ
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Phòng chiếu (Rooms) -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #0dcaf0 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #0dcaf0 !important; font-weight: 600;">PHÒNG CHIẾU</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalRooms) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(13, 202, 240, 0.1);">
              <i class="bi bi-building fs-4" style="color: #0dcaf0;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info-subtle text-info">Tổng số phòng</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Statistics Row -->
  <div class="row g-4">
    <!-- Card 5: Phim -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #6f42c1 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #6f42c1 !important; font-weight: 600;">PHIM</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalMovies) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(111, 66, 193, 0.1);">
              <i class="bi bi-film fs-4" style="color: #6f42c1;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-purple-subtle text-purple">Tổng số phim</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 6: Người dùng -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #fd7e14 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #fd7e14 !important; font-weight: 600;">NGƯỜI DÙNG</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalUsers) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(253, 126, 20, 0.1);">
              <i class="bi bi-person-circle fs-4" style="color: #fd7e14;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-orange-subtle text-orange">Tổng số người dùng</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 7: Rạp -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card" style="border-left: 4px solid #dc3545 !important;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1" style="color: #dc3545 !important; font-weight: 600;">RẠP</p>
              <h3 class="mb-0 fw-bold"><?= number_format($totalCinemas) ?></h3>
            </div>
            <div class="p-3 rounded-circle" style="background-color: rgba(220, 53, 69, 0.1);">
              <i class="bi bi-building fs-4" style="color: #dc3545;"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-subtle text-danger">Tổng số rạp</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.dashboard-card {
  transition: transform 0.2s, box-shadow 0.2s;
  border-radius: 8px;
}

.dashboard-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.bg-purple-subtle {
  background-color: rgba(111, 66, 193, 0.1);
  color: #6f42c1;
}

.bg-orange-subtle {
  background-color: rgba(253, 126, 20, 0.1);
  color: #fd7e14;
}

.bg-danger-subtle {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}
</style>

<script>
function exportReport() {
  // Logic để xuất báo cáo
  alert('Chức năng xuất báo cáo sẽ được triển khai sau');
  // Có thể redirect đến API xuất báo cáo hoặc mở modal
}

function shareReport() {
  // Logic để chia sẻ báo cáo
  if (navigator.share) {
    navigator.share({
      title: 'Báo cáo Dashboard',
      text: 'Xem báo cáo thống kê hệ thống',
      url: window.location.href
    });
  } else {
    // Fallback: Copy link
    navigator.clipboard.writeText(window.location.href);
    alert('Đã sao chép link vào clipboard!');
  }
}
</script>

