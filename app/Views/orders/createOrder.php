<?php $title = 'Create Service Order';
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title mb-0"><?php echo $title ?></h5>
    <a href="/orders" class="btn btn-secondary btn-sm">Back to Orders</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/orders" class="needs-validation">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Search Customer</label>
                <div class="input-group">
                    <input type="search" id="customer_search" class="form-control" placeholder="Type customer name..." autocomplete="off">
                    <button type="button" id="customer_search_button" class="btn btn-outline-secondary">Search</button>
                </div>
                <div class="form-text">Enter at least 3 characters to search by name.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Customer Results</label>
                <select id="customer_results" name="customer_id" class="form-select">
                    <option value="">No customer selected</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Full name">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" id="customer_phone" name="customer_phone" class="form-control"
                        inputmode="numeric"
                        autocomplete="tel"
                        maxlength="16"
                        pattern="\([0-9]{2}\) [0-9] [0-9]{4}-[0-9]{4}"
                        placeholder="(12) 9 1234-5678"
                    >
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="email@example.com">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Brand</label>
                    <select name="vehicle_brand_id" class="form-select">
                        <option value="">Select a brand</option>
                        <?php if (!empty($vehicle_brands)): ?>
                            <?php foreach ($vehicle_brands as $brand): ?>
                                <option value="<?= htmlspecialchars($brand->getId()) ?>">
                                    <?= htmlspecialchars($brand->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Model</label>
                    <input type="text" name="vehicle_model" class="form-control" placeholder="Model name">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Plate Number</label>
                    <input type="text" name="vehicle_plate" class="form-control" placeholder="AB-1234">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Year</label>
                    <input type="number" name="vehicle_year" class="form-control" min="1900" max="2100" placeholder="2024">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the service needed..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Ticket</button>
            <a href="/orders" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const customerSearch = document.getElementById('customer_search');
    const customerSearchButton = document.getElementById('customer_search_button');
    const customerResults = document.getElementById('customer_results');
    const customerName = document.getElementById('customer_name');
    const customerPhone = document.getElementById('customer_phone');
    const customerEmail = document.getElementById('customer_email');

    async function searchCustomers() {
        const term = customerSearch.value.trim();
        customerResults.innerHTML = '<option value="">Searching...</option>';

        if (term.length < 3) {
            customerResults.innerHTML = '<option value="">Enter at least 3 characters</option>';
            return;
        }

        try {
            const response = await fetch('/api/customer/search?term=' + encodeURIComponent(term), {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const error = await response.json();
                customerResults.innerHTML = `<option value="">${error.error || 'Search failed'}</option>`;
                return;
            }

            const data = await response.json();
            if (!Array.isArray(data) || data.length === 0) {
                customerResults.innerHTML = '<option value="">No matching customers found</option>';
                return;
            }

            customerResults.innerHTML = '<option value="">Select a customer</option>' + data.map(customer => {
                const label = `${customer.name}`;
                return `<option value="${customer.id}" data-name="${customer.name}" data-phone="${customer.phone || ''}" data-email="${customer.email || ''}">${label}</option>`;
            }).join('');
        } catch (error) {
            customerResults.innerHTML = '<option value="">Search error</option>';
        }
    }

    customerSearchButton.addEventListener('click', searchCustomers);
    customerSearch.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchCustomers();
        }
    });

    customerResults.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (selected && selected.value) {
            customerName.value = selected.dataset.name || '';
            customerPhone.value = selected.dataset.phone || '';
            customerEmail.value = selected.dataset.email || '';
        }
    });

    const phoneInput = document.querySelector('input[name="customer_phone"]');

    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            const digits = this.value.replace(/\D/g, '').slice(0, 11);
            const area = digits.substring(0, 2);
            const first = digits.substring(2, 3);
            const middle = digits.substring(3, 7);
            const last = digits.substring(7, 11);

            let formatted = '';
            if (area) {
                formatted = `(${area})`;
            }
            if (first) {
                formatted += ` ${first}`;
            }
            if (middle) {
                formatted += ` ${middle}`;
            }
            if (last) {
                formatted += `-${last}`;
            }

            this.value = formatted;
        });
    }
</script>
<?php $content = ob_get_clean();
require __DIR__ . '/../layouts/dashboard.php'; ?>