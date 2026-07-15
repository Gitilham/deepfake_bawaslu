<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>

<style>
    :root {
        --adm-red: #c1121f;
        --adm-red-2: #e63946;
        --adm-dark: #111827;
        --adm-muted: #6b7280;
        --adm-border: #e5e7eb;
        --adm-soft: #f8fafc;
        --adm-blue: #2563eb;
        --adm-green: #059669;
        --adm-orange: #f59e0b;
        --adm-purple: #7c3aed;
    }

    .admin-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 26%),
            linear-gradient(135deg, #111827 0%, #1f2937 35%, #7f1d1d 100%);
        color: #fff;
        padding: 30px;
        margin-bottom: 24px;
        box-shadow: 0 24px 55px rgba(15, 23, 42, .16);
    }

    .admin-hero::before {
        content: "";
        position: absolute;
        right: -90px;
        top: -90px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .admin-hero::after {
        content: "";
        position: absolute;
        left: -70px;
        bottom: -70px;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: rgba(193,18,31,.24);
    }

    .admin-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .admin-hero h3 {
        font-weight: 900;
        margin-bottom: 8px;
        color: #fff;
    }

    .admin-hero p {
        color: rgba(255,255,255,.82);
        line-height: 1.7;
        margin-bottom: 0;
        max-width: 760px;
    }

    .hero-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 800;
        background: rgba(255,255,255,.10);
        color: #fff;
        border: 1px solid rgba(255,255,255,.10);
    }

    .hero-action-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .hero-btn-light {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 0;
        border-radius: 16px;
        background: #fff;
        color: var(--adm-red);
        padding: 13px 18px;
        font-weight: 800;
        box-shadow: 0 14px 32px rgba(0,0,0,.16);
    }

    .hero-btn-dark {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.08);
        color: #fff;
        padding: 13px 18px;
        font-weight: 800;
    }

    .hero-btn-light:hover,
    .hero-btn-dark:hover {
        transform: translateY(-1px);
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        background: #fff;
        border: 1px solid #edf2f7;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, .06);
        min-height: 136px;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        right: -36px;
        top: -36px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: .10;
    }

    .stat-card.users::before { background: var(--adm-blue); }
    .stat-card.videos::before { background: var(--adm-purple); }
    .stat-card.deepfake::before { background: var(--adm-red); }
    .stat-card.real::before { background: var(--adm-green); }
    .stat-card.failed::before { background: var(--adm-orange); }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .stat-title {
        color: var(--adm-muted);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 34px;
        line-height: 1;
        font-weight: 900;
        color: var(--adm-dark);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-icon.users { background: rgba(37,99,235,.10); color: var(--adm-blue); }
    .stat-icon.videos { background: rgba(124,58,237,.10); color: var(--adm-purple); }
    .stat-icon.deepfake { background: rgba(193,18,31,.10); color: var(--adm-red); }
    .stat-icon.real { background: rgba(5,150,105,.10); color: var(--adm-green); }
    .stat-icon.failed { background: rgba(245,158,11,.12); color: var(--adm-orange); }

    .stat-foot {
        margin-top: 18px;
        position: relative;
        z-index: 2;
    }

    .mini-progress {
        height: 7px;
        background: #edf2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .mini-progress span {
        display: block;
        height: 100%;
        border-radius: 999px;
    }

    .stat-card.users .mini-progress span { background: var(--adm-blue); }
    .stat-card.videos .mini-progress span { background: var(--adm-purple); }
    .stat-card.deepfake .mini-progress span { background: var(--adm-red); }
    .stat-card.real .mini-progress span { background: var(--adm-green); }
    .stat-card.failed .mini-progress span { background: var(--adm-orange); }

    .stat-note {
        margin-top: 8px;
        font-size: 12px;
        color: var(--adm-muted);
        font-weight: 600;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: .92fr 1.08fr;
        gap: 22px;
        margin-bottom: 24px;
    }

    .dash-card {
        border: 0;
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .dash-card-header {
        padding: 19px 22px;
        border-bottom: 1px solid var(--adm-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        background: #fff;
    }

    .dash-card-header h5 {
        font-weight: 900;
        color: var(--adm-dark);
        margin-bottom: 0;
    }

    .distribution-body {
        padding: 22px;
    }

    .distribution-item {
        margin-bottom: 18px;
    }

    .distribution-item:last-child {
        margin-bottom: 0;
    }

    .distribution-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        font-size: 13px;
        font-weight: 800;
        color: var(--adm-dark);
        margin-bottom: 8px;
    }

    .distribution-top span:last-child {
        color: var(--adm-muted);
    }

    .distribution-bar {
        height: 12px;
        background: #eef2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .distribution-bar span {
        display: block;
        height: 100%;
        border-radius: 999px;
    }

    .quick-actions {
        padding: 22px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-radius: 18px;
        border: 1px solid #edf2f7;
        background: linear-gradient(180deg, #fff, #fafafa);
        color: var(--adm-dark);
        transition: .2s ease;
    }

    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(15,23,42,.07);
        color: var(--adm-red);
    }

    .quick-action-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: rgba(193,18,31,.08);
        color: var(--adm-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .quick-action strong {
        display: block;
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 2px;
    }

    .quick-action span {
        display: block;
        font-size: 12.5px;
        color: var(--adm-muted);
        line-height: 1.5;
    }

    .table-admin {
        margin-bottom: 0;
    }

    .table-admin thead th {
        background: var(--adm-soft);
        color: #334155;
        font-size: 13px;
        font-weight: 800;
        border-bottom: 1px solid var(--adm-border);
        padding: 14px 18px;
        white-space: nowrap;
    }

    .table-admin tbody td {
        padding: 15px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
    }

    .file-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 230px;
    }

    .file-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: rgba(193,18,31,.08);
        color: var(--adm-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .file-name {
        font-weight: 800;
        color: var(--adm-dark);
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-id {
        font-size: 12px;
        color: var(--adm-muted);
    }

    .badge-pill {
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 11.5px;
        font-weight: 800;
    }

    .empty-box {
        padding: 50px 16px;
        text-align: center;
        color: var(--adm-muted);
    }

    .empty-box i {
        font-size: 42px;
        margin-bottom: 10px;
        display: block;
    }

    @media (max-width: 1399.98px) {
        .stat-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1199.98px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991.98px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575.98px) {
        .admin-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .table-admin thead {
            display: none;
        }

        .table-admin,
        .table-admin tbody,
        .table-admin tr,
        .table-admin td {
            display: block;
            width: 100%;
        }

        .table-admin tbody tr {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .table-admin tbody td {
            padding: 7px 0;
            border-bottom: 0;
        }

        .dash-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$totalUsers     = (int) ($total_users ?? 0);
$totalVideos    = (int) ($total_videos ?? 0);
$totalDeepfake  = (int) ($total_deepfake ?? 0);
$totalReal      = (int) ($total_real ?? 0);
$totalFailed    = (int) ($total_failed ?? 0);

$latestRows = array_slice($latest_data ?? ($latest_detections ?? []), 0, 5);

$realPercent      = $totalVideos > 0 ? round(($totalReal / $totalVideos) * 100, 1) : 0;
$deepfakePercent  = $totalVideos > 0 ? round(($totalDeepfake / $totalVideos) * 100, 1) : 0;
$failedPercent    = $totalVideos > 0 ? round(($totalFailed / $totalVideos) * 100, 1) : 0;
$processedPercent = $totalVideos > 0 ? round((($totalReal + $totalDeepfake) / $totalVideos) * 100, 1) : 0;

$statusBadge = static function (?string $status): string {
    return match ($status) {
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'failed' => 'danger',
        'reviewed' => 'primary',
        default => 'secondary',
    };
};

$labelBadge = static function (?string $label): string {
    return match ($label) {
        'REAL' => 'success',
        'MENCURIGAKAN' => 'warning',
        'DEEPFAKE' => 'danger',
        'NO_FACE' => 'info',
        'UNKNOWN' => 'secondary',
        default => 'secondary',
    };
};
?>

<div class="admin-hero">
    <div class="admin-hero-content">
        <div>
            <h3>Dashboard Admin</h3>
            <p>
                Ringkasan utama sistem deteksi deepfake video. Pantau aktivitas pengguna,
                hasil klasifikasi, dan akses cepat ke pengelolaan data dari satu halaman.
            </p>

            <div class="hero-badges">
                <div class="hero-badge">
                    <i class="bi bi-shield-lock"></i>
                    Panel Administrator
                </div>
                <div class="hero-badge">
                    <i class="bi bi-cpu"></i>
                    Integrasi Backend API
                </div>
                <div class="hero-badge">
                    <i class="bi bi-bar-chart"></i>
                    Monitoring Sistem
                </div>
            </div>
        </div>

        <div class="hero-action-group">
            <a href="<?= base_url('admin/detections/create') ?>" class="hero-btn-light">
                <i class="bi bi-cloud-arrow-up"></i>
                Deteksi Video
            </a>

            <a href="<?= base_url('admin/reports') ?>" class="hero-btn-dark">
                <i class="bi bi-file-earmark-bar-graph"></i>
                Lihat Laporan
            </a>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card users">
        <div class="stat-top">
            <div>
                <div class="stat-title">User Masyarakat</div>
                <div class="stat-value"><?= esc($totalUsers) ?></div>
            </div>
            <div class="stat-icon users">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="stat-foot">
            <div class="mini-progress"><span style="width: 100%;"></span></div>
            <div class="stat-note">Total akun pengguna yang terdaftar.</div>
        </div>
    </div>

    <div class="stat-card videos">
        <div class="stat-top">
            <div>
                <div class="stat-title">Total Video</div>
                <div class="stat-value"><?= esc($totalVideos) ?></div>
            </div>
            <div class="stat-icon videos">
                <i class="bi bi-collection-play"></i>
            </div>
        </div>
        <div class="stat-foot">
            <div class="mini-progress"><span style="width: 100%;"></span></div>
            <div class="stat-note">Semua data video yang masuk ke sistem.</div>
        </div>
    </div>

    <div class="stat-card deepfake">
        <div class="stat-top">
            <div>
                <div class="stat-title">Deepfake</div>
                <div class="stat-value text-danger"><?= esc($totalDeepfake) ?></div>
            </div>
            <div class="stat-icon deepfake">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-foot">
            <div class="mini-progress"><span style="width: <?= esc($deepfakePercent) ?>%;"></span></div>
            <div class="stat-note"><?= esc($deepfakePercent) ?>% dari total video.</div>
        </div>
    </div>

    <div class="stat-card real">
        <div class="stat-top">
            <div>
                <div class="stat-title">Real / Asli</div>
                <div class="stat-value text-success"><?= esc($totalReal) ?></div>
            </div>
            <div class="stat-icon real">
                <i class="bi bi-patch-check"></i>
            </div>
        </div>
        <div class="stat-foot">
            <div class="mini-progress"><span style="width: <?= esc($realPercent) ?>%;"></span></div>
            <div class="stat-note"><?= esc($realPercent) ?>% dari total video.</div>
        </div>
    </div>

    <div class="stat-card failed">
        <div class="stat-top">
            <div>
                <div class="stat-title">Proses Gagal</div>
                <div class="stat-value text-warning"><?= esc($totalFailed) ?></div>
            </div>
            <div class="stat-icon failed">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="stat-foot">
            <div class="mini-progress"><span style="width: <?= esc($failedPercent) ?>%;"></span></div>
            <div class="stat-note"><?= esc($failedPercent) ?>% dari total video.</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dash-card">
        <div class="dash-card-header">
            <h5>Distribusi & Ringkasan</h5>
        </div>

        <div class="distribution-body">
            <div class="distribution-item">
                <div class="distribution-top">
                    <span>REAL</span>
                    <span><?= esc($realPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($realPercent) ?>%; background: var(--adm-green);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>DEEPFAKE</span>
                    <span><?= esc($deepfakePercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($deepfakePercent) ?>%; background: var(--adm-red);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>GAGAL</span>
                    <span><?= esc($failedPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($failedPercent) ?>%; background: var(--adm-orange);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>VIDEO BERHASIL DIPROSES</span>
                    <span><?= esc($processedPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($processedPercent) ?>%; background: var(--adm-purple);"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-header">
            <h5>Akses Cepat</h5>
        </div>

        <div class="quick-actions">
            <a href="<?= base_url('admin/users') ?>" class="quick-action">
                <div class="quick-action-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <strong>Kelola User</strong>
                    <span>Lihat dan kelola data user masyarakat.</span>
                </div>
            </a>

            <a href="<?= base_url('admin/detections') ?>" class="quick-action">
                <div class="quick-action-icon">
                    <i class="bi bi-camera-video"></i>
                </div>
                <div>
                    <strong>Deteksi Video</strong>
                    <span>Monitor hasil deteksi video terbaru.</span>
                </div>
            </a>

            <a href="<?= base_url('admin/api-settings') ?>" class="quick-action">
                <div class="quick-action-icon">
                    <i class="bi bi-hdd-network"></i>
                </div>
                <div>
                    <strong>Konfigurasi API</strong>
                    <span>Atur koneksi Backend API dan endpoint.</span>
                </div>
            </a>

            <a href="<?= base_url('admin/reports') ?>" class="quick-action">
                <div class="quick-action-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <div>
                    <strong>Laporan</strong>
                    <span>Lihat ringkasan dan data laporan sistem.</span>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header">
        <h5>Deteksi Terbaru</h5>
        <a href="<?= base_url('admin/detections') ?>" class="btn btn-sm btn-outline-danger">
            Lihat Semua
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-admin">
            <thead>
                <tr>
                    <th>File</th>
                    <th>User</th>
                    <th>Hasil</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="90">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($latestRows)) : ?>
                    <?php foreach ($latestRows as $row) : ?>
                        <?php
                        $label = $row['predicted_label'] ?? 'UNKNOWN';
                        $status = $row['status'] ?? 'pending';
                        $userName = $row['full_name'] ?? $row['user_name'] ?? $row['name'] ?? 'User';
                        $fileName = $row['original_filename'] ?? $row['file_name'] ?? '-';
                        ?>
                        <tr>
                            <td>
                                <div class="file-cell">
                                    <div class="file-icon">
                                        <i class="bi bi-file-earmark-play"></i>
                                    </div>
                                    <div>
                                        <div class="file-name"><?= esc($fileName) ?></div>
                                        <div class="file-id">ID: <?= esc($row['id'] ?? '-') ?></div>
                                    </div>
                                </div>
                            </td>

                            <td><?= esc($userName) ?></td>

                            <td>
                                <span class="badge badge-pill text-bg-<?= esc($labelBadge($label)) ?>">
                                    <?= esc($label) ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge text-bg-<?= esc($statusBadge($status)) ?>">
                                    <?= esc($status) ?>
                                </span>
                            </td>

                            <td><?= esc($row['created_at'] ?? '-') ?></td>

                            <td>
                                <a href="<?= base_url('admin/detections/detail/' . ($row['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-box">
                                <i class="bi bi-inbox"></i>
                                Belum ada data deteksi terbaru.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
