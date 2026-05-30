<?php $title = 'Service Orders';
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="card-title mb-0">Service Orders</h5>
  <a href="/orders/create" class="btn btn-primary btn-sm">Create Order</a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Customer</th>
          <th scope="col">Vehicle</th>
          <th scope="col">Total</th>
          <th scope="col">Status</th>
          <th scope="col">Created</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tickets)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No service orders yet.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($tickets as $ticket): ?>
            <tr>
              <td><strong>#<?= htmlspecialchars($ticket['id']) ?></strong></td>
              <td><?= htmlspecialchars($ticket['customer_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($ticket['vehicle_model'] ?? 'N/A') ?></td>
              <td><strong>$<?= number_format((float)$ticket['total'], 2) ?></strong></td>
              <td>
                <span class="badge bg-<?= $ticket['status'] === 'COMPLETED' ? 'success' : ($ticket['status'] === 'IN_PROGRESS' ? 'warning' : 'secondary') ?>">
                  <?= htmlspecialchars($ticket['status']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars(substr($ticket['created_at'] ?? 'N/A', 0, 10)) ?></td>
              <td>
                <a href="/orders/<?= htmlspecialchars($ticket['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>