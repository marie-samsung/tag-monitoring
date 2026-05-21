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
            <a href="<?= site_url('tags/renew') ?>" title="Renew selected tags" class="apple-btn-primary btn-sm ms-auto px-3" id="btnRenew" style="display:none;">Renew</a>
            <a href="<?= site_url('tags/create') ?>" title="Add a new tag" class="apple-btn-primary btn-sm ms-auto px-3">Create New Tag</a>
            <a href="<?= site_url('tags/export?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm ms-auto px-3">Export as CSV</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="apple-metric-card" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=' . Constants::STATUS_ENABLED) ?>'">
                <div class="apple-metric-label"><i class="bi bi-check-circle-fill text-success"></i> Active</div>
                <div class="apple-metric-value text-success"><?= $tagsModel->countActiveTags() ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="apple-metric-card" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=expiring') ?>'">
                <div class="apple-metric-label"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Needs Attention</div>
                <div class="apple-metric-value text-danger"><?= $tagsModel->countExpiringTags() ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="apple-metric-card" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=' . Constants::STATUS_PENDING_VERIFICATION) ?>'">
                <div class="apple-metric-label"><i class="bi bi-hourglass-split text-warning"></i> Pending</div>
                <div class="apple-metric-value text-warning"><?= $tagsModel->countPendingVerificationTags() ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="apple-metric-card" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=all') ?>'">
                <div class="apple-metric-label"><i class="bi bi-collection-fill text-secondary"></i> Total</div>
                <div class="apple-metric-value text-dark"><?= $tagsModel->countAllTags() ?></div>
            </div>
        </div>
    </div>

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
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('tags') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblTags" style="border-collapse: separate; border-spacing: 0; min-width: 100%;">
            <thead>
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
                            <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" class="apple-link-dark form-label-sm ms-1">
                                <?= $properties[$tag['property_id']]['name'] ?>
                            </a>
                        </td>
                        <!-- Name -->
                        <td class="form-label-sm">
                            <span class="group-hover-container align-items-center">
                                <a href="<?= $tag['ref_link'] ?>" class="apple-link-dark">
                                    <?= esc($tag['name']) ?>
                                </a>
                                <a href="javascript:void(0)" onclick="copyTagName('<?= esc($tag['name']) ?>', this)" title="Copy tag name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                    <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                </a>
                            </span>
                        </td>
                        <!-- Status -->
                        <td>
                            <span class="badge <?= status_class($tag['status']) ?>">
                                <?= esc(status_label($tag['status'])) ?>
                            </span>
                            <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                <br>
                                <a href="<?= Constants::JIRA_LINK . $ticketId ?>" class="apple-link ms-1" style="font-size: 0.8rem;">
                                    <?= $ticketId ?>
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
                            <a href="<?= site_url('tags/view/'.$tag['id']) ?>" title="View tag details" class="apple-btn-secondary btn-sm text-decoration-none">View</a>
                            <a href="<?= site_url('tags/update/'.$tag['id']) ?>" title="Update tag details" class="apple-btn-primary btn-sm text-decoration-none">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
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
        window.location.href = url;
    });

    window.copyTagName = function(text, el) {
        const onSuccess = () => {
            el.innerHTML = '<i class="bi bi-check-lg text-success" style="font-size: 0.85rem;"></i>';
            setTimeout(() => {
                el.innerHTML = '<i class="bi bi-copy" style="font-size: 0.85rem;"></i>';
            }, 1200);
        };

        // Modern approach (requires HTTPS or localhost)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(onSuccess).catch(err => {
                console.error("Failed to copy text: ", err);
            });
        } else {
            // Fallback for non-HTTPS local domains (like http://tag-monitoring)
            const textArea = document.createElement("textarea");
            textArea.value = text;
            // Prevent scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) onSuccess();
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }

            document.body.removeChild(textArea);
        }
    };
});

</script>

<?= $this->endSection() ?>
