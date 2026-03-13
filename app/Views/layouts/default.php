<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Tag Monitoring System' ?></title>
    <link rel="stylesheet" href=<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/select2/css/select2.min.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- LEFT SIDEBAR -->
            <div class="col-3 col-md-2 bg-light border-end" style="min-height: 100vh;">
                <nav class="nav flex-column py-2">
                    <a class="nav-link" href="<?= site_url('/tags') ?>">Tags</a>
                    <a class="nav-link" href="<?= site_url('/properties') ?>">Properties</a>
                </nav>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-9 col-md-10 mt-4">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src=<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>></script>
    <script src="<?= base_url('assets/jquery/js/jquery.min.js') ?>"></script>
    <script src=<?= base_url('assets/select2/js/select2.min.js') ?>></script>
</body>
</html>
 
