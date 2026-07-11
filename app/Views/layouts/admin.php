<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?></title>

    <link rel="shortcut icon" href="<?= base_url('assets/landing/pavicondeepfake.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --admin-red: #c1121f;
            --admin-red-2: #e63946;
            --admin-dark: #0f172a;
            --admin-dark-2: #111827;
            --admin-text: #e5e7eb;
            --admin-muted: #94a3b8;
            --admin-border: rgba(255,255,255,.08);
            --admin-sidebar-width: 292px;
            --admin-topbar-height: 78px;
            --admin-content-bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(193, 18, 31, .08), transparent 25%),
                linear-gradient(180deg, #f8fafc, #f1f5f9);
            color: #111827;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* =========================
           SIDEBAR ADMIN
        ========================= */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid rgba(255,255,255,.05);
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(193,18,31,.16), transparent 26%),
                linear-gradient(180deg, #111111 0%, #161616 45%, #1c1b22 100%);
            box-shadow: 20px 0 44px rgba(15, 23, 42, .18);
        }

        .admin-sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 34px 34px;
            opacity: .35;
            pointer-events: none;
        }

        .sidebar-brand {
            position: relative;
            z-index: 2;
            padding: 26px 22px 22px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            background: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0));
        }

        .sidebar-brand-inner {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            background: linear-gradient(135deg, var(--admin-red), var(--admin-red-2));
            box-shadow: 0 14px 30px rgba(193, 18, 31, .35);
            flex-shrink: 0;
        }

        .sidebar-brand-title {
            display: block;
            font-size: 17px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.1;
            letter-spacing: .2px;
        }

        .sidebar-brand-subtitle {
            display: block;
            font-size: 12.5px;
            color: rgba(255,255,255,.70);
            margin-top: 5px;
        }

        .sidebar-menu {
            position: relative;
            z-index: 2;
            padding: 22px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 11px;
            padding: 15px 16px;
            border-radius: 17px;
            color: #e2e8f0;
            font-weight: 760;
            transition: .22s ease;
            overflow: hidden;
            border: 1px solid transparent;
            background: rgba(255,255,255,.02);
        }

        .sidebar-link::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(193,18,31,.18), rgba(255,255,255,.03));
            opacity: 0;
            transition: .22s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            opacity: 1;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #ffffff;
            transform: translateX(3px);
            border-color: rgba(255,255,255,.05);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(193,18,31,.95), rgba(193,18,31,.85));
            box-shadow: 0 16px 32px rgba(193,18,31,.22);
        }

        .sidebar-link.active::after {
            content: "";
            position: absolute;
            right: 14px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 0 6px rgba(255,255,255,.10);
        }

        .sidebar-link i,
        .sidebar-link span {
            position: relative;
            z-index: 2;
        }

        .sidebar-link i {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .sidebar-separator {
            height: 1px;
            background: rgba(255,255,255,.08);
            margin: 14px 8px 10px;
            border-radius: 999px;
        }

        .sidebar-footer-note {
            position: relative;
            z-index: 2;
            margin: 10px 14px 18px;
            padding: 14px 15px;
            border-radius: 18px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            color: rgba(255,255,255,.82);
        }

        .sidebar-footer-note strong {
            display: block;
            font-size: 13px;
            color: #fff;
            margin-bottom: 4px;
        }

        .sidebar-footer-note span {
            display: block;
            font-size: 12px;
            color: rgba(255,255,255,.65);
            line-height: 1.5;
        }

        /* =========================
           MAIN AREA
        ========================= */
        .admin-main {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--admin-topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: var(--admin-red);
            border-radius: 14px;
            font-size: 22px;
        }

        .topbar-title strong {
            display: block;
            font-size: 16px;
            font-weight: 900;
            color: #111827;
        }

        .topbar-title span {
            display: block;
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .admin-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #111827;
            font-weight: 700;
        }

        .admin-avatar {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
            background: linear-gradient(135deg, var(--admin-red), var(--admin-red-2));
        }

        .dropdown-menu {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .14);
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 12px;
            padding: 10px 12px;
            font-weight: 600;
        }

        .dropdown-item i {
            width: 22px;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(193,18,31,.08);
            color: var(--admin-red) !important;
        }

        .admin-content {
            padding: 28px;
        }

        .page-title {
            font-weight: 900;
            color: #111827;
        }

        .btn-admin {
            background: linear-gradient(90deg, var(--admin-red), var(--admin-red-2));
            color: #fff;
            border: 0;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 12px 28px rgba(193,18,31,.20);
        }

        .btn-admin:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .sidebar-overlay {
            display: none;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: .25s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15,23,42,.55);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                z-index: 1035;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .topbar-chip {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .admin-topbar {
                padding: 0 16px;
            }

            .admin-content {
                padding: 20px 16px;
            }

            .topbar-title span {
                display: none;
            }

            .admin-user-btn .user-name {
                display: none;
            }
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body>

<?php
$currentPath = uri_string();

$isDashboard   = $currentPath === 'admin' || $currentPath === 'admin/dashboard';
$isUsers       = str_starts_with($currentPath, 'admin/users');
$isDetections  = str_starts_with($currentPath, 'admin/detections');
$isFlaskConfig = str_starts_with($currentPath, 'admin/flask') || str_starts_with($currentPath, 'admin/settings/flask');
$isReports     = str_starts_with($currentPath, 'admin/reports') || str_starts_with($currentPath, 'admin/laporan');
$isProfile     = str_starts_with($currentPath, 'admin/profile');

$fullName = session()->get('full_name') ?? 'Admin';
$initial  = strtoupper(substr(trim($fullName), 0, 1));
?>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-inner">
            <div class="sidebar-brand-logo">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <span class="sidebar-brand-title">BAWASLU</span>
                <span class="sidebar-brand-subtitle">Deepfake Detection</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= $isDashboard ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('admin/users') ?>" class="sidebar-link <?= $isUsers ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Data User</span>
        </a>

        <a href="<?= base_url('admin/detections') ?>" class="sidebar-link <?= $isDetections ? 'active' : '' ?>">
            <i class="bi bi-camera-video"></i>
            <span>Data Deteksi Video</span>
        </a>

        <a href="<?= base_url('admin/api-settings') ?>" class="sidebar-link <?= $isFlaskConfig ? 'active' : '' ?>">
    <i class="bi bi-hdd-network"></i>
    <span>Konfigurasi Flask API</span>
</a>

        <a href="<?= base_url('admin/reports') ?>" class="sidebar-link <?= $isReports ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan</span>
        </a>

        <div class="sidebar-separator"></div>

        <a href="<?= base_url('admin/profile') ?>" class="sidebar-link <?= $isProfile ? 'active' : '' ?>">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- <div class="sidebar-footer-note">
        <strong>Panel Administrator</strong>
        <span>Kelola pengguna, data deteksi, konfigurasi API, dan laporan sistem.</span>
    </div> -->
</aside>

<main class="admin-main">
    <header class="admin-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" type="button" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-title">
                <strong>Sistem Deteksi Deepfake</strong>
                <span>Panel Admin</span>
            </div>
        </div>

        <div class="topbar-actions">
            <div class="topbar-chip">
                <i class="bi bi-shield-lock-fill"></i>
                Admin Access
            </div>

            <div class="dropdown">
                <button class="admin-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="admin-avatar"><?= esc($initial) ?></span>
                    <span class="user-name"><?= esc($fullName) ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                            <i class="bi bi-person"></i>
                            Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="admin-content">
        <?= $this->include('partials/alert') ?>
        <?= $this->renderSection('content') ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        adminSidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    }

    function closeSidebar() {
        adminSidebar.classList.remove('show');
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