<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Deteksi Deepfake Bawaslu') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bawaslu-red: #b31312;
            --bawaslu-dark-red: #7f0d0d;
            --bawaslu-black: #1f1f1f;
            --bawaslu-gray: #f4f5f7;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #ffffff;
            color: #222;
        }

        .navbar-public {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--bawaslu-red) !important;
            letter-spacing: .2px;
        }

        .btn-bawaslu {
            background: var(--bawaslu-red);
            border-color: var(--bawaslu-red);
            color: #fff;
        }

        .btn-bawaslu:hover {
            background: var(--bawaslu-dark-red);
            border-color: var(--bawaslu-dark-red);
            color: #fff;
        }

        .btn-outline-bawaslu {
            color: var(--bawaslu-red);
            border-color: var(--bawaslu-red);
        }

        .btn-outline-bawaslu:hover {
            background: var(--bawaslu-red);
            color: #fff;
        }

        .hero-section {
            min-height: 78vh;
            background:
                linear-gradient(135deg, rgba(179, 19, 18, .95), rgba(31, 31, 31, .95)),
                radial-gradient(circle at top right, rgba(255,255,255,.15), transparent 30%);
            color: #fff;
            display: flex;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .25);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: 800;
            color: var(--bawaslu-black);
        }

        .feature-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(0,0,0,.07);
            height: 100%;
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(179, 19, 18, .10);
            color: var(--bawaslu-red);
            font-size: 26px;
            margin-bottom: 18px;
        }

        .footer-public {
            background: var(--bawaslu-black);
            color: #fff;
            padding: 24px 0;
        }

        .auth-wrapper {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(179, 19, 18, .95), rgba(31, 31, 31, .95));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 18px 50px rgba(0,0,0,.20);
        }

        .auth-logo {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: rgba(179, 19, 18, .10);
            color: var(--bawaslu-red);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 12px;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body>

<?= $this->renderSection('content') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>