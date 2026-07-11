<?= $this->extend('layouts/public') ?>

<?= $this->section('styles') ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --register-red: #b31312;
        --register-red-2: #e63946;
        --register-dark: #1f1f1f;
        --register-text: #1f2937;
        --register-muted: #6b7280;
        --register-border: #e5e7eb;
    }

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .register-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background:
            linear-gradient(135deg, rgba(179, 19, 18, .96), rgba(31, 31, 31, .96)),
            radial-gradient(circle at top right, rgba(255,255,255,.14), transparent 35%);
        position: relative;
        overflow: hidden;
    }

    .register-page::before {
        content: "";
        position: absolute;
        inset: auto 0 0 0;
        height: 170px;
        background-image:
            linear-gradient(to right, rgba(255,255,255,.06) 1px, transparent 1px),
            linear-gradient(to top, rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: .35;
    }

    .register-wrapper {
        width: 100%;
        max-width: 1120px;
        min-height: 650px;
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        border-radius: 26px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 28px 80px rgba(0,0,0,.28);
        position: relative;
        z-index: 2;
    }

    /* =========================
       LEFT PANEL
    ========================= */
    .register-left {
        background:
            linear-gradient(145deg, rgba(179, 19, 18, .96), rgba(31, 31, 31, .98));
        color: #fff;
        padding: 46px 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .register-left::before {
        content: "";
        position: absolute;
        width: 360px;
        height: 360px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.16), transparent 68%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .register-left-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 470px;
    }

    .register-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.16);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 26px;
    }

    .register-visual {
        width: 240px;
        height: 240px;
        margin: 0 auto 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.14);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
        position: relative;
    }

    .register-visual::before,
    .register-visual::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.72);
        border-color: rgba(255,255,255,.72) transparent transparent transparent;
    }

    .register-visual::before {
        width: 190px;
        height: 190px;
        transform: rotate(-22deg);
    }

    .register-visual::after {
        width: 220px;
        height: 220px;
        transform: rotate(155deg);
        opacity: .45;
    }

    .register-visual-box {
        width: 130px;
        height: 130px;
        border-radius: 24px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        position: relative;
        z-index: 2;
    }

    .register-visual-box i {
        font-size: 54px;
        color: #fff;
    }

    .register-left h1 {
        font-size: 24px;
        line-height: 1.35;
        font-weight: 800;
        margin-bottom: 14px;
        color: #fff;
    }

    .register-left p {
        font-size: 14.5px;
        line-height: 1.9;
        color: rgba(255,255,255,.85);
        margin-bottom: 28px;
    }

    .register-left-points {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .register-point {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 16px;
        padding: 14px 10px;
    }

    .register-point strong {
        display: block;
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .register-point span {
        font-size: 12px;
        color: rgba(255,255,255,.76);
    }

    /* =========================
       RIGHT PANEL
    ========================= */
    .register-right {
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 38px 38px;
    }

    .register-form-wrap {
        width: 100%;
        max-width: 410px;
    }

    .register-logo {
        text-align: center;
        margin-bottom: 16px;
    }

    .register-logo img {
        height: 70px;
        max-width: 240px;
        object-fit: contain;
    }

    .register-title {
        text-align: center;
        margin-bottom: 24px;
    }

    .register-title h2 {
        font-size: 32px;
        font-weight: 800;
        color: var(--register-text);
        margin-bottom: 8px;
    }

    .register-title p {
        margin: 0;
        font-size: 14.5px;
        color: var(--register-muted);
    }

    .register-alert {
        border-radius: 14px;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .floating-group {
        position: relative;
        margin-bottom: 18px;
    }

    .floating-group input {
        width: 100%;
        height: 58px;
        border: 1.8px solid #d8dde6;
        border-radius: 14px;
        background: #fff;
        outline: none;
        font-size: 14.5px;
        color: var(--register-text);
        padding: 23px 46px 9px 16px;
        transition: .25s ease;
    }

    .floating-group input:focus {
        border-color: var(--register-red);
        box-shadow: 0 0 0 4px rgba(179, 19, 18, .08);
    }

    .floating-group label {
        position: absolute;
        left: 16px;
        top: 17px;
        color: var(--register-muted);
        font-size: 14px;
        font-weight: 500;
        pointer-events: none;
        transition: .22s ease;
        background: #fff;
        padding: 0 6px;
    }

    .floating-group input:focus + label,
    .floating-group input:not(:placeholder-shown) + label {
        top: -8px;
        left: 12px;
        font-size: 12px;
        font-weight: 700;
        color: var(--register-red);
    }

    .floating-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
    }

    .password-toggle {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 0;
        background: transparent;
        color: #94a3b8;
        border-radius: 10px;
        transition: .2s ease;
    }

    .password-toggle:hover {
        background: rgba(179,19,18,.06);
        color: var(--register-red);
    }

    .register-submit {
        width: 100%;
        height: 58px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--register-red), var(--register-red-2));
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 16px 34px rgba(179,19,18,.22);
        transition: .25s ease;
        margin-top: 6px;
    }

    .register-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(179,19,18,.30);
    }

    .register-links {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 22px;
        font-size: 14px;
    }

    .register-links a {
        text-decoration: none;
        color: var(--register-text);
        font-weight: 600;
        transition: .2s ease;
    }

    .register-links a:hover {
        color: var(--register-red);
    }

    .register-back {
        text-align: center;
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid #eef1f5;
    }

    .register-back a {
        color: var(--register-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .register-back a:hover {
        color: var(--register-red);
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 991px) {
        .register-wrapper {
            grid-template-columns: 1fr;
            max-width: 620px;
        }

        .register-left {
            padding: 36px 24px;
        }

        .register-right {
            padding: 36px 24px;
        }

        .register-visual {
            width: 210px;
            height: 210px;
        }

        .register-left-points {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .register-page {
            padding: 14px;
        }

        .register-wrapper {
            border-radius: 20px;
        }

        .register-left,
        .register-right {
            padding: 28px 18px;
        }

        .register-title h2 {
            font-size: 28px;
        }

        .register-links {
            flex-direction: column;
            text-align: center;
        }

        .register-logo img {
            height: 64px;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="register-page">
    <div class="register-wrapper">

        <!-- LEFT -->
        <div class="register-left">
            <div class="register-left-content">
                <div class="register-badge">
                    <i class="bi bi-person-plus"></i>
                    Registrasi User Masyarakat
                </div>

                <div class="register-visual">
                    <div class="register-visual-box">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>

                <h1>
                    Buat Akun untuk<br>
                    Verifikasi Video Deepfake
                </h1>

                <p>
                    Daftar sebagai user masyarakat untuk mengupload video,
                    melihat hasil deteksi, serta menyimpan riwayat klasifikasi
                    secara aman di dalam sistem.
                </p>

                <div class="register-left-points">
                    <div class="register-point">
                        <strong>Upload</strong>
                        <span>Video</span>
                    </div>
                    <div class="register-point">
                        <strong>Deteksi</strong>
                        <span>AI Model</span>
                    </div>
                    <div class="register-point">
                        <strong>Riwayat</strong>
                        <span>Tersimpan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="register-right">
            <div class="register-form-wrap">

                <div class="register-logo">
                    <img src="<?= base_url('assets/landing/logo.png') ?>" alt="BAWASLU Logo">
                </div>

                <div class="register-title">
                    <h2>Register</h2>
                    <p>Buat akun masyarakat untuk menggunakan sistem</p>
                </div>

                <?= $this->include('partials/alert') ?>

                <form action="<?= base_url('register') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="floating-group">
                        <input
                            type="text"
                            name="full_name"
                            id="full_name"
                            value="<?= old('full_name') ?>"
                            placeholder=" "
                            required
                        >
                        <label for="full_name">Nama Lengkap</label>
                        <i class="bi bi-person floating-icon"></i>
                    </div>

                    <div class="floating-group">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?= old('email') ?>"
                            placeholder=" "
                            required
                        >
                        <label for="email">Email</label>
                        <i class="bi bi-envelope floating-icon"></i>
                    </div>

                    <div class="floating-group">
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="<?= old('phone') ?>"
                            placeholder=" "
                        >
                        <label for="phone">Nomor HP</label>
                        <i class="bi bi-telephone floating-icon"></i>
                    </div>

                    <div class="floating-group">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder=" "
                            required
                        >
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>

                    <div class="floating-group">
                        <input
                            type="password"
                            name="password_confirm"
                            id="password_confirm"
                            placeholder=" "
                            required
                        >
                        <label for="password_confirm">Konfirmasi Password</label>
                        <button type="button" class="password-toggle" id="togglePasswordConfirm">
                            <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                        </button>
                    </div>

                    <button type="submit" class="register-submit">
                        <i class="bi bi-person-plus me-2"></i>
                        Register
                    </button>
                </form>

                <div class="register-links">
                    <a href="<?= base_url('login') ?>">Sudah punya akun?</a>
                    <a href="<?= base_url('login') ?>">Login sekarang</a>
                </div>
<!-- 
                <div class="register-back">
                    <a href="<?= base_url('/') ?>">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke landing page
                    </a>
                </div>

            </div> -->
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    function togglePassword(inputId, buttonId, iconId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const icon = document.getElementById(iconId);

        if (!input || !button || !icon) {
            return;
        }

        button.addEventListener('click', function () {
            const isPassword = input.getAttribute('type') === 'password';

            input.setAttribute('type', isPassword ? 'text' : 'password');
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    togglePassword('password', 'togglePassword', 'togglePasswordIcon');
    togglePassword('password_confirm', 'togglePasswordConfirm', 'togglePasswordConfirmIcon');
</script>

<?= $this->endSection() ?>