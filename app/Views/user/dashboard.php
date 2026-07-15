<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php $dashboardCss = FCPATH . 'assets/css/dashboard.css'; ?>
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css?v=' . (is_file($dashboardCss) ? filemtime($dashboardCss) : '1')) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$statusBadge = static fn (?string $status): string => match ($status) {
    'pending' => 'warning', 'processing' => 'info', 'completed' => 'success',
    'failed' => 'danger', 'reviewed' => 'primary', default => 'secondary',
};
$labelBadge = static fn (?string $label): string => match ($label) {
    'REAL' => 'success', 'MENCURIGAKAN' => 'warning', 'DEEPFAKE' => 'danger',
    'NO_FACE' => 'info', default => 'secondary',
};
$totalVideos = (int) ($total_videos ?? 0);
$totalReal = (int) ($total_real ?? 0);
$totalDeepfake = (int) ($total_deepfake ?? 0);
$totalFailed = (int) ($total_failed ?? 0);
$realPercent = $totalVideos > 0 ? round(($totalReal / $totalVideos) * 100, 1) : 0;
$deepfakePercent = $totalVideos > 0 ? round(($totalDeepfake / $totalVideos) * 100, 1) : 0;
$failedPercent = $totalVideos > 0 ? round(($totalFailed / $totalVideos) * 100, 1) : 0;
$latestRows = array_slice($latest_data ?? [], 0, 5);
?>

<section class="dashboard-hero" aria-labelledby="dashboardTitle">
    <div class="dashboard-hero-content">
        <span class="hero-eyebrow"><i class="bi bi-grid-1x2-fill"></i> Ringkasan aktivitas</span>
        <h1 id="dashboardTitle">Dashboard User</h1>
        <p>Selamat datang, <?= esc(session()->get('full_name') ?? 'User') ?>. Pantau ringkasan deteksi video dan lakukan analisis deepfake dari satu halaman.</p>
        <a href="<?= base_url('user/detections/create') ?>" class="hero-action"><i class="bi bi-cloud-arrow-up"></i> Deteksi Video</a>
    </div>
</section>

<section class="stat-grid" aria-label="Statistik deteksi">
    <?php
    $stats = [
        ['class' => 'total', 'label' => 'Total Video', 'value' => $totalVideos, 'icon' => 'bi-camera-video', 'percent' => $totalVideos > 0 ? 100 : 0],
        ['class' => 'real', 'label' => 'Video REAL', 'value' => $totalReal, 'icon' => 'bi-check-circle', 'percent' => $realPercent],
        ['class' => 'fake', 'label' => 'Video DEEPFAKE', 'value' => $totalDeepfake, 'icon' => 'bi-shield-exclamation', 'percent' => $deepfakePercent],
        ['class' => 'failed', 'label' => 'Proses Gagal', 'value' => $totalFailed, 'icon' => 'bi-x-circle', 'percent' => $failedPercent],
    ];
    ?>
    <?php foreach ($stats as $stat) : ?>
        <article class="dash-stat-card <?= esc($stat['class'], 'attr') ?>">
            <div class="stat-copy"><span><?= esc($stat['label']) ?></span><strong><?= esc($stat['value']) ?></strong></div>
            <span class="stat-icon"><i class="bi <?= esc($stat['icon'], 'attr') ?>"></i></span>
            <div class="stat-progress" role="progressbar" aria-valuenow="<?= esc($stat['percent']) ?>" aria-valuemin="0" aria-valuemax="100">
                <span style="width: <?= esc($stat['percent']) ?>%"></span>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<div class="dashboard-grid">
    <section class="dash-card guide-card" aria-labelledby="guideTitle">
        <div class="dash-card-header"><div><span class="section-kicker">Alur penggunaan</span><h2 id="guideTitle">Panduan Singkat</h2></div></div>
        <div class="guide-list">
            <?php
            $guides = [
                ['icon' => 'bi-cloud-arrow-up', 'title' => 'Upload Video', 'text' => 'Pilih video yang ingin dianalisis melalui menu Deteksi Video.'],
                ['icon' => 'bi-cpu', 'title' => 'Proses Model AI', 'text' => 'Sistem mengirim video ke Backend API untuk diproses oleh model deepfake.'],
                ['icon' => 'bi-check2-circle', 'title' => 'Lihat Hasil', 'text' => 'Hasil klasifikasi ditampilkan sebagai REAL, MENCURIGAKAN, atau DEEPFAKE.'],
            ];
            ?>
            <?php foreach ($guides as $index => $guide) : ?>
                <div class="guide-item">
                    <span class="guide-icon"><i class="bi <?= esc($guide['icon'], 'attr') ?>"></i></span>
                    <span class="guide-number"><?= esc($index + 1) ?></span>
                    <div><strong><?= esc($guide['title']) ?></strong><p><?= esc($guide['text']) ?></p></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="education-box"><i class="bi bi-lightbulb"></i><div><strong>Kenali ciri deepfake</strong><p>Pelajari tanda video manipulatif sebelum membagikan informasi.</p></div><a href="<?= base_url('user/education') ?>">Baca edukasi <i class="bi bi-arrow-right"></i></a></div>
    </section>

    <section class="dash-card history-preview" aria-labelledby="latestTitle">
        <div class="dash-card-header"><div><span class="section-kicker">Aktivitas terakhir</span><h2 id="latestTitle">Riwayat Terbaru</h2></div><a href="<?= base_url('user/history') ?>" class="section-link">Lihat Semua <i class="bi bi-arrow-right"></i></a></div>
        <?php if (! empty($latestRows)) : ?>
            <div class="history-table-desktop table-responsive">
                <table class="table table-dashboard align-middle mb-0">
                    <thead><tr><th>Video</th><th>Hasil</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($latestRows as $row) : ?>
                        <?php $label = $row['predicted_label'] ?? 'UNKNOWN'; $status = $row['status'] ?? 'pending'; ?>
                        <tr>
                            <td><div class="video-cell"><span class="video-icon"><i class="bi bi-file-earmark-play"></i></span><div><strong><?= esc($row['original_filename'] ?? '-') ?></strong><small>ID: <?= esc($row['id'] ?? '-') ?></small></div></div></td>
                            <td><span class="badge text-bg-<?= esc($labelBadge($label)) ?>"><?= esc($label) ?></span></td>
                            <td><span class="badge text-bg-<?= esc($statusBadge($status)) ?>"><?= esc($status) ?></span></td>
                            <td class="date-cell"><?= esc($row['created_at'] ?? '-') ?></td>
                            <td><a href="<?= base_url('user/history/detail/' . ($row['id'] ?? 0)) ?>" class="btn-detail">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="history-list-mobile">
                <?php foreach ($latestRows as $row) : ?>
                    <?php $label = $row['predicted_label'] ?? 'UNKNOWN'; $status = $row['status'] ?? 'pending'; ?>
                    <article class="mobile-history-item">
                        <div class="mobile-history-head"><span class="video-icon"><i class="bi bi-file-earmark-play"></i></span><div><strong><?= esc($row['original_filename'] ?? '-') ?></strong><small>ID: <?= esc($row['id'] ?? '-') ?></small></div></div>
                        <div class="mobile-history-meta"><span class="badge text-bg-<?= esc($labelBadge($label)) ?>"><?= esc($label) ?></span><span class="badge text-bg-<?= esc($statusBadge($status)) ?>"><?= esc($status) ?></span><time><i class="bi bi-calendar3"></i> <?= esc($row['created_at'] ?? '-') ?></time></div>
                        <a href="<?= base_url('user/history/detail/' . ($row['id'] ?? 0)) ?>" class="btn-detail">Lihat Detail</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="dashboard-empty"><i class="bi bi-camera-video-off"></i><strong>Belum ada riwayat deteksi</strong><p>Mulai unggah video untuk melihat hasil analisis di sini.</p><a href="<?= base_url('user/detections/create') ?>" class="btn btn-bawaslu">Mulai Deteksi</a></div>
        <?php endif; ?>
    </section>
</div>

<section class="insight-card" aria-labelledby="percentageTitle">
    <div><span class="section-kicker">Komposisi hasil</span><h2 id="percentageTitle">Ringkasan Persentase</h2></div>
    <div class="insight-grid">
        <?php foreach ([['REAL', $realPercent, 'real'], ['DEEPFAKE', $deepfakePercent, 'fake'], ['Gagal', $failedPercent, 'failed']] as $insight) : ?>
            <div class="insight-item"><div><span><?= esc($insight[0]) ?></span><strong><?= esc($insight[1]) ?>%</strong></div><div class="insight-bar"><span class="<?= esc($insight[2], 'attr') ?>" style="width: <?= esc($insight[1]) ?>%"></span></div></div>
        <?php endforeach; ?>
    </div>
</section>

<?= $this->endSection() ?>
