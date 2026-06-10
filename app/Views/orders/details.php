<?php $title = 'Order #' . htmlspecialchars($order->getId());
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title mb-0">Order #<?= htmlspecialchars($order->getId()) ?></h5>
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
                            <span class="badge bg-<?= $order->getStatus() === 'COMPLETED' ? 'success' : ($order->getStatus() === 'IN_PROGRESS' ? 'warning' : 'secondary') ?>">
                                <?= htmlspecialchars($order->getStatus()) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td><?= htmlspecialchars(substr($order->getCreatedAt(), 0, 10)) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mechanic:</strong></td>
                        <td><?= htmlspecialchars($order->getMechanicName()  ?? 'Not assigned') ?></td>
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
                <p class="mb-2"><strong><?= htmlspecialchars($order->getCustomerName() ?? 'N/A') ?></strong></p>
                <p class="mb-1 text-muted small">
                    <strong>Phone:</strong> <?= htmlspecialchars($order->getCustomerPhone() ?? 'N/A') ?>
                </p>
                <p class="mb-0 text-muted small">
                    <strong>Email:</strong> <?= htmlspecialchars($order->getCustomerEmail() ?? 'N/A') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php if (($currentRole ?? '') === 'Mechanic' && !empty($currentUserId) && $order->getMechanicUserId() === $currentUserId): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Update Order Status</h6>
        </div>
        <div class="card-body">
            <form method="post" action="/orders/status">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order->getId()) ?>">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <?php foreach (['PENDING' => 'PENDING', 'IN_PROGRESS' => 'IN PROGRESS', 'COMPLETED' => 'COMPLETED'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $order->getStatus() === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($order->getPlateNumber())): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Vehicle Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Plate Number:</strong> <?= htmlspecialchars($order->getPlateNumber()) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Model:</strong> <?= htmlspecialchars($order->getVehicleModel() ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0"><strong>Year:</strong> <?= htmlspecialchars($order->getVehicleYear() ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Service Description</h6>
        </div>
        <div class="card-body">
            <p class="mb-0"><?= nl2br(htmlspecialchars($order->getServiceDescription() ?? 'No description provided.')) ?></p>
        </div>
    </div>

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
                    <strong>$<?= number_format((float)$order->getSubtotal(), 2) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Tax:</span>
                    <strong>$<?= number_format((float)$order->getTax(), 2) ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5 mb-0">Total:</span>
                    <strong class="h5 mb-0">$<?= number_format((float)$order->getTotal(), 2) ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>