<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
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
        'DEEPFAKE' => 'danger',
        'UNKNOWN' => 'secondary',
        default => 'secondary',
    };
};

$totalVideos   = (int) ($total_videos ?? 0);
$totalReal     = (int) ($total_real ?? 0);
$totalDeepfake = (int) ($total_deepfake ?? 0);
$totalFailed   = (int) ($total_failed ?? 0);

$realPercent = $totalVideos > 0 ? round(($totalReal / $totalVideos) * 100, 1) : 0;
$deepfakePercent = $totalVideos > 0 ? round(($totalDeepfake / $totalVideos) * 100, 1) : 0;
$failedPercent = $totalVideos > 0 ? round(($totalFailed / $totalVideos) * 100, 1) : 0;

$latestRows = array_slice($latest_data ?? [], 0, 5);
?>

<div class="dashboard-hero">
    <div class="dashboard-hero-content">
        <div>
            <h3>Dashboard User</h3>
            <p>
                Selamat datang, <?= esc(session()->get('full_name') ?? 'User') ?>.
                Pantau ringkasan deteksi video dan lakukan analisis deepfake dari satu halaman.
            </p>
        </div>

        <!-- <a href="<?= base_url('user/detections/create') ?>" class="hero-action">
            <i class="bi bi-cloud-arrow-up"></i>
            Deteksi Video
        </a> -->
    </div>
</div>

<div class="stat-grid">
    <div class="dash-stat-card total">
        <div class="stat-top">
            <div>
                <div class="stat-label">Total Video</div>
                <div class="stat-value"><?= esc($totalVideos) ?></div>
            </div>
            <div class="stat-icon total">
                <i class="bi bi-camera-video"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span style="width: <?= $totalVideos > 0 ? '100' : '0' ?>%;"></span>
            </div>
        </div>
    </div>

    <div class="dash-stat-card real">
        <div class="stat-top">
            <div>
                <div class="stat-label">Video REAL</div>
                <div class="stat-value text-success"><?= esc($totalReal) ?></div>
            </div>
            <div class="stat-icon real">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span style="width: <?= esc($realPercent) ?>%;"></span>
            </div>
        </div>
    </div>

    <div class="dash-stat-card fake">
        <div class="stat-top">
            <div>
                <div class="stat-label">Video DEEPFAKE</div>
                <div class="stat-value text-danger"><?= esc($totalDeepfake) ?></div>
            </div>
            <div class="stat-icon fake">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span style="width: <?= esc($deepfakePercent) ?>%;"></span>
            </div>
        </div>
    </div>

    <div class="dash-stat-card failed">
        <div class="stat-top">
            <div>
                <div class="stat-label">Proses Gagal</div>
                <div class="stat-value text-warning"><?= esc($totalFailed) ?></div>
            </div>
            <div class="stat-icon failed">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-progress">
                <span style="width: <?= esc($failedPercent) ?>%;"></span>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dash-card">
        <div class="dash-card-header">
            <h5>Panduan Singkat</h5>
        </div>

        <div class="guide-list">
            <div class="guide-item">
                <div class="guide-icon">
                    <i class="bi bi-upload"></i>
                </div>
                <div>
                    <strong>1. Upload Video</strong>
                    <span>Pilih video yang ingin dianalisis melalui menu Deteksi Video.</span>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon">
                    <i class="bi bi-cpu"></i>
                </div>
                <div>
                    <strong>2. Proses Model AI</strong>
                    <span>Sistem mengirim video ke Flask API untuk diproses oleh model deepfake.</span>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div>
                    <strong>3. Lihat Hasil</strong>
                    <span>Hasil klasifikasi akan ditampilkan sebagai REAL atau DEEPFAKE.</span>
                </div>
            </div>
        </div>

        <div class="education-box">
            <h6>
                <i class="bi bi-journal-text me-1"></i>
                Edukasi Deepfake
            </h6>
            <p>
                Pelajari ciri-ciri umum video manipulatif agar lebih berhati-hati sebelum membagikan informasi.
            </p>
            <a href="<?= base_url('user/education') ?>" class="btn btn-outline-danger w-100">
                Baca Edukasi
            </a>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-header">
            <h5>Riwayat Terbaru</h5>
            <a href="<?= base_url('user/history') ?>" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle table-dashboard">
                <thead>
                    <tr>
                        <th>Video</th>
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
                            ?>
                            <tr>
                                <td>
                                    <div class="video-cell">
                                        <div class="video-icon">
                                            <i class="bi bi-file-earmark-play"></i>
                                        </div>
                                        <div>
                                            <div class="video-name"><?= esc($row['original_filename'] ?? '-') ?></div>
                                            <div class="video-id">ID: <?= esc($row['id'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge badge-result text-bg-<?= esc($labelBadge($label)) ?>">
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
                                    <a href="<?= base_url('user/history/detail/' . ($row['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada riwayat deteksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="quick-section">
    <div class="insight-card">
        <h5>Ringkasan Persentase</h5>

        <div class="insight-row">
            <div class="insight-label">
                <span>REAL</span>
                <span><?= esc($realPercent) ?>%</span>
            </div>
            <div class="insight-bar">
                <span style="width: <?= esc($realPercent) ?>%; background: var(--dash-green);"></span>
            </div>
        </div>

        <div class="insight-row">
            <div class="insight-label">
                <span>DEEPFAKE</span>
                <span><?= esc($deepfakePercent) ?>%</span>
            </div>
            <div class="insight-bar">
                <span style="width: <?= esc($deepfakePercent) ?>%; background: var(--dash-red);"></span>
            </div>
        </div>

        <div class="insight-row">
            <div class="insight-label">
                <span>Gagal</span>
                <span><?= esc($failedPercent) ?>%</span>
            </div>
            <div class="insight-bar">
                <span style="width: <?= esc($failedPercent) ?>%; background: var(--dash-orange);"></span>
            </div>
        </div>
    </div>

    <div class="cta-card">
        <div class="cta-card-content">
            <h5>Mulai Analisis Video Baru</h5>
            <p>
                Upload video terbaru untuk mendapatkan hasil klasifikasi dari model deepfake.
            </p>
            <a href="<?= base_url('user/detections/create') ?>" class="btn btn-light w-100">
                <i class="bi bi-cloud-arrow-up me-1"></i>
                Upload Video
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>