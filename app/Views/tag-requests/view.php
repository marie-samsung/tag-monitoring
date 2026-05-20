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

<div class="container">
    <h6 class="text-danger fw-semibold">
        <?= Constants::SUBSIDIARIES[$properties[$request['property_id']]['subsidiary_id']] ?>
    </h6>
    <h3>
        <?php if ($hasNewTag): ?>
            <a href="<?= Constants::JIRA_LINK . $request['new_tag_ticket'] ?>" target="_blank">
                [<?= $request['new_tag_ticket'] ?>]
            </a>
        <?php elseif ($hasNewRule): ?>
            <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" target="_blank">
                [<?= $request['new_rule_ticket'] ?>]
            </a>
        <?php endif; ?>
        [<?= $vendors[$request['vendor_id']] ?>]
        <?= Constants::JIRA_TICKET_TYPES[$request['type']] ?>
    </h3>
</div>

<div class="container d-flex my-3 gap-3">
    <!-- #region | REQUEST DETAILS -->
    <div class="w-25 p-4 card">
        <div class="mb-2">
            <label class="form-label-xs text-primary">Property</label>
            <div class="form-control-plaintext p-0 ms-2">
                <a href="<?= esc($properties[$request['property_id']]['ref_link'] ?? '#') ?>" target="_blank">
                    <?= esc($properties[$request['property_id']]['name'] ?? '—') ?>
                </a>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Vendor</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($vendors[$request['vendor_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Status</label>
            <div class="d-flex gap-2">
                <div class="form-control-plaintext p-0 ms-2 align-items-center">
                    <span class="badge <?= status_request_class($request['status']) ?>">
                        <?= esc(Constants::STATUS_REQUESTS[$request['status']] ?? 'Unknown') ?>
                    </span>

                    <?php if ($request['status'] == Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED ||
                            ($request['status'] == Constants::STATUS_REQUEST_HQ_REVIEW && $request['type'] == Constants::JIRA_TYPE_NEW_RULE)): ?>
                        <a href="<?= site_url('tag-requests/request-approved/' . $request['id']) ?>"
                           class="badge bg-success text-decoration-none"
                           onclick="return confirm('Confirm that the tags are published live? (This will set tags to ENABLED status)');">
                            Published
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($request['type'] == Constants::JIRA_TYPE_NEW_TAG): ?>
            <?php if (!$hasNewTag): ?>
                <div class="mb-2">
                    <form action="<?= site_url('tag-requests/add-new-tag') ?>" method="post">
                        <label class="form-label-xs text-primary">New Tag Request</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="request_id" value="<?= esc($request['id'] ?? '') ?>">
                            <input type="text" name="new_tag_ticket" id="new_tag_ticket"
                                   class="form-control form-control-xs w-50"
                                   value="<?= old('new_tag_ticket', $old['new_tag_ticket'] ?? '') ?>"
                                   required>
                            <button type="submit"
                                    class="badge bg-primary text-decoration-none border-0"
                                    onclick="return confirm('Confirm add New Tag ticket ID?');">
                                Add Ticket
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="mb-2">
                <label class="form-label-xs text-primary">New Rule Request</label>
                <div class="mb-2">
                <?php if (!$hasNewRule): ?>
                    <form action="<?= site_url('tag-requests/add-new-rule') ?>" method="post">
                        <div class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="request_id" value="<?= esc($request['id'] ?? '') ?>">
                            <input type="text" name="new_rule_ticket" id="new_rule_ticket"
                                   class="form-control form-control-xs w-50"
                                   value="<?= old('new_rule_ticket', $old['new_rule_ticket'] ?? '') ?>"
                                   required>
                            <button type="submit"
                                    class="badge bg-primary text-decoration-none border-0"
                                    onclick="return confirm('Confirm add New Rule ticket ID?');">
                                Add Ticket
                            </button>
                        </div>
                    </form>

                <?php else: ?>
                    <div class="form-control-plaintext p-0 ms-2 align-items-center">
                        <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" target="_blank">
                            <?= esc($request['new_rule_ticket']) ?>
                        </a>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <label class="form-label m-0 fw-semibold mt-2">Requester Details</label>
        <div class="mb-2">
            <label class="form-label-xs text-primary">Name</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($request['requester_name'] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Email Address</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($request['requester_email'] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs text-primary">Organization</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($organizations[$request['requester_organization']] ?? '—') ?>
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-center gap-1">
            <a href="<?= site_url('tag-requests') ?>" class="apple-btn-secondary btn-sm px-2">Back</a>
<!--            <a href="--><?php //= site_url('tag-requests/update/' . $request['id']) ?><!--" class="apple-btn-primary btn-sm">Edit</a>-->
        </div>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS LIST & FILES -->
    <div class="w-75">
        <div class="card p-4">
            <h4>Tag(s)</h4>

            <table class="table table-hover align-middle" style="border-collapse: separate; border-spacing: 0; min-width: 100%;">
                <tbody>
                    <?php foreach ($tags as $tag) : ?>
                        <tr>
                            <td>
                                <a href="<?= site_url('tags/view/' . $tag['id']) ?>" target="_blank">
                                    <?= $tag['name'] ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card p-4 mt-3">
            <div class="d-flex gap-2">
                <h4>File(s)</h4>
                <i class="bi bi-info-circle-fill text-primary" title="Clicking the name will download the file"></i>
            </div>

            <?php if (!empty($files)) : ?>
            <table class="table table-bordered file-table">
                <thead>
                    <tr>
                        <th>File Type</th>
                        <th>File Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file) : ?>
                        <tr>
                            <td><?= Constants::ATTACHMENTS[$file['type']] ?></td>
                            <td>
                                <a href="<?= '/tag-monitoring/download.php?file=' . rawurlencode($file['file_path']) ?>" download="<?= $file['file_name'] ?>" rel="noopener">
                                    <?= $file['file_name'] ?>
                                </a>
                            </td>
<!--                            <td class="text-center">-->
<!--                                <a href="--><?php //= '/tag-monitoring/download.php?file=' . rawurlencode($file['file_path']) ?><!--" download="--><?php //= $file['file_name'] ?><!--" class="btn btn-sm btn-primary">-->
<!--                                    Download-->
<!--                                </a>-->
<!--                            </td>-->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>
                <span class="alert alert-info">No file available for this request.</span>
            <?php endif; ?>

            <hr/>

            <form action="<?= site_url('tag-requests/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="request_id" value="<?= esc($request['id']) ?>">
                <input type="hidden" name="property_id" value="<?= esc($request['property_id']) ?>">
                <input type="hidden" name="vendor_name" value="<?= esc($vendors[$request['vendor_id']]) ?>">
                <div class="d-flex gap-3 align-items-center">
                    <div class="col-6">
                        <label for="type" class="form-label-xs text-primary">File Type</label>
                        <select name="type" class="form-select" required>
                            <?php foreach (Constants::ATTACHMENTS as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                        <?= old('type', $old['type'] ?? 1) == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

<!--                    <div class="col">-->
<!--                        <label for="file_name" class="form-label-xs text-primary">File Name</label>-->
<!--                        <input type="text" name="file_name" id="file_name"-->
<!--                               class="form-control"-->
<!--                               value="--><?php //= old('file_name', $old['file_name'] ?? '') ?><!--"-->
<!--                               required>-->
<!--                    </div>-->

                    <div class="col-4">
                        <label for="file_path" class="form-label-xs text-primary">Upload File</label>
                        <br />
                        <input type="file" name="file_path" required>
                    </div>

                    <div class="col mt-auto d-flex flex-column">
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm uploading of this file?');">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
