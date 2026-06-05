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
    /* Badge/Count Style */
    .premium-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        color: #1b5e20;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 2px 4px rgba(27, 94, 32, 0.15);
        border: 1px solid rgba(27, 94, 32, 0.1);
    }

    .premium-count-badge:hover {
        background: linear-gradient(135deg, #1b5e20 0%, #123e15 100%);
        color: #ffffff !important;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 10px rgba(27, 94, 32, 0.3);
    }

    .premium-count-empty {
        color: #adb5bd;
        font-weight: 300;
    }

    .vendor-name {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--color-text-base);
    }
</style>

<?= csrf_field() ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h2 class="mb-0 fw-bold">Vendors</h2>
            <p class="text-secondary small mb-0">Overview of vendors and their tag count across different subsidiaries.</p>
        </div>
    </div>

    <!-- #region | SEARCH FILTER -->
    <div class="apple-control-bar mb-4">
        <form method="get" class="m-0">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label-xs fw-bold text-muted text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Search</label>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control form-select-xs" 
                        placeholder="Search..."
                        value="<?= esc($_GET['keyword'] ?? '') ?>"
                    >
                </div>

                <div class="col-md-5">
                    <label class="form-label-xs fw-bold text-muted text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Vendor</label>
                    <select name="id" class="form-select searchable-select">
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
                    <button class="apple-btn-primary btn-sm w-100" style="height: 31px;">Filter</button>
                    <a href="<?= site_url('vendors') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none d-flex align-items-center justify-content-center" style="height: 31px;">Clear</a>
                </div>
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

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.searchable-select').select2({
        width: '100%',
        placeholder: "All Vendors",
        allowClear: true
    });
});
</script>
<?= $this->endSection() ?>
