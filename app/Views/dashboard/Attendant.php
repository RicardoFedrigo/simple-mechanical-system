<?php $title = $title ?? 'Dashboard';
ob_start(); ?>
<div class="row g-4">
  <div class="col-md-6 col-xl-3">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="text-muted">Total Customers</div>
        <h3 class="mt-2">18</h3>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
  </div>

</div>
<div class="card shadow-sm border-0 mt-4">
  <div class="card-body">
    <h5 class="card-title">Service Flow Overview</h5>
    <p class="text-muted">Charts and workload snapshots can be connected here using Chart.js.</p>
  </div>
</div>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>