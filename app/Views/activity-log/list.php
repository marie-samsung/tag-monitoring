<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Activity Logs
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= csrf_field() ?>

<div class="container">
    <h2>Activity Logs</h2>
    <div class="alert alert-warning mt-3">
        <i class="bi bi-info-square-fill"></i>
        This feature is a work in progress.
    </div>
</div>

<?= $this->endSection() ?>
