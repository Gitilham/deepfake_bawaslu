<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php $historyCss = FCPATH . 'assets/css/history.css'; ?>
<link rel="stylesheet" href="<?= base_url('assets/css/history.css?v=' . (is_file($historyCss) ? filemtime($historyCss) : '1')) ?>">
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
        'MENCURIGAKAN' => 'warning',
        'DEEPFAKE' => 'danger',
        'NO_FACE' => 'info',
        default => 'secondary',
    };
};
?>

<header class="history-page-header">
    <div>
        <h3 class="page-title">Riwayat Deteksi</h3>
        <p>Daftar video yang pernah Anda upload dan hasil klasifikasinya.</p>
    </div>

    <a href="<?= base_url('user/detections/create') ?>" class="btn btn-bawaslu">
        <i class="bi bi-plus-circle me-1"></i>
        Deteksi Baru
    </a>
</header>

<div class="history-summary-grid">
    <div class="history-summary-card">
        <div class="history-summary-icon total">
            <i class="bi bi-camera-video"></i>
        </div>
        <div>
            <span>Total Video</span>
            <strong><?= esc($totalData) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon real">
            <i class="bi bi-check-circle"></i>
        </div>
        <div>
            <span>REAL</span>
            <strong><?= esc($totalReal) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon fake">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div>
            <span>DEEPFAKE</span>
            <strong><?= esc($totalDeepfake) ?></strong>
        </div>
    </div>

    <div class="history-summary-card">
        <div class="history-summary-icon failed">
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

    <?php if (! empty($rows)) : ?>
    <div class="history-table-desktop table-responsive">
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
            </tbody>
        </table>
    </div>

    <div class="history-list-mobile">
        <?php foreach ($rows as $row) : ?>
            <?php
            $label = $row['predicted_label'] ?? 'UNKNOWN';
            $status = $row['status'] ?? 'pending';
            $confidence = $row['confidence'] ?? null;
            ?>
            <article class="history-mobile-card">
                <div class="history-mobile-head">
                    <span class="history-file-icon"><i class="bi bi-file-earmark-play"></i></span>
                    <div><strong><?= esc($row['original_filename'] ?? '-') ?></strong><small>ID Deteksi: <?= esc($row['id'] ?? '-') ?></small></div>
                </div>
                <dl class="history-mobile-meta">
                    <div><dt>Hasil</dt><dd><span class="badge text-bg-<?= esc($labelBadge($label)) ?>"><?= esc($label) ?></span></dd></div>
                    <div><dt>Confidence</dt><dd><?= $confidence !== null && $confidence !== '' ? number_format((float) $confidence * 100, 2) . '%' : '-' ?></dd></div>
                    <div><dt>Status</dt><dd><span class="badge text-bg-<?= esc($statusBadge($status)) ?>"><?= esc($status) ?></span></dd></div>
                    <div><dt>Tanggal Upload</dt><dd><?= esc($row['created_at'] ?? '-') ?></dd></div>
                </dl>
                <a href="<?= base_url('user/history/detail/' . ($row['id'] ?? 0)) ?>" class="btn-history-detail">Lihat Detail</a>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else : ?>
        <div class="history-empty">
            <div class="history-empty-icon"><i class="bi bi-file-earmark-play"></i></div>
            <h5>Belum Ada Riwayat Deteksi</h5>
            <p>Mulai deteksi video pertama Anda untuk melihat hasil analisis di halaman ini.</p>
            <a href="<?= base_url('user/detections/create') ?>" class="btn btn-bawaslu"><i class="bi bi-cloud-arrow-up me-1"></i> Mulai Deteksi</a>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($pager)) : ?>
    <div class="mt-3"><?= $pager->links('history', 'default_full') ?></div>
<?php endif; ?>

<?= $this->endSection() ?>
