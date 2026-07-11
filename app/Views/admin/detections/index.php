<?= $this->extend('layouts/admin') ?>

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
        'MENCURIGAKAN' => 'warning',
        'DEEPFAKE' => 'danger',
        'NO_FACE' => 'info',
        'UNKNOWN' => 'secondary',
        default => 'secondary',
    };
};
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-1">Data Deteksi Video</h3>
        <p class="text-muted mb-0">Semua riwayat deteksi video dari user masyarakat.</p>
    </div>
</div>

<div class="card table-card mb-4">
    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">Filter Data</h5>
    </div>

    <div class="card-body">
        <form action="<?= base_url('admin/detections') ?>" method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($filters['start_date'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($filters['end_date'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Hasil Prediksi</label>
                <select name="predicted_label" class="form-select">
                    <option value="">Semua</option>
                    <option value="REAL" <?= (($filters['predicted_label'] ?? '') === 'REAL') ? 'selected' : '' ?>>REAL</option>
                    <option value="MENCURIGAKAN" <?= (($filters['predicted_label'] ?? '') === 'MENCURIGAKAN') ? 'selected' : '' ?>>MENCURIGAKAN</option>
                    <option value="DEEPFAKE" <?= (($filters['predicted_label'] ?? '') === 'DEEPFAKE') ? 'selected' : '' ?>>DEEPFAKE</option>
                    <option value="NO_FACE" <?= (($filters['predicted_label'] ?? '') === 'NO_FACE') ? 'selected' : '' ?>>NO_FACE</option>
                    <option value="UNKNOWN" <?= (($filters['predicted_label'] ?? '') === 'UNKNOWN') ? 'selected' : '' ?>>UNKNOWN</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach (['pending', 'processing', 'completed', 'failed'] as $status) : ?>
                        <option value="<?= esc($status) ?>" <?= (($filters['status'] ?? '') === $status) ? 'selected' : '' ?>>
                            <?= esc($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-bawaslu">
                    <i class="bi bi-search me-1"></i>
                    Filter
                </button>

                <a href="<?= base_url('admin/detections') ?>" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Deteksi</h5>
        <span class="badge text-bg-secondary"><?= count($detections ?? []) ?> Data</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>File</th>
                    <th>User</th>
                    <th>Hasil</th>
                    <th>Confidence</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($detections)) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($detections as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($row['original_filename']) ?></div>
                                <small class="text-muted">ID: <?= esc($row['id']) ?></small>
                            </td>
                            <td>
                                <div><?= esc($row['full_name'] ?? '-') ?></div>
                                <small class="text-muted"><?= esc($row['email'] ?? '-') ?></small>
                            </td>
                            <td>
                                <span class="badge text-bg-<?= esc($labelBadge($row['predicted_label'] ?? 'UNKNOWN')) ?>">
                                    <?= esc($row['predicted_label'] ?? 'UNKNOWN') ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['confidence'] !== null) : ?>
                                    <?= number_format((float) $row['confidence'] * 100, 2) ?>%
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge text-bg-<?= esc($statusBadge($row['status'] ?? 'pending')) ?>">
                                    <?= esc($row['status'] ?? 'pending') ?>
                                </span>
                            </td>
                            <td><?= esc($row['created_at'] ?? '-') ?></td>
                            <td>
                                <a href="<?= base_url('admin/detections/detail/' . $row['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Data deteksi belum tersedia.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($pager)) : ?>
    <div class="mt-3"><?= $pager->only(['start_date', 'end_date', 'predicted_label', 'status'])->links('detections', 'default_full') ?></div>
<?php endif; ?>

<?= $this->endSection() ?>
