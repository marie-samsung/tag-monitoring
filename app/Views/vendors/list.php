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
            <a href="<?= site_url('vendors/create') ?>" target="_blank" title="Add a new vendor" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
        </div> -->
    </div>

    <!-- #region | SEARCH FILTER -->
    <div class="mb-2">
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

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="btn btn-primary w-100">Filter</button>
                <a href="<?= site_url('vendors') ?>" class="btn btn-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | PROPERTIES TABLE -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped" id="tblProperties">
            <thead class="table-active">
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SGE] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SESAR] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SETK] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEIL] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEILPS] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SELV] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEEG] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEMAG] ?></th>
                    <th><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEPAK] ?></th>
                    <!-- <th></th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?= $vendor['id'] ?></td>
                        <td class="col-3"><?= $vendor['name'] ?></td>

                        <?php foreach (Constants::SUBSIDIARIES as $id => $sub): ?>
                            <?php $count = $vendorsModel->getTags($vendor['id'], $id); ?>

                            <?php if ($count > 0): ?>
                                <td>
                                    <a href="<?= site_url('vendors/view/'.$vendor['id'].'?subsidiary_id='.$id) ?>" target="_blank" style="text-decoration: none;">
                                        <?= $count ?>
                                    </a>
                                </td>
                            <?php else: ?>
                                <td><?= "-" ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
