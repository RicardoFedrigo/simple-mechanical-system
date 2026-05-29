<?php $title = $title ?? 'Reports'; ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="card-title">Revenue & Service Reports</h5>
    <p class="text-muted">Charts, revenue trends and parts usage summaries are ready for integration.</p>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
