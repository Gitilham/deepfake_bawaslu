<?php
$fullName = (string) (session()->get('full_name') ?? 'User');
$trimmedName = trim($fullName);
$initial = function_exists('mb_substr') ? mb_substr($trimmedName, 0, 1) : substr($trimmedName, 0, 1);
$initial = strtoupper($initial ?: 'U');
?>
<header class="user-topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Buka menu navigasi" aria-controls="userSidebar" aria-expanded="false">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <div class="topbar-title">
            <strong>Sistem Deteksi Deepfake</strong>
            <span>Panel Masyarakat</span>
        </div>
    </div>

    <div class="topbar-actions">
        <div class="dropdown">
            <button class="user-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Buka menu akun <?= esc($fullName, 'attr') ?>">
                <span class="user-avatar"><?= esc($initial) ?></span>
                <span class="user-name"><?= esc($fullName) ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= base_url('user/profile') ?>"><i class="bi bi-person"></i> Profil Saya</a></li>
                <li><a class="dropdown-item" href="<?= base_url('user/history') ?>"><i class="bi bi-clock-history"></i> Riwayat Deteksi</a></li>
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
