<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php $educationCss = FCPATH . 'assets/css/education.css'; ?>
<link rel="stylesheet" href="<?= base_url('assets/css/education.css?v=' . (is_file($educationCss) ? filemtime($educationCss) : '1')) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<header class="education-header">
    <div class="education-heading-icon"><i class="bi bi-shield-check"></i></div>
    <div><span class="education-eyebrow">Literasi digital</span><h1 class="page-title">Edukasi Deepfake</h1><p>Informasi singkat mengenai deepfake, ciri-ciri, dan dampaknya terhadap informasi publik.</p></div>
</header>

<?php
$educationIcons = ['bi-cpu', 'bi-eye', 'bi-megaphone', 'bi-shield-exclamation', 'bi-camera-video'];
$educationClasses = ['info', 'scan', 'impact', 'alerting', 'video'];
?>
<div class="education-grid">
    <main class="education-main">
        <?php if (! empty($contents)) : ?>
            <?php foreach ($contents as $index => $content) : ?>
                <article class="education-card">
                    <span class="education-card-icon <?= esc($educationClasses[$index % count($educationClasses)], 'attr') ?>"><i class="bi <?= esc($educationIcons[$index % count($educationIcons)], 'attr') ?>"></i></span>
                    <div><h2><?= esc($content['title']) ?></h2><p><?= nl2br(esc($content['content'])) ?></p></div>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="education-empty"><i class="bi bi-journal-x"></i><h2>Belum Ada Konten Edukasi</h2><p>Konten edukasi belum tersedia.</p></div>
        <?php endif; ?>
    </main>

    <aside class="education-side" aria-label="Informasi tambahan deepfake">
        <section class="education-side-card">
            <div class="education-side-header"><span><i class="bi bi-search"></i></span><div><small>Perhatikan detail</small><h2>Ciri-Ciri Umum Deepfake</h2></div></div>
            <?php
            $signs = [
                ['bi-soundwave', 'Gerakan bibir tidak sinkron dengan suara.'],
                ['bi-emoji-neutral', 'Ekspresi wajah terlihat kurang natural.'],
                ['bi-sun', 'Pencahayaan wajah tidak sesuai dengan lingkungan.'],
                ['bi-bounding-box-circles', 'Tepi wajah terlihat aneh atau berubah-ubah.'],
                ['bi-volume-up', 'Suara terdengar tidak sesuai dengan gerakan mulut.'],
            ];
            ?>
            <ul class="sign-list">
                <?php foreach ($signs as $sign) : ?><li><i class="bi <?= esc($sign[0], 'attr') ?>"></i><span><?= esc($sign[1]) ?></span></li><?php endforeach; ?>
            </ul>
        </section>

        <section class="education-side-card tips-card">
            <div class="education-side-header"><span><i class="bi bi-lightbulb"></i></span><div><small>Langkah sederhana</small><h2>Tips Masyarakat</h2></div></div>
            <div class="tip-list">
                <div class="tip-item warning"><i class="bi bi-exclamation-triangle"></i><div><strong>Jangan langsung percaya</strong><p>Berhenti sejenak sebelum menyebarkan video.</p></div></div>
                <div class="tip-item info"><i class="bi bi-link-45deg"></i><div><strong>Periksa sumber video</strong><p>Pastikan video berasal dari sumber yang dapat dipercaya.</p></div></div>
                <div class="tip-item neutral"><i class="bi bi-newspaper"></i><div><strong>Bandingkan informasi</strong><p>Cari konfirmasi dari media atau kanal resmi.</p></div></div>
                <div class="tip-item danger"><i class="bi bi-shield-exclamation"></i><div><strong>Gunakan sebagai bantuan awal</strong><p>Hasil sistem bukan satu-satunya dasar pengambilan keputusan.</p></div></div>
            </div>
        </section>
    </aside>
</div>
<?= $this->endSection() ?>
