<?php
$currentPath = uri_string();
$menuItems = [
    ['url' => 'admin/dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'active' => $currentPath === 'admin' || $currentPath === 'admin/dashboard'],
    ['url' => 'admin/users', 'label' => 'Data User', 'icon' => 'bi-people', 'active' => str_starts_with($currentPath, 'admin/users')],
    ['url' => 'admin/detections/create', 'label' => 'Deteksi Video', 'icon' => 'bi-cloud-arrow-up', 'active' => $currentPath === 'admin/detections/create'],
    ['url' => 'admin/detections', 'label' => 'Data Deteksi', 'icon' => 'bi-camera-video', 'active' => str_starts_with($currentPath, 'admin/detections') && $currentPath !== 'admin/detections/create'],
    ['url' => 'admin/api-settings', 'label' => 'Konfigurasi API', 'icon' => 'bi-sliders', 'active' => str_starts_with($currentPath, 'admin/api-settings')],
    ['url' => 'admin/reports', 'label' => 'Laporan', 'icon' => 'bi-file-earmark-bar-graph', 'active' => str_starts_with($currentPath, 'admin/reports')],
    ['url' => 'admin/profile', 'label' => 'Profil', 'icon' => 'bi-person-circle', 'active' => str_starts_with($currentPath, 'admin/profile')],
];
?>
<aside class="user-sidebar" id="userSidebar" aria-label="Navigasi administrator" aria-hidden="false">
    <div class="sidebar-brand">
        <a href="<?= base_url('admin/dashboard') ?>" class="brand-inner" aria-label="Panel Admin BAWASLU">
            <span class="brand-logo"><i class="bi bi-shield-lock"></i></span>
            <span class="brand-copy"><strong>BAWASLU</strong><small>Panel Administrator</small></span>
        </a>
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu navigasi">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-menu">
        <?php foreach ($menuItems as $item) : ?>
            <a href="<?= base_url($item['url']) ?>"
               class="sidebar-link <?= $item['active'] ? 'active' : '' ?>"
               title="<?= esc($item['label'], 'attr') ?>"
               <?= $item['active'] ? 'aria-current="page"' : '' ?>>
                <i class="bi <?= esc($item['icon'], 'attr') ?>"></i>
                <span><?= esc($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <span class="sidebar-footer-icon"><i class="bi bi-shield-check"></i></span>
        <span>Kelola sistem dan tinjau hasil deteksi secara bertanggung jawab.</span>
    </div>
</aside>
