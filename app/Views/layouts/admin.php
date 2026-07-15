<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel Admin BAWASLU') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/landing/pavicondeepfake.png?v=1') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php $layoutCss = FCPATH . 'assets/css/user.css'; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css?v=' . (is_file($layoutCss) ? filemtime($layoutCss) : '1')) ?>">
    <?php $adminCss = FCPATH . 'assets/css/admin.css'; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css?v=' . (is_file($adminCss) ? filemtime($adminCss) : '1')) ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body class="admin-role">
<a class="skip-link" href="#mainContent">Lewati ke konten utama</a>
<?= $this->include('partials/sidebar_overlay') ?>
<?= $this->include('partials/admin_sidebar') ?>

<main class="user-main" id="mainContent">
    <?= $this->include('partials/admin_topbar') ?>
    <section class="content-wrapper">
        <?= $this->include('partials/alert') ?>
        <?= $this->renderSection('content') ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $layoutJs = FCPATH . 'assets/js/user-layout.js'; ?>
<script src="<?= base_url('assets/js/user-layout.js?v=' . (is_file($layoutJs) ? filemtime($layoutJs) : '1')) ?>" defer></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
