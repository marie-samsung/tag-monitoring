<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Tag Monitoring System' ?></title>
    <link rel="stylesheet" href=<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>
    <script src=<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>></script>
</body>
</html>
 