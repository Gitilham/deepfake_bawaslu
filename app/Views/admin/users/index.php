<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h3 class="page-title mb-1">Data User Masyarakat</h3>
        <p class="text-muted mb-0">Kelola akun user masyarakat yang terdaftar.</p>
    </div>
</div>

<div class="card table-card">
    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">Daftar User</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($users)) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($user['full_name']) ?></div>
                                <small class="text-muted">ID: <?= esc($user['id']) ?></small>
                            </td>
                            <td><?= esc($user['email']) ?></td>
                            <td><?= esc($user['phone'] ?: '-') ?></td>
                            <td>
                                <?php if ((int) $user['is_active'] === 1) : ?>
                                    <span class="badge text-bg-success">Aktif</span>
                                <?php else : ?>
                                    <span class="badge text-bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($user['created_at'] ?? '-') ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= base_url('admin/users/detail/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>

                                    <?php if ((int) $user['is_active'] === 1) : ?>
                                        <a href="<?= base_url('admin/users/toggle/' . $user['id']) ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Nonaktifkan user ini?')">
                                            Nonaktif
                                        </a>
                                    <?php else : ?>
                                        <a href="<?= base_url('admin/users/toggle/' . $user['id']) ?>"
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('Aktifkan user ini?')">
                                            Aktifkan
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada user masyarakat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>