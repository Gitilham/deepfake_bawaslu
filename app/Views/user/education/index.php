<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-1">Edukasi Deepfake</h3>
        <p class="text-muted mb-0">
            Informasi singkat mengenai deepfake, ciri-ciri, dan dampaknya terhadap informasi publik.
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <?php if (! empty($contents)) : ?>
            <?php foreach ($contents as $index => $content) : ?>
                <div class="card content-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div class="stat-icon flex-shrink-0">
                                <i class="bi bi-journal-text"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-2">
                                    <?= esc($content['title']) ?>
                                </h5>

                                <p class="text-muted mb-0" style="line-height: 1.8;">
                                    <?= nl2br(esc($content['content'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="card content-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <h5 class="fw-bold mt-3">Belum Ada Konten Edukasi</h5>
                    <p class="text-muted mb-0">
                        Konten edukasi belum tersedia.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card content-card mb-4">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Ciri-Ciri Umum Deepfake</h5>
            </div>

            <div class="card-body">
                <ul class="mb-0">
                    <li class="mb-2">Gerakan bibir tidak sinkron dengan suara.</li>
                    <li class="mb-2">Ekspresi wajah terlihat kurang natural.</li>
                    <li class="mb-2">Pencahayaan wajah tidak sesuai dengan lingkungan.</li>
                    <li class="mb-2">Tepi wajah terlihat aneh atau berubah-ubah.</li>
                    <li>Suara terdengar tidak sesuai dengan gerakan mulut.</li>
                </ul>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Tips Masyarakat</h5>
            </div>

            <div class="card-body">
                <div class="alert alert-warning mb-3">
                    <div class="fw-bold">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Jangan langsung percaya
                    </div>
                    Periksa sumber video sebelum menyebarkan informasi.
                </div>

                <div class="alert alert-light border mb-0">
                    Gunakan sistem ini sebagai alat bantu deteksi awal,
                    bukan sebagai satu-satunya dasar pengambilan keputusan.
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>