<?= $this->extend($layout ?? 'layouts/user') ?>

<?= $this->section('styles') ?>
<?php
$uploadCss = FCPATH . 'assets/css/detection-upload.css';
$assetVersion = is_file($uploadCss) ? (string) filemtime($uploadCss) : '1';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/detection-upload.css?v=' . $assetVersion) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
/*
 * Compatibility fallback:
 * Controller lama tidak mengirim status model.
 * Jika pada masa depan controller mengirim $modelReady,
 * nilai dari controller tetap menjadi sumber utama.
 */
$modelReady = isset($modelReady) ? (bool) $modelReady : true;
$modelStatusMessage = $modelStatusMessage
    ?? ($modelReady ? 'Model Siap Digunakan' : 'Model Tidak Tersedia');

$maxSizeMb = (int) ($maxVideoSizeMb ?? $max_size_mb ?? 100);

$allowedExtensionsSource = $allowedVideoExtensions
    ?? explode(',', (string) ($allowed_types ?? 'mp4,avi,mov,mkv'));

$allowedExtensions = array_values(array_filter(array_map(
    static fn ($type): string => strtolower(trim((string) $type)),
    (array) $allowedExtensionsSource
)));

$allowedTypes = implode(',', $allowedExtensions);
$acceptTypes = implode(',', array_map(
    static fn (string $type): string => '.' . $type,
    $allowedExtensions
));

$detectionResult = session()->getFlashdata('detection_result');
?>

<div class="upload-header">
    <div>
        <h3>Deteksi Video Deepfake</h3>
        <p>Unggah video yang ingin diperiksa. Sistem akan menganalisis video untuk mencari indikasi manipulasi deepfake.</p>
    </div>

    <!-- <a href="<?= base_url('user/history') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-clock-history me-1"></i>
        Riwayat
    </a> -->
</div>

<div class="upload-shell">
    <div class="upload-card" id="uploadCard">

        <div class="upload-top">
            <div class="upload-title-wrap">
                <div class="upload-icon">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <div>
                    <h5>Unggah Video</h5>
                    <small>Video akan dianalisis secara otomatis setelah dikirim.</small>
                </div>
            </div>

            <div class="model-status <?= empty($modelReady) ? 'model-status-unavailable' : '' ?>">
                <i class="bi <?= empty($modelReady) ? 'bi-x-circle-fill' : 'bi-check-circle-fill' ?>"></i>
                <?= esc($modelStatusMessage ?? 'Model Tidak Tersedia') ?>
            </div>
        </div>

        <form action="<?= base_url($formAction ?? 'user/detections/store') ?>" method="post" enctype="multipart/form-data" id="uploadForm" novalidate
              data-max-size-mb="<?= esc($maxSizeMb, 'attr') ?>"
              data-max-duration-seconds="<?= esc((int) ($maxVideoDuration ?? 0), 'attr') ?>"
              data-allowed-extensions="<?= esc(json_encode($allowedExtensions), 'attr') ?>">
            <?= csrf_field() ?>

            <div class="upload-body">
                <div>
                    <!-- ================================================
                         UPLOAD ZONE (placeholder)
                         Disembunyikan lewat class .hide begitu video
                         sudah dipilih, digantikan oleh #uploadPreview.
                    ================================================= -->
                    <label class="upload-zone" for="video" id="uploadZone">
                        <input
                            type="file"
                            name="video"
                            id="video"
                            accept="<?= esc($acceptTypes, 'attr') ?>"
                            required
                        >

                        <div class="upload-zone-inner">
                            <div class="upload-zone-icon">
                                <i class="bi bi-file-earmark-play"></i>
                            </div>

                            <h4>Tarik dan letakkan video di sini</h4>
                            <p>atau klik untuk memilih video dari perangkat Anda</p>

                            <span class="upload-choose-button">
                                <i class="bi bi-folder2-open"></i>
                                Pilih Video
                            </span>

                            <div class="upload-badges">
                                <span class="upload-badge">
                                    <i class="bi bi-filetype-mp4"></i>
                                    <?= esc($allowedTypes) ?>
                                </span>
                                <span class="upload-badge">
                                    <i class="bi bi-hdd"></i>
                                    Maks. <?= esc($maxSizeMb) ?> MB
                                </span>
                            </div>
                        </div>
                    </label>

                    <!-- ================================================
                         VIDEO PREVIEW
                         Ini yang menggantikan posisi upload zone begitu
                         video dipilih (via klik ataupun drag & drop).
                    ================================================= -->
                    <div class="upload-preview" id="uploadPreview">
                        <div class="preview-header">
                            <h6>
                                <i class="bi bi-play-circle-fill me-2"></i>
                                Preview Video
                            </h6>
                        </div>

                        <div class="preview-video">
                            <video
                                id="previewPlayer"
                                controls
                                preload="metadata"
                                controlsList="nodownload">
                                Browser Anda tidak mendukung video.
                            </video>
                        </div>

                        <div class="preview-info">
                            <div class="preview-file">
                                <div class="preview-file-icon">
                                    <i class="bi bi-camera-video-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="preview-file-name" id="selectedFileName">-</div>
                                    <div class="preview-file-meta">
                                        <span id="selectedFileSize">-</span>
                                        <span aria-hidden="true">&bull;</span>
                                        <span id="selectedFileType">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="preview-actions">
                                <button type="button" class="btn-change-video" id="changeVideoBtn">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Ganti Video
                                </button>
                                <button type="button" class="btn-remove-video" id="removeVideoBtn">
                                    <i class="bi bi-trash me-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="upload-side">
                    <h6>Informasi unggahan</h6>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-film"></i>
                        </div>
                        <div>
                            <strong>Format yang didukung</strong>
                            <span><?= esc(strtoupper(implode(', ', $allowedExtensions))) ?></span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <div>
                            <strong>Ukuran maksimal</strong>
                            <span><?= esc($maxSizeMb) ?> MB sesuai konfigurasi aplikasi.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div>
                            <strong>Analisis otomatis</strong>
                            <span>Sistem memeriksa frame, wajah, dan pola visual video.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong>Privasi proses</strong>
                            <span>Hanya satu file dikirim saat Anda menekan Mulai Deteksi.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="upload-validation" id="uploadValidation" role="alert" aria-live="polite" hidden></div>

            <div class="upload-footer">
                <div class="upload-note">
                    <i class="bi bi-info-circle me-1"></i>
                    Jangan menutup halaman ketika proses upload dan prediksi sedang berjalan.
                </div>

                <div class="upload-actions">
                    <!-- <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                        Reset
                    </button> -->

                    <button type="submit" class="btn-detect" id="submitBtn" <?= empty($modelReady) ? 'disabled' : '' ?>>
                        <i class="bi bi-shield-check me-1"></i>
                        Mulai Deteksi
                    </button>
                </div>
            </div>
        </form>

        <div class="card-loading" id="cardLoading" role="dialog" aria-modal="true" aria-labelledby="analysisTitle">
            <div class="loading-box">
                <div class="loading-visual" aria-hidden="true">
                    <div class="loading-animation">
                        <lottie-player
                            src="<?= base_url('assets/landing/upload-loading.json') ?>"
                            background="transparent"
                            speed="1"
                            loop
                            autoplay>
                        </lottie-player>
                    </div>
                </div>

                <span class="loading-kicker"><i class="bi bi-stars"></i> Analisis video otomatis</span>

                <h5 id="analysisTitle">Mengunggah Video</h5>

                <div class="upload-progress-wrap" id="uploadProgressWrap" aria-live="polite">
                    <div class="upload-progress-meta">
                        <span id="uploadProgressLabel">Upload 0%</span>
                        <span id="analysisElapsed">00:00</span>
                    </div>
                    <progress id="uploadProgress" max="100" value="0">0%</progress>
                </div>

                <div class="analysis-insight" id="analysisInsight" aria-live="polite">
                    <div class="analysis-insight-icon" id="analysisInsightIcon"><i class="bi bi-lightbulb"></i></div>
                    <div class="analysis-insight-copy">
                        <span id="analysisInsightLabel">Tahukah Anda?</span>
                        <strong id="analysisInsightTitle">Video diperiksa dari banyak frame</strong>
                        <p id="analysisInsightText">Sistem membandingkan pola visual pada sejumlah frame, bukan hanya satu gambar.</p>
                    </div>
                    <div class="analysis-insight-dots" id="analysisInsightDots" aria-hidden="true">
                        <span class="active"></span><span></span><span></span><span></span>
                    </div>
                </div>

                <small class="loading-note"><i class="bi bi-shield-lock"></i> Video diproses dengan aman. Jangan menutup halaman.</small>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script src="<?= base_url('assets/js/lottie-player.js?v=2.0.8') ?>" defer></script>
<script src="<?= base_url('assets/js/detection-upload.js?v=' . $assetVersion) ?>" defer></script>
<?php if (is_array($detectionResult)) : ?>
<?php
$modalPayload = json_encode($detectionResult, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES);
?>
<script>
window.addEventListener('DOMContentLoaded', function () {
    const result = <?= $modalPayload ?: '{}' ?>;
    const label = String(result.label || 'UNKNOWN').toUpperCase();
    const confidence = result.confidence === null || result.confidence === undefined
        ? null
        : Math.max(0, Math.min(100, Number(result.confidence) * 100));

    const presentations = {
        REAL: {
            icon: 'success',
            title: 'Video Anda Terdeteksi Real',
            color: '#059669',
            description: 'Sistem mendeteksi video ini sebagai video real.'
        },
        DEEPFAKE: {
            icon: 'warning',
            title: 'Video Anda Terdeteksi Deepfake',
            color: '#dc2626',
            description: 'Sistem mendeteksi video ini sebagai video deepfake.'
        },
        MENCURIGAKAN: {
            icon: 'question',
            title: 'Hasil Video Mencurigakan',
            color: '#d97706',
            description: 'Skor berada tepat di batas keputusan. Video sebaiknya diperiksa lebih lanjut.'
        },
        NO_FACE: {
            icon: 'info',
            title: 'Wajah Tidak Terdeteksi',
            color: '#2563eb',
            description: 'Sistem belum dapat menilai video karena wajah tidak terlihat dengan cukup jelas.'
        }
    };
    const view = presentations[label] || {
        icon: 'info', title: 'Analisis Video Selesai', color: '#475569',
        description: 'Proses analisis telah selesai. Buka detail untuk melihat hasil lengkap.'
    };
    const confidenceText = confidence === null || Number.isNaN(confidence)
        ? ''
        : '<div style="margin-top:14px;padding:12px;border-radius:12px;background:#f8fafc">Tingkat keyakinan: <strong>' + confidence.toFixed(2).replace('.', ',') + '%</strong></div>';

    Swal.fire({
        icon: view.icon,
        title: '<strong>' + view.title + '</strong>',
        html: '<p style="margin:0;color:#64748b">' + view.description + '</p>' + confidenceText,
        confirmButtonText: '<i class="bi bi-eye me-1"></i> Lihat Detail',
        showDenyButton: true,
        denyButtonText: '<i class="bi bi-plus-circle me-1"></i> Deteksi Lagi',
        confirmButtonColor: view.color,
        denyButtonColor: '#64748b',
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: 560,
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-3 px-4', denyButton: 'rounded-3 px-4' }
    }).then(function (choice) {
        if (choice.isConfirmed && result.detail_url) {
            window.location.href = result.detail_url;
        }
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
