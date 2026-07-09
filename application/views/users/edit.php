<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-pencil"></i> Ubah Pengguna</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pengguna
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Informasi Pengguna</h6>
                </div>
                <div class="card-body">
                    <?= form_open('users/edit/' . $user->id, array('id' => 'editUserForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= set_value('name', $user->name) ?>"
                                   placeholder="Masukkan nama lengkap" required>
                            <?= form_error('name', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= set_value('email', $user->email) ?>"
                                   placeholder="Masukkan alamat email" required>
                            <?= form_error('email', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Peran <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">Pilih peran</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->id ?>"
                                            <?= set_select('role_id', $role->id, $user->role_id == $role->id) ?>>
                                        <?= htmlspecialchars($role->display_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('role_id', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Baru</label>
                            <small class="text-muted d-block mb-2">Biarkan kosong untuk tetap menggunakan kata sandi saat ini</small>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Kata sandi baru">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                           placeholder="Konfirmasi kata sandi baru">
                                </div>
                            </div>
                            <?= form_error('password', '<div class="text-danger small mt-1">', '</div>') ?>
                            <?= form_error('confirm_password', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Perbarui Pengguna
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Statistik Pengguna</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Status:</span>
                        <strong>
                            <?php if ($user->is_active): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Peran:</span>
                        <strong><?= htmlspecialchars($user->role_display_name) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Bergabung Sejak:</span>
                        <strong><?= format_date($user->created_at) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Terakhir Diperbarui:</span>
                        <strong><?= format_date($user->updated_at) ?></strong>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="bi bi-exclamation-triangle"></i> Aksi Akun
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" onclick="toggleUserStatus()"
                                class="btn btn-<?= $user->is_active ? 'warning' : 'success' ?>">
                            <i class="bi bi-<?= $user->is_active ? 'toggle-on' : 'toggle-off' ?>"></i>
                            <?= $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' ?>
                        </button>
                        <button type="button" onclick="confirmDelete()"
                                class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus Pengguna
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="toggleForm" method="post" action="<?= base_url('users/toggle/' . $user->id) ?>">
    <?= csrf_field() ?>
</form>

<form id="deleteForm" method="post" action="<?= base_url('users/delete/' . $user->id) ?>">
    <?= csrf_field() ?>
</form>

<script>
function toggleUserStatus() {
    const action = <?= $user->is_active ? "'menonaktifkan'" : "'mengaktifkan'" ?>;
    if (confirm('Apakah Anda yakin ingin ' + action + ' akun pengguna ini?')) {
        document.getElementById('toggleForm').submit();
    }
}

function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan dan semua data terkait akan terpengaruh.')) {
        document.getElementById('deleteForm').submit();
    }
}

document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;

    if (password && password !== confirm) {
        this.setCustomValidity('Kata sandi tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirm = document.getElementById('confirm_password');
    if (confirm.value) {
        if (this.value !== confirm.value) {
            confirm.setCustomValidity('Kata sandi tidak cocok');
        } else {
            confirm.setCustomValidity('');
        }
    }
});
</script>
