<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag: Requests (New Rule, New Tag)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\SubsidiariesModel;
    use App\Models\TagRequestsModel;
    use App\Models\VendorsModel;
    use App\Models\JiraHistoryModel;
    use App\Models\TagsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
    $propertyOptions = $propertiesModel->Options();

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaries = $subsidiariesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $tagsModel = new TagsModel();

    $tagRequestsModel = new TagRequestsModel();

    $newTagRequestsCount = 0;
    $newRuleRequestsCount = 0;
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Tag Requests</h2>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tag-requests/upload-excel') ?>" title="Upload Excel" class="apple-btn-primary btn-sm ms-auto px-2">Create New Request</a>
            <!-- <a href="<?= site_url('tag-requests/create') ?>" title="Create a new request" class="apple-btn-primary btn-sm ms-auto px-2">Add Request</a> -->
            <a href="<?= site_url('tag-requests/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm ms-auto px-2">Export as CSV</a>
        </div>
    </div>

    <!-- <div class="d-flex justify-content-start gap-2 form-label-xs">
        <p>Active: <span class="text-success fw-semibold"><?= $tagsModel->countActiveTags() ?></span></p>
        <p>Expiring: <span class="text-danger fw-semibold"><?= $tagsModel->countExpiringTags() ?></span></p>
        <p>Pending Verification: <span class="text-warning fw-semibold"><?= $tagsModel->countPendingVerificationTags() ?></span></p>
        <p>Total: <span class="text-dark fw-semibold"><?= $tagsModel->countAllTags() ?></span></p>
    </div> -->

    <!-- #region | SEARCH FILTER -->
    <div class="apple-control-bar mb-4">
        <form method="get" class="row g-2 w-100 m-0">
            <div class="col">
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control form-select-xs" 
                    placeholder="Search..."
                    value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col">
                <select name="subsidiary_id" class="form-select form-select-xs">
                    <option value="">All Subsidiaries</option>
                    <?php foreach ($subsidiaries as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $filters['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="property_id" class="form-select searchable-select">
                    <option value="">All Properties</option>
                    <?php foreach ($propertyOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('property_id', $filters['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="vendor_id" class="form-select form-select-xs">
                    <option value="">All Vendors</option>
                    <?php foreach ($vendors as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                                <?= old('vendor_id', $filters['vendor_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="type" class="form-select form-select-xs">
                    <option value="">All Types</option>
                    <?php foreach (Constants::REQUEST_TYPES as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('type', $filters['type'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label . ' Request') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="status" class="form-select form-select-xs">
                    <option value="">All Status</option>
                    <option value="pending" <?= old('status', $filters['status'] ?? null) === 'pending' ? 'selected' : '' ?>>Pending Requests</option>
                    <?php foreach (Constants::STATUS_REQUESTS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('tag-requests') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblTags">
            <thead>
                <tr>
                    <th class="form-label-sm fw-semibold">#</th>
                    <th class="form-label-sm fw-semibold">Subsidiary</th>
                    <th class="form-label-sm fw-semibold">Property</th>
                    <th class="form-label-sm fw-semibold">Ticket ID</th>
                    <th class="form-label-sm fw-semibold">Type</th>
                    <th class="form-label-sm fw-semibold">Status</th>
                    <th class="form-label-sm fw-semibold">Created At</th>
                    <th class="form-label-sm fw-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($requests as $request):
                        $subsidiary = Constants::SUBSIDIARIES[$properties[$request['property_id']]['subsidiary_id']];
                        $property = $properties[$request['property_id']];
                        $type = $request['type'];
                        $ticketColumn = 'new_tag_ticket';

                        if ($type == Constants::JIRA_TYPE_NEW_RULE) {
                            $newRuleRequestsCount++;
                            $ticketColumn = 'new_rule_ticket';
                        } else {
                            $newTagRequestsCount++;
                        }
                ?>
                    <tr>
                        <!-- ID -->
                        <td><?= $request['id'] ?></td>
                        <!-- Subsidiary -->
                        <td>
                            <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                <?= esc($subsidiary) ?>
                            </span>
                        </td>
                        <!-- Property -->
                        <td>
                            <i class="bi bi-tag-fill" style="<?= random_text_style($property['name']) ?>"></i>
                            <a href="<?= $property['ref_link'] ?>" class="apple-link-dark form-label-sm ms-1">
                                <?= $property['name'] ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Constants::JIRA_LINK . $request[$ticketColumn] ?>" class="apple-link">
                                <?= $request[$ticketColumn] ?>
                            </a>
                            <span class="text-muted ms-1" style="font-size: 0.8rem;">(<?= $vendors[$request['vendor_id']] ?? '' ?>)</span>
                            <?php if ($type == Constants::JIRA_TYPE_NEW_TAG && !empty($request['new_rule_ticket'])) : ?>
                                <br/>
                                <small class="text-muted" style="font-size: 0.75rem;">New Rule:</small>
                                <a href="<?= Constants::JIRA_LINK . $request['new_rule_ticket'] ?>" class="apple-link" style="font-size: 0.85rem;">
                                    <?= $request['new_rule_ticket'] ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= Constants::REQUEST_TYPES[$type] ?>
                        </td>
                        <td>
                            <span class="badge <?= status_request_class($request['status']) ?>">
                                <?= Constants::STATUS_REQUESTS[$request['status']] ?>
                            </span>
                        </td>
                        <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($request['created_at']))) ?></td>
                        <!-- Actions -->
                        <td class="text-nowrap">
                            <a href="<?= site_url('tag-requests/view/'.$request['id']) ?>" title="View tag details" class="apple-btn-secondary btn-sm">View</a>
<!--                            <a href="--><?php //= site_url('tag-requests/update/'.$request['id']) ?><!--" title="Update tag details" class="apple-btn-primary btn-sm">Update</a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div class="mt-4 d-flex justify-content-center">
                <?= $pager->links('default', 'apple_full') ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- #endregion -->

<!--    <div class="d-flex justify-content-start gap-2 form-label-xs">-->
        <!--        <p>New Tag Requests: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countNewTags() ?><!--</span></p>-->
        <!--        <p>New Rule Requests: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countNewRules() ?><!--</span></p>-->
        <!--        <p>Total: <span class="text-dark fw-semibold">--><?php //= $tagRequestsModel->countAllRequests() ?><!--</span></p>-->

<!--        <p>New Tag Requests: <span class="text-dark fw-semibold">--><?php //= $newTagRequestsCount ?><!--</span></p>-->
<!--        <p>New Rule Requests: <span class="text-dark fw-semibold">--><?php //= $newRuleRequestsCount ?><!--</span></p>-->
<!--        <p>Total: <span class="text-dark fw-semibold">--><?php //= $newTagRequestsCount + $newRuleRequestsCount ?><!--</span></p>-->
<!--    </div>-->
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });

    const btnRenew = document.getElementById('btnRenew');
    const table = document.getElementById('tblTags');

    const toggleButton = () => {
        const anyChecked = table.querySelectorAll('input[type="checkbox"]:checked').length > 0;
        btnRenew.style.display = anyChecked ? 'inline-block' : 'none';
    };

    table.addEventListener('change', function (e) {
        if (e.target && e.target.type === 'checkbox') {
            toggleButton();
        }
    });

    toggleButton();

    btnRenew.addEventListener('click', function (e) {
        e.preventDefault();

        const checked = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .map(cb => cb.value);

        if (checked.length === 0) return;

        const url = "<?= site_url('tags/renew') ?>?tags=" + checked.join(',');
        window.open(url, "_blank");
    });

    window.copyTagName = function(text, el) {
        navigator.clipboard.writeText(text).then(() => {
            el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';
            setTimeout(() => {
                el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
            }, 1200);
        });
    };
});

</script>

<?= $this->endSection() ?>
