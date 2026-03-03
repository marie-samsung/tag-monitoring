<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Tag Inventory</h2>
    <div>
        <a href="<?= site_url('tags/create') ?>" target="_blank" title="Renew selected tags" class="btn btn-primary btn-sm ms-auto px-2" id="btnRenew" style="display:none;">Renew</a>
        <a href="<?= site_url('tags/create') ?>" target="_blank" title="Add a new tag" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
    </div>
</div>

<?= csrf_field() ?>

<div class="container">
    <!-- #region | SEARCH FILTER -->
    <div class="row">
        <form method="get" class="row g-3 mb-4">

            <div class="col">
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control" 
                    placeholder="Search..."
                    value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col">
                <select name="property_id" class="form-select searchable-select">
                    <option value="">All Properties</option>
                    <?php foreach ($properties as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('property_id', $filters['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name="status" class="form-select">
                    <option value="">All</option>
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
        <table class="table table-hover" id="tblTags">
            <thead>
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>Name</th>
                    <th>Property</th>
                    <th>Status</th>
                    <th>End Date</th>
                    <th>Updated</th>
                    <!-- <th>Updated By</th> -->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td><?= $tag['id'] ?></td>
                        <td>
                            <input type="checkbox"
                                name="tag_ids[]"
                                value="<?= htmlspecialchars($tag['id']) ?>">
                        </td>
                        <td class="col-4"><?= esc($tag['name']) ?></td>
                        <td><?= $properties[$tag['property_id']] ?></td>
                        <td>
                            <span class="badge <?= status_class($tag['status']) ?>">
                                <?= esc(status_label($tag['status'])) ?>
                            </span>
                        </td>
                        <td><?= esc($tag['al_end_date']) ?></td>
                        <td><?= esc($tag['updated_at']) ?></td>
                        <!-- <td><?= esc($tag['updated_by']) ?></td> -->
                        <td class="gap-2">
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
/** document.getElementById('searchInput').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tagsTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}); **/

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
});

</script>

<?= $this->endSection() ?>
