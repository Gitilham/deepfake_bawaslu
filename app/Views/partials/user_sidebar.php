<?php
$currentPath = uri_string();
$isDashboard = $currentPath === 'user' || $currentPath === 'user/dashboard';
$isDetection = str_starts_with($currentPath, 'user/detections');
$isHistory = str_starts_with($currentPath, 'user/history');
$isEducation = str_starts_with($currentPath, 'user/education');
$isProfile = str_starts_with($currentPath, 'user/profile');
$menuItems = [
    ['url' => 'user/dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'active' => $isDashboard],
    ['url' => 'user/detections/create', 'label' => 'Deteksi Video', 'icon' => 'bi-cloud-arrow-up', 'active' => $isDetection],
    ['url' => 'user/history', 'label' => 'Riwayat Deteksi', 'icon' => 'bi-clock-history', 'active' => $isHistory],
    ['url' => 'user/education', 'label' => 'Edukasi Deepfake', 'icon' => 'bi-shield-check', 'active' => $isEducation],
    ['url' => 'user/profile', 'label' => 'Profil', 'icon' => 'bi-person-circle', 'active' => $isProfile],
];
?>
<aside class="user-sidebar" id="userSidebar" aria-label="Navigasi pengguna" aria-hidden="false">
    <div class="sidebar-brand">
        <a href="<?= base_url('user/dashboard') ?>" class="brand-inner" aria-label="BAWASLU Deepfake Detection">
            <span class="brand-logo"><i class="bi bi-shield-check"></i></span>
            <span class="brand-copy"><strong>BAWASLU</strong><small>Deepfake Detection</small></span>
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
        <span class="sidebar-footer-icon"><i class="bi bi-info-circle"></i></span>
        <span>Gunakan hasil sistem sebagai bantuan verifikasi awal.</span>
    </div>
</aside>
