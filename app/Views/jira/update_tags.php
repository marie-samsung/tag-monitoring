<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Update Related Tags : <?= esc($ticket['ticket_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;

helper('custom_functions');

$currentTags = [];
if (!empty($currentTagIds)) {
    $tagModel = new \App\Models\TagsModel();
    $tagsQuery = $tagModel->select('tags.*, properties.name as property_name')
        ->join('properties', 'properties.id = tags.property_id')
        ->whereIn('tags.id', $currentTagIds);
    $currentTags = $tagsQuery->findAll();
}

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
                    
                    <!-- Related Tags List -->
                    <div class="mb-4">
                        <label class="form-label-sm fw-semibold text-secondary mb-2">Associated Tags</label>
                        <div id="related-tags-list" class="mb-2">
                            <?php if (empty($currentTags)): ?>
                                <div id="no-tags-alert" class="alert alert-info py-2 px-3 m-0" style="font-size: 0.85rem; border-radius: 8px;">
                                    No tags currently associated with this JIRA ticket.
                                </div>
                            <?php else: ?>
                                <div id="no-tags-alert" style="display: none;" class="alert alert-info py-2 px-3 m-0" style="font-size: 0.85rem; border-radius: 8px;">
                                    No tags currently associated with this JIRA ticket.
                                </div>
                                <table class="table table-sm align-middle mb-0" id="tag-association-table" style="border-radius: 10px; overflow: hidden; border: 1px solid var(--color-border-input, #e5e5ea);">
                                    <thead style="background-color: var(--color-secondary-bg, #F5F5F7);">
                                        <tr>
                                            <th class="form-label-sm fw-semibold text-secondary ps-3" style="width: 40%; padding: 10px 12px;">Property</th>
                                            <th class="form-label-sm fw-semibold text-secondary" style="padding: 10px 12px;">Tag Name</th>
                                            <th style="width: 40px; padding: 10px 12px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tag-association-tbody">
                                        <?php foreach ($currentTags as $tag): ?>
                                            <tr class="tag-row-item" data-tag-id="<?= $tag['id'] ?>" style="transition: all 0.3s ease;">
                                                <input type="hidden" name="tag_ids[]" value="<?= $tag['id'] ?>">
                                                <td class="ps-3" style="padding: 10px 12px;">
                                                    <span class="d-flex align-items-center gap-2">
                                                        <i class="bi bi-tag-fill" style="<?= random_text_style($tag['property_id']) ?>; font-size: 0.85rem;"></i>
                                                        <span class="fw-medium text-dark form-label-sm"><?= esc($tag['property_name']) ?></span>
                                                    </span>
                                                </td>
                                                <td style="padding: 10px 12px;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="<?= site_url('tags/view/' . $tag['id']) ?>" target="_blank" class="apple-link-dark fw-semibold form-label-sm"><?= esc($tag['name']) ?></a>
                                                        <span class="badge bg-secondary text-secondary" style="font-size: 0.7rem; border-radius: 4px; padding: 2px 6px;">#<?= $tag['id'] ?></span>
                                                    </div>
                                                </td>
                                                <td style="padding: 10px 12px; text-align: center;">
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0 remove-tag-btn" onclick="removeTagRow(<?= $tag['id'] ?>)" title="Remove Tag" style="box-shadow: none; line-height: 1;">
                                                        <i class="bi bi-trash" style="font-size: 1rem;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Add Tag Dropdown with Search -->
                    <div class="mb-4">
                        <label for="add-tag-select" class="form-label-sm fw-semibold text-secondary mb-2">Add Tag</label>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <select id="add-tag-select" class="form-select searchable-select-single">
                                    <option value="">Search and select tag to add...</option>
                                    <?php foreach ($groupedTags as $propertyName => $tags): ?>
                                        <optgroup label="<?= esc($propertyName) ?>">
                                            <?php foreach ($tags as $tag): ?>
                                                <option value="<?= $tag['id'] ?>" data-name="<?= esc($tag['name']) ?>" data-property="<?= esc($tag['property_name'] ?? $propertyName) ?>">
                                                    #<?= $tag['id'] ?> - <?= esc($tag['name']) ?> (<?= esc($tag['property_name'] ?? $propertyName) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" id="btn-add-tag" class="apple-btn-secondary d-inline-flex align-items-center justify-content-center" style="height: 38px; min-width: 90px; border-radius: 8px;">Add Tag</button>
                        </div>
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
    const tagsViewUrl = '<?= site_url('tags/view') ?>/';

    $('.searchable-select-single').select2({
        width: '100%',
        placeholder: "Search and select tag to add...",
        allowClear: true
    });

    function addSelectedTag() {
        const select = $('#add-tag-select');
        const tagId = select.val();
        if (!tagId) return;

        const selectedOption = select.find('option:selected');
        const tagName = selectedOption.data('name');
        const propertyName = selectedOption.data('property');

        // Check if already in the list
        if ($(`.tag-row-item[data-tag-id="${tagId}"]`).length > 0) {
            alert('This tag is already associated with the ticket.');
            select.val('').trigger('change');
            return;
        }

        // Remove "no tags" alert if it exists
        $('#no-tags-alert').hide();

        // Ensure the table exists (first tag being added to an empty list)
        if ($('#tag-association-table').length === 0) {
            const tableHtml = `
                <table class="table table-sm align-middle mb-0" id="tag-association-table" style="border-radius: 10px; overflow: hidden; border: 1px solid var(--color-border-input, #e5e5ea);">
                    <thead style="background-color: var(--color-secondary-bg, #F5F5F7);">
                        <tr>
                            <th class="form-label-sm fw-semibold text-secondary ps-3" style="width: 40%; padding: 10px 12px;">Property</th>
                            <th class="form-label-sm fw-semibold text-secondary" style="padding: 10px 12px;">Tag Name</th>
                            <th style="width: 40px; padding: 10px 12px;"></th>
                        </tr>
                    </thead>
                    <tbody id="tag-association-tbody"></tbody>
                </table>
            `;
            $('#related-tags-list').append(tableHtml);
        }

        // Append new tag row inside the table body
        const rowHtml = `
            <tr class="tag-row-item" data-tag-id="${tagId}" style="opacity: 0; transition: opacity 0.3s ease;">
                <input type="hidden" name="tag_ids[]" value="${tagId}">
                <td class="ps-3" style="padding: 10px 12px;">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-tag-fill text-primary" style="font-size: 0.85rem;"></i>
                        <span class="fw-medium text-dark form-label-sm">${propertyName}</span>
                    </span>
                </td>
                <td style="padding: 10px 12px;">
                    <div class="d-flex align-items-center gap-2">
                        <a href="${tagsViewUrl}${tagId}" target="_blank" class="apple-link-dark fw-semibold form-label-sm">${tagName}</a>
                        <span class="badge bg-secondary text-secondary" style="font-size: 0.7rem; border-radius: 4px; padding: 2px 6px;">#${tagId}</span>
                    </div>
                </td>
                <td style="padding: 10px 12px; text-align: center;">
                    <button type="button" class="btn btn-link text-danger p-0 border-0 remove-tag-btn" onclick="removeTagRow(${tagId})" title="Remove Tag" style="box-shadow: none; line-height: 1;">
                        <i class="bi bi-trash" style="font-size: 1rem;"></i>
                    </button>
                </td>
            </tr>
        `;

        const $newRow = $(rowHtml);
        $('#tag-association-tbody').append($newRow);

        // Trigger fade-in animation
        setTimeout(() => {
            $newRow.css('opacity', '1');
        }, 50);

        // Reset select2 selection
        select.val('').trigger('change');
    }

    // Add on click
    $('#btn-add-tag').on('click', function() {
        addSelectedTag();
    });

    // Add on select2 close / selection
    $('#add-tag-select').on('select2:select', function(e) {
        addSelectedTag();
    });

    // Also support enter key in select2 search container
    $(document).on('keydown', '.select2-search__field', function(e) {
        if (e.which === 13) {
            // Delay to let Select2 finish its select event
            setTimeout(addSelectedTag, 100);
        }
    });

    window.removeTagRow = function(tagId) {
        const row = $(`.tag-row-item[data-tag-id="${tagId}"]`);
        if (row.length > 0) {
            row.css('opacity', '0');
            setTimeout(() => {
                row.remove();
                if ($('#related-tags-list .tag-row-item').length === 0) {
                    $('#tag-association-table').remove();
                    $('#no-tags-alert').show();
                }
            }, 300);
        }
    };
});
</script>
<?= $this->endSection() ?>
