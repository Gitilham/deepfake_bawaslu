<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
$status = $detection['status'] ?? 'pending';
$statusClass = match ($status) {
    'pending' => 'warning',
    'processing' => 'info',
    'completed' => 'success',
    'failed' => 'danger',
    'reviewed' => 'primary',
    default => 'secondary',
};

$label = $detection['predicted_label'] ?? 'UNKNOWN';
$labelClass = match ($label) {
    'REAL' => 'success',
    'DEEPFAKE' => 'danger',
    default => 'secondary',
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
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-1">Detail Deteksi Video</h3>
        <p class="text-muted mb-0">Informasi lengkap hasil deteksi video.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/detections') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

        <?php if ($status !== 'reviewed') : ?>
            <a href="<?= base_url('admin/detections/review/' . $detection['id']) ?>"
               class="btn btn-bawaslu"
               onclick="return confirm('Tandai data ini sebagai reviewed?')">
                <i class="bi bi-check2-circle me-1"></i>
                Tandai Reviewed
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card table-card">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 90px; height: 90px; font-size: 42px;">
                    <i class="bi bi-camera-video"></i>
                </div>

                <h5 class="fw-bold mb-1"><?= esc($detection['original_filename']) ?></h5>
                <p class="text-muted mb-3">ID Deteksi: <?= esc($detection['id']) ?></p>

                <div class="d-flex justify-content-center gap-2">
                    <span class="badge text-bg-<?= esc($labelClass) ?>">
                        <?= esc($label) ?>
                    </span>
                    <span class="badge text-bg-<?= esc($statusClass) ?>">
                        <?= esc($status) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Ringkasan Prediksi</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small">Confidence</div>
                            <div class="fw-bold fs-5">
                                <?= $detection['confidence'] !== null ? number_format((float) $detection['confidence'] * 100, 2) . '%' : '-' ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small">Real Score</div>
                            <div class="fw-bold fs-5 text-success">
                                <?= $detection['real_score'] !== null ? number_format((float) $detection['real_score'] * 100, 2) . '%' : '-' ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small">Fake Score</div>
                            <div class="fw-bold fs-5 text-danger">
                                <?= $detection['fake_score'] !== null ? number_format((float) $detection['fake_score'] * 100, 2) . '%' : '-' ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small">Durasi</div>
                            <div class="fw-bold fs-5">
                                <?= $detection['duration_seconds'] !== null ? esc($detection['duration_seconds']) . ' detik' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (! empty($detection['error_message'])) : ?>
                    <div class="alert alert-danger mt-3 mb-0">
                        <div class="fw-bold">Error Message:</div>
                        <?= esc($detection['error_message']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Informasi File</h5>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="220" class="bg-light">Nama File Asli</th>
                        <td><?= esc($detection['original_filename']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Nama File Server</th>
                        <td><?= esc($detection['stored_filename']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Path</th>
                        <td><?= esc($detection['file_path']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">MIME</th>
                        <td><?= esc($detection['file_mime'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Ukuran</th>
                        <td><?= esc($formatBytes($detection['file_size'])) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tanggal Upload</th>
                        <td><?= esc($detection['created_at'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Informasi User</h5>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="220" class="bg-light">Nama User</th>
                        <td><?= esc($detection['full_name'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Email</th>
                        <td><?= esc($detection['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Reviewed By</th>
                        <td><?= esc($detection['reviewed_by'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Reviewed At</th>
                        <td><?= esc($detection['reviewed_at'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Update Terakhir</th>
                        <td><?= esc($detection['updated_at'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card table-card mb-4">
    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">Detail Frame</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Waktu Frame</th>
                    <th>Label</th>
                    <th>Confidence</th>
                    <th>Real Score</th>
                    <th>Fake Score</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($frames)) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($frames as $frame) : ?>
                        <?php
                        $frameLabel = $frame['label'] ?? 'UNKNOWN';
                        $frameLabelClass = match ($frameLabel) {
                            'REAL' => 'success',
                            'DEEPFAKE' => 'danger',
                            default => 'secondary',
                        };
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($frame['frame_time'] ?? '-') ?> detik</td>
                            <td>
                                <span class="badge text-bg-<?= esc($frameLabelClass) ?>">
                                    <?= esc($frameLabel) ?>
                                </span>
                            </td>
                            <td><?= $frame['confidence'] !== null ? number_format((float) $frame['confidence'] * 100, 2) . '%' : '-' ?></td>
                            <td><?= $frame['real_score'] !== null ? number_format((float) $frame['real_score'] * 100, 2) . '%' : '-' ?></td>
                            <td><?= $frame['fake_score'] !== null ? number_format((float) $frame['fake_score'] * 100, 2) . '%' : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada detail frame dari Flask API.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card table-card">
    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">Response JSON Flask API</h5>
    </div>

    <div class="card-body">
        <pre class="bg-light border rounded-4 p-3 mb-0" style="white-space: pre-wrap; max-height: 420px; overflow:auto;"><?= esc($detection['api_response_json'] ?: 'Tidak ada response JSON.') ?></pre>
    </div>
</div>

<?= $this->endSection() ?>