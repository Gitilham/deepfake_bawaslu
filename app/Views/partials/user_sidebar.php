<?php
$current = uri_string();

$active = static function (string $path) use ($current): string {
    $path = trim($path, '/');

    if ($current === $path) {
        return 'active';
    }

    if (str_starts_with($current, $path . '/')) {
        return 'active';
    }

    return '';
};
?>

<aside class="user-sidebar" id="userSidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-check"></i>
        <div>
            <div>BAWASLU</div>
            <small class="fw-normal text-muted">Deepfake Detection</small>
        </div>
    </div>

    <div class="py-3">
        <a href="<?= base_url('user/dashboard') ?>" class="nav-link <?= $active('user/dashboard') ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('user/detections/create') ?>" class="nav-link <?= $active('user/detections') ?>">
            <i class="bi bi-upload"></i>
            <span>Deteksi Video</span>
        </a>

        <a href="<?= base_url('user/history') ?>" class="nav-link <?= $active('user/history') ?>">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Deteksi</span>
        </a>

        <a href="<?= base_url('user/education') ?>" class="nav-link <?= $active('user/education') ?>">
            <i class="bi bi-journal-text"></i>
            <span>Edukasi Deepfake</span>
        </a>

        <a href="<?= base_url('user/profile') ?>" class="nav-link <?= $active('user/profile') ?>">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>

        <hr class="mx-3">

        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>