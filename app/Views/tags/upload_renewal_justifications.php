<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Upload Renewal Justifications
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Upload Renewal Justifications</h2>
        <a href="<?= site_url('tags') ?>" class="apple-btn-secondary btn-sm px-3">Back</a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card p-4">
        <form action="<?= site_url('tags/parse-renewal-justifications') ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update the renewal justifications?');">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="excel_file" class="form-label text-primary fw-semibold">Select Excel File (.xlsx)</label>
                <input class="form-control" type="file" id="excel_file" name="excel_file" accept=".xlsx" required>
                <div class="form-text">Please upload the Renewal Justification excel template.</div>
            </div>
            <button type="submit" class="apple-btn-primary px-4 mt-2">Upload and Extract</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
