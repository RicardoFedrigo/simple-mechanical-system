<?php $title = $title ?? 'Customers';
ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Customer Management</h5>
      <a href="/customers/create" class="btn btn-primary btn-sm">New Customer</a>
    </div>

    <form method="get" action="/customers" class="row g-2 mb-3">
      <?php $term = htmlspecialchars($filters['term'] ?? ''); $plate = htmlspecialchars($filters['vehicle_plate'] ?? ''); ?>
      <div class="col-auto">
        <input type="search" name="term" value="<?= $term ?>" class="form-control" placeholder="Search by name, phone or email">
      </div>
      <div class="col-auto">
        <input type="search" name="vehicle_plate" value="<?= $plate ?>" class="form-control" placeholder="Vehicle plate">
      </div>
      <div class="col-auto d-flex">
        <button type="submit" class="btn btn-outline-primary">Filter</button>
        <a href="/customers" class="btn btn-outline-secondary ms-2">Clear</a>
      </div>
    </form>

    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Vehicle(s)</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr>
            <td colspan="4" class="text-center text-muted py-4">No customers found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($customers as $cust): ?>
            <tr>
              <td><?= htmlspecialchars($cust['name']) ?></td>
              <td><?= htmlspecialchars($cust['phone'] ?? '') ?>
                <div class="text-muted small"><?= htmlspecialchars($cust['email'] ?? '') ?></div>
              </td>
              <td>
                <?php if (empty($cust['vehicles'])): ?>
                  <span class="text-muted">No vehicles</span>
                <?php else: ?>
                  <?php foreach ($cust['vehicles'] as $v): ?>
                    <div><?= htmlspecialchars($v['plate_number'] ?? '') ?> — <?= htmlspecialchars($v['model'] ?? '') ?></div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars(substr($cust['created_at'] ?? '', 0, 10)) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>