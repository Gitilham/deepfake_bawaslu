<?php
$photoPath = (string) ($user['profile_photo'] ?? '');
$photoUrl = $photoPath !== '' ? base_url($photoPath) : '';
$photoName = trim((string) ($user['full_name'] ?? 'User'));
$photoInitial = function_exists('mb_substr') ? mb_substr($photoName, 0, 1) : substr($photoName, 0, 1);
?>
<div class="profile-photo-editor">
    <div class="profile-photo-preview" id="profilePhotoPreview">
        <?php if ($photoUrl !== '') : ?>
            <img src="<?= esc($photoUrl, 'attr') ?>" alt="Foto profil <?= esc($photoName, 'attr') ?>" id="profilePhotoImage">
            <span class="profile-photo-initial d-none" id="profilePhotoInitial"><?= esc(strtoupper($photoInitial ?: 'U')) ?></span>
        <?php else : ?>
            <img src="" alt="Preview foto profil" class="d-none" id="profilePhotoImage">
            <span class="profile-photo-initial" id="profilePhotoInitial"><?= esc(strtoupper($photoInitial ?: 'U')) ?></span>
        <?php endif; ?>
    </div>
    <div class="profile-photo-copy">
        <strong>Foto Profil</strong>
        <span>JPG, PNG, atau WEBP. Maksimal 2 MB.</span>
        <label for="profilePhotoInput" class="profile-photo-button">
            <i class="bi bi-camera"></i> Pilih Foto
        </label>
        <input id="profilePhotoInput" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp">
    </div>
</div>

<script>
document.getElementById('profilePhotoInput')?.addEventListener('change', function () {
    const file = this.files?.[0];
    if (!file) return;
    const image = document.getElementById('profilePhotoImage');
    const initial = document.getElementById('profilePhotoInitial');
    image.src = URL.createObjectURL(file);
    image.classList.remove('d-none');
    initial?.classList.add('d-none');
});
</script>
