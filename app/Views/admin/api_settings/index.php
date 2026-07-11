<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>

<style>
    :root {
        --api-red: #c1121f;
        --api-red-2: #e63946;
        --api-dark: #111827;
        --api-muted: #6b7280;
        --api-border: #e5e7eb;
        --api-soft: #f8fafc;
        --api-green: #059669;
        --api-blue: #2563eb;
        --api-orange: #f59e0b;
    }

    .api-header {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 28%),
            linear-gradient(135deg, #111827 0%, #1f2937 42%, #7f1d1d 100%);
        padding: 30px;
        color: #fff;
        box-shadow: 0 24px 55px rgba(15, 23, 42, .15);
        margin-bottom: 24px;
    }

    .api-header::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -80px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(255,255,255,.09);
    }

    .api-header::after {
        content: "";
        position: absolute;
        left: -70px;
        bottom: -70px;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: rgba(193,18,31,.25);
    }

    .api-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }

    .api-header h3 {
        font-weight: 900;
        color: #fff;
        margin-bottom: 8px;
    }

    .api-header p {
        max-width: 780px;
        color: rgba(255,255,255,.82);
        line-height: 1.75;
        margin-bottom: 0;
    }

    .api-header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-api-light {
        border: 0;
        border-radius: 16px;
        background: #fff;
        color: var(--api-red);
        padding: 13px 18px;
        font-weight: 800;
        box-shadow: 0 14px 32px rgba(0,0,0,.16);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-api-glass {
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

    .btn-api-light:hover {
        color: var(--api-red);
        transform: translateY(-1px);
    }

    .btn-api-glass:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .api-status-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .api-status-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, .06);
    }

    .api-status-card::before {
        content: "";
        position: absolute;
        right: -38px;
        top: -38px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: .10;
    }

    .api-status-card.url::before {
        background: var(--api-blue);
    }

    .api-status-card.endpoint::before {
        background: var(--api-red);
    }

    .api-status-card.status::before {
        background: var(--api-green);
    }

    .api-status-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        z-index: 2;
    }

    .api-status-card span {
        display: block;
        color: var(--api-muted);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .api-status-card strong {
        display: block;
        color: var(--api-dark);
        font-size: 16px;
        font-weight: 900;
        word-break: break-word;
        line-height: 1.45;
    }

    .api-status-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .api-status-icon.url {
        background: rgba(37,99,235,.10);
        color: var(--api-blue);
    }

    .api-status-icon.endpoint {
        background: rgba(193,18,31,.10);
        color: var(--api-red);
    }

    .api-status-icon.status {
        background: rgba(5,150,105,.10);
        color: var(--api-green);
    }

    .api-main-grid {
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        gap: 22px;
    }

    .api-card {
        background: #fff;
        border: 0;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .api-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--api-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .api-card-title {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .api-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: rgba(193,18,31,.10);
        color: var(--api-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .api-card-header h5 {
        font-weight: 900;
        color: var(--api-dark);
        margin-bottom: 3px;
    }

    .api-card-header small {
        color: var(--api-muted);
    }

    .api-card-body {
        padding: 24px;
    }

    .api-form-label {
        color: var(--api-dark);
        font-weight: 800;
        margin-bottom: 8px;
    }

    .api-input-group {
        border: 1.8px solid #d8dde6;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        transition: .22s ease;
    }

    .api-input-group:focus-within {
        border-color: var(--api-red);
        box-shadow: 0 0 0 4px rgba(193,18,31,.08);
    }

    .api-input-group .input-group-text {
        border: 0;
        background: #fff;
        color: var(--api-red);
        min-width: 50px;
        justify-content: center;
    }

    .api-input-group .form-control {
        border: 0;
        box-shadow: none !important;
        padding-top: 13px;
        padding-bottom: 13px;
        font-size: 14.5px;
        color: var(--api-dark);
    }

    .api-help {
        display: block;
        margin-top: 7px;
        color: var(--api-muted);
        font-size: 12.5px;
        line-height: 1.6;
    }

    .api-preview-box {
        margin-top: 18px;
        border-radius: 18px;
        border: 1px solid #dbe3ef;
        background: #f8fafc;
        padding: 16px;
    }

    .api-preview-box span {
        display: block;
        font-size: 12.5px;
        color: var(--api-muted);
        font-weight: 700;
        margin-bottom: 6px;
    }

    .api-preview-url {
        color: var(--api-dark);
        font-family: Consolas, Monaco, monospace;
        font-size: 13.5px;
        word-break: break-all;
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 11px 12px;
    }

    .api-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .btn-api-primary {
        border: 0;
        border-radius: 14px;
        background: linear-gradient(90deg, var(--api-red), var(--api-red-2));
        color: #fff;
        padding: 13px 20px;
        font-weight: 800;
        box-shadow: 0 14px 30px rgba(193,18,31,.22);
    }

    .btn-api-primary:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 36px rgba(193,18,31,.28);
    }

    .api-info-list {
        display: grid;
        gap: 14px;
    }

    .api-info-item {
        display: flex;
        gap: 13px;
        align-items: flex-start;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 16px;
        background: linear-gradient(180deg, #fff, #fbfbfb);
    }

    .api-info-icon {
        width: 42px;
        height: 42px;
        border-radius: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(193,18,31,.09);
        color: var(--api-red);
        flex-shrink: 0;
        font-size: 20px;
    }

    .api-info-item strong {
        display: block;
        color: var(--api-dark);
        font-size: 14px;
        margin-bottom: 4px;
    }

    .api-info-item span {
        display: block;
        color: var(--api-muted);
        font-size: 13px;
        line-height: 1.65;
    }

    .api-warning {
        margin-top: 18px;
        border-radius: 18px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #92400e;
        padding: 16px;
        line-height: 1.7;
        font-size: 13.5px;
    }

    .api-warning i {
        color: #ea580c;
    }

    @media (max-width: 1199.98px) {
        .api-status-grid {
            grid-template-columns: 1fr;
        }

        .api-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .api-header {
            padding: 24px 20px;
        }

        .api-header-content {
            align-items: flex-start;
        }

        .api-header-actions,
        .api-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .btn-api-light,
        .btn-api-glass,
        .btn-api-primary,
        .api-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .api-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
/*
 * Helper kecil agar view aman dipakai walaupun controller mengirim data
 * dalam bentuk $settings atau $apiSettings.
 */
$settingSource = $settings ?? $apiSettings ?? [];

$getSetting = static function (string $key, string $default = '') use ($settingSource): string {
    if (empty($settingSource)) {
        return $default;
    }

    if (is_array($settingSource)) {
        if (array_key_exists($key, $settingSource)) {
            return (string) $settingSource[$key];
        }

        foreach ($settingSource as $item) {
            if (is_array($item)) {
                $itemKey = $item['setting_key'] ?? $item['key'] ?? $item['name'] ?? null;
                $itemValue = $item['setting_value'] ?? $item['value'] ?? null;

                if ($itemKey === $key) {
                    return (string) $itemValue;
                }
            }
        }
    }

    return $default;
};

$baseUrl = old('flask_api_base_url', $getSetting('flask_api_base_url', 'http://127.0.0.1:5000'));
$predictEndpoint = old('flask_api_predict_endpoint', $getSetting('flask_api_predict_endpoint', '/predict-video'));

$baseUrl = rtrim((string) $baseUrl, '/');
$predictEndpoint = '/' . ltrim((string) $predictEndpoint, '/');
$fullPredictUrl = $baseUrl . $predictEndpoint;
?>

<div class="api-header">
    <div class="api-header-content">
        <div>
            <h3>Konfigurasi Flask API</h3>
            <p>
                Atur koneksi antara website CodeIgniter 4 dengan backend Flask API yang menjalankan model deteksi deepfake.
                Pastikan URL dan endpoint sesuai dengan server Flask yang sedang aktif.
            </p>
        </div>

        <div class="api-header-actions">
            <a href="<?= base_url('admin/api-settings/test') ?>" class="btn-api-light">
                <i class="bi bi-wifi"></i>
                Test Koneksi
            </a>

            <a href="<?= esc($baseUrl) ?>" target="_blank" class="btn-api-glass">
                <i class="bi bi-box-arrow-up-right"></i>
                Buka API
            </a>
        </div>
    </div>
</div>

<div class="api-status-grid">
    <div class="api-status-card url">
        <div class="api-status-top">
            <div>
                <span>Base URL Flask</span>
                <strong><?= esc($baseUrl) ?></strong>
            </div>
            <div class="api-status-icon url">
                <i class="bi bi-globe2"></i>
            </div>
        </div>
    </div>

    <div class="api-status-card endpoint">
        <div class="api-status-top">
            <div>
                <span>Endpoint Prediksi</span>
                <strong><?= esc($predictEndpoint) ?></strong>
            </div>
            <div class="api-status-icon endpoint">
                <i class="bi bi-hdd-network"></i>
            </div>
        </div>
    </div>

    <div class="api-status-card status">
        <div class="api-status-top">
            <div>
                <span>Status Integrasi</span>
                <strong>Siap Dikonfigurasi</strong>
            </div>
            <div class="api-status-icon status">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="api-main-grid">
    <div class="api-card">
        <div class="api-card-header">
            <div class="api-card-title">
                <div class="api-card-icon">
                    <i class="bi bi-sliders"></i>
                </div>
                <div>
                    <h5>Pengaturan Endpoint</h5>
                    <small>Ubah URL Flask API dan endpoint prediksi video.</small>
                </div>
            </div>
        </div>

        <div class="api-card-body">
            <form action="<?= base_url('admin/api-settings/update') ?>" method="post" id="apiSettingForm">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="flask_api_base_url" class="form-label api-form-label">
                        Base URL Flask API
                    </label>

                    <div class="input-group api-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-link-45deg"></i>
                        </span>
                        <input
                            type="url"
                            class="form-control"
                            id="flask_api_base_url"
                            name="flask_api_base_url"
                            value="<?= esc($baseUrl) ?>"
                            placeholder="http://127.0.0.1:5000"
                            required
                        >
                    </div>

                    <span class="api-help">
                        Contoh: <code>http://127.0.0.1:5000</code>. Jangan tambahkan slash di akhir URL.
                    </span>
                </div>

                <div class="mb-4">
                    <label for="flask_api_predict_endpoint" class="form-label api-form-label">
                        Endpoint Prediksi Video
                    </label>

                    <div class="input-group api-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-terminal"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            id="flask_api_predict_endpoint"
                            name="flask_api_predict_endpoint"
                            value="<?= esc($predictEndpoint) ?>"
                            placeholder="/predict-video"
                            required
                        >
                    </div>

                    <span class="api-help">
                        Endpoint ini dipakai saat website mengirim video ke Flask API.
                    </span>
                </div>

                <div class="api-preview-box">
                    <span>Preview URL Prediksi</span>
                    <div class="api-preview-url" id="previewUrl">
                        <?= esc($fullPredictUrl) ?>
                    </div>
                </div>

                <div class="api-actions">
                    <!-- <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <a href="<?= base_url('admin/api-settings/test') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-wifi me-1"></i>
                        Test Koneksi
                    </a> -->

                    <button type="submit" class="btn-api-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="api-card">
        <div class="api-card-header">
            <div class="api-card-title">
                <div class="api-card-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div>
                    <h5>Informasi Integrasi</h5>
                    <small>Ringkasan alur komunikasi sistem.</small>
                </div>
            </div>
        </div>

        <div class="api-card-body">
            <div class="api-info-list">
                <div class="api-info-item">
                    <div class="api-info-icon">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div>
                        <strong>Upload dari Website</strong>
                        <span>User mengupload video melalui halaman deteksi video di CodeIgniter 4.</span>
                    </div>
                </div>

                <div class="api-info-item">
                    <div class="api-info-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <strong>Kirim ke Flask API</strong>
                        <span>Website mengirim file video ke endpoint prediksi Flask API.</span>
                    </div>
                </div>

                <div class="api-info-item">
                    <div class="api-info-icon">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <div>
                        <strong>Model Melakukan Prediksi</strong>
                        <span>Flask API menjalankan proses ekstraksi fitur dan klasifikasi REAL atau DEEPFAKE.</span>
                    </div>
                </div>

                <div class="api-info-item">
                    <div class="api-info-icon">
                        <i class="bi bi-database-check"></i>
                    </div>
                    <div>
                        <strong>Hasil Disimpan</strong>
                        <span>Response API disimpan ke database dan ditampilkan pada halaman riwayat deteksi.</span>
                    </div>
                </div>
            </div>

            <div class="api-warning">
                <i class="bi bi-exclamation-circle me-1"></i>
                Pastikan Flask API sedang berjalan sebelum user melakukan upload video.
                Jalankan Flask dari folder <code>flask_api</code> menggunakan perintah <code>python app.py</code>.
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const baseUrlInput = document.getElementById('flask_api_base_url');
    const endpointInput = document.getElementById('flask_api_predict_endpoint');
    const previewUrl = document.getElementById('previewUrl');

    function normalizeBaseUrl(value) {
        return value.replace(/\/+$/, '');
    }

    function normalizeEndpoint(value) {
        value = value.trim();

        if (!value) {
            return '';
        }

        return '/' + value.replace(/^\/+/, '');
    }

    function updatePreview() {
        const baseUrl = normalizeBaseUrl(baseUrlInput.value.trim());
        const endpoint = normalizeEndpoint(endpointInput.value.trim());

        previewUrl.textContent = baseUrl + endpoint;
    }

    if (baseUrlInput && endpointInput && previewUrl) {
        baseUrlInput.addEventListener('input', updatePreview);
        endpointInput.addEventListener('input', updatePreview);

        document.getElementById('apiSettingForm').addEventListener('submit', function () {
            baseUrlInput.value = normalizeBaseUrl(baseUrlInput.value.trim());
            endpointInput.value = normalizeEndpoint(endpointInput.value.trim());
        });
    }
</script>

<?= $this->endSection() ?>