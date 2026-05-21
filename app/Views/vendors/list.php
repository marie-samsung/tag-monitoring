<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Vendors List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\VendorsModel;
    use App\Models\Constants;

    $vendorsModel = new VendorsModel();
    $vendorOptions = $vendorsModel->Options();

    helper('custom_functions');
?>

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between">
        <h2>Vendors</h2>
        <!-- <div>
            <a href="<?= site_url('vendors/create') ?>" title="Add a new vendor" class="apple-btn-primary btn-sm ms-auto px-2">Create New</a>
        </div> -->
    </div>

    <!-- #region | SEARCH FILTER -->
    <div class="apple-control-bar mb-4">
        <form method="get" class="row g-2 w-100 m-0">

            <div class="col">
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control form-control-xs" 
                    placeholder="Search..."
                    value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col">
                <select name="id" class="form-select form-select-xs">
                    <option value="">All Vendors</option>
                    <?php foreach ($vendorOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('id', $filters['id'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('vendors') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
            </div>
        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | PROPERTIES TABLE -->
    <div class="apple-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tblProperties">
                <thead>
                    <tr>
                        <th class="form-label-sm fw-semibold">#</th>
                        <th class="form-label-sm fw-semibold">Vendor</th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SGE] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SESAR] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SETK] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEIL] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEILPS] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SELV] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEEG] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEMAG] ?></th>
                        <th class="form-label-sm fw-semibold"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEPAK] ?></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendors as $vendor): ?>
                        <tr>
                            <td><?= $vendor['id'] ?></td>
                            <td class="col-3 fw-medium"><?= $vendor['name'] ?></td>

                            <?php foreach (Constants::SUBSIDIARIES as $id => $sub): ?>
                                <?php $count = $vendorsModel->getTags($vendor['id'], $id); ?>

                                <?php if ($count > 0): ?>
                                    <td>
                                        <a href="<?= site_url('vendors/view/'.$vendor['id'].'?subsidiary_id='.$id) ?>" class="text-decoration-none fw-semibold" style="color: #0071E3;">
                                            <?= $count ?>
                                        </a>
                                    </td>
                                <?php else: ?>
                                    <td class="text-muted"><?= "-" ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
