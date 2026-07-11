<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>

<style>
    :root {
        --df-red: #b31312;
        --df-red-2: #e63946;
        --df-dark: #111827;
        --df-muted: #6b7280;
        --df-border: #e5e7eb;
        --df-soft: #f8fafc;
    }

    .upload-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .upload-header h3 {
        font-weight: 900;
        color: var(--df-dark);
        margin-bottom: 6px;
    }

    .upload-header p {
        max-width: 740px;
        color: var(--df-muted);
        margin-bottom: 0;
        line-height: 1.7;
    }

    .upload-shell {
        max-width: 1080px;
        margin: 0 auto;
    }

    .upload-card {
        position: relative;
        overflow: hidden;
        border: 0;
        border-radius: 28px;
        background: #fff;
        box-shadow: 0 22px 60px rgba(15, 23, 42, .08);
    }

    .upload-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 8px;
        background: linear-gradient(90deg, var(--df-red), var(--df-red-2), #111827);
    }

    .upload-top {
        padding: 28px 30px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        border-bottom: 1px solid var(--df-border);
    }

    .upload-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .upload-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: rgba(179, 19, 18, .10);
        color: var(--df-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        flex-shrink: 0;
    }

    .upload-top h5 {
        font-weight: 900;
        color: var(--df-dark);
        margin-bottom: 4px;
    }

    .upload-top small {
        color: var(--df-muted);
    }

    .model-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #bbf7d0;
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .upload-body {
        padding: 30px;
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(280px, .55fr);
        gap: 24px;
        align-items: stretch;
    }

    .upload-zone {
        position: relative;
        min-height: 310px;
        border: 2px dashed #d7dce5;
        border-radius: 24px;
        background:
            linear-gradient(180deg, #fff, #f8fafc),
            radial-gradient(circle at top right, rgba(179, 19, 18, .08), transparent 35%);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        padding: 32px 24px;
        transition: .25s ease;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: var(--df-red);
        background: rgba(179, 19, 18, .035);
        transform: translateY(-2px);
    }

    .upload-zone input {
        display: none;
    }

    .upload-zone-inner {
        max-width: 470px;
    }

    .upload-zone-icon {
        width: 86px;
        height: 86px;
        margin: 0 auto 18px;
        border-radius: 26px;
        background: linear-gradient(135deg, rgba(179,19,18,.10), rgba(230,57,70,.14));
        color: var(--df-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
    }

    .upload-zone h4 {
        font-weight: 900;
        color: var(--df-dark);
        margin-bottom: 10px;
    }

    .upload-zone p {
        color: var(--df-muted);
        margin-bottom: 18px;
        line-height: 1.7;
    }

    .upload-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .upload-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        color: #64748b;
        border: 1px solid var(--df-border);
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }

    .upload-badge i {
        color: var(--df-red);
    }

    .upload-side {
        border-radius: 24px;
        background: linear-gradient(180deg, #fff7f7, #ffffff);
        border: 1px solid #f1d4d4;
        padding: 22px;
    }

    .upload-side h6 {
        font-weight: 900;
        color: var(--df-dark);
        margin-bottom: 16px;
    }

    .mini-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .mini-step:last-child {
        margin-bottom: 0;
    }

    .mini-step-icon {
        width: 38px;
        height: 38px;
        border-radius: 13px;
        background: rgba(179, 19, 18, .10);
        color: var(--df-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .mini-step strong {
        display: block;
        color: var(--df-dark);
        font-size: 14px;
        margin-bottom: 2px;
    }

    .mini-step span {
        color: var(--df-muted);
        font-size: 12.5px;
        line-height: 1.55;
    }

    .selected-file {
        display: none;
        margin-top: 22px;
        border: 1px solid #dce3ee;
        border-radius: 18px;
        padding: 15px 16px;
        background: #fff;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }
    /* ======================================
   VIDEO PREVIEW
====================================== */

.video-preview {
    display: none;
    margin-top: 22px;
    border: 1px solid #dce3ee;
    border-radius: 20px;
    overflow: hidden;
    background: #000;
    box-shadow: 0 10px 30px rgba(0,0,0,.08);
}

.video-preview.show{
    display:block;
}

.video-preview video{
    width:100%;
    display:block;
    max-height:420px;
    background:#000;
}

.preview-title{
    padding:12px 18px;
    background:#fff;
    border-bottom:1px solid #ececec;
    font-weight:700;
    color:#111827;
}

.preview-footer{
    padding:10px 18px;
    background:#fff;
    color:#6b7280;
    font-size:13px;
}

    .selected-file.show {
        display: flex;
    }

    .selected-file-left {
        display: flex;
        gap: 12px;
        align-items: center;
        min-width: 0;
    }

    .selected-file-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: rgba(179, 19, 18, .10);
        color: var(--df-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .selected-file-name {
        font-weight: 800;
        color: var(--df-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 520px;
    }

    .selected-file-size {
        color: var(--df-muted);
        font-size: 13px;
    }

    .remove-file {
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        transition: .2s ease;
    }

    .remove-file:hover {
        background: rgba(179,19,18,.10);
        color: var(--df-red);
    }

    .upload-footer {
        padding: 0 30px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .upload-note {
        color: #92400e;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 14px;
        padding: 11px 14px;
        font-size: 13px;
        line-height: 1.6;
        max-width: 650px;
    }

    .upload-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-detect {
        border: 0;
        border-radius: 14px;
        background: linear-gradient(90deg, var(--df-red), var(--df-red-2));
        color: #fff;
        padding: 13px 22px;
        font-weight: 800;
        box-shadow: 0 16px 32px rgba(179, 19, 18, .23);
        transition: .25s ease;
    }

    .btn-detect:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 38px rgba(179, 19, 18, .30);
    }

    .btn-detect:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Loading hanya di atas card upload */
    .card-loading {
        position: absolute;
        inset: 8px 0 0 0;
        background: rgba(255, 255, 255, .86);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 30;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .upload-card.loading .card-loading {
        display: flex;
    }

    .loading-box {
        width: 100%;
        max-width: 390px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid var(--df-border);
        box-shadow: 0 22px 60px rgba(15, 23, 42, .16);
        padding: 26px 24px;
        text-align: center;
        animation: popLoading .25s ease;
    }

    @keyframes popLoading {
        from {
            opacity: 0;
            transform: scale(.96) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .loading-box lottie-player {
        width: 145px;
        height: 145px;
        margin: 0 auto 8px;
    }

    .loading-box h5 {
        font-weight: 900;
        color: var(--df-dark);
        margin-bottom: 8px;
    }

    .loading-box p {
        color: var(--df-muted);
        font-size: 13.5px;
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .loading-line {
        height: 9px;
        background: #edf2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .loading-line span {
        display: block;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, var(--df-red), var(--df-red-2), var(--df-red));
        background-size: 220% 100%;
        animation: lineMove 1.15s infinite linear;
    }

    @keyframes lineMove {
        from { background-position: 0 0; }
        to { background-position: 220% 0; }
    }

    @media (max-width: 991px) {
        .upload-body {
            grid-template-columns: 1fr;
        }

        .upload-top,
        .upload-footer {
            padding-left: 22px;
            padding-right: 22px;
        }

        .upload-body {
            padding: 24px 22px;
        }
    }

    @media (max-width: 575px) {
        .upload-header {
            flex-direction: column;
        }

        .upload-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .model-status {
            align-self: flex-start;
        }

        .upload-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .upload-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .upload-actions .btn,
        .btn-detect {
            width: 100%;
        }

        .selected-file.show {
            align-items: flex-start;
        }

        .selected-file-name {
            max-width: 220px;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$maxSizeMb    = (int) ($max_size_mb ?? 100);
$allowedTypes = $allowed_types ?? 'mp4,avi,mov,mkv';
?>

<div class="upload-header">
    <div>
        <h3>Deteksi Video Deepfake</h3>
        <p>
            Upload video untuk dianalisis oleh model AI. Hasil klasifikasi akan ditampilkan setelah proses selesai.
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

            <div class="model-status">
                <i class="bi bi-check-circle-fill"></i>
                Model Siap
            </div>
        </div>

        <form action="<?= base_url('user/detections/store') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
            <?= csrf_field() ?>

            <div class="upload-body">
                <div>
                    <label class="upload-zone" for="video" id="uploadZone">
                        <input
                            type="file"
                            name="video"
                            id="video"
                            accept=".mp4,.avi,.mov,.mkv,video/mp4,video/x-msvideo,video/quicktime,video/x-matroska"
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
                    <!-- =========================================
     VIDEO PREVIEW
========================================= -->

<div class="video-preview" id="videoPreview">

    <div class="preview-title">
        <i class="bi bi-play-circle-fill me-2"></i>
        Preview Video
    </div>

    <video
        id="previewPlayer"
        controls
        preload="metadata"
        controlsList="nodownload">

        Browser Anda tidak mendukung video.

    </video>

    <div class="preview-footer">
        Video yang dipilih akan ditampilkan di sini sebelum proses upload.
    </div>

</div>

                    <div class="selected-file" id="selectedFileBox">
                        <div class="selected-file-left">
                            <div class="selected-file-icon">
                                <i class="bi bi-camera-video"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="selected-file-name" id="selectedFileName">-</div>
                                <div class="selected-file-size" id="selectedFileSize">-</div>
                            </div>
                        </div>

                        <button type="button" class="remove-file" id="removeFileBtn">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="upload-side">
                    <h6>Yang dilakukan sistem</h6>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-film"></i>
                        </div>
                        <div>
                            <strong>Ambil Frame</strong>
                            <span>Sistem mengambil frame dari video yang diupload.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <div>
                            <strong>Deteksi Wajah</strong>
                            <span>Area wajah diproses sebagai fokus analisis.</span>
                        </div>
                    </div>

                    <div class="mini-step">
                        <div class="mini-step-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong>Klasifikasi</strong>
                            <span>Hasil akhir ditampilkan sebagai REAL atau DEEPFAKE.</span>
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
                    <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                        Reset
                    </button>

                    <button type="submit" class="btn-detect" id="submitBtn">
                        <i class="bi bi-shield-check me-1"></i>
                        Mulai Deteksi
                    </button>
                </div>
            </div>
        </form>

        <div class="card-loading" id="cardLoading">
            <div class="loading-box">
                <lottie-player
                    src="https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json"
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

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
const uploadForm = document.getElementById('uploadForm');
const videoInput = document.getElementById('video');
const uploadZone = document.getElementById('uploadZone');
const uploadCard = document.getElementById('uploadCard');

const selectedFileBox = document.getElementById('selectedFileBox');
const selectedFileName = document.getElementById('selectedFileName');
const selectedFileSize = document.getElementById('selectedFileSize');

const videoPreview = document.getElementById('videoPreview');
const previewPlayer = document.getElementById('previewPlayer');

const removeFileBtn = document.getElementById('removeFileBtn');
const resetBtn = document.getElementById('resetBtn');
const submitBtn = document.getElementById('submitBtn');

const maxSizeMb = <?= json_encode($maxSizeMb) ?>;
const maxSizeBytes = maxSizeMb * 1024 * 1024;
const allowedExt = ['mp4', 'avi', 'mov', 'mkv'];

function formatFileSize(bytes) {

    const units = ['B','KB','MB','GB'];

    let size = bytes;
    let index = 0;

    while(size >= 1024 && index < units.length - 1){
        size /= 1024;
        index++;
    }

    return size.toFixed(size >= 10 ? 1 : 2) + ' ' + units[index];
}

function getExtension(filename){
    return filename.split('.').pop().toLowerCase();
}

function clearSelectedFile(){

    videoInput.value = '';

    selectedFileName.textContent = '-';
    selectedFileSize.textContent = '-';

    selectedFileBox.classList.remove('show');

    previewPlayer.pause();

    previewPlayer.removeAttribute('src');

    previewPlayer.load();

    videoPreview.classList.remove('show');

}

function setSelectedFile(file){

    if(!file){
        clearSelectedFile();
        return;
    }

    const ext = getExtension(file.name);

    if(!allowedExt.includes(ext)){
        alert('Format video tidak didukung. Gunakan MP4, AVI, MOV atau MKV.');
        clearSelectedFile();
        return;
    }

    if(file.size > maxSizeBytes){
        alert('Ukuran video melebihi batas maksimal ' + maxSizeMb + ' MB.');
        clearSelectedFile();
        return;
    }

    /* ============================================
       Informasi File
    ============================================ */

    selectedFileName.textContent = file.name;

    selectedFileSize.textContent = formatFileSize(file.size);

    selectedFileBox.classList.add('show');

    /* ============================================
       Preview Video
    ============================================ */

    const url = URL.createObjectURL(file);

    previewPlayer.src = url;

    previewPlayer.load();

    videoPreview.classList.add('show');

}

videoInput.addEventListener('change', function(){

    if(this.files.length){

        setSelectedFile(this.files[0]);

    }

});

removeFileBtn.addEventListener('click', function(){

    clearSelectedFile();

});

resetBtn.addEventListener('click', function(){

    setTimeout(function(){

        clearSelectedFile();

    },50);

});

uploadZone.addEventListener('dragover', function(e){

    e.preventDefault();

    uploadZone.classList.add('dragover');

});

uploadZone.addEventListener('dragleave', function(){

    uploadZone.classList.remove('dragover');

});

uploadZone.addEventListener('drop', function(e){

    e.preventDefault();

    uploadZone.classList.remove('dragover');

    const files = e.dataTransfer.files;

    if(files.length === 0){
        return;
    }

    const dt = new DataTransfer();

    dt.items.add(files[0]);

    videoInput.files = dt.files;

    setSelectedFile(files[0]);

});

uploadForm.addEventListener('submit', function(e){

    const file = videoInput.files[0];

    if(!file){
        e.preventDefault();
        alert('Silakan pilih video terlebih dahulu.');
        return;
    }

    const ext = getExtension(file.name);

    if(!allowedExt.includes(ext)){
        e.preventDefault();
        alert('Format video tidak didukung.');
        return;
    }

    if(file.size > maxSizeBytes){
        e.preventDefault();
        alert('Ukuran video melebihi batas maksimal ' + maxSizeMb + ' MB.');
        return;
    }

    uploadCard.classList.add('loading');

    submitBtn.disabled = true;

    submitBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Memproses';

});
</script>

<?= $this->endSection() ?>