<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>

<style>
    :root {
        --rep-red: #c1121f;
        --rep-red-2: #e63946;
        --rep-dark: #111827;
        --rep-muted: #6b7280;
        --rep-border: #e5e7eb;
        --rep-soft: #f8fafc;
        --rep-blue: #2563eb;
        --rep-green: #059669;
        --rep-orange: #f59e0b;
        --rep-purple: #7c3aed;
    }

    .report-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 30px;
        color: #fff;
        margin-bottom: 24px;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 28%),
            linear-gradient(135deg, #111827 0%, #1f2937 45%, #7f1d1d 100%);
        box-shadow: 0 24px 55px rgba(15, 23, 42, .15);
    }

    .report-hero::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -80px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(255,255,255,.09);
    }

    .report-hero::after {
        content: "";
        position: absolute;
        left: -70px;
        bottom: -70px;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: rgba(193,18,31,.25);
    }

    .report-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }

    .report-hero h3 {
        font-weight: 900;
        color: #fff;
        margin-bottom: 8px;
    }

    .report-hero p {
        max-width: 780px;
        color: rgba(255,255,255,.82);
        line-height: 1.75;
        margin-bottom: 0;
    }

    .report-hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-report-light {
        border: 0;
        border-radius: 16px;
        background: #fff;
        color: var(--rep-red);
        padding: 13px 18px;
        font-weight: 800;
        box-shadow: 0 14px 32px rgba(0,0,0,.16);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-report-glass {
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.08);
        color: #fff;
        padding: 13px 18px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-report-light:hover {
        color: var(--rep-red);
        transform: translateY(-1px);
    }

    .btn-report-glass:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .report-summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .report-summary-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, .06);
        min-height: 136px;
    }

    .report-summary-card::before {
        content: "";
        position: absolute;
        right: -36px;
        top: -36px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: .10;
    }

    .report-summary-card.total::before { background: var(--rep-blue); }
    .report-summary-card.real::before { background: var(--rep-green); }
    .report-summary-card.fake::before { background: var(--rep-red); }
    .report-summary-card.failed::before { background: var(--rep-orange); }
    .report-summary-card.user::before { background: var(--rep-purple); }

    .report-summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .report-summary-label {
        color: var(--rep-muted);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .report-summary-value {
        font-size: 34px;
        line-height: 1;
        font-weight: 900;
        color: var(--rep-dark);
    }

    .report-summary-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .report-summary-icon.total { background: rgba(37,99,235,.10); color: var(--rep-blue); }
    .report-summary-icon.real { background: rgba(5,150,105,.10); color: var(--rep-green); }
    .report-summary-icon.fake { background: rgba(193,18,31,.10); color: var(--rep-red); }
    .report-summary-icon.failed { background: rgba(245,158,11,.12); color: var(--rep-orange); }
    .report-summary-icon.user { background: rgba(124,58,237,.10); color: var(--rep-purple); }

    .report-summary-note {
        position: relative;
        z-index: 2;
        margin-top: 14px;
        color: var(--rep-muted);
        font-size: 12.5px;
        font-weight: 600;
    }

    .report-main-grid {
        display: grid;
        grid-template-columns: .82fr 1.18fr;
        gap: 22px;
        margin-bottom: 24px;
    }

    .report-card {
        background: #fff;
        border: 0;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .report-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--rep-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .report-card-title {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .report-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: rgba(193,18,31,.10);
        color: var(--rep-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .report-card-header h5 {
        font-weight: 900;
        color: var(--rep-dark);
        margin-bottom: 3px;
    }

    .report-card-header small {
        color: var(--rep-muted);
    }

    .report-card-body {
        padding: 24px;
    }

    .filter-label {
        color: var(--rep-dark);
        font-weight: 800;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .filter-input,
    .filter-select {
        border-radius: 14px;
        border: 1.7px solid #d8dde6;
        padding: 12px 14px;
        box-shadow: none !important;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: var(--rep-red);
        box-shadow: 0 0 0 4px rgba(193,18,31,.08) !important;
    }

    .btn-filter-primary {
        border: 0;
        border-radius: 14px;
        background: linear-gradient(90deg, var(--rep-red), var(--rep-red-2));
        color: #fff;
        padding: 12px 18px;
        font-weight: 800;
        box-shadow: 0 14px 30px rgba(193,18,31,.22);
    }

    .btn-filter-primary:hover {
        color: #fff;
        transform: translateY(-1px);
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
        color: var(--rep-dark);
        margin-bottom: 8px;
    }

    .distribution-top span:last-child {
        color: var(--rep-muted);
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

    .report-table-card {
        background: #fff;
        border: 0;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .report-table-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--rep-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .report-table-header h5 {
        color: var(--rep-dark);
        font-weight: 900;
        margin-bottom: 2px;
    }

    .report-table-header small {
        color: var(--rep-muted);
    }

    .table-report {
        margin-bottom: 0;
    }

    .table-report thead th {
        background: var(--rep-soft);
        color: #334155;
        font-size: 13px;
        font-weight: 800;
        border-bottom: 1px solid var(--rep-border);
        padding: 14px 18px;
        white-space: nowrap;
    }

    .table-report tbody td {
        padding: 15px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
    }

    .file-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 240px;
    }

    .file-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: rgba(193,18,31,.08);
        color: var(--rep-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .file-name {
        font-weight: 800;
        color: var(--rep-dark);
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-id {
        font-size: 12px;
        color: var(--rep-muted);
    }

    .badge-pill {
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 11.5px;
        font-weight: 800;
    }

    .score-text {
        font-weight: 800;
        color: var(--rep-dark);
    }

    .empty-report {
        text-align: center;
        padding: 64px 18px;
    }

    .empty-report-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 16px;
        border-radius: 24px;
        background: rgba(193,18,31,.08);
        color: var(--rep-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
    }

    .empty-report h5 {
        font-weight: 900;
        color: var(--rep-dark);
        margin-bottom: 8px;
    }

    .empty-report p {
        color: var(--rep-muted);
        margin-bottom: 0;
    }

    @media print {
        .admin-sidebar,
        .admin-topbar,
        .report-hero-actions,
        .report-card,
        .btn,
        .dropdown {
            display: none !important;
        }

        .admin-main {
            margin-left: 0 !important;
        }

        .admin-content {
            padding: 0 !important;
        }

        .report-hero {
            background: #fff !important;
            color: #111827 !important;
            box-shadow: none !important;
            border: 1px solid #e5e7eb;
        }

        .report-hero h3,
        .report-hero p {
            color: #111827 !important;
        }

        .report-summary-grid {
            grid-template-columns: repeat(5, 1fr);
        }

        .report-table-card,
        .report-summary-card {
            box-shadow: none !important;
            border: 1px solid #e5e7eb;
        }
    }

    @media (max-width: 1399.98px) {
        .report-summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1199.98px) {
        .report-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991.98px) {
        .report-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575.98px) {
        .report-hero {
            padding: 24px 20px;
        }

        .report-hero-content {
            align-items: flex-start;
        }

        .report-hero-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-report-light,
        .btn-report-glass {
            width: 100%;
            justify-content: center;
        }

        .report-summary-grid {
            grid-template-columns: 1fr;
        }

        .report-table-header,
        .report-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-report thead {
            display: none;
        }

        .table-report,
        .table-report tbody,
        .table-report tr,
        .table-report td {
            display: block;
            width: 100%;
        }

        .table-report tbody tr {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .table-report tbody td {
            padding: 7px 0;
            border-bottom: 0;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$request = service('request');

$rows = $reports ?? $detections ?? $report_data ?? $latest_data ?? [];

$totalUsers    = (int) ($total_users ?? 0);
$totalVideos   = (int) ($total_videos ?? count($rows));
$totalReal     = (int) ($total_real ?? 0);
$totalDeepfake = (int) ($total_deepfake ?? 0);
$totalFailed   = (int) ($total_failed ?? 0);

if (($total_real ?? null) === null || ($total_deepfake ?? null) === null || ($total_failed ?? null) === null) {
    $totalReal = 0;
    $totalDeepfake = 0;
    $totalFailed = 0;

    foreach ($rows as $row) {
        if (($row['predicted_label'] ?? '') === 'REAL') {
            $totalReal++;
        }

        if (($row['predicted_label'] ?? '') === 'DEEPFAKE') {
            $totalDeepfake++;
        }

        if (($row['status'] ?? '') === 'failed') {
            $totalFailed++;
        }
    }
}

$realPercent = $totalVideos > 0 ? round(($totalReal / $totalVideos) * 100, 1) : 0;
$deepfakePercent = $totalVideos > 0 ? round(($totalDeepfake / $totalVideos) * 100, 1) : 0;
$failedPercent = $totalVideos > 0 ? round(($totalFailed / $totalVideos) * 100, 1) : 0;
$processedPercent = $totalVideos > 0 ? round((($totalReal + $totalDeepfake) / $totalVideos) * 100, 1) : 0;

$dateFrom = $request->getGet('date_from') ?? ($date_from ?? '');
$dateTo   = $request->getGet('date_to') ?? ($date_to ?? '');
$labelFilter = $request->getGet('label') ?? ($label ?? '');
$statusFilter = $request->getGet('status') ?? ($status ?? '');

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

$formatPercent = static function ($value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    return number_format((float) $value * 100, 2) . '%';
};
?>

<div class="report-hero">
    <div class="report-hero-content">
        <div>
            <h3>Laporan Sistem Deteksi</h3>
            <p>
                Pantau rekapitulasi hasil deteksi video, distribusi klasifikasi,
                dan data laporan yang dapat digunakan untuk kebutuhan evaluasi sistem.
            </p>
        </div>

        <div class="report-hero-actions">
            <button type="button" class="btn-report-light" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Cetak Laporan
            </button>

            <!-- <a href="<?= base_url('admin/dashboard') ?>" class="btn-report-glass">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a> -->
        </div>
    </div>
</div>

<div class="report-summary-grid">
    <div class="report-summary-card user">
        <div class="report-summary-top">
            <div>
                <div class="report-summary-label">User Masyarakat</div>
                <div class="report-summary-value"><?= esc($totalUsers) ?></div>
            </div>
            <div class="report-summary-icon user">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="report-summary-note">Total user terdaftar.</div>
    </div>

    <div class="report-summary-card total">
        <div class="report-summary-top">
            <div>
                <div class="report-summary-label">Total Video</div>
                <div class="report-summary-value"><?= esc($totalVideos) ?></div>
            </div>
            <div class="report-summary-icon total">
                <i class="bi bi-collection-play"></i>
            </div>
        </div>
        <div class="report-summary-note">Semua video yang masuk.</div>
    </div>

    <div class="report-summary-card fake">
        <div class="report-summary-top">
            <div>
                <div class="report-summary-label">Deepfake</div>
                <div class="report-summary-value text-danger"><?= esc($totalDeepfake) ?></div>
            </div>
            <div class="report-summary-icon fake">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="report-summary-note"><?= esc($deepfakePercent) ?>% dari total video.</div>
    </div>

    <div class="report-summary-card real">
        <div class="report-summary-top">
            <div>
                <div class="report-summary-label">Real / Asli</div>
                <div class="report-summary-value text-success"><?= esc($totalReal) ?></div>
            </div>
            <div class="report-summary-icon real">
                <i class="bi bi-patch-check"></i>
            </div>
        </div>
        <div class="report-summary-note"><?= esc($realPercent) ?>% dari total video.</div>
    </div>

    <div class="report-summary-card failed">
        <div class="report-summary-top">
            <div>
                <div class="report-summary-label">Gagal</div>
                <div class="report-summary-value text-warning"><?= esc($totalFailed) ?></div>
            </div>
            <div class="report-summary-icon failed">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="report-summary-note"><?= esc($failedPercent) ?>% dari total video.</div>
    </div>
</div>

<div class="report-main-grid">
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-card-icon">
                    <i class="bi bi-funnel"></i>
                </div>
                <div>
                    <h5>Filter Laporan</h5>
                    <small>Saring data berdasarkan tanggal, hasil, dan status.</small>
                </div>
            </div>
        </div>

        <div class="report-card-body">
            <form action="<?= base_url('admin/reports') ?>" method="get">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label filter-label" for="date_from">Tanggal Awal</label>
                        <input
                            type="date"
                            class="form-control filter-input"
                            id="date_from"
                            name="date_from"
                            value="<?= esc($dateFrom) ?>"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label filter-label" for="date_to">Tanggal Akhir</label>
                        <input
                            type="date"
                            class="form-control filter-input"
                            id="date_to"
                            name="date_to"
                            value="<?= esc($dateTo) ?>"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label filter-label" for="label">Hasil Klasifikasi</label>
                        <select class="form-select filter-select" id="label" name="label">
                            <option value="">Semua Hasil</option>
                            <option value="REAL" <?= $labelFilter === 'REAL' ? 'selected' : '' ?>>REAL</option>
                            <option value="MENCURIGAKAN" <?= $labelFilter === 'MENCURIGAKAN' ? 'selected' : '' ?>>MENCURIGAKAN</option>
                            <option value="DEEPFAKE" <?= $labelFilter === 'DEEPFAKE' ? 'selected' : '' ?>>DEEPFAKE</option>
                            <option value="NO_FACE" <?= $labelFilter === 'NO_FACE' ? 'selected' : '' ?>>NO_FACE</option>
                            <option value="UNKNOWN" <?= $labelFilter === 'UNKNOWN' ? 'selected' : '' ?>>UNKNOWN</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label filter-label" for="status">Status Proses</label>
                        <select class="form-select filter-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>completed</option>
                            <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>processing</option>
                            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>pending</option>
                            <option value="failed" <?= $statusFilter === 'failed' ? 'selected' : '' ?>>failed</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 flex-wrap">
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>

                    <button type="submit" class="btn-filter-primary">
                        <i class="bi bi-search me-1"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-card-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div>
                    <h5>Distribusi Hasil</h5>
                    <small>Ringkasan persentase hasil klasifikasi.</small>
                </div>
            </div>
        </div>

        <div class="report-card-body">
            <div class="distribution-item">
                <div class="distribution-top">
                    <span>REAL</span>
                    <span><?= esc($realPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($realPercent) ?>%; background: var(--rep-green);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>DEEPFAKE</span>
                    <span><?= esc($deepfakePercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($deepfakePercent) ?>%; background: var(--rep-red);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>GAGAL</span>
                    <span><?= esc($failedPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($failedPercent) ?>%; background: var(--rep-orange);"></span>
                </div>
            </div>

            <div class="distribution-item">
                <div class="distribution-top">
                    <span>BERHASIL DIPROSES</span>
                    <span><?= esc($processedPercent) ?>%</span>
                </div>
                <div class="distribution-bar">
                    <span style="width: <?= esc($processedPercent) ?>%; background: var(--rep-purple);"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="report-table-card">
    <div class="report-table-header">
        <div>
            <h5>Data Laporan Deteksi</h5>
            <small>Menampilkan data video dan hasil klasifikasi yang tersimpan di sistem.</small>
        </div>

        <span class="badge text-bg-light border">
            <?= esc(count($rows)) ?> Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-report">
            <thead>
                <tr>
                    <th>File Video</th>
                    <th>User</th>
                    <th>Hasil</th>
                    <th>Confidence</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="90">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (! empty($rows)) : ?>
                    <?php foreach ($rows as $row) : ?>
                        <?php
                        $fileName = $row['original_filename'] ?? $row['file_name'] ?? '-';
                        $userName = $row['full_name'] ?? $row['user_name'] ?? $row['name'] ?? '-';
                        $label = $row['predicted_label'] ?? 'UNKNOWN';
                        $status = $row['status'] ?? 'pending';
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
                                <span class="score-text">
                                    <?= esc($formatPercent($row['confidence'] ?? null)) ?>
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
                        <td colspan="7">
                            <div class="empty-report">
                                <div class="empty-report-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h5>Belum ada data laporan</h5>
                                <p>Data akan muncul setelah user melakukan deteksi video.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($pager)) : ?>
    <div class="mt-3"><?= $pager->only(['start_date', 'end_date', 'predicted_label', 'status'])->links('reports', 'admin_full') ?></div>
<?php endif; ?>

<?= $this->endSection() ?>
