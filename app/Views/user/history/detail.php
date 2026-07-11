<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php
$detailCss = FCPATH . 'assets/css/detection-detail.css';
$detailAssetVersion = is_file($detailCss) ? (string) filemtime($detailCss) : '1';
$detailJs = FCPATH . 'assets/js/detection-result.js';
$detailJsVersion = is_file($detailJs) ? (string) filemtime($detailJs) : '1';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/detection-detail.css?v=' . $detailAssetVersion) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$status = strtolower((string) ($detection['status'] ?? 'pending'));
$label = strtoupper((string) ($detection['predicted_label'] ?? 'UNKNOWN'));
$isFailed = $status === 'failed';
$isProcessing = in_array($status, ['pending', 'processing'], true);
$resultType = $isFailed ? 'failed' : ($isProcessing ? 'processing' : match ($label) {
    'REAL' => 'real',
    'DEEPFAKE' => 'deepfake',
    default => 'uncertain',
});

$resultContent = match ($resultType) {
    'real' => [
        'title' => 'Video Cenderung Asli',
        'description' => 'Analisis sistem menunjukkan bahwa video ini lebih cenderung merupakan video asli.',
        'meaning' => 'Video lebih cenderung asli berdasarkan pola visual yang dianalisis oleh sistem. Namun, hasil otomatis tetap perlu dipertimbangkan bersama sumber dan konteks video.',
        'badge' => 'Cenderung Asli',
        'icon' => 'bi-check-circle-fill',
    ],
    'deepfake' => [
        'title' => 'Video Terdeteksi Deepfake',
        'description' => 'Analisis sistem menemukan indikasi manipulasi deepfake pada video ini.',
        'meaning' => 'Video menunjukkan pola yang menyerupai hasil manipulasi deepfake. Hindari langsung mempercayai atau menyebarkan video sebelum melakukan verifikasi tambahan.',
        'badge' => 'Terdeteksi Deepfake',
        'icon' => 'bi-exclamation-triangle-fill',
    ],
    'uncertain' => [
        'title' => 'Hasil Belum Meyakinkan',
        'description' => 'Skor asli dan deepfake cukup berdekatan sehingga hasil perlu diperiksa lebih lanjut.',
        'meaning' => 'Sistem belum cukup yakin untuk menentukan apakah video asli atau deepfake. Lakukan pemeriksaan manual atau gunakan sumber pembanding.',
        'badge' => 'Perlu Pemeriksaan',
        'icon' => 'bi-question-circle-fill',
    ],
    'failed' => [
        'title' => 'Video Gagal Dianalisis',
        'description' => 'Terjadi kendala saat menganalisis video. Silakan coba kembali menggunakan video lain.',
        'meaning' => 'Belum ada kesimpulan yang dapat diberikan untuk video ini karena proses analisis tidak selesai.',
        'badge' => 'Gagal Dianalisis',
        'icon' => 'bi-x-octagon-fill',
    ],
    default => [
        'title' => 'Video Sedang Diproses',
        'description' => 'Sistem sedang memproses video dan hasil belum tersedia.',
        'meaning' => 'Tunggu hingga proses selesai sebelum menggunakan hasil analisis.',
        'badge' => 'Sedang Diproses',
        'icon' => 'bi-hourglass-split',
    ],
};

$statusText = match ($status) {
    'completed', 'reviewed' => 'Selesai Diproses',
    'processing' => 'Sedang Diproses',
    'failed' => 'Gagal Dianalisis',
    default => 'Menunggu Diproses',
};

$formatBytes = static function ($bytes): string {
    if ($bytes === null || $bytes === '') return '-';
    $bytes = (float) $bytes;
    $units = ['B', 'KB', 'MB', 'GB'];
    for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) $bytes /= 1024;
    return number_format($bytes, $bytes >= 10 ? 1 : 2, ',', '.') . ' ' . $units[$i];
};

$percentValue = static function ($value): ?float {
    if ($value === null || $value === '' || ! is_numeric($value)) return null;
    return max(0, min(100, (float) $value * 100));
};

$formatPercent = static function ($value) use ($percentValue): string {
    $percent = $percentValue($value);
    return $percent === null ? '-' : number_format($percent, 2, ',', '.') . '%';
};

$formatDate = static function ($value): string {
    if (empty($value)) return '-';
    try {
        $date = new DateTime((string) $value);
        $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y, H.i');
    } catch (Throwable $exception) {
        return (string) $value;
    }
};

$realPercent = $percentValue($detection['real_score'] ?? null);
$fakePercent = $percentValue($detection['fake_score'] ?? null);
$showResultAlert = (bool) session()->getFlashdata('show_result_alert');
$alertConfig = [
    'show' => $showResultAlert,
    'type' => $resultType,
    'otherUrl' => base_url('user/detections/create'),
];
?>

<div class="detail-page-header">
    <div>
        <span class="page-eyebrow">HASIL ANALISIS VIDEO</span>
        <h3 class="page-title">Detail Hasil Deteksi</h3>
        <p>Ringkasan utama ditampilkan lebih dulu, sementara data teknis dapat dibuka bila diperlukan.</p>
    </div>
    <div class="detail-actions">
        <a href="<?= base_url('user/detections/create') ?>" class="btn btn-danger">
            <i class="bi bi-plus-circle me-1"></i> Deteksi Video Lain
        </a>
        <a href="<?= base_url('user/history') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<section class="result-hero result-<?= esc($resultType, 'attr') ?>" aria-labelledby="resultTitle">
    <div class="result-summary">
        <div class="result-icon"><i class="bi <?= esc($resultContent['icon'], 'attr') ?>"></i></div>
        <div>
            <span class="result-badge"><?= esc($resultContent['badge']) ?></span>
            <h4 id="resultTitle"><?= esc($resultContent['title']) ?></h4>
            <p><?= esc($resultContent['description']) ?></p>
        </div>
    </div>
    <div class="result-file">
        <span>Video yang diperiksa</span>
        <strong title="<?= esc($detection['original_filename'] ?? '-', 'attr') ?>"><?= esc($detection['original_filename'] ?? '-') ?></strong>
    </div>
</section>

<div class="detail-layout">
    <div class="detail-main">
        <section class="detail-card" aria-labelledby="scoreTitle">
            <div class="detail-card-heading">
                <div>
                    <span class="section-kicker">PERBANDINGAN SKOR</span>
                    <h5 id="scoreTitle">Bagaimana sistem menilai video ini?</h5>
                </div>
                <div class="confidence-pill">
                    <span>Tingkat Keyakinan Sistem</span>
                    <strong><?= esc($formatPercent($detection['confidence'] ?? null)) ?></strong>
                </div>
            </div>
            <p class="confidence-help"><i class="bi bi-info-circle"></i> Menunjukkan seberapa kuat sistem condong terhadap hasil yang dipilih.</p>

            <div class="score-row">
                <div class="score-label"><span>Skor Video Asli</span><strong class="text-success"><?= esc($formatPercent($detection['real_score'] ?? null)) ?></strong></div>
                <div class="score-track" role="progressbar" aria-label="Skor Video Asli" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= esc($realPercent ?? 0, 'attr') ?>">
                    <span class="score-real" style="width: <?= esc($realPercent ?? 0, 'attr') ?>%"></span>
                </div>
            </div>
            <div class="score-row">
                <div class="score-label"><span>Skor Deepfake</span><strong class="text-danger"><?= esc($formatPercent($detection['fake_score'] ?? null)) ?></strong></div>
                <div class="score-track" role="progressbar" aria-label="Skor Deepfake" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= esc($fakePercent ?? 0, 'attr') ?>">
                    <span class="score-fake" style="width: <?= esc($fakePercent ?? 0, 'attr') ?>%"></span>
                </div>
            </div>

            <?php if ($resultType === 'uncertain') : ?>
                <div class="uncertain-note"><i class="bi bi-exclamation-circle"></i> Nilai kedua kategori cukup berdekatan sehingga hasil belum meyakinkan.</div>
            <?php endif; ?>

            <div class="summary-metrics">
                <div><span>Kecenderungan Sistem</span><strong><?= esc($resultContent['badge']) ?></strong></div>
                <div><span>Durasi Analisis</span><strong><?= isset($detection['duration_seconds']) && $detection['duration_seconds'] !== null ? esc($detection['duration_seconds']) . ' detik' : '-' ?></strong></div>
            </div>
        </section>

        <section class="detail-card meaning-card" aria-labelledby="meaningTitle">
            <div class="meaning-icon"><i class="bi bi-lightbulb"></i></div>
            <div><h5 id="meaningTitle">Apa arti hasil ini?</h5><p><?= esc($resultContent['meaning']) ?></p></div>
        </section>

        <?php if ($isFailed && ! empty($detection['error_message'])) : ?>
            <div class="error-box"><i class="bi bi-exclamation-octagon"></i><div><strong>Kendala saat analisis</strong><p><?= esc($detection['error_message']) ?></p></div></div>
        <?php endif; ?>
    </div>

    <aside class="detail-side">
        <section class="detail-card" aria-labelledby="videoInfoTitle">
            <div class="side-card-title"><i class="bi bi-file-earmark-play"></i><h5 id="videoInfoTitle">Informasi Video</h5></div>
            <dl class="info-list">
                <div><dt>ID deteksi</dt><dd><?= esc($detection['id'] ?? '-') ?></dd></div>
                <div><dt>Nama file</dt><dd><?= esc($detection['original_filename'] ?? '-') ?></dd></div>
                <div><dt>Ukuran</dt><dd><?= esc($formatBytes($detection['file_size'] ?? null)) ?></dd></div>
                <div><dt>Format</dt><dd><?= esc($detection['file_mime'] ?? '-') ?></dd></div>
                <div><dt>Tanggal upload</dt><dd><?= esc($formatDate($detection['created_at'] ?? null)) ?></dd></div>
                <div><dt>Waktu selesai</dt><dd><?= esc($formatDate($detection['updated_at'] ?? null)) ?></dd></div>
                <div><dt>Status proses</dt><dd><span class="process-badge process-<?= esc($status, 'attr') ?>"><?= esc($statusText) ?></span></dd></div>
            </dl>
        </section>
    </aside>
</div>

<section class="technical-card">
    <div class="accordion" id="technicalAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="technicalHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#technicalDetail" aria-expanded="false" aria-controls="technicalDetail">
                    <i class="bi bi-code-square me-2"></i> Lihat Detail Teknis
                </button>
            </h2>
            <div id="technicalDetail" class="accordion-collapse collapse" aria-labelledby="technicalHeading" data-bs-parent="#technicalAccordion">
                <div class="accordion-body technical-grid">
                    <div><span>ID deteksi</span><strong><?= esc($detection['id'] ?? '-') ?></strong></div>
                    <div><span>MIME</span><strong><?= esc($detection['file_mime'] ?? '-') ?></strong></div>
                    <div><span>Status internal</span><strong><?= esc($status) ?></strong></div>
                    <div><span>Label internal</span><strong><?= esc($label) ?></strong></div>
                    <div><span>Nama model</span><strong><?= esc($detection['model_version'] ?? '-') ?></strong></div>
                    <div><span>Threshold</span><strong><?= esc($detection['threshold'] ?? '-') ?></strong></div>
                    <div><span>Frame dianalisis</span><strong><?= esc($detection['frames_used'] ?? '-') ?></strong></div>
                    <div><span>Wajah terdeteksi</span><strong><?= esc($detection['face_detected_count'] ?? '-') ?></strong></div>
                    <div><span>ID permintaan API</span><strong><?= esc($detection['request_id'] ?? '-') ?></strong></div>
                    <div><span>Catatan sistem</span><strong><?= esc($detection['confidence_note'] ?? '-') ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="resultAlertConfig" data-config="<?= esc(json_encode($alertConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'attr') ?>" hidden></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script src="<?= base_url('assets/js/detection-result.js?v=' . $detailJsVersion) ?>" defer></script>
<?= $this->endSection() ?>
