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

<?= csrf_field() ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Properties</h2>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('properties/create') ?>" title="Add a new property" class="apple-btn-primary btn-sm ms-auto px-3">Create New</a>
        </div>
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
                <select name="subsidiary_id" class="form-select form-select-xs">
                    <option value="">All Subsidiaries</option>
                    <?php foreach ($subsidiaryOptions as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('subsidiary_id', $filters['subsidiary_id'] ?? null) == $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center gap-2">
                <button class="apple-btn-primary btn-sm w-100">Filter</button>
                <a href="<?= site_url('properties') ?>" class="apple-btn-secondary btn-sm w-100 text-center text-decoration-none">Clear</a>
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
                        <th class="form-label-sm fw-semibold">Subsidiary</th>
                        <th class="form-label-sm fw-semibold">Property</th>
                        <th class="form-label-sm fw-semibold">Is EPP?</th>
                        <th class="form-label-sm fw-semibold">Is AEM?</th>
                        <th class="form-label-sm fw-semibold">Is Shop?</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($properties as $property): ?>
                        <tr>
                            <td><?= $property['id'] ?></td>
                            <td><?= $subsidiaryOptions[$property['subsidiary_id']] ?? 'Unknown' ?></td>
                            <td class="form-label-sm">
                                <span class="group-hover-container align-items-center">
                                    <a href="<?= $property['ref_link'] ?>" class="apple-link-dark form-label-sm ms-1">
                                        <?= $property['name'] ?>
                                    </a>
                                    <a href="javascript:void(0)" onclick="copyName('<?= esc($property['name']) ?>', this)" title="Copy property name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                        <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                    </a>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= bool_class($property['is_epp']) ?>">
                                    <?= esc(bool_label($property['is_epp'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= bool_class(!$property['is_shop']) ?>">
                                    <?= esc(bool_label(!$property['is_shop'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= bool_class($property['is_shop']) ?>">
                                    <?= esc(bool_label($property['is_shop'])) ?>
                                </span>
                            </td>
                            <!-- <td class="gap-2">
                                <a href="<?= site_url('properties/view/'.$property['id']) ?>" title="View property details" class="apple-btn-secondary btn-sm">View</a>
                                <a href="<?= site_url('properties/update/'.$property['id']) ?>" title="Update property details" class="apple-btn-primary btn-sm">Update</a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- #endregion -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.copyName = function(text, el) {
            const temp = document.createElement('textarea');
            temp.value = text;

            temp.style.position = 'fixed';
            temp.style.left = '-9999px';

            document.body.appendChild(temp);

            temp.focus();
            temp.select();

            document.execCommand('copy');

            document.body.removeChild(temp);

            el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';

            setTimeout(() => {
                el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
            }, 1200);
        };
    });
</script>

<?= $this->endSection() ?>
