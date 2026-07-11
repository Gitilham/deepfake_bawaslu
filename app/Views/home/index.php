<?= $this->extend('layouts/public') ?>

<?= $this->section('styles') ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --lp-red: #b31312;
        --lp-red-2: #e63946;
        --lp-dark: #1f1f1f;
        --lp-navy: #19233a;
        --lp-soft: #f6f8fb;
        --lp-text: #1d2738;
        --lp-muted: #5b6475;
        --lp-yellow: #f4b400;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        background: #ffffff;
        overflow-x: hidden;
    }

    .lp-page {
        background: #ffffff;
    }

    /* =========================
       FLOATING NAVBAR
    ========================== */
    .lp-header {
        position: fixed;
        top: 22px;
        left: 50%;
        transform: translateX(-50%);
        width: min(92%, 1500px);
        z-index: 999;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(0, 0, 0, .12);
        border: 1px solid rgba(255, 255, 255, .7);
    }

    .lp-nav {
        min-height: 86px;
        padding: 0 34px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .lp-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--lp-red);
        font-weight: 900;
        letter-spacing: .2px;
        min-width: 240px;
    }

    .lp-brand img {
        height: 58px;
        width: auto;
        display: block;
    }

    .lp-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .lp-brand-title {
        font-size: 20px;
        font-weight: 900;
        color: var(--lp-red);
    }

    .lp-brand-subtitle {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: .4px;
    }

    .lp-nav-right {
        display: flex;
        align-items: center;
        gap: 34px;
    }

    .lp-menu {
        display: flex;
        align-items: center;
        gap: 30px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .lp-menu a {
        color: var(--lp-text);
        text-decoration: none;
        font-size: 15px;
        font-weight: 700;
        position: relative;
        transition: .25s ease;
    }

    .lp-menu a:hover {
        color: var(--lp-red);
    }

    .lp-menu a.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 70%;
        height: 3px;
        background: var(--lp-red-2);
        border-radius: 999px;
    }

    .lp-nav-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .lp-btn-login {
        border: 1.5px solid var(--lp-red);
        color: var(--lp-red);
        background: #fff;
        text-decoration: none;
        padding: 11px 24px;
        border-radius: 13px;
        font-size: 14px;
        font-weight: 700;
        transition: .25s ease;
    }

    .lp-btn-login:hover {
        background: var(--lp-red);
        color: #fff;
        transform: translateY(-2px);
    }

    .lp-btn-register {
        background: linear-gradient(90deg, var(--lp-navy), var(--lp-red-2));
        color: #fff;
        text-decoration: none;
        padding: 12px 28px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        box-shadow: 0 12px 30px rgba(230, 57, 70, .25);
        transition: .25s ease;
    }

    .lp-btn-register:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 16px 38px rgba(230, 57, 70, .35);
    }

    .lp-mobile-toggle {
        display: none;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: var(--lp-red);
        font-size: 22px;
    }

    /* =========================
       HERO
    ========================== */
    .lp-hero {
        position: relative;
        min-height: 100vh;
        padding: 150px 0 80px;
        display: flex;
        align-items: center;
        overflow: hidden;

        /* Background dipertahankan dari landing page sekarang */
        background:
            linear-gradient(135deg, rgba(179, 19, 18, .97), rgba(31, 31, 31, .97)),
            radial-gradient(circle at top right, rgba(255,255,255,.14), transparent 35%);
        color: #fff;
    }

    .lp-hero::after {
        content: "";
        position: absolute;
        right: -170px;
        top: 140px;
        width: 620px;
        height: 620px;
        background: radial-gradient(circle, rgba(255,255,255,.16), transparent 62%);
        z-index: 0;
    }

    .lp-hero::before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 170px;
        background-image:
            linear-gradient(to right, rgba(255,255,255,.07) 1px, transparent 1px),
            linear-gradient(to top, rgba(255,255,255,.07) 1px, transparent 1px);
        background-size: 42px 42px;
        opacity: .35;
        z-index: 0;
    }

    .lp-container {
        width: min(92%, 1360px);
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .lp-hero-grid {
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        align-items: center;
        gap: 70px;
    }

    .lp-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .25);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 26px;
    }

    .lp-hero-text {
        opacity: 0;
        transform: translateX(-55px);
        animation: lpSlideLeft 1s ease forwards;
        animation-delay: .15s;
    }

    .lp-hero-text h1 {
        font-size: clamp(42px, 5vw, 72px);
        font-weight: 900;
        line-height: 1.12;
        margin-bottom: 24px;
        letter-spacing: -.9px;
        color: #fff;
        text-transform: none;
    }

    .lp-hero-text p {
        max-width: 680px;
        color: rgba(255,255,255,.88);
        font-size: 18px;
        line-height: 1.8;
        margin-bottom: 34px;
    }

    .lp-hero-actions {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .lp-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(90deg, #1f2a44, #e63946);
        color: #fff;
        text-decoration: none;
        border: 0;
        padding: 16px 30px;
        border-radius: 999px;
        font-weight: 800;
        box-shadow: 0 18px 40px rgba(230, 57, 70, .35);
        transition: .25s ease;
    }

    .lp-btn-primary:hover {
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 24px 48px rgba(230, 57, 70, .45);
    }

    .lp-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,.10);
        color: #fff;
        text-decoration: none;
        border: 1.4px solid rgba(255,255,255,.75);
        padding: 15px 30px;
        border-radius: 999px;
        font-weight: 800;
        transition: .25s ease;
    }

    .lp-btn-outline:hover {
        color: var(--lp-red);
        background: #fff;
        transform: translateY(-3px);
    }

    .lp-hero-visual {
        opacity: 0;
        transform: translateX(55px);
        animation: lpSlideRight 1s ease forwards;
        animation-delay: .35s;
        position: relative;
    }

    .lp-visual-card {
        min-height: 470px;
        border-radius: 34px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 30px 75px rgba(0,0,0,.24);
        padding: 28px;
        position: relative;
        overflow: hidden;
        color: var(--lp-text);
    }

    .lp-visual-card::before {
        content: "";
        position: absolute;
        right: -120px;
        top: -120px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(230,57,70,.14), transparent 65%);
    }

    .lp-lottie-wrap {
        height: 330px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lp-lottie-wrap lottie-player {
        width: 100%;
        max-width: 430px;
        height: 330px;
    }

    .lp-analysis-box {
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 18px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(0,0,0,.06);
    }

    .lp-analysis-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .lp-analysis-title {
        font-weight: 800;
        color: var(--lp-text);
    }

    .lp-ready {
        background: #138a43;
        color: #fff;
        padding: 5px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 800;
    }

    .lp-progress {
        height: 12px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 11px;
    }

    .lp-progress span {
        display: block;
        width: 82%;
        height: 100%;
        background: linear-gradient(90deg, var(--lp-red), var(--lp-red-2));
    }

    .lp-tech-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        text-align: center;
        gap: 12px;
        margin-top: 22px;
    }

    .lp-tech-row strong {
        display: block;
        color: var(--lp-red-2);
        font-size: 24px;
        font-weight: 900;
    }

    .lp-tech-row small {
        color: var(--lp-muted);
        font-weight: 500;
    }

    @keyframes lpSlideLeft {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes lpSlideRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* =========================
       SECTIONS
    ========================== */
    .lp-section {
        padding: 105px 0;
        position: relative;
    }

    .lp-section-soft {
        background:
            linear-gradient(180deg, #ffffff, #f4f7fb);
        overflow: hidden;
    }

    .lp-section-soft::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(to right, rgba(0,0,0,.035) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(0,0,0,.035) 1px, transparent 1px);
        background-size: 70px 70px;
        opacity: .65;
    }

    .lp-section-title {
        position: relative;
        z-index: 2;
        text-align: center;
        margin-bottom: 58px;
    }

    .lp-section-title span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--lp-red);
        background: rgba(179,19,18,.08);
        padding: 8px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .lp-section-title h2 {
        font-size: clamp(30px, 3vw, 44px);
        font-weight: 900;
        color: var(--lp-text);
        margin-bottom: 14px;
    }

    .lp-section-title p {
        color: var(--lp-muted);
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.8;
    }

    .lp-feature-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    .lp-feature-card {
        position: relative;
        padding: 42px 34px;
        border-radius: 26px;
        background: #ffffff;
        box-shadow: 0 18px 45px rgba(0,0,0,.07);
        border: 1px solid rgba(0,0,0,.04);
        overflow: hidden;
        transition: .35s ease;
    }

    .lp-feature-card::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -70px;
        top: -70px;
        background: radial-gradient(circle, rgba(230,57,70,.13), transparent 62%);
        transition: .35s ease;
    }

    .lp-feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 28px 70px rgba(179,19,18,.15);
    }

    .lp-feature-card:hover::before {
        background: radial-gradient(circle, rgba(244,180,0,.20), transparent 62%);
    }

    .lp-feature-icon {
        width: 76px;
        height: 76px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--lp-yellow), var(--lp-red-2));
        box-shadow: 0 12px 28px rgba(230,57,70,.25);
        margin-bottom: 26px;
        position: relative;
        z-index: 2;
    }

    .lp-feature-icon img {
        width: 42px;
        height: 42px;
        object-fit: contain;
        filter: drop-shadow(0 3px 4px rgba(0,0,0,.15));
    }

    .lp-feature-card h3 {
        position: relative;
        z-index: 2;
        font-size: 20px;
        font-weight: 900;
        color: var(--lp-text);
        margin-bottom: 14px;
    }

    .lp-feature-card p {
        position: relative;
        z-index: 2;
        color: var(--lp-muted);
        line-height: 1.75;
        margin: 0;
    }

    /* =========================
       HOW IT WORKS
    ========================== */
    .lp-steps {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
        position: relative;
        z-index: 2;
    }

    .lp-step-card {
        background: #fff;
        border-radius: 22px;
        padding: 26px 20px;
        box-shadow: 0 16px 42px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.04);
        text-align: center;
        transition: .3s ease;
    }

    .lp-step-card:hover {
        transform: translateY(-7px);
    }

    .lp-step-number {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--lp-navy), var(--lp-red-2));
        color: #fff;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 10px 24px rgba(230,57,70,.25);
    }

    .lp-step-card h4 {
        font-size: 15px;
        font-weight: 900;
        color: var(--lp-text);
        margin-bottom: 8px;
    }

    .lp-step-card p {
        font-size: 13px;
        color: var(--lp-muted);
        line-height: 1.6;
        margin: 0;
    }

    /* =========================
       ABOUT
    ========================== */
    .lp-about {
        background:
            linear-gradient(135deg, rgba(179, 19, 18, .97), rgba(31, 31, 31, .97));
        color: #fff;
        overflow: hidden;
    }

    .lp-about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 46px;
        align-items: center;
    }

    .lp-about h2 {
        font-size: clamp(30px, 3vw, 44px);
        font-weight: 900;
        margin-bottom: 20px;
    }

    .lp-about p {
        color: rgba(255,255,255,.84);
        line-height: 1.85;
        font-size: 16px;
    }

    .lp-about-card {
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 28px;
        padding: 34px;
        backdrop-filter: blur(10px);
    }

    .lp-check {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 18px;
        color: rgba(255,255,255,.90);
        font-weight: 600;
    }

    .lp-check i {
        color: #64d98a;
        font-size: 20px;
        margin-top: 1px;
    }

    /* =========================
       FOOTER
    ========================== */
    .lp-footer {
        background: #151515;
        color: #fff;
        padding: 30px 0;
    }

    .lp-footer-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .lp-footer strong {
        color: #fff;
    }

    .lp-footer small {
        color: rgba(255,255,255,.60);
    }

    /* =========================
       RESPONSIVE
    ========================== */
    @media (max-width: 1199px) {
        .lp-steps {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 991px) {
        .lp-header {
            top: 14px;
            width: calc(100% - 24px);
            border-radius: 18px;
        }

        .lp-nav {
            min-height: 74px;
            padding: 0 18px;
        }

        .lp-brand img {
            height: 48px;
        }

        .lp-brand-title {
            font-size: 16px;
        }

        .lp-brand-subtitle {
            font-size: 10px;
        }

        .lp-mobile-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .lp-nav-right {
            display: none;
            position: absolute;
            left: 16px;
            right: 16px;
            top: 86px;
            background: #fff;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 18px 45px rgba(0,0,0,.15);
            flex-direction: column;
            align-items: stretch;
            gap: 18px;
        }

        .lp-nav-right.show {
            display: flex;
        }

        .lp-menu {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .lp-menu a.active::after {
            display: none;
        }

        .lp-nav-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .lp-btn-login,
        .lp-btn-register {
            text-align: center;
        }

        .lp-hero {
            padding-top: 130px;
            min-height: auto;
        }

        .lp-hero-grid {
            grid-template-columns: 1fr;
            gap: 44px;
            text-align: center;
        }

        .lp-hero-text p {
            margin-left: auto;
            margin-right: auto;
        }

        .lp-hero-actions {
            justify-content: center;
        }

        .lp-visual-card {
            max-width: 560px;
            margin: 0 auto;
        }

        .lp-feature-grid {
            grid-template-columns: 1fr;
        }

        .lp-about-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .lp-hero-text h1 {
            font-size: 38px;
        }

        .lp-hero-text p {
            font-size: 15.5px;
        }

        .lp-hero-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .lp-btn-primary,
        .lp-btn-outline {
            justify-content: center;
        }

        .lp-visual-card {
            min-height: auto;
            padding: 22px;
        }

        .lp-lottie-wrap {
            height: 260px;
        }

        .lp-lottie-wrap lottie-player {
            height: 260px;
        }

        .lp-tech-row {
            grid-template-columns: 1fr;
        }

        .lp-steps {
            grid-template-columns: 1fr;
        }

        .lp-section {
            padding: 76px 0;
        }

        .lp-footer-inner {
            text-align: center;
            justify-content: center;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="lp-page">

    <!-- ================= NAVBAR ================= -->
    <header class="lp-header">
        <nav class="lp-nav">
            <a href="<?= base_url('/') ?>" class="lp-brand">
                <img src="<?= base_url('assets/landing/logo.png') ?>" alt="BAWASLU">
                <span class="lp-brand-text">
                    <span class="lp-brand-title">BAWASLU</span>
                    <span class="lp-brand-subtitle">Deepfake Detection</span>
                </span>
            </a>

            <button class="lp-mobile-toggle" type="button" id="lpMobileToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="lp-nav-right" id="lpNavRight">
                <ul class="lp-menu">
                    <li><a href="#home" class="active">Home</a></li>
                    <li><a href="#tentang">About</a></li>
                    <li><a href="#fitur">Features</a></li>
                    <li><a href="#alur">Resources</a></li>
                    <li><a href="#kontak">Contact</a></li>
                </ul>

                <div class="lp-nav-actions">
                    <a href="<?= base_url('login') ?>" class="lp-btn-login">Login</a>
                    <a href="<?= base_url('register') ?>" class="lp-btn-register">Sign Up</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ================= HERO ================= -->
    <section class="lp-hero" id="home">
        <div class="lp-container">
            <div class="lp-hero-grid">

                <div class="lp-hero-text">
                    <!-- <div class="lp-hero-badge">
                        <i class="bi bi-shield-check"></i>
                        Sistem Deteksi dan Klasifikasi Video Deepfake
                    </div> -->

                    <h1>
                        Sistem Deteksi Video<br>
                        Deepfake Berbasis<br>
                        YOLOv8-CNN
                    </h1>

                    <p>
                        Solusi teknologi canggih untuk memverifikasi keaslian video dan
                        menjaga integritas pemantauan pemilu dari ancaman manipulasi digital.
                        Sistem ini terhubung dengan model AI melalui Flask API.
                    </p>

                    <div class="lp-hero-actions">
                        <a href="<?= base_url('login') ?>" class="lp-btn-primary">
                            <i class="bi bi-upload"></i>
                            Upload Video untuk Verifikasi
                        </a>

                        <a href="#fitur" class="lp-btn-outline">
                            <i class="bi bi-info-circle"></i>
                            Pelajari Sistem
                        </a>
                    </div>
                </div>

                <div class="lp-hero-visual">
                    <div class="lp-visual-card">
                        <div class="lp-lottie-wrap">
                            <lottie-player
                                src="<?= base_url('assets/landing/icondeepfake.json') ?>"
                                background="transparent"
                                speed="1"
                                loop
                                autoplay>
                            </lottie-player>
                        </div>

                        <div class="lp-analysis-box">
                            <div class="lp-analysis-top">
                                <div class="lp-analysis-title">Analisis Video Digital</div>
                                <span class="lp-ready">Ready</span>
                            </div>

                            <div class="lp-progress">
                                <span></span>
                            </div>

                            <div class="small text-muted">
                                Upload video, sistem mengirim file ke Flask API,
                                lalu menampilkan hasil klasifikasi.
                            </div>

                            <div class="lp-tech-row">
                                <div>
                                    <strong>AI</strong>
                                    <small>Model</small>
                                </div>
                                <div>
                                    <strong>CI4</strong>
                                    <small>Website</small>
                                </div>
                                <div>
                                    <strong>API</strong>
                                    <small>Flask</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FEATURES ================= -->
    <section class="lp-section lp-section-soft" id="fitur">
        <div class="lp-container">
            <div class="lp-section-title">
                <span>
                    <i class="bi bi-stars"></i>
                    Keunggulan Teknologi
                </span>
                <h2>Fitur Sistem</h2>
                <p>
                    Sistem dirancang untuk mendukung proses deteksi video deepfake
                    melalui kombinasi YOLOv8, CNN/Xception, fitur artefak, dan Flask API.
                </p>
            </div>

            <div class="lp-feature-grid">
                <div class="lp-feature-card">
                    <div class="lp-feature-icon">
                        <img src="<?= base_url('assets/landing/face_detection.png') ?>" alt="Face Detection">
                    </div>
                    <h3>Face Detection YOLOv8</h3>
                    <p>
                        Sistem mengambil frame video dan mendeteksi area wajah menggunakan YOLOv8
                        agar proses klasifikasi lebih fokus pada bagian wajah.
                    </p>
                </div>

                <div class="lp-feature-card">
                    <div class="lp-feature-icon">
                        <img src="<?= base_url('assets/landing/Clacificaation_cnn.png') ?>" alt="CNN Classification">
                    </div>
                    <h3>Deepfake Classification</h3>
                    <p>
                        Video diproses menggunakan model klasifikasi berbasis CNN/Xception
                        dan fitur artefak untuk menentukan apakah video REAL atau DEEPFAKE.
                    </p>
                </div>

                <div class="lp-feature-card">
                    <div class="lp-feature-icon">
                        <img src="<?= base_url('assets/landing/Fast_chek.png') ?>" alt="Fast Verification">
                    </div>
                    <h3>Web-Based Verification</h3>
                    <p>
                        Masyarakat dapat mengupload video secara langsung melalui website,
                        lalu sistem menyimpan hasil deteksi ke database.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= HOW IT WORKS ================= -->
    <section class="lp-section" id="alur">
        <div class="lp-container">
            <div class="lp-section-title">
                <span>
                    <i class="bi bi-diagram-3"></i>
                    Cara Kerja Sistem
                </span>
                <h2>Alur Deteksi Video</h2>
                <p>
                    Alur sistem dibuat sederhana agar user masyarakat dapat melakukan
                    verifikasi video dengan mudah.
                </p>
            </div>

            <div class="lp-steps">
                <div class="lp-step-card">
                    <div class="lp-step-number">1</div>
                    <h4>Upload Video</h4>
                    <p>User memilih video yang ingin diverifikasi.</p>
                </div>

                <div class="lp-step-card">
                    <div class="lp-step-number">2</div>
                    <h4>Frame Extraction</h4>
                    <p>Sistem mengambil frame dari video yang diupload.</p>
                </div>

                <div class="lp-step-card">
                    <div class="lp-step-number">3</div>
                    <h4>Face Detection</h4>
                    <p>YOLOv8 digunakan untuk mendeteksi wajah pada frame.</p>
                </div>

                <div class="lp-step-card">
                    <div class="lp-step-number">4</div>
                    <h4>Classification</h4>
                    <p>Model melakukan klasifikasi terhadap fitur video.</p>
                </div>

                <div class="lp-step-card">
                    <div class="lp-step-number">5</div>
                    <h4>Result</h4>
                    <p>Hasil ditampilkan sebagai REAL atau DEEPFAKE.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= ABOUT ================= -->
    <section class="lp-section lp-about" id="tentang">
        <div class="lp-container">
            <div class="lp-about-grid">
                <div>
                    <h2>Tentang Penelitian</h2>
                    <p>
                        Website ini dibuat sebagai media pendukung penelitian skripsi
                        mengenai deteksi dan klasifikasi deepfake pada video.
                        CodeIgniter 4 digunakan sebagai website utama, MySQL sebagai database,
                        dan Flask API sebagai penghubung ke model AI.
                    </p>
                    <p>
                        Tema Bawaslu digunakan karena penelitian berkaitan dengan pengawasan
                        informasi publik dan potensi penyebaran konten manipulatif dalam konteks pemilu.
                    </p>
                </div>

                <div class="lp-about-card">
                    <div class="lp-check">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>Deteksi video berbasis upload masyarakat.</div>
                    </div>

                    <div class="lp-check">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>Klasifikasi hasil REAL dan DEEPFAKE.</div>
                    </div>

                    <div class="lp-check">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>Integrasi website CodeIgniter 4 dengan Flask API.</div>
                    </div>

                    <div class="lp-check">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>Riwayat hasil deteksi tersimpan di database.</div>
                    </div>

                    <div class="lp-check mb-0">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>Admin dapat memantau data user, laporan, dan hasil deteksi.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="lp-footer" id="kontak">
        <div class="lp-container">
            <div class="lp-footer-inner">
                <div>
                    <strong>BAWASLU Deepfake Detection</strong>
                    <br>
                    <small>Sistem Deteksi dan Klasifikasi Deepfake pada Video</small>
                </div>

                <small>
                    &copy; <?= date('Y') ?> Sistem Skripsi - Dikembangkan untuk penelitian.
                </small>
            </div>
        </div>
    </footer>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
    const lpMobileToggle = document.getElementById('lpMobileToggle');
    const lpNavRight = document.getElementById('lpNavRight');

    if (lpMobileToggle && lpNavRight) {
        lpMobileToggle.addEventListener('click', function () {
            lpNavRight.classList.toggle('show');
        });
    }

    document.querySelectorAll('.lp-menu a').forEach(function (item) {
        item.addEventListener('click', function () {
            if (lpNavRight) {
                lpNavRight.classList.remove('show');
            }
        });
    });
</script>

<?= $this->endSection() ?>