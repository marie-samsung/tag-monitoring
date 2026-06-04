<!DOCTYPE html>
<html>
<head>
    <title><?= $this->renderSection('title') ?? 'Tag Monitoring System' ?></title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/select2/css/select2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/summernote/summernote-lite.css') ?>" rel="stylesheet">


</head>

<?php date_default_timezone_set('Asia/Dubai'); ?>

<body>
    <div class="container-fluid">
        <!-- NAVIGATION BAR -->
        <div class="py-2 sticky-nav-bar mx-auto mt-3">
            <?= $this->include('layouts/sidebar') ?>
        </div>

        <!-- MAIN CONTENT -->
        <div class="row mt-4">
            <div class="col container apple-card my-3">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="container alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif (session()->getFlashdata('error')) : ?>
                    <div class="container alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/jquery/js/jquery.min.js') ?>"></script>
    <script src=<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>></script>
    <script src=<?= base_url('assets/select2/js/select2.min.js') ?>></script>
    <script src="<?= base_url('assets/summernote/summernote-lite.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

    <?php if (\App\Models\Constants::IS_DEV) : ?>
        <div class="dev-mode-badge-container">
            <span class="badge dev-mode-badge">
                DEVELOPMENT MODE
            </span>
        </div>
    <?php endif; ?>
</body>
</html>
 
