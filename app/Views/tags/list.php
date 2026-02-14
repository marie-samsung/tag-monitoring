<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h2 class="mb-3">Tags</h2>

<form method="get" class="row g-3 mb-4">

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

</form>


<!-- Table -->
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="tagsTable">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Property ID</th>
                <th>Status</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tags as $tag): ?>
                <tr>
                    <td><?= esc($tag['name']) ?></td>
                    <td><?= esc($tag['property_id']) ?></td>
                    <td><?= esc($tag['status']) ?></td>
                    <td><?= esc($tag['al_end_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Simple JS Filter -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tagsTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?= $this->endSection() ?>