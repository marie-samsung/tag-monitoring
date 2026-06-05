<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($vendor['name']) ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    helper('custom_functions');
    $cancelUrl = !empty($subsidiary_id) 
        ? site_url('vendors/view/' . $vendor['id'] . '?subsidiary_id=' . $subsidiary_id)
        : site_url('vendors');
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= $cancelUrl ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 fw-bold">Update Vendor</h2>
                <div class="text-secondary small mb-0 fw-semibold d-flex align-items-center gap-2 mt-1">
                    <?php if (!empty($subsidiary_id)): ?>
                        <?php $subsidiary = App\Models\Constants::SUBSIDIARIES[$subsidiary_id] ?? 'Unknown'; ?>
                        <span class="badge" style="<?= random_bg_style($subsidiary) ?>"><?= esc($subsidiary) ?></span>
                        <span>•</span>
                    <?php endif; ?>
                    <span class="text-muted"><?= esc($vendor['name']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url("vendors/update/{$vendor['id']}") ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if (!empty($subsidiary_id)): ?>
            <input type="hidden" name="subsidiary_id" value="<?= esc($subsidiary_id) ?>">
        <?php endif; ?>

        <div class="apple-card">
            <!-- Vendor Name -->
            <div class="mb-4">
                <label for="name" class="form-label-xs text-primary fw-semibold mb-2">Vendor Name</label>
                <input type="text" name="name" id="name"
                    class="form-control"
                    value="<?= esc($vendor['name']) ?>"
                    required>
            </div>

            <!-- Description Fields (Side-by-side layout in a single row) -->
            <div class="row g-3">
                <!-- About -->
                <div class="col-md-4">
                    <label for="about" class="form-label-xs text-primary fw-semibold mb-2">About</label>
                    <textarea name="about" id="about" class="form-control" rows="4"><?= esc($vendor['about']) ?></textarea>
                </div>

                <!-- Business Reason -->
                <div class="col-md-4">
                    <label for="reason" class="form-label-xs text-primary fw-semibold mb-2">Business Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="4"><?= esc($vendor['reason']) ?></textarea>
                </div>

                <!-- Tag Purpose -->
                <div class="col-md-4">
                    <label for="tag_purpose" class="form-label-xs text-primary fw-semibold mb-2">Tag Purpose</label>
                    <textarea name="tag_purpose" id="tag_purpose" class="form-control" rows="4"><?= esc($vendor['tag_purpose']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 mb-3 border-top pt-3">
            <a href="<?= $cancelUrl ?>" title="Cancel" class="apple-btn-secondary btn-sm text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary btn-sm">Update</button>
        </div>
    </form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#about').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#reason').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#tag_purpose').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

<?= $this->endSection() ?>
