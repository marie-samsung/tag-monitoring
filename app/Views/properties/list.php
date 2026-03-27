<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Properties List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\SubsidiariesModel;

    $subsidiariesModel = new SubsidiariesModel();
    $subsidiaryOptions = $subsidiariesModel->Options();

    helper('custom_functions');
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Property List</h2>
    <div>
        <a href="<?= site_url('properties/create') ?>" target="_blank" title="Add a new property" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
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
                <select name="subsidiary_id" class="form-select">
                    <option value="">All Subsidiaries</option>
                    <?php foreach ($subsidiaryOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $filters['subsidiary_id'] ?? null) == $value ? 'selected' : '' ?>>
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
                    <th>Subsidiary</th>
                    <th>Property</th>
                    <th>Is EPP?</th>
                    <th>Is Shop?</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?= $property['id'] ?></td>
                        <td><?= $subsidiaryOptions[$property['subsidiary_id']] ?? 'Unknown' ?></td>
                        <td><?= $property['name'] ?></td>
                        <td>
                            <span class="badge <?= bool_class($property['is_epp']) ?>">
                                <?= esc(bool_label($property['is_epp'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= bool_class($property['is_shop']) ?>">
                                <?= esc(bool_label($property['is_shop'])) ?>
                            </span>
                        </td>
                        <td class="gap-2">
                            <a href="<?= site_url('properties/view/'.$property['id']) ?>" target="_blank" title="View property details" class="btn btn-secondary btn-sm">View</a>
                            <a href="<?= site_url('properties/update/'.$property['id']) ?>" target="_blank" title="Update property details" class="btn btn-primary btn-sm">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
