<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-folder"></i> Proyek</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Proyek Baru
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">Semua Proyek</h6>
                </div>
                <div class="col-auto">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active">Semua</button>
                        <button class="btn btn-outline-primary">Aktif</button>
                        <button class="btn btn-outline-primary">Selesai</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($projects)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-folder" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Proyek</h4>
                    <p class="text-muted">Buat proyek pertama Anda untuk mulai mengelola tugas.</p>
                    <a href="<?= base_url('projects/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Buat Proyek
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Proyek</th>
                                <th>Tipe</th>
                                <th>Periode</th>
                                <th>Tenggat</th>
                                <th>Progres</th>
                                <th>Tugas</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center"
                                                 style="width: 48px; height: 48px;">
                                                <i class="bi bi-<?= $project->type == 'weekly' ? 'calendar-week' : 'calendar-month' ?>"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1"><?= htmlspecialchars($project->title) ?></h6>
                                            <small class="text-muted">
                                                <?= $project->description ? htmlspecialchars(substr($project->description, 0, 50)) . '...' : 'Tidak ada deskripsi' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-calendar-<?= $project->type == 'weekly' ? 'week' : 'month' ?>"></i>
                                        <?= $project->type == 'weekly' ? 'Mingguan' : 'Bulanan' ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?= format_date($project->periode_start) ?> -<br>
                                        <?= format_date($project->periode_end) ?>
                                    </small>
                                </td>
                                <td>
                                    <small class="<?= is_deadline_overdue($project->deadline) ? 'text-danger' : (is_deadline_near($project->deadline) ? 'text-warning' : '') ?>">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= format_date($project->deadline) ?>
                                    </small>
                                </td>
                                <td style="min-width: 150px;">
                                    <?php if ($project->stats['total'] > 0): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar <?= $project->progress == 100 ? 'bg-success' : 'bg-primary' ?>"
                                                     style="width: <?= $project->progress ?>%"></div>
                                            </div>
                                            <span class="ms-2 small"><?= $project->progress ?>%</span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Tidak ada tugas</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= $project->stats['total'] ?></span>
                                    <small class="text-muted d-block">
                                        <?= $project->stats['done'] ?> selesai
                                    </small>
                                </td>
                                <td><?= status_badge($project->status) ?></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="<?= base_url('projects/detail/' . $project->id) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('projects/edit/' . $project->id) ?>"
                                           class="btn btn-sm btn-outline-warning" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $project->id ?>, '<?= htmlspecialchars($project->title, ENT_QUOTES) ?>')"
                                                class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
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

<form id="deleteForm" method="post" action="<?= base_url('projects/delete') ?>">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function confirmDelete(id, title) {
    if (confirm('Apakah Anda yakin ingin menghapus proyek "' + title + '"? Semua tugas terkait juga akan dihapus.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
