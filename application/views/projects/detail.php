<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2"><i class="bi bi-folder"></i> <?= htmlspecialchars($project->title) ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('projects') ?>">Proyek</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($project->title) ?></li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="<?= base_url('tasks/create?project_id=' . $project->id) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                </a>
            </div>
            <div class="btn-group">
                <a href="<?= base_url('projects/edit/' . $project->id) ?>" class="btn btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                </a>
                <button onclick="confirmDelete(<?= $project->id ?>, '<?= htmlspecialchars($project->title, ENT_QUOTES) ?>')"
                        class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Project Info Card -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Proyek</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Deskripsi</h6>
                            <p><?= $project->description ? htmlspecialchars($project->description) : '<em class="text-muted">Tidak ada deskripsi</em>' ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted small">Tipe</h6>
                            <p>
                                <span class="badge bg-info">
                                    <i class="bi bi-calendar-<?= $project->type == 'weekly' ? 'week' : 'month' ?>"></i>
                                    <?= $project->type == 'weekly' ? 'Mingguan' : 'Bulanan' ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted small">Status</h6>
                            <p><?= status_badge($project->status) ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Awal Periode</h6>
                            <p><i class="bi bi-calendar"></i> <?= format_date($project->periode_start) ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Akhir Periode</h6>
                            <p><i class="bi bi-calendar"></i> <?= format_date($project->periode_end) ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Tenggat Waktu</h6>
                            <p class="<?= is_deadline_overdue($project->deadline) ? 'text-danger' : (is_deadline_near($project->deadline) ? 'text-warning' : '') ?>">
                                <i class="bi bi-calendar-event"></i> <?= format_date($project->deadline) ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Dibuat Oleh</h6>
                            <p><i class="bi bi-person"></i> <?= htmlspecialchars($project->creator_name) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Dibuat Pada</h6>
                            <p><i class="bi bi-clock"></i> <?= format_datetime($project->created_at) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Progres</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="display-4"><?= $progress ?>%</h2>
                        <p class="text-muted">Selesai</p>
                    </div>
                    <div class="progress mb-3" style="height: 15px;">
                        <div class="progress-bar <?= $progress == 100 ? 'bg-success' : 'bg-primary' ?>"
                             style="width: <?= $progress ?>%"><?= $progress ?>%</div>
                    </div>
                    <div class="row text-center">
                        <div class="col-3">
                            <h5 class="text-secondary"><?= $stats['pending'] ?></h5>
                            <small class="text-muted">Menunggu</small>
                        </div>
                        <div class="col-3">
                            <h5 class="text-warning"><?= $stats['on_progress'] ?></h5>
                            <small class="text-muted">Progres</small>
                        </div>
                        <div class="col-3">
                            <h5 class="text-success"><?= $stats['done'] ?></h5>
                            <small class="text-muted">Selesai</small>
                        </div>
                        <div class="col-3">
                            <h5 class="text-primary"><?= $stats['total'] ?></h5>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-list-task"></i> Tugas (<?= count($tasks) ?>)
            </h6>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary active" onclick="filterTasks('all', this)">Semua</button>
                <button class="btn btn-outline-primary" onclick="filterTasks('pending', this)">Menunggu</button>
                <button class="btn btn-outline-primary" onclick="filterTasks('on_progress', this)">Dikerjakan</button>
                <button class="btn btn-outline-primary" onclick="filterTasks('done', this)">Selesai</button>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($tasks)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-clipboard-data" style="font-size: 3rem; color: #dee2e6;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Tugas</h5>
                    <p class="text-muted">Tambahkan tugas ke proyek ini dan tugaskan ke anggota tim.</p>
                    <a href="<?= base_url('tasks/create?project_id=' . $project->id) ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Tugas Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Tugas</th>
                                <th>Ditugaskan Ke</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                            <tr data-status="<?= $task->status ?>">
                                <td>
                                    <h6 class="mb-1"><?= htmlspecialchars($task->title) ?></h6>
                                    <small class="text-muted"><?= priority_badge($task->priority) ?></small>
                                </td>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    <?= htmlspecialchars($task->assigned_to_name) ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($task->assigned_role_display) ?></small>
                                </td>
                                <td>
                                    <small class="<?= is_deadline_overdue($task->deadline) ? 'text-danger' : (is_deadline_near($task->deadline) ? 'text-warning' : '') ?>">
                                        <?= format_date($task->deadline) ?>
                                    </small>
                                </td>
                                <td><?= status_badge($task->status) ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('tasks/detail/' . $task->id) ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
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
function filterTasks(status, btn) {
    document.querySelectorAll('.btn-group button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('#tasksTable tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmDelete(id, title) {
    if (confirm('Apakah Anda yakin ingin menghapus proyek "' + title + '"? Semua tugas terkait juga akan dihapus.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
