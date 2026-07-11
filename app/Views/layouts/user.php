<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Deepfake Detection BAWASLU') ?></title>
    <link rel="icon" href="<?= base_url('favicon1.ico?v=1') ?>" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php $layoutCss = FCPATH . 'assets/css/user.css'; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css?v=' . (is_file($layoutCss) ? filemtime($layoutCss) : '1')) ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>
<a class="skip-link" href="#mainContent">Lewati ke konten utama</a>
<?= $this->include('partials/sidebar_overlay') ?>
<?= $this->include('partials/user_sidebar') ?>

<main class="user-main" id="mainContent">
    <?= $this->include('partials/user_topbar') ?>
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
