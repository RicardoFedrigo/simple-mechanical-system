<?php $title = $title ?? 'Customers';
ob_start(); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Customer Management</h5>
      <button class="btn btn-primary btn-sm">New Customer</button>
    </div>
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Vehicle</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>