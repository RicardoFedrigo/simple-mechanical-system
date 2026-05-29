<?php $title = $title ?? 'Users'; ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="card-title">Team Users</h5>
    <p class="text-muted">Admin can manage mechanics, attendants and support roles here.</p>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
