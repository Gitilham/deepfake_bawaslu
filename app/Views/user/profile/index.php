<?= $this->extend('layouts/user') ?>

<?= $this->section('styles') ?>
<?php $profileCss = FCPATH . 'assets/css/profile.css'; ?>
<link rel="stylesheet" href="<?= base_url('assets/css/profile.css?v=' . (is_file($profileCss) ? filemtime($profileCss) : '1')) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$profileName = trim((string) ($user['full_name'] ?? 'User'));
$profileInitial = function_exists('mb_substr') ? mb_substr($profileName, 0, 1) : substr($profileName, 0, 1);
?>
<header class="profile-header">
    <span class="profile-avatar"><?= esc(strtoupper($profileInitial ?: 'U')) ?></span>
    <div><span class="profile-eyebrow">Pengaturan akun</span><h1 class="page-title">Profil Saya</h1><p>Kelola informasi akun dan password Anda.</p></div>
</header>

<div class="profile-grid">
    <section class="profile-card profile-main-card" aria-labelledby="profileInfoTitle">
        <div class="profile-card-header"><span><i class="bi bi-person-lines-fill"></i></span><div><h2 id="profileInfoTitle">Informasi Profil</h2><p>Pastikan informasi kontak Anda tetap akurat.</p></div></div>
        <div class="profile-card-body">
            <form action="<?= base_url('user/profile/update') ?>" method="post">
                <?= csrf_field() ?>
                <div class="profile-form-grid">
                    <div class="form-field"><label for="fullName">Nama Lengkap</label><input id="fullName" type="text" name="full_name" class="form-control" value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>" required><small>Nama yang ditampilkan pada akun Anda.</small></div>
                    <div class="form-field"><label for="email">Email</label><input id="email" type="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" readonly><small>Email tidak dapat diubah dari halaman ini.</small></div>
                    <div class="form-field profile-full-field"><label for="phone">Nomor HP</label><input id="phone" type="text" name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>" placeholder="Opsional"><small>Nomor yang dapat dihubungi bila diperlukan.</small></div>
                    <div class="form-field profile-full-field"><label for="address">Alamat</label><textarea id="address" name="address" class="form-control" rows="5" placeholder="Opsional"><?= esc(old('address', $user['address'] ?? '')) ?></textarea></div>
                </div>
                <button type="submit" class="btn btn-bawaslu profile-submit"><i class="bi bi-check2-circle me-1"></i> Simpan Profil</button>
            </form>
        </div>
    </section>

    <div class="profile-side">
        <section class="profile-card" aria-labelledby="passwordTitle">
            <div class="profile-card-header"><span><i class="bi bi-shield-lock"></i></span><div><h2 id="passwordTitle">Ubah Password</h2><p>Gunakan minimal 8 karakter.</p></div></div>
            <div class="profile-card-body">
                <form action="<?= base_url('user/profile/password') ?>" method="post">
                    <?= csrf_field() ?>
                    <?php foreach ([['old_password', 'Password Lama'], ['new_password', 'Password Baru'], ['new_password_confirm', 'Konfirmasi Password Baru']] as $passwordField) : ?>
                        <div class="form-field password-field"><label for="<?= esc($passwordField[0], 'attr') ?>"><?= esc($passwordField[1]) ?></label><div class="password-control"><input id="<?= esc($passwordField[0], 'attr') ?>" type="password" name="<?= esc($passwordField[0], 'attr') ?>" class="form-control" <?= $passwordField[0] !== 'old_password' ? 'minlength="8"' : '' ?> required><button type="button" class="password-toggle" data-password-toggle="<?= esc($passwordField[0], 'attr') ?>" aria-label="Tampilkan <?= esc($passwordField[1], 'attr') ?>" aria-pressed="false"><i class="bi bi-eye"></i></button></div></div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-outline-danger password-submit"><i class="bi bi-key me-1"></i> Ubah Password</button>
                </form>
            </div>
        </section>

        <section class="profile-card" aria-labelledby="accountTitle">
            <div class="profile-card-header"><span><i class="bi bi-info-circle"></i></span><div><h2 id="accountTitle">Informasi Akun</h2><p>Ringkasan akun terdaftar.</p></div></div>
            <dl class="account-list">
                <div><dt><i class="bi bi-person-badge"></i> Role</dt><dd>User Masyarakat</dd></div>
                <div><dt><i class="bi bi-envelope"></i> Email</dt><dd><?= esc($user['email'] ?? '-') ?></dd></div>
                <div><dt><i class="bi bi-clock-history"></i> Login Terakhir</dt><dd><?= esc($user['last_login'] ?? '-') ?></dd></div>
                <div><dt><i class="bi bi-calendar-check"></i> Terdaftar</dt><dd><?= esc($user['created_at'] ?? '-') ?></dd></div>
            </dl>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
