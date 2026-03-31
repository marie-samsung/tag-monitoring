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
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
    $propertyOptions = $propertiesModel->Options();

    $subsidiaryModel = new SubsidiariesModel();
    $subsidiaries = $subsidiaryModel->Options();
?>

<div class="container-fluid d-flex justify-content-between">
    <h2>Tag Inventory</h2>
    <div>
        <a href="<?= site_url('tags/renew') ?>" target="_blank" title="Renew selected tags" class="btn btn-primary btn-sm ms-auto px-2" id="btnRenew" style="display:none;">Renew</a>
        <a href="<?= site_url('tags/create') ?>" target="_blank" title="Add a new tag" class="btn btn-success btn-sm ms-auto px-2">Create New Tag</a>
    </div>
</div>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- #region | SEARCH FILTER -->
    <div class="row">
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
                    <option value="">All Status</option>
                    <?php foreach (Constants::STATUS as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('status', $filters['status'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" id="tblTags">
            <thead class="table-active">
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>Subsidiary</th>
                    <th>Property</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Days</th>
                    <th>End Date</th>
                    <th>Updated</th>
                    <!-- <th>Updated By</th> -->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($tags as $tag): 
                    $ticketId = (new JiraHistoryModel())->getLatest($tag['id'])['ticket_id'] ?? null;
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
                            <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" target="_blank" class="form-label-s">
                                <?= $properties[$tag['property_id']]['name'] ?>
                            </a>
                        </td>
                        <!-- Name -->
                        <td class="form-label-s">
                            <a href="<?= $tag['ref_link'] ?>" target="_blank">
                                <?= esc($tag['name']) ?>
                            </a>
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
                        <td class="<?= days_class($diffDays) ?> form-label-s">
                            <?= esc($diffDays) ?>
                        </td>
                        <!-- End Date -->
                        <td class="<?= date_class($tag['al_end_date'], $tag['status']) ?> form-label-s" style="width:max-content; white-space:nowrap;">
                            <?= esc(date('d-m-Y', strtotime($tag['al_end_date']))) ?>
                            <?php if ($tag['requested_date']) : ?>
                                <br><small class="text-muted">[<?= esc(date('d-m-Y', strtotime($tag['requested_date']))) ?>]</small>
                            <?php endif; ?>
                        </td>
                        <!-- Updated At -->
                        <td class="form-label-s" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($tag['updated_at']))) ?></td>
                        <!-- <td><?= esc($tag['updated_by']) ?></td> -->
                        <!-- Actions -->
                        <td class="d-flex gap-2">
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

});

</script>

<?= $this->endSection() ?>
