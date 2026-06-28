<?php
$title = $title ?? 'Page Not Found';
$message = $message ?? 'The page you are looking for does not exist or is not available.';
$homeUrl = isset($_SESSION['user']) ? '/dashboard' : '/login';
$homeLabel = isset($_SESSION['user']) ? 'Back to Dashboard' : 'Back to Login';

ob_start();
?>
<div class="row justify-content-center">
  <div class="col-lg-7 col-xl-6">
    <div class="card shadow-sm border-0 text-center">
      <div class="card-body p-5">
        <p class="text-uppercase text-muted small fw-semibold mb-2">Error 404</p>
        <h1 class="display-5 fw-bold mb-3">Page not found</h1>
        <p class="text-muted mb-4"><?= htmlspecialchars($message) ?></p>
        <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn btn-primary">
          <?= htmlspecialchars($homeLabel) ?>
        </a>
      </div>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();

if (isset($_SESSION['user'])) {
    require __DIR__ . '/layouts/dashboard.php';
} else {
    require __DIR__ . '/layouts/auth.php';
}
