<?php $title = 'Order #' . htmlspecialchars($order['id']);
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title mb-0">Order #<?= htmlspecialchars($order['id']) ?></h5>
    <a href="/orders" class="btn btn-secondary btn-sm">Back to Orders</a>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0">Order Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-<?= $order['status'] === 'COMPLETED' ? 'success' : ($order['status'] === 'IN_PROGRESS' ? 'warning' : 'secondary') ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td><?= htmlspecialchars(substr($order['created_at'], 0, 10)) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mechanic:</strong></td>
                        <td><?= htmlspecialchars($order['mechanic_name'] ?? 'Not assigned') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></strong></p>
                <p class="mb-1 text-muted small">
                    <strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?>
                </p>
                <p class="mb-0 text-muted small">
                    <strong>Email:</strong> <?= htmlspecialchars($order['customer_email'] ?? 'N/A') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($order['plate_number'])): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Vehicle Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Plate Number:</strong> <?= htmlspecialchars($order['plate_number']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Model:</strong> <?= htmlspecialchars($order['vehicle_model'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0"><strong>Year:</strong> <?= htmlspecialchars($order['vehicle_year'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0">Service Items & Parts</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No items added yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format((float)$item['unit_price'], 2) ?></td>
                            <td><strong>$<?= number_format((float)$item['total'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-4 offset-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>$<?= number_format((float)$order['subtotal'], 2) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Tax:</span>
                    <strong>$<?= number_format((float)$order['tax'], 2) ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5 mb-0">Total:</span>
                    <strong class="h5 mb-0">$<?= number_format((float)$order['total'], 2) ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>