<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Thêm lịch chiếu mới</h4>
      <a href="<?= BASE_URL ?>?act=showtimes" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại các trường sau:</strong>
          <ul class="mb-0 mt-2">
            <?php foreach ($errors as $field => $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="movie_id" class="form-label">Phim <span class="text-danger">*</span></label>
              <select name="movie_id" id="movie_id" class="form-select <?= !empty($errors['movie_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Chọn phim --</option>
                <?php if (!empty($movies)): ?>
                  <?php foreach ($movies as $movie): ?>
                    <option value="<?= $movie['id'] ?>" <?= (isset($_POST['movie_id']) && $_POST['movie_id'] == $movie['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($movie['title']) ?>
                      <?php if (!empty($movie['release_date'])): ?>
                        (<?= date('d/m/Y', strtotime($movie['release_date'])) ?>)
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['movie_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['movie_id'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="cinema_id" class="form-label">Rạp chiếu phim <span class="text-danger">*</span></label>
              <select name="cinema_id" id="cinema_id" class="form-select <?= !empty($errors['cinema_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Chọn rạp chiếu phim --</option>
                <?php if (!empty($cinemas)): ?>
                  <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= (isset($_POST['cinema_id']) && $_POST['cinema_id'] == $cinema['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cinema['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['cinema_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['cinema_id'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Vui lòng chọn rạp trước khi chọn phòng</small>
            </div>

            <div class="mb-3">
              <label for="room_id" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select <?= !empty($errors['room_id']) ? 'is-invalid' : '' ?>" required disabled>
                <option value="">-- Chọn phòng chiếu --</option>
                <?php if (!empty($rooms)): ?>
                  <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['id'] ?>" data-cinema-id="<?= $room['cinema_id'] ?>" <?= (isset($_POST['room_id']) && $_POST['room_id'] == $room['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($room['name'] ?? '') ?>
                      <?php if (!empty($room['room_code'])): ?>
                        (<?= htmlspecialchars($room['room_code']) ?>)
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['room_id'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Vui lòng chọn rạp trước</small>
            </div>

            <div class="mb-3">
              <label for="show_date" class="form-label">Ngày chiếu <span class="text-danger">*</span></label>
              <input type="date" name="show_date" id="show_date" 
                     class="form-control <?= !empty($errors['show_date']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['show_date'] ?? date('Y-m-d')) ?>" 
                     min="<?= date('Y-m-d') ?>" required>
              <?php if (!empty($errors['show_date'])): ?>
                <div class="text-danger small mt-1"><?= $errors['show_date'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="start_time" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
              <input type="time" name="start_time" id="start_time" 
                     class="form-control <?= !empty($errors['start_time']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>" required>
              <?php if (!empty($errors['start_time'])): ?>
                <div class="text-danger small mt-1"><?= $errors['start_time'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="end_time" class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
              <input type="time" name="end_time" id="end_time" 
                     class="form-control <?= !empty($errors['end_time']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['end_time'] ?? '') ?>" required>
              <?php if (!empty($errors['end_time'])): ?>
                <div class="text-danger small mt-1"><?= $errors['end_time'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Giờ kết thúc phải sau giờ bắt đầu</small>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="adult_price" class="form-label">Giá vé người lớn (VNĐ) <span class="text-danger">*</span></label>
              <input type="number" name="adult_price" id="adult_price" 
                     class="form-control <?= !empty($errors['adult_price']) ? 'is-invalid' : '' ?>" 
                     min="0" step="1000" 
                     value="<?= htmlspecialchars($_POST['adult_price'] ?? '') ?>" required>
              <?php if (!empty($errors['adult_price'])): ?>
                <div class="text-danger small mt-1"><?= $errors['adult_price'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="student_price" class="form-label">Giá vé học sinh (VNĐ) <span class="text-danger">*</span></label>
              <input type="number" name="student_price" id="student_price" 
                     class="form-control <?= !empty($errors['student_price']) ? 'is-invalid' : '' ?>" 
                     min="0" step="1000" 
                     value="<?= htmlspecialchars($_POST['student_price'] ?? '') ?>" required>
              <?php if (!empty($errors['student_price'])): ?>
                <div class="text-danger small mt-1"><?= $errors['student_price'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="format" class="form-label">Định dạng <span class="text-danger">*</span></label>
              <select name="format" id="format" class="form-select <?= !empty($errors['format']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Chọn định dạng --</option>
                <option value="2D" <?= (isset($_POST['format']) && $_POST['format'] == '2D') ? 'selected' : 'selected' ?>>2D</option>
                <option value="3D" <?= (isset($_POST['format']) && $_POST['format'] == '3D') ? 'selected' : '' ?>>3D</option>
                <option value="IMAX" <?= (isset($_POST['format']) && $_POST['format'] == 'IMAX') ? 'selected' : '' ?>>IMAX</option>
                <option value="4DX" <?= (isset($_POST['format']) && $_POST['format'] == '4DX') ? 'selected' : '' ?>>4DX</option>
              </select>
              <?php if (!empty($errors['format'])): ?>
                <div class="text-danger small mt-1"><?= $errors['format'] ?></div>
              <?php endif; ?>
            </div>

            <?php if (!empty($errors['conflict'])): ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($errors['conflict']) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Thêm lịch chiếu
          </button>
          <a href="<?= BASE_URL ?>?act=showtimes" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Movie durations (you can fetch this via AJAX if needed)
  const movieDurations = {
    <?php if (!empty($movies)): ?>
      <?php foreach ($movies as $movie): ?>
        <?= $movie['id'] ?>: <?= $movie['duration'] ?? 0 ?>,
      <?php endforeach; ?>
    <?php endif; ?>
  };

  // Auto calculate end_time based on movie duration and start_time
  function calculateEndTime() {
    const movieId = document.getElementById('movie_id').value;
    const startTime = document.getElementById('start_time').value;
    const duration = movieDurations[movieId];

    if (movieId && startTime && duration) {
      const [hours, minutes] = startTime.split(':').map(Number);
      const startDate = new Date();
      startDate.setHours(hours, minutes, 0, 0);
      
      // Add duration in minutes
      const endDate = new Date(startDate.getTime() + duration * 60000);
      
      // Format as HH:MM
      const endHours = String(endDate.getHours()).padStart(2, '0');
      const endMinutes = String(endDate.getMinutes()).padStart(2, '0');
      document.getElementById('end_time').value = `${endHours}:${endMinutes}`;
    }
  }

  document.getElementById('movie_id').addEventListener('change', calculateEndTime);
  document.getElementById('start_time').addEventListener('change', calculateEndTime);

  // Validate end_time > start_time
  document.getElementById('end_time').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const endTime = this.value;
    if (startTime && endTime) {
      if (endTime <= startTime) {
        alert('Giờ kết thúc phải sau giờ bắt đầu!');
        this.value = '';
      }
    }
  });

  // Filter rooms by selected cinema
  const cinemaSelect = document.getElementById('cinema_id');
  const roomSelect = document.getElementById('room_id');
  
  // Store all rooms from server
  const allRoomsData = [
    <?php if (!empty($rooms)): ?>
      <?php foreach ($rooms as $index => $room): ?>
        {
          id: <?= $room['id'] ?>,
          cinema_id: <?= $room['cinema_id'] ?>,
          name: '<?= htmlspecialchars($room['name'] ?? '', ENT_QUOTES) ?>',
          room_code: '<?= htmlspecialchars($room['room_code'] ?? '', ENT_QUOTES) ?>'
        }<?= $index < count($rooms) - 1 ? ',' : '' ?>
      <?php endforeach; ?>
    <?php endif; ?>
  ];

  function filterRoomsByCinema(cinemaId) {
    // Clear room selection
    roomSelect.innerHTML = '<option value="">-- Chọn phòng chiếu --</option>';
    
    if (cinemaId) {
      // Enable room select
      roomSelect.disabled = false;
      
      // Filter and add rooms belonging to selected cinema
      allRoomsData.forEach(room => {
        if (room.cinema_id == cinemaId) {
          const option = document.createElement('option');
          option.value = room.id;
          option.textContent = room.name + (room.room_code ? ' (' + room.room_code + ')' : '');
          option.dataset.cinemaId = room.cinema_id;
          roomSelect.appendChild(option);
        }
      });
    } else {
      // Disable room select if no cinema selected
      roomSelect.disabled = true;
    }
  }

  // Filter when cinema changes
  cinemaSelect.addEventListener('change', function() {
    filterRoomsByCinema(this.value);
  });
</script>

