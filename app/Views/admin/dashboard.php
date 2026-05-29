<?php $title = $title ?? 'Admin Dashboard'; ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="card-title">Admin Control Center</h5>
    <p class="text-muted">Protected admin routes are managed through the middleware layer.</p>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">User role enforcement</li>
      <li class="list-group-item">Admin-only dashboard and settings</li>
      <li class="list-group-item">Service and inventory analytics</li>
    </ul>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
