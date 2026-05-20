<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag Monitoring System
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\SubsidiariesModel;
    use App\Models\JiraHistoryModel;
    use App\Models\TagsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
    $propertyOptions = $propertiesModel->Options();

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaries = $subsidiariesModel->Options();

    $tagsModel = new TagsModel();
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Tag Inventory</h2>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tags/renew') ?>" target="_blank" title="Renew selected tags" class="btn btn-primary btn-sm ms-auto px-2" id="btnRenew" style="display:none;">Extend Expiry Date</a>
            <a href="<?= site_url('tags/update-rule-request') ?>" target="_blank" title="Create update rule request" class="btn btn-primary btn-sm ms-auto px-2" id="btnUpdateRule" style="display:none;">Create Update Rule Request</a>
            <a href="<?= site_url('tags/create') ?>" target="_blank" title="Add a new tag" class="btn btn-success btn-sm ms-auto px-2">Add Tag Information</a>
            <a href="<?= site_url('tags/export?' . http_build_query($_GET)) ?>" class="btn btn-sm btn-warning ms-auto px-2">Export as CSV</a>
        </div>
    </div>

    <div class="d-flex justify-content-start gap-2 form-label-xs">
        <p>Active: <span class="text-success fw-semibold"><?= $tagsModel->countActiveTags() ?></span></p>
        <p>Needs Attention: <span class="text-danger fw-semibold"><?= $tagsModel->countExpiringTags() ?></span></p>
        <p>Pending Verification: <span class="text-warning fw-semibold"><?= $tagsModel->countPendingVerificationTags() ?></span></p>
        <p>Total: <span class="text-dark fw-semibold"><?= $tagsModel->countAllTags() ?></span></p>
    </div>

    <!-- #region | SEARCH FILTER -->
    <div>
        <form method="get" class="row g-3 mb-3">
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
                <select name="status" class="form-select form-select-xs">
                    <option value="<?= Constants::STATUS_ALL ?>">All Status</option>
                    <?php foreach (Constants::STATUS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-primary w-100">Filter</button>
                <a href="<?= site_url('tags') ?>" class="btn btn-sm btn-secondary w-100">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" id="tblTags">
            <thead class="table-active">
                <tr>
                    <th class="form-label-sm fw-semibold">#</th>
                    <th></th>
                    <th class="form-label-sm fw-semibold">Subsidiary</th>
                    <th class="form-label-sm fw-semibold">Property</th>
                    <th class="form-label-sm fw-semibold">Name</th>
                    <th class="form-label-sm fw-semibold">Status</th>
                    <th class="form-label-sm fw-semibold">Days</th>
                    <th class="form-label-sm fw-semibold">End Date</th>
                    <th class="form-label-sm fw-semibold">Updated</th>
                    <th class="form-label-sm fw-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($tags as $tag): 
                    $ticketId = (new JiraHistoryModel())->getLatestTag($tag['id'])['ticket_id'] ?? null;
                    $subsidiaryId = $properties[$tag['property_id']]['subsidiary_id'];
                    $subsidiary = $subsidiaries[$subsidiaryId];
                ?>
                    <tr>
                        <!-- ID -->
                        <td><?= $tag['id'] ?></td>
                        <!-- Checkbox -->
                        <td>
                            <input type="checkbox"
                                name="tag_ids[]"
                                value="<?= htmlspecialchars($tag['id']) ?>">
                        </td>
                        <!-- Subsidiary -->
                        <td>
                            <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                <?= esc($subsidiary) ?>
                            </span>
                        </td>
                        <!-- Property -->
                        <td>
                            <i class="bi bi-tag-fill" style="<?= random_text_style($tag['property_id']) ?>"></i>
                            <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" target="_blank" class="form-label-sm">
                                <?= $properties[$tag['property_id']]['name'] ?>
                            </a>
                        </td>
                        <!-- Name -->
                        <td class="form-label-sm">
                            <div class="d-flex justify-content-between align-items-center gap-1">
                                <a href="<?= $tag['ref_link'] ?>" target="_blank">
                                    <?= esc($tag['name']) ?>
                                </a>

                                <!-- Copy button -->
                                <a href="javascript:void(0)" onclick="copyTagName('<?= esc($tag['name']) ?>', this)" title="Copy tag name">
                                    <i class="bi bi-copy text-secondary fs-7"></i>
                                </a>
                            </div>
                        </td>
                        <!-- Status -->
                        <td>
                            <span class="badge <?= status_class($tag['status']) ?>">
                                <?= esc(status_label($tag['status'])) ?>
                            </span>
                            <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                <a href="<?= Constants::JIRA_LINK . $ticketId ?>" target="_blank">
                                   <br><small class="text-muted"><?= $ticketId ?></small> 
                                </a>
                            <?php endif; ?>
                        </td>
                        <!-- Days -->
                        <?php
                            $alEndDate = strtotime($tag['al_end_date']);
                            $now = time();
                            $diffDays = ceil(($alEndDate - $now) / 86400);
                        ?>
                        <td class="<?= days_class($diffDays) ?> form-label-sm">
                            <?= esc($diffDays) ?>
                        </td>
                        <!-- End Date -->
                        <td class="<?= date_class($tag['al_end_date'], $tag['status']) ?> form-label-sm" style="width:max-content; white-space:nowrap;">
                            <?= esc(date('d-m-Y', strtotime($tag['al_end_date']))) ?>
                            <?php if ($tag['requested_date']) : ?>
                                <br><small class="text-muted">[<?= esc(date('d-m-Y', strtotime($tag['requested_date']))) ?>]</small>
                            <?php endif; ?>
                        </td>
                        <!-- Updated At -->
                        <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($tag['updated_at']))) ?></td>
                        <!-- <td><?= esc($tag['updated_by']) ?></td> -->
                        <!-- Actions -->
                        <td class="text-nowrap">
                            <a href="<?= site_url('tags/view/'.$tag['id']) ?>" target="_blank" title="View tag details" class="btn btn-secondary btn-sm">View</a>
                            <a href="<?= site_url('tags/update/'.$tag['id']) ?>" target="_blank" title="Update tag details" class="btn btn-primary btn-sm">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Properties",
        allowClear: true
    });

    const btnRenew = document.getElementById('btnRenew');
    const btnUpdateRule = document.getElementById('btnUpdateRule');
    const table = document.getElementById('tblTags');

    const toggleButton = () => {
        const anyChecked = table.querySelectorAll('input[type="checkbox"]:checked').length > 0;
        btnRenew.style.display = anyChecked ? 'inline-block' : 'none';
        btnUpdateRule.style.display = anyChecked ? 'inline-block' : 'none';
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

    btnUpdateRule.addEventListener('click', function (e) {
        e.preventDefault();

        const checked = [...document.querySelectorAll('input[name="tag_ids[]"]:checked')]
            .map(cb => cb.value);

        if (checked.length === 0) return;

        const url = "<?= site_url('tags/update-rule-request') ?>?tags=" + checked.join(',');
        window.open(url, "_blank");
    });

    window.copyTagName = function(text, el) {
        const temp = document.createElement('textarea');
        temp.value = text;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';

        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');

        document.body.removeChild(temp);

        el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';

        setTimeout(() => {
            el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
        }, 1200);
    };
});

</script>

<?= $this->endSection() ?>
