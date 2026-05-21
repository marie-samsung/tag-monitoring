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
        <div class="position-sticky py-2 sticky-nav mx-auto mt-3" style="top: 16px; background: rgba(255, 255, 255, 0.72); backdrop-filter: saturate(180%) blur(20px); border: 1px solid rgba(0,0,0,0.1); border-radius: 20px; z-index: 1050; max-width: 98%; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
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
<!--                --><?php //elseif (session()->getFlashdata('error')) : ?>
<!--                    <div class="container alert alert-danger alert-dismissible fade show mt-3" role="alert">-->
<!--                        --><?php //= esc(session()->getFlashdata('error')) ?>
<!--                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
<!--                    </div>-->
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
        <div style="position: fixed; bottom: 16px; right: 16px; z-index: 9999; pointer-events: none;">
            <span class="badge" style="background: rgba(255, 193, 7, 0.85); color: #000; font-size: 0.65rem; letter-spacing: 1px; padding: 0.4em 0.8em; backdrop-filter: blur(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid rgba(255, 193, 7, 0.3);">
                DEVELOPMENT MODE
            </span>
        </div>
    <?php endif; ?>
</body>
</html>
 
