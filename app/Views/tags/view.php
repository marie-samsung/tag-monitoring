<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h2>View Tag</h2>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();
?>

<div class="row mb-3">
    <div class="col">
        <label class="form-label-sm">Property</label>
        <div class="form-control-plaintext fw-semibold">
            <?= esc($properties[$tag['property_id']] ?? '—') ?>
        </div>
    </div>

    <div class="col">
        <label class="form-label-sm">Tag Name</label>
        <div class="form-control-plaintext fw-semibold">
            <?= esc($tag['name']) ?>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col">
        <label class="form-label-sm">Conditions</label>
        <div class="form-control-plaintext">
            <?= esc($tag['conditions']) ?>
        </div>
    </div>

    <div class="col">
        <label class="form-label-sm">Status</label>
        <div class="form-control-plaintext">
            <span class="badge <?= status_class($tag['status']) ?>">
                <?= esc(Constants::IS_ACTIVE[$tag['status']] ?? 'Unknown') ?>
            </span>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <label class="form-label-sm">AL End Date</label>
        <div class="form-control-plaintext">
            <?= esc($tag['al_end_date'] ?: '—') ?>
        </div>
    </div>

    <div class="col">
        <label class="form-label-sm">AL Link</label>
        <div class="form-control-plaintext">
            <?php if (!empty($tag['ref_link'])): ?>
                <a href="<?= esc($tag['ref_link']) ?>" target="_blank">
                    <?= esc($tag['ref_link']) ?>
                </a>
            <?php else: ?>
                —
            <?php endif; ?>
        </div>
    </div>
</div>

<a href="<?= site_url('tags') ?>" class="btn btn-secondary">Back</a>
<a href="<?= site_url('tags/edit/' . $tag['id']) ?>" class="btn btn-primary">Edit</a>

<?= $this->endSection() ?>