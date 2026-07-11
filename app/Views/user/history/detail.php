<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>

<style>
    :root {
        --detail-red: #b31312;
        --detail-red-soft: rgba(179, 19, 18, .09);
        --detail-dark: #111827;
        --detail-muted: #6b7280;
        --detail-border: #e5e7eb;
        --detail-soft: #f8fafc;
    }

    .detail-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .detail-page-header h3 {
        font-weight: 900;
        color: var(--detail-dark);
        margin-bottom: 6px;
    }

    .detail-page-header p {
        color: var(--detail-muted);
        margin-bottom: 0;
    }

    .result-card {
        border: 0;
        border-radius: 28px;
        background: #fff;
        box-shadow: 0 20px 55px rgba(15, 23, 42, .08);
        overflow: hidden;
        margin-bottom: 22px;
        position: relative;
    }

    .result-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 8px;
        background: linear-gradient(90deg, var(--detail-red), #e63946, #111827);
    }

    .result-card-body {
        padding: 34px;
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        gap: 28px;
        align-items: center;
    }

    .result-left {
        text-align: center;
        border-right: 1px solid var(--detail-border);
        padding-right: 28px;
    }

    .result-icon {
        width: 92px;
        height: 92px;
        margin: 0 auto 18px;
        border-radius: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 45px;
    }

    .result-icon.real {
        background: #ecfdf5;
        color: #059669;
    }

    .result-icon.fake {
        background: #fef2f2;
        color: var(--detail-red);
    }

    .result-icon.unknown {
        background: #f1f5f9;
        color: #64748b;
    }

    .result-left h4 {
        font-weight: 900;
        color: var(--detail-dark);
        margin-bottom: 7px;
        word-break: break-word;
    }

    .result-left p {
        color: var(--detail-muted);
        margin-bottom: 14px;
    }

    .result-badge {
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 900;
        font-size: 13px;
    }

    .result-right h4 {
        font-weight: 900;
        color: var(--detail-dark);
        margin-bottom: 10px;
    }

    .result-right p {
        color: var(--detail-muted);
        line-height: 1.8;
        margin-bottom: 22px;
    }

    .score-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 13px;
    }

    .score-box {
        border: 1px solid var(--detail-border);
        border-radius: 18px;
        padding: 15px;
        background: #fff;
    }

    .score-box span {
        display: block;
        color: var(--detail-muted);
        font-size: 12.5px;
        margin-bottom: 6px;
    }

    .score-box strong {
        color: var(--detail-dark);
        font-size: 20px;
        font-weight: 900;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .info-card {
        border: 0;
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .info-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--detail-border);
        background: #fff;
        font-weight: 900;
        color: var(--detail-dark);
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .info-card-header i {
        color: var(--detail-red);
    }

    .info-list {
        padding: 8px 22px 18px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 13px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .info-item:last-child {
        border-bottom: 0;
    }

    .info-item span {
        color: var(--detail-muted);
    }

    .info-item strong {
        color: var(--detail-dark);
        text-align: right;
        word-break: break-word;
    }

    .error-box {
        margin-top: 18px;
        border-radius: 18px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 16px;
        line-height: 1.7;
    }

    @media (max-width: 1199.98px) {
        .score-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 991.98px) {
        .result-card-body {
            grid-template-columns: 1fr;
        }

        .result-left {
            border-right: 0;
            border-bottom: 1px solid var(--detail-border);
            padding-right: 0;
            padding-bottom: 24px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .detail-page-header {
            flex-direction: column;
        }

        .result-card-body {
            padding: 24px 18px;
        }

        .score-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            flex-direction: column;
            gap: 4px;
        }

        .info-item strong {
            text-align: left;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$status = $detection['status'] ?? 'pending';
$label = $detection['predicted_label'] ?? 'UNKNOWN';

$statusClass = match ($status) {
    'pending' => 'warning',
    'processing' => 'info',
    'completed' => 'success',
    'failed' => 'danger',
    'reviewed' => 'primary',
    default => 'secondary',
};

$labelClass = match ($label) {
    'REAL' => 'success',
    'DEEPFAKE' => 'danger',
    default => 'secondary',
};

$iconClass = match ($label) {
    'REAL' => 'real',
    'DEEPFAKE' => 'fake',
    default => 'unknown',
};

$iconName = match ($label) {
    'REAL' => 'bi-check-circle-fill',
    'DEEPFAKE' => 'bi-exclamation-triangle-fill',
    default => 'bi-question-circle-fill',
};

$description = match ($label) {
    'REAL' => 'Sistem mengklasifikasikan video ini sebagai video asli berdasarkan hasil prediksi model.',
    'DEEPFAKE' => 'Sistem mengklasifikasikan video ini sebagai video yang terindikasi manipulasi deepfake.',
    default => 'Video belum memiliki hasil klasifikasi yang valid.',
};

$formatBytes = static function ($bytes): string {
    if ($bytes === null || $bytes === '') {
        return '-';
    }

    $bytes = (float) $bytes;
    $units = ['B', 'KB', 'MB', 'GB'];

    for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
};

$formatPercent = static function ($value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    return number_format((float) $value * 100, 2) . '%';
};
?>

<div class="detail-page-header">
    <div>
        <h3 class="page-title">Detail Hasil Deteksi</h3>
        <p>Ringkasan hasil analisis video Anda.</p>
    </div>

    <!-- <a href="<?= base_url('user/history') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali
    </a> -->
</div>

<div class="result-card">
    <div class="result-card-body">
        <div class="result-left">
            <div class="result-icon <?= esc($iconClass) ?>">
                <i class="bi <?= esc($iconName) ?>"></i>
            </div>

            <h4><?= esc($detection['original_filename'] ?? '-') ?></h4>
            <p>ID Deteksi: <?= esc($detection['id'] ?? '-') ?></p>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <span class="badge result-badge text-bg-<?= esc($labelClass) ?>">
                    <?= esc($label) ?>
                </span>

                <span class="badge result-badge text-bg-<?= esc($statusClass) ?>">
                    <?= esc($status) ?>
                </span>
            </div>
        </div>

        <div class="result-right">
            <h4>Kesimpulan Sistem</h4>
            <p><?= esc($description) ?></p>

            <div class="score-grid">
                <div class="score-box">
                    <span>Confidence</span>
                    <strong><?= esc($formatPercent($detection['confidence'] ?? null)) ?></strong>
                </div>

                <div class="score-box">
                    <span>Real Score</span>
                    <strong class="text-success"><?= esc($formatPercent($detection['real_score'] ?? null)) ?></strong>
                </div>

                <div class="score-box">
                    <span>Fake Score</span>
                    <strong class="text-danger"><?= esc($formatPercent($detection['fake_score'] ?? null)) ?></strong>
                </div>

                <div class="score-box">
                    <span>Durasi Proses</span>
                    <strong>
                        <?= ($detection['duration_seconds'] ?? null) !== null && ($detection['duration_seconds'] ?? '') !== ''
                            ? esc($detection['duration_seconds']) . ' detik'
                            : '-' ?>
                    </strong>
                </div>
            </div>

            <?php if (! empty($detection['error_message'])) : ?>
                <div class="error-box">
                    <strong>Proses gagal:</strong><br>
                    <?= esc($detection['error_message']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="info-grid">
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-file-earmark-play"></i>
            Informasi Video
        </div>

        <div class="info-list">
            <div class="info-item">
                <span>Nama File</span>
                <strong><?= esc($detection['original_filename'] ?? '-') ?></strong>
            </div>

            <div class="info-item">
                <span>Ukuran</span>
                <strong><?= esc($formatBytes($detection['file_size'] ?? null)) ?></strong>
            </div>

            <div class="info-item">
                <span>MIME</span>
                <strong><?= esc($detection['file_mime'] ?? '-') ?></strong>
            </div>

            <div class="info-item">
                <span>Tanggal Upload</span>
                <strong><?= esc($detection['created_at'] ?? '-') ?></strong>
            </div>
        </div>
    </div>

    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-shield-check"></i>
            Informasi Deteksi
        </div>

        <div class="info-list">
            <div class="info-item">
                <span>Status</span>
                <strong>
                    <span class="badge text-bg-<?= esc($statusClass) ?>">
                        <?= esc($status) ?>
                    </span>
                </strong>
            </div>

            <div class="info-item">
                <span>Hasil Akhir</span>
                <strong><?= esc($label) ?></strong>
            </div>

            <div class="info-item">
                <span>Update Terakhir</span>
                <strong><?= esc($detection['updated_at'] ?? '-') ?></strong>
            </div>

            <div class="info-item">
                <span>Catatan</span>
                <strong>Hasil berasal dari model Flask API.</strong>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>