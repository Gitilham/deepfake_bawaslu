<?= $this->extend('layouts/public') ?>

<?= $this->section('styles') ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --login-red: #b31312;
        --login-red-2: #e63946;
        --login-dark: #1f1f1f;
        --login-text: #1f2937;
        --login-muted: #6b7280;
        --login-border: #e5e7eb;
        --login-soft: #f8fafc;
    }

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .login-page {
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

    .login-page::before {
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

    .login-wrapper {
        width: 100%;
        max-width: 1080px;
        min-height: 620px;
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
    .login-left {
        background:
            linear-gradient(145deg, rgba(179, 19, 18, .96), rgba(31, 31, 31, .98));
        color: #fff;
        padding: 46px 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .login-left::before {
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

    .login-left-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 470px;
    }

    .login-badge {
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

    .login-visual {
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

    .login-visual::before,
    .login-visual::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.72);
        border-color: rgba(255,255,255,.72) transparent transparent transparent;
    }

    .login-visual::before {
        width: 190px;
        height: 190px;
        transform: rotate(-22deg);
    }

    .login-visual::after {
        width: 220px;
        height: 220px;
        transform: rotate(155deg);
        opacity: .45;
    }

    .login-visual-box {
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

    .login-visual-box i {
        font-size: 54px;
        color: #fff;
    }

    .login-left h1 {
        font-size: 24px;
        line-height: 1.35;
        font-weight: 800;
        margin-bottom: 14px;
        color: #fff;
    }

    .login-left p {
        font-size: 14.5px;
        line-height: 1.9;
        color: rgba(255,255,255,.85);
        margin-bottom: 28px;
    }

    .login-left-points {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .login-point {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 16px;
        padding: 14px 10px;
    }

    .login-point strong {
        display: block;
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .login-point span {
        font-size: 12px;
        color: rgba(255,255,255,.76);
    }

    /* =========================
       RIGHT PANEL
    ========================= */
    .login-right {
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 46px 38px;
    }

    .login-form-wrap {
        width: 100%;
        max-width: 390px;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 22px;
    }

    .login-logo img {
        height: 72px;
        max-width: 240px;
        object-fit: contain;
    }

    .login-title {
        text-align: center;
        margin-bottom: 28px;
    }

    .login-title h2 {
        font-size: 34px;
        font-weight: 800;
        color: var(--login-text);
        margin-bottom: 8px;
    }

    .login-title p {
        margin: 0;
        font-size: 15px;
        color: var(--login-muted);
    }

    .login-alert {
        border-radius: 14px;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .floating-group {
        position: relative;
        margin-bottom: 22px;
    }

    .floating-group input {
        width: 100%;
        height: 60px;
        border: 1.8px solid #d8dde6;
        border-radius: 14px;
        background: #fff;
        outline: none;
        font-size: 15px;
        color: var(--login-text);
        padding: 24px 46px 10px 16px;
        transition: .25s ease;
    }

    .floating-group input:focus {
        border-color: var(--login-red);
        box-shadow: 0 0 0 4px rgba(179, 19, 18, .08);
    }

    .floating-group label {
        position: absolute;
        left: 16px;
        top: 18px;
        color: var(--login-muted);
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
        color: var(--login-red);
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
        color: var(--login-red);
    }

    .login-submit {
        width: 100%;
        height: 58px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--login-red), var(--login-red-2));
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 16px 34px rgba(179,19,18,.22);
        transition: .25s ease;
        margin-top: 8px;
    }

    .login-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(179,19,18,.30);
    }

    .login-links {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 22px;
        font-size: 14px;
    }

    .login-links a {
        text-decoration: none;
        color: var(--login-text);
        font-weight: 600;
        transition: .2s ease;
    }

    .login-links a:hover {
        color: var(--login-red);
    }

    .login-back {
        text-align: center;
        margin-top: 26px;
        padding-top: 22px;
        border-top: 1px solid #eef1f5;
    }

    .login-back a {
        color: var(--login-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .login-back a:hover {
        color: var(--login-red);
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 991px) {
        .login-wrapper {
            grid-template-columns: 1fr;
            max-width: 620px;
        }

        .login-left {
            padding: 36px 24px;
        }

        .login-right {
            padding: 36px 24px;
        }

        .login-visual {
            width: 210px;
            height: 210px;
        }

        .login-left-points {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        html,
        body {
            height: 100%;
            overflow: hidden;
            background: #fff;
        }

        .login-page {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            padding: 0;
            overflow: hidden;
            background: #fff;
        }

        .login-wrapper {
            width: 100%;
            height: 100vh;
            height: 100dvh;
            min-height: 0;
            max-width: none;
            display: block;
            overflow: hidden;
            border-radius: 0;
            box-shadow: none;
        }

        .login-left {
            display: none;
        }

        .login-right {
            width: 100%;
            height: 100%;
            padding: 20px 22px;
            overflow: hidden;
        }

        .login-form-wrap {
            max-width: 420px;
        }

        .login-title h2 {
            font-size: 28px;
        }

        .login-links {
            margin-top: 18px;
            text-align: center;
        }

        .login-logo img {
            height: 52px;
        }

        .login-logo { margin-bottom: 14px; }
        .login-title { margin-bottom: 22px; }
        .floating-group { margin-bottom: 16px; }
        .floating-group input { height: 56px; }
        .login-submit { height: 54px; }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="login-page">
    <div class="login-wrapper">

        <!-- LEFT -->
        <div class="login-left">
            <div class="login-left-content">
                <div class="login-badge">
                    <i class="bi bi-shield-check"></i>
                    Sistem Deteksi Video Deepfake
                </div>

                <div class="login-visual">
                    <div class="login-visual-box">
                        <i class="bi bi-camera-video-fill"></i>
                    </div>
                </div>

                <h1>
                    Selamat Datang di<br>
                    BAWASLU Deepfake Detection
                </h1>

                <p>
                    Masuk ke sistem untuk melakukan verifikasi video,
                    melihat hasil klasifikasi, dan memantau riwayat
                    deteksi secara lebih mudah.
                </p>

                <div class="login-left-points">
                    <div class="login-point">
                        <strong>YOLOv8</strong>
                        <span>Face Detection</span>
                    </div>
                    <div class="login-point">
                        <strong>CNN</strong>
                        <span>Classification</span>
                    </div>
                    <div class="login-point">
                        <strong>FastAPI</strong>
                        <span>API Model</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="login-right">
            <div class="login-form-wrap">

                <div class="login-logo">
                    <img src="<?= base_url('assets/landing/logo.png') ?>" alt="BAWASLU Logo">
                </div>

                <div class="login-title">
                    <h2>Login</h2>
                    <p>Silakan masuk ke akun Anda</p>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success login-alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger login-alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)) : ?>
                    <div class="alert alert-danger login-alert">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="floating-group">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?= old('email') ?>"
                            placeholder=" "
                            required
                            autofocus
                        >
                        <label for="email">Email</label>
                        <i class="bi bi-envelope floating-icon"></i>
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

                    <button type="submit" class="login-submit">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login
                    </button>
                </form>

                <div class="login-links">
                    <a href="<?= base_url('register') ?>">Create Account</a>
                    <a href="javascript:void(0)">Forgot Password?</a>
                </div>

                <!-- <div class="login-back">
                    <a href="<?= base_url('/') ?>">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke landing page
                    </a>
                </div> -->

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    if (togglePassword && passwordInput && togglePasswordIcon) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
</script>

<?= $this->endSection() ?>
