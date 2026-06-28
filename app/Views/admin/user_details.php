<?php $title = $title ?? 'User Details'; ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="card-title mb-0">User Details</h5>
  <div class="d-flex gap-2">
    <a href="/admin/users/<?= htmlspecialchars($user->getId()) ?>/edit" class="btn btn-primary btn-sm">Edit</a>
    <a href="/admin/users" class="btn btn-secondary btn-sm">Back to Users</a>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">Name</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($user->getName()) ?></dd>

      <dt class="col-sm-3">Email</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($user->getEmail()) ?></dd>

      <dt class="col-sm-3">Role</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($user->getRole()?->getName() ?? 'N/A') ?></dd>

      <dt class="col-sm-3">Status</dt>
      <dd class="col-sm-9">
        <span class="badge bg-<?= $user->isActive() ? 'success' : 'secondary' ?>">
          <?= $user->isActive() ? 'Active' : 'Inactive' ?>
        </span>
      </dd>

      <dt class="col-sm-3">Created</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($user->getCreatedAt()) ?></dd>

      <dt class="col-sm-3">Updated</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($user->getUpdatedAt()) ?></dd>
    </dl>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
