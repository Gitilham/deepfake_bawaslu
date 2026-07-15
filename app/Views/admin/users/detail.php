<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<style>
    .user-edit-hero { background:linear-gradient(135deg,#111827,#172554 55%,#991b1b); color:#fff; border-radius:24px; padding:26px 28px; margin-bottom:24px; box-shadow:0 20px 45px rgba(15,23,42,.14) }
    .user-edit-hero p { color:rgba(255,255,255,.72); margin:6px 0 0 }
    .user-edit-card { border:1px solid #e5e7eb; border-radius:22px; overflow:hidden; box-shadow:0 16px 38px rgba(15,23,42,.06) }
    .user-edit-card .card-header { padding:20px 24px; border-bottom:1px solid #e5e7eb }
    .user-edit-card .card-body { padding:24px }
    .user-avatar-large { width:76px; height:76px; border-radius:22px; display:flex; align-items:center; justify-content:center; background:#fee2e2; color:#c1121f; font-size:30px; font-weight:900 }
    .account-summary { background:#f8fafc; border:1px solid #e5e7eb; border-radius:18px; padding:18px }
    .form-control,.form-select { min-height:50px; border-radius:14px }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$initial = strtoupper(substr((string) ($user['full_name'] ?? 'U'), 0, 1));
$selectedRole = (string) old('role_id', $user['role_id'] ?? '');
?>

<div class="user-edit-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold mb-0">Edit Pengguna</h3>
        <p>Perbarui nama, email, dan hak akses pengguna.</p>
    </div>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-light rounded-3 px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card user-edit-card">
            <div class="card-header bg-white"><h5 class="fw-bold mb-0">Informasi Akun</h5></div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/users/update/' . $user['id']) ?>">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="full_name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" id="full_name" name="full_name" class="form-control"
                               value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>" required maxlength="150">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?= esc(old('email', $user['email'] ?? '')) ?>" required maxlength="191">
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="form-label fw-semibold">Role</label>
                        <select id="role_id" name="role_id" class="form-select" required>
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?= esc($role['id']) ?>" <?= $selectedRole === (string) $role['id'] ? 'selected' : '' ?>>
                                    <?= esc(($role['role_name'] ?? '') === 'admin' ? 'Administrator' : 'Masyarakat') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Administrator dapat membuka panel admin. Masyarakat menggunakan panel deteksi user.</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-danger rounded-3 px-4">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary rounded-3 px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card user-edit-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="user-avatar-large"><?= esc($initial) ?></div>
                    <div>
                        <h5 class="fw-bold mb-1"><?= esc($user['full_name'] ?? '-') ?></h5>
                        <span class="badge <?= ($user['role_name'] ?? '') === 'admin' ? 'text-bg-danger' : 'text-bg-primary' ?>">
                            <?= esc(($user['role_name'] ?? '') === 'admin' ? 'Administrator' : 'Masyarakat') ?>
                        </span>
                    </div>
                </div>
                <div class="account-summary">
                    <div class="text-muted small">ID Pengguna</div><div class="fw-semibold mb-3">#<?= esc($user['id']) ?></div>
                    <div class="text-muted small">Status</div><div class="fw-semibold mb-3"><?= (int) $user['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></div>
                    <div class="text-muted small">Terdaftar</div><div class="fw-semibold"><?= esc($user['created_at'] ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

