<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<?php $photoCss = FCPATH . 'assets/css/profile-photo.css'; ?>
<link rel="stylesheet" href="<?= base_url('assets/css/profile-photo.css?v=' . (is_file($photoCss) ? filemtime($photoCss) : '1')) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-1">Profil Admin</h3>
        <p class="text-muted mb-0">Kelola informasi profil dan password akun admin.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Informasi Profil</h5>
            </div>

            <div class="card-body">
                <form action="<?= base_url('admin/profile/update') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?= $this->include('partials/profile_photo_field') ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input
                            type="text"
                            name="full_name"
                            class="form-control"
                            value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            value="<?= esc($user['email'] ?? '') ?>"
                            readonly
                        >
                        <small class="text-muted">Email tidak diubah dari halaman ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor HP</label>
                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="<?= esc(old('phone', $user['phone'] ?? '')) ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control" rows="4"><?= esc(old('address', $user['address'] ?? '')) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-bawaslu">
                        <i class="bi bi-save me-1"></i>
                        Simpan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card table-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Ubah Password</h5>
            </div>

            <div class="card-body">
                <form action="<?= base_url('admin/profile/password') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama</label>
                        <input
                            type="password"
                            name="old_password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input
                            type="password"
                            name="new_password"
                            class="form-control"
                            minlength="8"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            name="new_password_confirm"
                            class="form-control"
                            minlength="8"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-key me-1"></i>
                        Ubah Password
                    </button>
                </form>
            </div>
        </div>

        <div class="card table-card mt-4">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Informasi Akun</h5>
            </div>

            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted small">Role</div>
                    <div class="fw-semibold">Administrator</div>
                </div>

                <div class="mb-2">
                    <div class="text-muted small">Login Terakhir</div>
                    <div class="fw-semibold"><?= esc($user['last_login'] ?? '-') ?></div>
                </div>

                <div>
                    <div class="text-muted small">Terdaftar</div>
                    <div class="fw-semibold"><?= esc($user['created_at'] ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
