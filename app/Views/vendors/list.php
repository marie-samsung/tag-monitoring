<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\VendorsModel;

    $vendorsModel = new VendorsModel();
    $vendorOptions = $vendorsModel->Options();

    helper('custom_functions');
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Vendor List</h2>
    <div>
        <a href="<?= site_url('vendors/create') ?>" target="_blank" title="Add a new vendor" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
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
                <select name="id" class="form-select">
                    <option value="">All Vendors</option>
                    <?php foreach ($vendorOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('id', $filters['id'] ?? null) == $value ? 'selected' : '' ?>>
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

    <!-- #region | PROPERTIES TABLE -->
    <div class="table-responsive">
        <table class="table table-hover" id="tblProperties">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th>About</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?= $vendor['id'] ?></td>
                        <td><?= $vendor['name'] ?></td>
                        <td><?= $vendor['about'] ?></td>
                        <td class="gap-2">
                            <a href="<?= site_url('vendors/view/'.$vendor['id']) ?>" target="_blank" title="View vendor details" class="btn btn-secondary btn-sm">View</a>
                            <a href="<?= site_url('vendors/update/'.$vendor['id']) ?>" target="_blank" title="Update vendor details" class="btn btn-primary btn-sm">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
