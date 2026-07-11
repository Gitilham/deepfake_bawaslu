user_sidebar.php<?php
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

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-check"></i>
        <div>
            <div>BAWASLU</div>
            <small class="fw-normal text-white-50">Deepfake Detection</small>
        </div>
    </div>

    <div class="py-3">
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= $active('admin/dashboard') ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('admin/users') ?>" class="nav-link <?= $active('admin/users') ?>">
            <i class="bi bi-people"></i>
            <span>Data User</span>
        </a>

        <a href="<?= base_url('admin/detections') ?>" class="nav-link <?= $active('admin/detections') ?>">
            <i class="bi bi-camera-video"></i>
            <span>Data Deteksi Video</span>
        </a>

        <a href="<?= base_url('admin/api-settings') ?>" class="nav-link <?= $active('admin/api-settings') ?>">
            <i class="bi bi-hdd-network"></i>
            <span>Konfigurasi Flask API</span>
        </a>

        <a href="<?= base_url('admin/reports') ?>" class="nav-link <?= $active('admin/reports') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan</span>
        </a>

        <a href="<?= base_url('admin/profile') ?>" class="nav-link <?= $active('admin/profile') ?>">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>

        <hr class="border-secondary mx-3">

        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>