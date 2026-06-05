<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Upload Renewal Justifications
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Upload Renewal Justifications</h2>
            <p class="text-secondary small mb-0">Import and parse an Excel sheet (.xlsx) to bulk update renewal justifications in the database.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('tags/parse-renewal-justifications') ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update the renewal justifications?');" style="max-width: 600px;">
        <?= csrf_field() ?>
        
        <!-- Upload Card Container -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-file-earmark-excel-fill"></i>
                <span>File Upload</span>
            </h5>
            
            <div class="mb-3">
                <label for="excel_file" class="form-label-sm fw-semibold text-secondary mb-2">Select Excel File (.xlsx)</label>
                <input class="form-control form-control-xs bg-white" type="file" id="excel_file" name="excel_file" accept=".xlsx" required style="border-radius: 8px; border: 1px solid var(--color-border-input); padding: 6px 12px; height: auto;">
                <div class="form-text text-muted mt-2" style="font-size: 0.75rem;">
                    Please select the completed <strong>Renewal Justification</strong> Excel template.
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary px-4">Upload and Extract</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

