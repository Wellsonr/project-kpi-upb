<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-person-plus"></i> Tambah Pengguna</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pengguna</h6>
                </div>
                <div class="card-body">
                    <?= form_open('users/create', array('id' => 'createUserForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Masukkan nama lengkap" required>
                            <?= form_error('name', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="Masukkan alamat email" required>
                            <?= form_error('email', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Masukkan kata sandi" required>
                                <?= form_error('password', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                       placeholder="Konfirmasi kata sandi" required>
                                <?= form_error('confirm_password', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Peran <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">Pilih peran</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->id ?>">
                                        <?= htmlspecialchars($role->display_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('role_id', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Peran:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Admin</strong> - Akses penuh ke semua fitur</li>
                                <li><strong>Video Editor</strong> - Dapat melihat dan memperbarui tugas yang ditugaskan</li>
                                <li><strong>Designer</strong> - Dapat melihat dan memperbarui tugas yang ditugaskan</li>
                                <li><strong>Social Media</strong> - Dapat melihat dan memperbarui tugas yang ditugaskan</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Buat Pengguna
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info"><i class="bi bi-info-circle"></i> Tips Kata Sandi</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Minimal 6 karakter
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Gunakan huruf besar dan kecil
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Gunakan angka dan karakter khusus
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success"></i>
                            Hindari kata atau pola yang umum
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;

    if (password !== confirm) {
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
