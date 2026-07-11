<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deepfake Detection BAWASLU</title>

    <link rel="icon" href="<?= base_url('favicon1.ico?v=1') ?>" sizes="32x32">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="<?= base_url('assets/css/user.css?v=1') ?>">
</head>
<body>
<?= $this->renderSection('styles') ?>
<?php
$currentPath = uri_string();

$isDashboard = $currentPath === 'user' || $currentPath === 'user/dashboard';
$isDetection = str_starts_with($currentPath, 'user/detections');
$isHistory   = str_starts_with($currentPath, 'user/history');
$isEducation = str_starts_with($currentPath, 'user/education');
$isProfile   = str_starts_with($currentPath, 'user/profile');

$fullName = session()->get('full_name') ?? 'User';
$initial = strtoupper(substr(trim($fullName), 0, 1));
?>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="user-sidebar" id="userSidebar">
    <div class="sidebar-brand">
        <div class="brand-inner">
            <div class="brand-logo">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <span class="brand-title">BAWASLU</span>
                <span class="brand-subtitle">Deepfake Detection</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="<?= base_url('user/dashboard') ?>" class="sidebar-link <?= $isDashboard ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('user/detections/create') ?>" class="sidebar-link <?= $isDetection ? 'active' : '' ?>">
            <i class="bi bi-cloud-arrow-up"></i>
            <span>Deteksi Video</span>
        </a>

        <a href="<?= base_url('user/history') ?>" class="sidebar-link <?= $isHistory ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Deteksi</span>
        </a>

        <a href="<?= base_url('user/education') ?>" class="sidebar-link <?= $isEducation ? 'active' : '' ?>">
            <i class="bi bi-journal-text"></i>
            <span>Edukasi Deepfake</span>
        </a>

        <div class="sidebar-spacer"></div>

        <a href="<?= base_url('user/profile') ?>" class="sidebar-link <?= $isProfile ? 'active' : '' ?>">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
    </nav>
</aside>

<main class="user-main">
    <header class="user-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" type="button" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-title">
                <strong>Sistem Deteksi Deepfake</strong>
                <span>Panel Masyarakat</span>
            </div>
        </div>

        <div class="topbar-actions">
            <!-- <div class="topbar-chip">
                <i class="bi bi-cpu-fill"></i>
                AI Model Ready
            </div> -->

            <div class="dropdown">
                <button class="user-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="user-avatar"><?= esc($initial) ?></span>
                    <span class="user-name"><?= esc($fullName) ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= base_url('user/profile') ?>">
                            <i class="bi bi-person"></i>
                            Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('user/history') ?>">
                            <i class="bi bi-clock-history"></i>
                            Riwayat Deteksi
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="post" action="<?= base_url('logout') ?>">
                            <?= csrf_field() ?>
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="content-wrapper">
        <?= $this->include('partials/alert') ?>
        <?= $this->renderSection('content') ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const userSidebar = document.getElementById('userSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        userSidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    }

    function closeSidebar() {
        userSidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', openSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('.sidebar-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 991) {
                closeSidebar();
            }
        });
    });
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
