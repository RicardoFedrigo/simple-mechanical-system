<?php $title = $title ?? 'Settings'; ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="card-title">Workshop Settings</h5>
    <p class="text-muted">Configure notifications, service rates and stock thresholds through this area.</p>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
