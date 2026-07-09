<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-shield-lock"></i> Peran & Hak Akses</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Peran
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
            <h6 class="m-0 font-weight-bold text-primary">Semua Peran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Peran</th>
                            <th>Hak Akses</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($role->display_name) ?></strong>
                                <?php if ($role->id == $this->session->userdata('user_role_id')): ?>
                                    <span class="badge bg-info">Peran Anda</span>
                                <?php endif; ?>
                                <div class="text-muted small"><?= htmlspecialchars($role->name) ?></div>
                            </td>
                            <td>
                                <?php if (empty($role_permissions[$role->id])): ?>
                                    <span class="text-muted small">Tidak ada hak akses</span>
                                <?php else: ?>
                                    <?php foreach ($role_permissions[$role->id] as $key): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($key) ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($role->id != $this->session->userdata('user_role_id')): ?>
                                <div class="btn-group">
                                    <a href="<?= base_url('roles/edit/' . $role->id) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $role->id ?>, '<?= htmlspecialchars($role->display_name, ENT_QUOTES) ?>')"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <?php else: ?>
                                    <span class="text-muted small">Peran saat ini</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="post" action="">
    <?= csrf_field() ?>
</form>

<script>
function confirmDelete(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus peran "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= base_url('roles/delete/') ?>' + id;
        form.submit();
    }
}
</script>
