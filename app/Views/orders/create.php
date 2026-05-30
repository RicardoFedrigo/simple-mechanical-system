<?php $title = 'Create Service Order';
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title mb-0"><?php echo $title ?></h5>
    <a href="/orders" class="btn btn-secondary btn-sm">Back to Orders</a>
</div>

<div class="card shadow-sm border-0">

    <div class="card-body">
        <form method="post" action="/customer" class="needs-validation">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="d-flex gap-2">
                <button onclick="searchCustomer()" class="btn btn-primary">Search</button>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
        </form>
    </div>
    </br>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/orders" class="needs-validation">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Model</label>
                    <input type="text" name="vehicle_model" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Brand</label>
                    <select name="vehicle_brand_id" class="form-select">
                        <option value="">Select a brand</option>
                        <?php if (!empty($vehicle_brands)): ?>
                            <?php foreach ($vehicle_brands as $brand): ?>
                                <option value="<?= htmlspecialchars($brand['id']) ?>">
                                    <?= htmlspecialchars($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Vehicles for selected customer will appear here</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mechanic</label>
                    <select name="mechanic_id" class="form-select">
                        <option value="">Assign mechanic (optional)</option>
                        <?php if (!empty($mechanics)): ?>
                            <?php foreach ($mechanics as $mechanic): ?>
                                <option value="<?= htmlspecialchars($mechanic['id']) ?>">
                                    <?= htmlspecialchars($mechanic['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="PENDING">Pending</option>
                        <option value="IN_PROGRESS">In Progress</option>
                        <option value="COMPLETED">Completed</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the service needed..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Subtotal</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="subtotal" class="form-control" step="0.01" value="0.00" min="0">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tax</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="tax" class="form-control" step="0.01" value="0.00" min="0">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Total</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="total" class="form-control" step="0.01" value="0.00" min="0" readonly>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Ticket</button>
                <a href="/order" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('input[name="subtotal"]').addEventListener('change', updateTotal);
    document.querySelector('input[name="tax"]').addEventListener('change', updateTotal);

    function searchCustomer() {
        const customerFound = fetch('')
    }

    function updateTotal() {
        const subtotal = parseFloat(document.querySelector('input[name="subtotal"]').value) || 0;
        const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
        const total = subtotal + tax;
        document.querySelector('input[name="total"]').value = total.toFixed(2);
    }
</script>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>