<?php
$booking = $booking ?? null;
$movie = $movie ?? null;
$existingComment = $existingComment ?? null;
$error = $_GET['error'] ?? '';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/review.css">

<div class="review-page">
  <div class="review-container">
    <div class="review-header">
      <a href="<?= BASE_URL ?>?act=profile&tab=bookings" class="back-link">
        <i class="bi bi-arrow-left"></i> Quay lại lịch sử đặt vé
      </a>
      <h1 class="review-title">
        <i class="bi bi-star-fill"></i> Đánh giá phim
      </h1>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($movie && $booking): ?>
      <div class="movie-info-card">
        <div class="movie-poster-large">
          <?php if (!empty($movie['image'])): ?>
            <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
          <?php else: ?>
            <img src="<?= BASE_URL ?>/image/logo.png" alt="Poster">
          <?php endif; ?>
        </div>
        <div class="movie-details">
          <h2 class="movie-title"><?= strtoupper(htmlspecialchars($movie['title'])) ?></h2>
          <div class="movie-meta">
            <?php if (!empty($movie['genre_name'])): ?>
              <span class="meta-item"><?= htmlspecialchars($movie['genre_name']) ?></span>
            <?php endif; ?>
            <?php if (!empty($movie['duration'])): ?>
              <span class="meta-item"><?= htmlspecialchars($movie['duration']) ?> phút</span>
            <?php endif; ?>
            <?php if (!empty($movie['format'])): ?>
              <span class="meta-item"><?= htmlspecialchars($movie['format']) ?></span>
            <?php endif; ?>
          </div>
          <div class="booking-info">
            <p><strong>Mã đặt vé:</strong> <?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></p>
            <p><strong>Ngày chiếu:</strong> <?= $booking['show_date'] ? date('d/m/Y', strtotime($booking['show_date'])) : 'N/A' ?></p>
            <p><strong>Giờ chiếu:</strong> <?= $booking['start_time'] ? date('H:i', strtotime($booking['start_time'])) : 'N/A' ?></p>
          </div>
        </div>
      </div>

      <div class="review-form-card">
        <h3 class="form-title">Chia sẻ đánh giá của bạn</h3>
        
        <form action="<?= BASE_URL ?>?act=submit-review" method="POST" id="reviewForm">
          <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
          <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">

          <div class="form-group">
            <label class="form-label">Đánh giá <span class="required">*</span></label>
            <div class="rating-container">
              <div class="star-rating" id="starRating">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                  <input type="radio" 
                         name="rating" 
                         id="star<?= $i ?>" 
                         value="<?= $i ?>" 
                         <?= ($existingComment && $existingComment['rating'] == $i) ? 'checked' : '' ?>
                         required>
                  <label for="star<?= $i ?>" class="star-label" data-rating="<?= $i ?>">
                    <i class="bi bi-star-fill"></i>
                  </label>
                <?php endfor; ?>
              </div>
              <span class="rating-text" id="ratingText">Chọn số sao</span>
            </div>
          </div>

          <div class="form-group">
            <label for="content" class="form-label">Bình luận / Góp ý <span class="required">*</span></label>
            <textarea 
              name="content" 
              id="content" 
              class="form-control review-textarea" 
              rows="8" 
              placeholder="Chia sẻ cảm nhận của bạn về bộ phim này... "
              required><?= $existingComment ? htmlspecialchars($existingComment['content']) : '' ?></textarea>
            
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= BASE_URL ?>?act=profile&tab=bookings'">
              Hủy
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> 
              <?= $existingComment ? 'Cập nhật đánh giá' : 'Gửi đánh giá' ?>
            </button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">
        Không tìm thấy thông tin phim hoặc đơn đặt vé.
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  // Star rating interaction
  const starLabels = document.querySelectorAll('.star-label');
  const ratingText = document.getElementById('ratingText');
  const ratingInputs = document.querySelectorAll('input[name="rating"]');
  
  const ratingTexts = {
    1: 'Rất tệ',
    2: 'Tệ',
    3: 'Bình thường',
    4: 'Tốt',
    5: 'Rất tốt'
  };

  starLabels.forEach(label => {
    label.addEventListener('mouseenter', function() {
      const rating = this.dataset.rating;
      highlightStars(rating);
      ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
    });
  });

  document.querySelector('.star-rating').addEventListener('mouseleave', function() {
    const checked = document.querySelector('input[name="rating"]:checked');
    if (checked) {
      const rating = checked.value;
      highlightStars(rating);
      ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
    } else {
      resetStars();
      ratingText.textContent = 'Chọn số sao';
    }
  });

  ratingInputs.forEach(input => {
    input.addEventListener('change', function() {
      const rating = this.value;
      highlightStars(rating);
      ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
    });
  });

  function highlightStars(rating) {
    starLabels.forEach((label, index) => {
      const starRating = label.dataset.rating;
      if (starRating <= rating) {
        label.classList.add('active');
      } else {
        label.classList.remove('active');
      }
    });
  }

  function resetStars() {
    starLabels.forEach(label => {
      label.classList.remove('active');
    });
  }

  // Initialize stars if existing comment
  <?php if ($existingComment && $existingComment['rating']): ?>
    highlightStars(<?= $existingComment['rating'] ?>);
    ratingText.textContent = ratingTexts[<?= $existingComment['rating'] ?>] || 'Chọn số sao';
  <?php endif; ?>

  // Character count
  const contentTextarea = document.getElementById('content');
  const charCount = document.getElementById('charCount');
  
  contentTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = length;
    
    if (length > 1000) {
      charCount.style.color = '#dc3545';
      this.value = this.value.substring(0, 1000);
      charCount.textContent = 1000;
    } else if (length >= 10) {
      charCount.style.color = '#28a745';
    } else {
      charCount.style.color = '#ffc107';
    }
  });

  // Form validation
  document.getElementById('reviewForm').addEventListener('submit', function(e) {
    const rating = document.querySelector('input[name="rating"]:checked');
    const content = contentTextarea.value.trim();

    if (!rating) {
      e.preventDefault();
      alert('Vui lòng chọn đánh giá từ 1 đến 5 sao');
      return false;
    }

    if (content.length < 10) {
      e.preventDefault();
      alert('Nội dung bình luận phải có ít nhất 10 ký tự');
      contentTextarea.focus();
      return false;
    }

    if (content.length > 1000) {
      e.preventDefault();
      alert('Nội dung bình luận không được vượt quá 1000 ký tự');
      contentTextarea.focus();
      return false;
    }
  });
</script>

