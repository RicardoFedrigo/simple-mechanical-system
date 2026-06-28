<?php $title = 'Customer Details';
ob_start(); ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">Customer Details</h5>
    </div>
    <div class="card-body">
        <p><strong>Name:</strong> <?= htmlspecialchars($customer->getName()) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($customer->getPhone() ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($customer->getEmail() ?? 'N/A') ?></p>
    </div>
</div>

<a href="/customers" class="btn btn-secondary">Back to List</a>

<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>
