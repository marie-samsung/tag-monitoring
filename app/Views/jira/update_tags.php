<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Update Related Tags : <?= esc($ticket['ticket_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;

helper('custom_functions');

// Check if comments contains actual user comment text (as opposed to just comma-separated tag IDs)
$isCustomComment = false;
$originalComment = trim($ticket['comments'] ?? '');
if (!empty($originalComment)) {
    $rawIds = explode(',', $originalComment);
    foreach ($rawIds as $rawId) {
        if (!is_numeric(trim($rawId))) {
            $isCustomComment = true;
            break;
        }
    }
}
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('jira') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to JIRA List">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Update Related Tags</h2>
            <p class="text-secondary small mb-0">Manage associated tags for JIRA Ticket: <strong><?= esc($ticket['ticket_id']) ?></strong></p>
        </div>
    </div>

    <?php if ($isCustomComment): ?>
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-3 mb-4" style="border-radius: 12px; background-color: rgba(255, 193, 7, 0.1);">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1"></i>
            <div>
                <h6 class="alert-heading mb-1 fw-bold text-dark">Existing Text Comment Detected</h6>
                <p class="mb-0 text-secondary small">
                    This ticket currently has a text comment/note: <strong>"<?= esc($originalComment) ?>"</strong>.
                    <br>
                    Saving related tags will overwrite this notes field with the selected tag IDs. Please make sure this is intended.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Ticket Information Card -->
        <div class="col-lg-4 mb-4">
            <div class="apple-card h-100" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Ticket Information</span>
                </h5>
                <div class="d-flex flex-column gap-3">
                    <div>
                        <span class="text-secondary small d-block">Ticket ID</span>
                        <a href="<?= Constants::JIRA_LINK . esc($ticket['ticket_id']) ?>" target="_blank" rel="noopener" class="apple-link fw-semibold">
                            <?= esc($ticket['ticket_id']) ?> <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                    </div>
                    <div>
                        <span class="text-secondary small d-block">Subsidiary</span>
                        <span class="badge mt-1" style="<?= random_bg_style($subsidiary['name'] ?? '') ?>">
                            <?= esc($subsidiary['name'] ?? 'None') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary small d-block">Ticket Type</span>
                        <span class="fw-semibold text-dark">
                            <?= esc(Constants::JIRA_TICKET_TYPES[$ticket['type']] ?? 'Unknown') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary small d-block">Created At</span>
                        <span class="text-dark">
                            <?= esc(date('d-m-Y H:i:s', strtotime($ticket['created_at']))) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tags Selection Card -->
        <div class="col-lg-8 mb-4">
            <form action="<?= site_url("jira/update-tags/{$ticket['id']}") ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="apple-card" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-tags-fill"></i>
                        <span>Tag Association</span>
                    </h5>
                    
                    <div class="mb-4">
                        <label for="tag_ids" class="form-label-sm fw-semibold text-secondary mb-2">Select Related Tags</label>
                        <p class="text-muted small mb-3">Only tags from the same subsidiary (<strong><?= esc($subsidiary['name'] ?? '') ?></strong>) are listed below, grouped by Property.</p>
                        
                        <select name="tag_ids[]" id="tag_ids" class="form-select searchable-select-multiple" multiple="multiple" style="display: none;">
                            <?php if (empty($groupedTags)): ?>
                                <option disabled>No tags found in this subsidiary</option>
                            <?php else: ?>
                                <?php foreach ($groupedTags as $propertyName => $tags): ?>
                                    <optgroup label="<?= esc($propertyName) ?>">
                                        <?php foreach ($tags as $tag): ?>
                                            <option value="<?= $tag['id'] ?>" <?= in_array((int)$tag['id'], $currentTagIds) ? 'selected' : '' ?>>
                                                #<?= $tag['id'] ?> - <?= esc($tag['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="<?= site_url('jira') ?>" class="apple-btn-secondary px-4 text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 38px;">Cancel</a>
                        <button type="submit" class="apple-btn-primary px-4 d-inline-flex align-items-center justify-content-center" style="height: 38px;">Update Tags</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select-multiple').select2({
        width: '100%',
        placeholder: "Search and select tags...",
        allowClear: true,
        closeOnSelect: false
    });
});
</script>
<?= $this->endSection() ?>
