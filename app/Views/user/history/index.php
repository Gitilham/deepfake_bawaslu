<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/history.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$rows = $detections ?? [];

$totalData = count($rows);
$totalReal = 0;
$totalDeepfake = 0;
$totalFailed = 0;

foreach ($rows as $item) {
    if (($item['predicted_label'] ?? '') === 'REAL') {
        $totalReal++;
    }

    if (($item['predicted_label'] ?? '') === 'DEEPFAKE') {
        $totalDeepfake++;
    }

    if (($item['status'] ?? '') === 'failed') {
        $totalFailed++;
    }
}

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
        default => 'secondary',
    };
};
?>

<div class="history-page-header">
    <div>
        <h3 class="page-title">Riwayat Deteksi</h3>
        <p>Daftar video yang pernah Anda upload dan hasil klasifikasinya.</p>
    </div>

    <a href="<?= base_url('user/detections/create') ?>" class="btn btn-bawaslu">
        <i class="bi bi-upload me-1"></i>
        Deteksi Baru
    </a>
</div>

<div class="history-summary-grid">
    <div class="history-summary-card">
        <div class="history-summary-icon bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-camera-video"></i>
        </div>
        <div>
            <span>Total Video</span>
            <strong><?= esc($totalData) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon bg-success bg-opacity-10 text-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div>
            <span>REAL</span>
            <strong><?= esc($totalReal) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon bg-danger bg-opacity-10 text-danger">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div>
            <span>DEEPFAKE</span>
            <strong><?= esc($totalDeepfake) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-x-circle"></i>
        </div>
        <div>
            <span>Gagal</span>
            <strong><?= esc($totalFailed) ?></strong>
        </div>
    </div>
</div>

<div class="history-card">
    <div class="history-card-header">
        <h5>Data Riwayat</h5>
        <span class="badge text-bg-light border"><?= esc($totalData) ?> Data</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle history-table">
            <thead>
                <tr>
                    <th>Video</th>
                    <th>Hasil</th>
                    <th>Confidence</th>
                    <th>Status</th>
                    <th>Tanggal Upload</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (! empty($rows)) : ?>
                    <?php foreach ($rows as $row) : ?>
                        <?php
                        $label = $row['predicted_label'] ?? 'UNKNOWN';
                        $status = $row['status'] ?? 'pending';
                        $confidence = $row['confidence'] ?? null;
                        ?>
                        <tr>
                            <td>
                                <div class="history-file">
                                    <div class="history-file-icon">
                                        <i class="bi bi-file-earmark-play"></i>
                                    </div>
                                    <div>
                                        <div class="history-file-name">
                                            <?= esc($row['original_filename'] ?? '-') ?>
                                        </div>
                                        <div class="history-file-id">
                                            ID Deteksi: <?= esc($row['id'] ?? '-') ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge history-result-badge text-bg-<?= esc($labelBadge($label)) ?>">
                                    <?= esc($label) ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($confidence !== null && $confidence !== '') : ?>
                                    <span class="history-confidence">
                                        <?= number_format((float) $confidence * 100, 2) ?>%
                                    </span>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="badge text-bg-<?= esc($statusBadge($status)) ?>">
                                    <?= esc($status) ?>
                                </span>
                            </td>

                            <td>
                                <?= esc($row['created_at'] ?? '-') ?>
                            </td>

                            <td>
                                <a href="<?= base_url('user/history/detail/' . ($row['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary btn-history-detail">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
                            <div class="history-empty">
                                <div class="history-empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>

                                <h5>Belum ada riwayat deteksi</h5>
                                <p>Upload video pertama Anda untuk melihat hasil deteksi.</p>

                                <a href="<?= base_url('user/detections/create') ?>" class="btn btn-bawaslu">
                                    <i class="bi bi-upload me-1"></i>
                                    Upload Video
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>