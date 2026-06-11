<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
[<?= $request['new_tag_ticket'] ?? $request['new_rule_ticket']  ?>] Request Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;
use App\Models\TagsModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\OrganizationsModel;
helper('custom_functions');

$propertiesModel = new PropertiesModel();
$properties = $propertiesModel->WebsiteLinks();

$vendorsModel = new VendorsModel();
$vendors = $vendorsModel->Options();

$organizationsModel = new OrganizationsModel();
$organizations = $organizationsModel->Options();

$tagModel = new TagsModel();

$hasNewTag = !empty($request['new_tag_ticket']);
$hasNewRule = !empty($request['new_rule_ticket']);

?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tag-requests') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Requests">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Request Details</h2>
            <div class="text-secondary small mb-0 fw-semibold d-flex align-items-center gap-2">
                <span class="badge bg-secondary"><?= esc(Constants::SUBSIDIARIES[$properties[$request['property_id']]['subsidiary_id']] ?? 'Unknown') ?></span>
                <span>•</span>
                <span class="text-muted"><?= esc(request_type_label((int)$request['type'], (int)$request['status'])) ?></span>
                <?php if ($hasNewTag): ?>
                    <span>•</span>
                    <a href="<?= Constants::JIRA_LINK . $request['new_tag_ticket'] ?>" target="_blank" class="apple-link fw-bold">
                        [<?= esc($request['new_tag_ticket']) ?>]
                    </a>
                <?php elseif ($hasNewRule): ?>
                    <span>•</span>
                    <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" target="_blank" class="apple-link fw-bold">
                        [<?= esc($request['new_rule_ticket']) ?>]
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Request & Requester Details -->
        <div class="col-lg-4">
            <div class="apple-card mb-4">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Specifications</span>
                </h5>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Property</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <a href="<?= esc($properties[$request['property_id']]['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                            <i class="bi bi-globe2 text-muted me-1"></i>
                            <?= esc($properties[$request['property_id']]['name'] ?? '—') ?>
                        </a>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Vendor</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($vendors[$request['vendor_id']] ?? '—') ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Status</label>
                    <div class="d-flex align-items-center gap-2 ms-1">
                        <span class="badge <?= status_request_class($request['status']) ?>">
                            <?= esc(Constants::STATUS_REQUESTS[$request['status']] ?? 'Unknown') ?>
                        </span>

                        <?php if ($request['status'] == Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED ||
                                ($request['status'] == Constants::STATUS_REQUEST_HQ_REVIEW && $request['type'] == Constants::JIRA_TYPE_NEW_RULE)): ?>
                            <a href="<?= site_url('tag-requests/request-approved/' . $request['id']) ?>"
                               class="badge bg-success text-decoration-none"
                               onclick="return confirm('Confirm that the tags are published live? (This will set tags to ENABLED status)');">
                                <i class="bi bi-check-circle-fill me-1"></i>Publish
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- JIRA Ticket Actions / Links -->
                <h6 class="fw-bold mt-4 mb-3 text-secondary border-top pt-3">JIRA Integration</h6>

                <?php if ($request['type'] == Constants::JIRA_TYPE_NEW_TAG): ?>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">New Tag Request</label>
                        <?php if (!$hasNewTag): ?>
                            <form action="<?= site_url('tag-requests/add-new-tag') ?>" method="post">
                                <div class="d-flex gap-2">
                                    <input type="hidden" name="request_id" value="<?= esc($request['id'] ?? '') ?>">
                                    <input type="text" name="new_tag_ticket" id="new_tag_ticket"
                                           class="form-control form-control-xs"
                                           value="<?= old('new_tag_ticket', $old['new_tag_ticket'] ?? '') ?>"
                                           placeholder="GTA-XXXXX"
                                           required>
                                    <button type="submit"
                                            class="apple-btn-primary btn-sm px-3 text-nowrap"
                                            onclick="return confirm('Confirm add New Tag ticket ID?');">
                                        Add
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                                <a href="<?= Constants::JIRA_LINK . $request['new_tag_ticket'] ?>" target="_blank" class="apple-link text-decoration-none fw-semibold">
                                    <?= esc($request['new_tag_ticket']) ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">New Rule Request</label>
                    <?php if (!$hasNewRule): ?>
                        <form action="<?= site_url('tag-requests/add-new-rule') ?>" method="post">
                            <div class="d-flex gap-2">
                                <input type="hidden" name="request_id" value="<?= esc($request['id'] ?? '') ?>">
                                <input type="text" name="new_rule_ticket" id="new_rule_ticket"
                                       class="form-control form-control-xs"
                                       value="<?= old('new_rule_ticket', $old['new_rule_ticket'] ?? '') ?>"
                                       placeholder="GTA-XXXXX"
                                       required>
                                <button type="submit"
                                        class="apple-btn-primary btn-sm px-3 text-nowrap"
                                        onclick="return confirm('Confirm add New Rule ticket ID?');">
                                    Add
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="d-flex align-items-center gap-2 ms-1">
                            <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" target="_blank" class="apple-link text-decoration-none fw-semibold">
                                <?= esc($request['new_rule_ticket']) ?>
                            </a>
                            <a href="<?= site_url('tag-requests/new-rule-request/' . $request['id']) ?>" class="apple-btn-secondary btn-sm px-2 py-1 text-decoration-none" style="font-size: 0.75rem; border-radius: 9999px;">
                                View Rule Request
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Requester Details -->
                <h6 class="fw-bold mt-4 mb-3 text-secondary border-top pt-3">Requester Details</h6>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Name</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($request['requester_name'] ?? '—') ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Email Address</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?php if (!empty($request['requester_email'])): ?>
                            <a href="mailto:<?= esc($request['requester_email']) ?>" class="apple-link text-decoration-none">
                                <?= esc($request['requester_email']) ?>
                            </a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Organization</label>
                    <span class="fw-medium text-dark ms-1" style="font-size: 0.9rem;">
                        <?= esc($organizations[$request['requester_organization']] ?? '—') ?>
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="<?= site_url('tag-requests') ?>" class="apple-btn-secondary btn-sm px-3 text-decoration-none w-100">Back to List</a>
                </div>
            </div>
        </div>

        <!-- Right Column: Associated Tags & Uploaded Files -->
        <div class="col-lg-8">
            <!-- Tags Section -->
            <div class="mb-4">
                <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-tags-fill"></i>
                    <span>Tags Associated</span>
                </h5>
                <div class="apple-table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 apple-table">
                            <thead>
                                <tr>
                                    <th class="form-label-sm fw-semibold">Tag Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tags as $tag) : ?>
                                    <tr>
                                        <td>
                                            <a href="<?= site_url('tags/view/' . $tag['id']) ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                                <?= esc($tag['name']) ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Files Section -->
            <div class="mb-4">
                <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                    <span>Attached Files</span>
                </h5>
                
                <?php if (!empty($files)) : ?>
                    <div class="apple-table-container mb-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 apple-table">
                                <thead>
                                    <tr>
                                        <th class="form-label-sm fw-semibold" style="width: 180px;">Type</th>
                                        <th class="form-label-sm fw-semibold">File Name</th>
                                        <th class="form-label-sm fw-semibold" style="width: 180px;">Uploaded At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($files as $file) : ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary text-nowrap">
                                                    <?= esc(Constants::ATTACHMENTS[$file['type']] ?? 'Attachment') ?>
                                                </span>
                                            </td>
                                            <td class="fw-medium">
                                                <a href="<?= base_url('download.php?file=' . rawurlencode($file['file_path'])) ?>" download="<?= esc($file['file_name']) ?>" rel="noopener" class="apple-link-dark text-decoration-none d-inline-flex align-items-center gap-1" title="Download File">
                                                    <i class="bi bi-download text-primary fs-6"></i>
                                                    <?= esc($file['file_name']) ?>
                                                </a>
                                            </td>
                                            <td class="text-secondary small">
                                                <?= esc($file['created_at']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info py-2 px-3 mb-4" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle me-1"></i> No files uploaded for this request yet.
                    </div>
                <?php endif; ?>

                <!-- File Upload Card -->
                <div class="apple-card" style="border: 1px dashed var(--color-border-input); background: transparent; box-shadow: none; padding: 20px;">
                    <h6 class="fw-bold mb-3 text-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span>Upload New File</span>
                    </h6>
                    <form action="<?= site_url('tag-requests/upload') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="request_id" value="<?= esc($request['id']) ?>">
                        <input type="hidden" name="property_id" value="<?= esc($request['property_id']) ?>">
                        <input type="hidden" name="vendor_name" value="<?= esc($vendors[$request['vendor_id']]) ?>">
                        
                        <div class="row align-items-end g-3">
                            <div class="col-md-5">
                                <label for="type" class="form-label-xs text-secondary fw-semibold mb-1">File Type</label>
                                <select name="type" class="form-select form-select-xs" required>
                                    <?php foreach (Constants::ATTACHMENTS as $value => $label): ?>
                                        <option value="<?= esc($value) ?>"
                                                <?= old('type', $old['type'] ?? 1) == $value ? 'selected' : '' ?>>
                                            <?= esc($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-5">
                                <label for="file_path" class="form-label-xs text-secondary fw-semibold mb-1">Select File</label>
                                <input type="file" name="file_path" class="form-control form-control-xs" required>
                            </div>
                            
                            <div class="col-md-2">
                                <button type="submit" class="apple-btn-primary btn-sm w-100 py-1" onclick="return confirm('Confirm uploading of this file?');">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
