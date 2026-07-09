<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-people"></i> Manajemen Pengguna</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Semua Pengguna</h6>
        </div>
        <div class="card-body">
            <?php if (empty($users)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Pengguna</h4>
                    <p class="text-muted">Tambahkan pengguna ke tim Anda dan tetapkan peran.</p>
                    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Tambah Pengguna Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pengguna</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($user->name, 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0"><?= htmlspecialchars($user->name) ?></h6>
                                            <?php if ($user->id == get_user_id()): ?>
                                                <span class="badge bg-info">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="bi bi-envelope"></i>
                                    <?= htmlspecialchars($user->email) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= in_array($user->role_id, $admin_role_ids) ? 'danger' : 'primary' ?>">
                                        <?= htmlspecialchars($user->role_display_name) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= format_date($user->created_at) ?></small>
                                </td>
                                <td class="text-end">
                                    <?php if ($user->id != get_user_id()): ?>
                                    <div class="btn-group">
                                        <a href="<?= base_url('users/edit/' . $user->id) ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="toggleUser(<?= $user->id ?>, '<?= htmlspecialchars($user->name, ENT_QUOTES) ?>', <?= $user->is_active ?>)"
                                                class="btn btn-sm btn-<?= $user->is_active ? 'warning' : 'success' ?>">
                                            <i class="bi bi-<?= $user->is_active ? 'toggle-on' : 'toggle-off' ?>"></i>
                                        </button>
                                        <button onclick="confirmDelete(<?= $user->id ?>, '<?= htmlspecialchars($user->name, ENT_QUOTES) ?>')"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Pengguna saat ini</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<form id="toggleForm" method="post" action="<?= base_url('users/toggle') ?>">
    <input type="hidden" name="id" id="toggleId">
</form>

<form id="deleteForm" method="post" action="<?= base_url('users/delete') ?>">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function toggleUser(id, name, isActive) {
    const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
    if (confirm('Apakah Anda yakin ingin ' + action + ' pengguna "' + name + '"?')) {
        document.getElementById('toggleId').value = id;
        document.getElementById('toggleForm').submit();
    }
}

function confirmDelete(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
