<!DOCTYPE html>
<html>
<head>
    <title><?= $this->renderSection('title') ?? 'Martech TMS' ?></title>
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

        <!-- FOOTER -->
        <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center py-4 mt-2 mb-4 gap-3" style="border-top: 1px solid var(--color-border-subtle); opacity: 0.85;">
            <!-- Left side: Samsung logo -->
            <div class="d-flex align-items-center justify-content-center justify-content-md-start" style="flex: 1;">
                <div class="d-flex align-items-center" style="opacity: 0.85;">
                    <img src="https://images.samsung.com/is/image/samsung/assets/ae/about-us/brand/logo/pc/300_186_1.png?$568_N_PNG$" alt="Samsung Logo" style="height: 80px; object-fit: contain;">
                </div>
            </div>

            <!-- Center: Copyrights & Developer info -->
            <div class="text-center" style="flex: 2;">
                <p class="mb-1 text-secondary" style="font-size: 0.8rem; font-weight: 400; letter-spacing: -0.1px;">
                    &copy; <?= date('Y') ?> Samsung SDS Middle East &amp; North Africa. All rights reserved.
                </p>
                <p class="mb-0 text-muted" style="font-size: 0.75rem; font-weight: 400; opacity: 0.8;">
                    Designed and developed by Martech Media Tagging
                </p>
            </div>

            <!-- Right side: Version -->
            <div class="d-flex align-items-center justify-content-center justify-content-md-end" style="flex: 1;">
                <span class="text-muted" style="font-size: 0.72rem; font-weight: 600; letter-spacing: 0.5px;">
                    V2.3
                </span>
            </div>
        </footer>
    </div>

    <script src="<?= base_url('assets/jquery/js/jquery.min.js') ?>"></script>
    <script src=<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>></script>
    <script src=<?= base_url('assets/select2/js/select2.min.js') ?>></script>
    <script src="<?= base_url('assets/summernote/summernote-lite.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

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
 
