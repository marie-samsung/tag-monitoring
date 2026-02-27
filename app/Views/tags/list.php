<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Tags</h2>
    <div>
        <a href="<?= site_url('tags/create') ?>" target="_blank" title="Renew selected tags" class="btn btn-primary btn-sm ms-auto px-2" id="btnRenew" style="display:none;">Renew</a>
        <a href="<?= site_url('tags/create') ?>" target="_blank" title="Add a new tag" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
    </div>
</div>

<?= csrf_field() ?>

<!-- <form method="get" class="row g-3 mb-4">

    <div class="col-md-3">
        <input 
            type="text" 
            name="keyword" 
            class="form-control" 
            placeholder="Search keyword..."
            value="<?= esc($_GET['keyword'] ?? '') ?>"
        >
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="1"   <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div class="col-md-2">
        <input 
            type="text" 
            name="property_id" 
            class="form-control" 
            placeholder="Property ID"
            value="<?= esc($_GET['property_id'] ?? '') ?>"
        >
    </div>

    <div class="col-md-2">
        <input 
            type="date" 
            name="date_from" 
            class="form-control"
            value="<?= esc($_GET['date_from'] ?? '') ?>"
        >
    </div>

    <div class="col-md-2">
        <input 
            type="date" 
            name="date_to" 
            class="form-control"
            value="<?= esc($_GET['date_to'] ?? '') ?>"
        >
    </div>

    <div class="col-md-1">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

</form> -->

<?php
    use App\Models\PropertiesModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();
?>

<div class="container">
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
                            <a href="<?= site_url('tags/update/'.$tag['id']) ?>" target="_blank" title="View tag details" class="btn btn-primary btn-sm">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
