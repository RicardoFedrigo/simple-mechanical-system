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
                        <td><?= htmlspecialchars($order->getMechanicName() ?? 'Not assigned') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mechanic Specialty:</strong></td>
                        <td><?= htmlspecialchars($order->getMechanic()?->getSpecialty() ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Mechanic Phone:</strong></td>
                        <td><?= htmlspecialchars($order->getMechanic()?->getPhone() ?? 'N/A') ?></td>
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

<?php $canModify = (in_array(($currentRole ?? ''), ['Mechanic', 'Admin'])) && 
             (($currentRole === 'Admin') || ($order->getMechanic()?->getUserId() === $currentUserId)); ?>

<?php if ($canModify && $order->getStatus() !== 'COMPLETED'): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Update Order Status</h6>
        </div>
        <div class="card-body">
            <form method="post" action="/orders/status">
                <?= csrf_field() ?>
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

<?php if (!empty($order->getVehicle())): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Vehicle Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Plate Number:</strong> <?= htmlspecialchars($order->getVehicle()->getPlateNumber()) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Model:</strong> <?= htmlspecialchars($order->getVehicle()->getModel() ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0"><strong>Year:</strong> <?= htmlspecialchars($order->getVehicle()->getYear() ?? 'N/A') ?></p>
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

    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Service, Items & Parts</h6>
        <?php if ($canModify && $order->getStatus() !== 'COMPLETED'): ?>
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addItemCollapse" aria-expanded="false" aria-controls="addItemCollapse">
                Add Item
            </button>
        <?php endif; ?>
    </div>
    
    <?php if ($canModify && $order->getStatus() !== 'COMPLETED'): ?>
<div class="collapse" id="addItemCollapse">
    <div class="card card-body border-0 bg-light">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="itemSearch" class="form-label">Search Item</label>
                <div class="input-group">
                    <input type="text" id="itemSearch" class="form-control" placeholder="Search by name or SKU...">
                    <button class="btn btn-outline-secondary" type="button" id="btnSearch">Search</button>
                </div>
                <div id="searchResults" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
            </div>
        </div>
        <form method="post" action="/orders/add-item" id="addItemForm">
            <?= csrf_field() ?>
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order->getId()) ?>">
            <input type="hidden" name="item_id" id="item_id">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="description" class="form-label">Item Name</label>
                    <input type="text" id="item_name" class="form-control" readonly>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" min="0" required readonly>
                </div>
                <div class="col-md-2 mb-3 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnSearch').addEventListener('click', function() {
        const term = document.getElementById('itemSearch').value;
        if (term.length < 3) return alert('Enter at least 3 characters');
        fetch('/orders/search-items?term=' + encodeURIComponent(term))
            .then(res => res.json())
            .then(items => {
                const container = document.getElementById('searchResults');
                container.innerHTML = '';
                if (items.length === 0) {
                    container.innerHTML = '<div class="list-group-item-action" style="color: #ff0000;">No items found.</div>';
                    return;
                }
                items.forEach(item => {
                    const div = document.createElement('a');
                    div.className = 'list-group-item list-group-item-action';
                    div.innerText = item.name + ' (' + item.sku + ') - $' + item.unit_price;
                    div.onclick = () => {
                        document.getElementById('item_id').value = item.id;
                        document.getElementById('item_name').value = item.name;
                        document.getElementById('unit_price').value = item.unit_price;
                        container.innerHTML = '';
                    };
                    container.appendChild(div);
                });
            });
    });
</script>

    <?php endif; ?>
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
                            <td><?= htmlspecialchars($item->getItem()?->getName() ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item->getQuantity()) ?></td>
                            <td>$<?= number_format((float)($item->getItem()?->getUnitPrice() ?? 0), 2) ?></td>
                            <td><strong>$<?= number_format((float)$item->getTotal(), 2) ?></strong></td>
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