<?php $title = 'Service Orders';
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="card-title mb-0">Service Orders</h5>
  <?php if (($currentRole ?? '') !== 'Mechanic'): ?>
    <a href="/orders/create" class="btn btn-primary btn-sm">Create Order</a>
  <?php endif; ?>
</div>

<form method="get" action="/orders" class="row g-2 mb-3">
  <?php $term = htmlspecialchars($_GET['term'] ?? '');
  $status = htmlspecialchars($_GET['status'] ?? ''); ?>
  <div class="col-auto">
    <input type="search" name="term" value="<?= $term ?>" class="form-control" placeholder="Search by customer name">
  </div>
  <div class="col-auto">
    <select name="status" class="form-select">
      <option value="" <?= $status === '' ? 'selected' : '' ?>>All statuses</option>
      <option value="PENDING" <?= $status === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
      <option value="IN_PROGRESS" <?= $status === 'IN_PROGRESS' ? 'selected' : '' ?>>IN PROGRESS</option>
      <option value="COMPLETED" <?= $status === 'COMPLETED' ? 'selected' : '' ?>>COMPLETED</option>
    </select>
  </div>
  <div class="col-auto d-flex">
    <button type="submit" class="btn btn-outline-primary">Filter</button>
    <a href="/orders" class="btn btn-outline-secondary ms-2">Clear</a>
  </div>
</form>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Customer</th>
          <th scope="col">Mechanic</th>
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
              <td><strong>#<?= htmlspecialchars($ticket->getId()) ?></strong></td>
              <td><?= htmlspecialchars($ticket->getCustomer()?->getName() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($ticket->getMechanic()?->getName() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($ticket->getVehicle()?->getModel() ?? 'N/A') ?></td>
              <td><strong>$<?= number_format($ticket->getTotal(), 2) ?></strong></td>
              <td>
                <span class="badge bg-<?= $ticket->getStatus() === 'COMPLETED' ? 'success' : ($ticket->getStatus() === 'IN_PROGRESS' ? 'warning' : 'secondary') ?>">
                  <?= htmlspecialchars($ticket->getStatus()) ?>
                </span>
              </td>
              <td><?= htmlspecialchars(substr($ticket->getCreatedAt() ?? 'N/A', 0, 10)) ?></td>
              <td>
                <a href="/orders/<?= htmlspecialchars($ticket->getId()) ?>" class="btn btn-sm btn-outline-primary">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<nav class="mt-3">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="/orders?page=<?= $i ?>&term=<?= $filters['term'] ?>&status=<?= $filters['status'] ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>