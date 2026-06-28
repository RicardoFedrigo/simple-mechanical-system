<?php $title = $title ?? 'Users'; ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="card-title mb-1">System Users</h5>
    <p class="text-muted mb-0">Manage every person with access to the system.</p>
  </div>
</div>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Role</th>
          <th scope="col">Status</th>
          <th scope="col">Created</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No users found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><strong><?= htmlspecialchars($user->getName()) ?></strong></td>
              <td><?= htmlspecialchars($user->getEmail()) ?></td>
              <td><?= htmlspecialchars($user->getRole()?->getName() ?? 'N/A') ?></td>
              <td>
                <span class="badge bg-<?= $user->isActive() ? 'success' : 'secondary' ?>">
                  <?= $user->isActive() ? 'Active' : 'Inactive' ?>
                </span>
              </td>
              <td><?= htmlspecialchars(substr($user->getCreatedAt(), 0, 10)) ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a href="/admin/users/<?= htmlspecialchars($user->getId()) ?>" class="btn btn-sm btn-outline-primary">Details</a>
                  <a href="/admin/users/<?= htmlspecialchars($user->getId()) ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                  <form method="post" action="/admin/users/<?= htmlspecialchars($user->getId()) ?>/deactivate">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger" <?= (!$user->isActive() || $user->getId() === ($currentUserId ?? null)) ? 'disabled' : '' ?>>Exclude</button>
                  </form>
                  <form method="post" action="/admin/users/<?= htmlspecialchars($user->getId()) ?>/activate">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-success" <?= ($user->isActive()) ? 'disabled' : '' ?>>Activate</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
