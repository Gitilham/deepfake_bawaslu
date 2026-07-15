<?php
$fullName = (string) (session()->get('full_name') ?? 'Administrator');
$trimmedName = trim($fullName);
$initial = function_exists('mb_substr') ? mb_substr($trimmedName, 0, 1) : substr($trimmedName, 0, 1);
$initial = strtoupper($initial ?: 'A');
$profilePhoto = (string) (session()->get('profile_photo') ?? '');
?>
<header class="user-topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Buka menu navigasi" aria-controls="userSidebar" aria-expanded="false">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <div class="topbar-title">
            <strong>Sistem Deteksi Deepfake</strong>
            <span>Panel Administrator</span>
        </div>
    </div>

    <div class="topbar-actions">
        <span class="admin-access-badge"><i class="bi bi-shield-lock"></i><span>Admin</span></span>
        <div class="dropdown">
            <button class="user-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Buka menu akun <?= esc($fullName, 'attr') ?>">
                <?php if ($profilePhoto !== '') : ?>
                    <span class="user-avatar has-photo"><img src="<?= esc(base_url($profilePhoto), 'attr') ?>" alt="Foto profil"></span>
                <?php else : ?>
                    <span class="user-avatar"><?= esc($initial) ?></span>
                <?php endif; ?>
                <span class="user-name"><?= esc($fullName) ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>"><i class="bi bi-person"></i> Profil Saya</a></li>
                <li><a class="dropdown-item" href="<?= base_url('admin/detections/create') ?>"><i class="bi bi-cloud-arrow-up"></i> Deteksi Video</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="post" action="<?= base_url('logout') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
