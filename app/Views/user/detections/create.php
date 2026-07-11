<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php
$uploadCss = FCPATH . 'assets/css/detection-upload.css';
$assetVersion = is_file($uploadCss) ? (string) filemtime($uploadCss) : '1';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/detection-upload.css?v=' . $assetVersion) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$maxSizeMb = (int) ($maxVideoSizeMb ?? $max_size_mb ?? 100);
$allowedExtensions = $allowedVideoExtensions ?? ['mp4', 'avi', 'mov', 'mkv'];
$allowedTypes = implode(',', $allowedExtensions);
$acceptTypes = implode(',', array_map(static fn (string $type): string => '.' . $type, $allowedExtensions));
?>

<div class="upload-header">
    <div>
        <h3>Deteksi Video Deepfake</h3>
        <p>
            Upload video untuk dianalisis oleh model AI. Setelah video dipilih, area upload
            akan berubah menjadi preview video sehingga Anda bisa memastikan video yang
            dipilih sudah benar sebelum memulai proses deteksi.
        </p>
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
                    <h5>Upload Video</h5>
                    <small>Pilih satu video untuk diperiksa oleh sistem.</small>
                </div>
            </div>

            <div class="model-status <?= empty($modelReady) ? 'model-status-unavailable' : '' ?>">
                <i class="bi <?= empty($modelReady) ? 'bi-x-circle-fill' : 'bi-check-circle-fill' ?>"></i>
                <?= esc($modelStatusMessage ?? 'Model Tidak Tersedia') ?>
            </div>
        </div>

        <form action="<?= base_url('user/detections/store') ?>" method="post" enctype="multipart/form-data" id="uploadForm"
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

                            <h4>Pilih atau tarik video ke sini</h4>
                            <p>
                                Gunakan video pendek untuk proses awal agar prediksi berjalan lebih cepat.
                            </p>

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
                                    <div class="preview-file-size" id="selectedFileSize">-</div>
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
                    <h6>Yang dilakukan sistem</h6>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-film"></i>
                        </div>
                        <div>
                            <strong>Ekstraksi Frame</strong>
                            <span>Sistem mengambil sejumlah frame dari video yang diupload.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <div>
                            <strong>Deteksi Wajah</strong>
                            <span>Area wajah dipotong menggunakan model YOLOv8 sebelum dianalisis.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div>
                            <strong>Analisis Deepfake</strong>
                            <span>Model AI menganalisis artefak visual untuk menentukan REAL atau DEEPFAKE.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong>Hasil Prediksi</strong>
                            <span>Sistem menampilkan confidence score beserta hasil klasifikasinya.</span>
                        </div>
                    </div>
                </div>
            </div>

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

        <div class="card-loading" id="cardLoading">
            <div class="loading-box">
                <lottie-player
                    src="<?= base_url('assets/landing/upload-loading.json') ?>"
                    background="transparent"
                    speed="1"
                    loop
                    autoplay>
                </lottie-player>

                <h5>Video sedang dianalisis</h5>
                <p>
                    Sistem sedang mengupload video dan memproses prediksi melalui Flask API.
                </p>

                <div class="loading-line">
                    <span></span>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/lottie-player.js?v=2.0.8') ?>" defer></script>
<script src="<?= base_url('assets/js/detection-upload.js?v=' . $assetVersion) ?>" defer></script>
<?= $this->endSection() ?>
