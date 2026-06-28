<?php $title = $title ?? 'Edit User'; ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="card-title mb-0">Edit User</h5>
  <a href="/admin/users/<?= htmlspecialchars($user->getId()) ?>" class="btn btn-secondary btn-sm">Back to Details</a>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <form method="post" action="/admin/users/<?= htmlspecialchars($user->getId()) ?>/edit">
        <?= csrf_field() ?>
        <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" name="name" type="text" class="form-control" value="<?= htmlspecialchars($user->getName()) ?>" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
      </div>

      <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select id="role_id" name="role_id" class="form-select" required>
          <?php foreach ($roles as $role): ?>
            <option value="<?= htmlspecialchars($role->getId()) ?>" <?= $role->getId() === $user->getRoleId() ? 'selected' : '' ?>>
              <?= htmlspecialchars($role->getName()) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-4">
        <label for="active" class="form-label">Status</label>
        <select id="active" name="active" class="form-select">
          <option value="1" <?= $user->isActive() ? 'selected' : '' ?>>Active</option>
          <option value="0" <?= !$user->isActive() ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="/admin/users" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/dashboard.php'; ?>
