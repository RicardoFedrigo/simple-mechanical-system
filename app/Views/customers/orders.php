<?php $title = $title ?? 'Customer Orders';
ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="card-title mb-0"><?= htmlspecialchars($title) ?></h5>
      <a href="/customers" class="btn btn-secondary btn-sm">Back to Customers</a>
    </div>

    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Status</th>
          <th>Created</th>
          <th>Total</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No orders found for this customer.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>#<?= htmlspecialchars($order->getId()) ?></td>
              <td>
                <span class="badge bg-<?= $order->getStatus() === 'COMPLETED' ? 'success' : 'warning' ?>">
                  <?= htmlspecialchars($order->getStatus()) ?>
                </span>
              </td>
              <td><?= htmlspecialchars(substr($order->getCreatedAt() ?? '', 0, 10)) ?></td>
              <td>$<?= number_format($order->getTotal(), 2) ?></td>
              <td>
                <a href="/orders/<?= htmlspecialchars($order->getId()) ?>" class="btn btn-sm btn-outline-info">View</a>
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
