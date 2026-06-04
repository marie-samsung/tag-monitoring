<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($vendor['name']) ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mb-2">
    <h7 class="text-danger fw-semibold">Update Vendor</h7>
    <h3><?= esc($vendor['name']) ?></h3>
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

    <div class="container">
        <div class="card p-4 shadow-sm border-0 bg-white">
            <div class="row mb-4">
                <div class="col">
                    <label for="name" class="form-label-xs text-primary fw-semibold">Vendor Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control"
                        value="<?= esc($vendor['name']) ?>"
                        required>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col">
                    <label for="about" class="form-label-xs text-primary fw-semibold">About</label>
                    <textarea name="about" id="about" class="form-control" rows="4"><?= esc($vendor['about']) ?></textarea>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="reason" class="form-label-xs text-primary fw-semibold">Business Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="4"><?= esc($vendor['reason']) ?></textarea>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="tag_purpose" class="form-label-xs text-primary fw-semibold">Tag Purpose</label>
                    <textarea name="tag_purpose" id="tag_purpose" class="form-control" rows="4"><?= esc($vendor['tag_purpose']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 mb-3">
            <?php
                $cancelUrl = !empty($subsidiary_id) 
                    ? site_url('vendors/view/' . $vendor['id'] . '?subsidiary_id=' . $subsidiary_id)
                    : site_url('vendors');
            ?>
            <a href="<?= $cancelUrl ?>" title="Cancel" class="apple-btn-secondary btn-sm text-decoration-none">Cancel</a>
            <button type="submit" class="apple-btn-primary btn-sm">Update</button>
        </div>
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
