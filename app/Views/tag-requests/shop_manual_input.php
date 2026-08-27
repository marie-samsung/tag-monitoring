<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Shop Manual Rule Input
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= site_url('tag-requests/view/' . $request['id']) ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Request">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 fw-bold">Manual Rule Input (Shop)</h2>
                <div class="text-secondary small mb-0 fw-semibold d-flex align-items-center gap-2">
                    <span class="badge bg-secondary"><?= esc($property['name'] ?? 'Unknown') ?></span>
                    <?php if (!empty($tagNames)): ?>
                        <span>•</span>
                        <?php foreach ($tagNames as $tagName): ?>
                            <span class="badge bg-info"><?= esc($tagName) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= site_url('tag-requests/generate-shop-excel') ?>" method="post" id="shopManualForm">
        <?= csrf_field() ?>
        <input type="hidden" name="request_id" value="<?= esc($request['id']) ?>">

        <div class="row g-4 mb-4">
            <!-- Cart Rules -->
            <div class="col-lg-4">
                <div class="apple-card h-100">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-cart"></i>
                        <span>Cart Logs</span>
                    </h5>
                    <p class="text-secondary small mb-3">Paste the console logs for the Cart page below. The system will automatically extract the rule names enclosed in double quotes.</p>
                    <textarea class="form-control font-monospace" name="cart_logs" rows="15" placeholder="watchConsoleLogs.8258a5d6.js:1 🚀 Rule &quot;HQ-GA-CM-gTag Load&quot; fired." required></textarea>
                </div>
            </div>

            <!-- Checkout Rules -->
            <div class="col-lg-4">
                <div class="apple-card h-100">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-credit-card"></i>
                        <span>Checkout Logs</span>
                    </h5>
                    <p class="text-secondary small mb-3">Paste the console logs for the Checkout page below. The system will automatically extract the rule names enclosed in double quotes.</p>
                    <textarea class="form-control font-monospace" name="checkout_logs" rows="15" placeholder="watchConsoleLogs.8258a5d6.js:1 🚀 Rule &quot;HQ-GA-CM-gTag Load&quot; fired." required></textarea>
                </div>
            </div>

            <!-- Order Confirmation Rules -->
            <div class="col-lg-4">
                <div class="apple-card h-100">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-bag-check"></i>
                        <span>Order Confirmation Logs</span>
                    </h5>
                    <p class="text-secondary small mb-3">Paste the console logs for the Order Confirmation page below. The system will automatically extract the rule names enclosed in double quotes.</p>
                    <textarea class="form-control font-monospace" name="order_logs" rows="15" placeholder="watchConsoleLogs.8258a5d6.js:1 🚀 Rule &quot;HQ-GA-CM-gTag Load&quot; fired." required></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="apple-btn-primary d-flex align-items-center gap-2 py-2 px-4">
                <i class="bi bi-file-earmark-excel-fill fs-5"></i> Generate Excel Report
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let isSubmitting = false;

document.getElementById('shopManualForm')?.addEventListener('submit', function() {
    isSubmitting = true;
});

window.addEventListener('beforeunload', function(e) {
    if (isSubmitting) return;

    const cartLogs = document.querySelector('textarea[name="cart_logs"]')?.value.trim();
    const checkoutLogs = document.querySelector('textarea[name="checkout_logs"]')?.value.trim();
    const orderLogs = document.querySelector('textarea[name="order_logs"]')?.value.trim();

    if (cartLogs || checkoutLogs || orderLogs) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
});
</script>
<?= $this->endSection() ?>

