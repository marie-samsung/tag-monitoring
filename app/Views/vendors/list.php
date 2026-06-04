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

<style>
    /* Premium Table Design Only */
    .premium-table-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .premium-table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    .premium-table thead {
        background: linear-gradient(90deg, #f8f9fa 0%, #f1f3f5 100%);
    }

    .premium-table th {
        font-weight: 600 !important;
        color: #49494D !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 14px 12px !important;
        border-bottom: 2px solid rgba(0,0,0,0.03) !important;
        border-top: none !important;
    }

    .premium-table td {
        padding: 14px 12px !important;
        vertical-align: middle;
        color: #1D1D1F;
        border-bottom: 1px solid rgba(0,0,0,0.02) !important;
        border-top: none !important;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .premium-table tbody tr {
        transition: all 0.3s ease;
    }

    .premium-table tbody tr:hover {
        background: rgba(0, 113, 227, 0.02) !important;
        transform: scale(1.001);
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        position: relative;
        z-index: 10;
    }
    
    .premium-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badge/Count Style */
    .premium-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0071E3;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 2px 4px rgba(0, 113, 227, 0.15);
    }

    .premium-count-badge:hover {
        background: linear-gradient(135deg, #0071E3 0%, #005bb5 100%);
        color: #ffffff !important;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 10px rgba(0, 113, 227, 0.3);
    }

    .premium-count-empty {
        color: #adb5bd;
        font-weight: 300;
    }

    .vendor-name {
        font-size: 0.95rem;
        font-weight: 500;
        color: #1e3c72;
    }
</style>

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
    <div class="premium-table-wrapper">
        <div class="table-responsive">
            <table class="table premium-table" id="tblProperties">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Vendor</th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SGE] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SESAR] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SETK] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEIL] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEILPS] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SELV] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEEG] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEMAG] ?></th>
                        <th class="text-center"><?= Constants::SUBSIDIARIES[Constants::SUBSIDIARY_SEPAK] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vendors)): ?>
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted fw-medium">
                                No vendors found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vendors as $vendor): ?>
                            <tr>
                                <td class="text-center text-muted fw-medium">#<?= $vendor['id'] ?></td>
                                <td class="vendor-name fw-medium"><?= esc($vendor['name']) ?></td>

                                <?php foreach (Constants::SUBSIDIARIES as $id => $sub): ?>
                                    <?php $count = $vendorsModel->getTags($vendor['id'], $id); ?>

                                    <td class="text-center">
                                        <?php if ($count > 0): ?>
                                            <a href="<?= site_url('vendors/view/'.$vendor['id'].'?subsidiary_id='.$id) ?>" class="premium-count-badge">
                                                <?= $count ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="premium-count-empty">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
