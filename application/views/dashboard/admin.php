<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard Admin</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="text-muted"><?= translate_date_id(date('l, d F Y')) ?></span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Proyek Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_projects ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-folder fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Tugas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $task_stats['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-task fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tugas Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $task_stats['pending'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Status Overview -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Status Tugas</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Selesai</span>
                            <span><?= $task_stats['done'] ?></span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: <?= $task_stats['total'] > 0 ? ($task_stats['done'] / $task_stats['total'] * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Sedang Dikerjakan</span>
                            <span><?= $task_stats['on_progress'] ?></span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" style="width: <?= $task_stats['total'] > 0 ? ($task_stats['on_progress'] / $task_stats['total'] * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Menunggu</span>
                            <span><?= $task_stats['pending'] ?></span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-secondary" style="width: <?= $task_stats['total'] > 0 ? ($task_stats['pending'] / $task_stats['total'] * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">Peringatan Tenggat (3 Hari Ke Depan)</h6>
                    <a href="<?= base_url('tasks') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($deadline_alerts) && empty($overdue_tasks)): ?>
                            <div class="text-center p-4 text-muted">
                                <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                                <p class="mt-2">Tidak ada tenggat mendesak</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($overdue_tasks as $task): ?>
                                <div class="list-group-item border-left-danger">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 text-danger">
                                                <i class="bi bi-exclamation-triangle"></i> TERLAMBAT
                                            </h6>
                                            <p class="mb-1"><strong><?= htmlspecialchars($task->title) ?></strong></p>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> <?= htmlspecialchars($task->assigned_to_name) ?>
                                                | <i class="bi bi-calendar-x"></i> <?= format_date($task->deadline) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <?= priority_badge($task->priority) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($deadline_alerts as $task): ?>
                                <div class="list-group-item border-left-warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-1"><strong><?= htmlspecialchars($task->title) ?></strong></p>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> <?= htmlspecialchars($task->assigned_to_name) ?>
                                                | <i class="bi bi-calendar"></i> <?= format_date($task->deadline) ?>
                                                <span class="badge bg-warning text-dark">
                                                    <?= days_remaining($task->deadline) ?> hari lagi
                                                </span>
                                            </small>
                                        </div>
                                        <div>
                                            <?= priority_badge($task->priority) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Proyek Terbaru</h6>
                    <a href="<?= base_url('projects') ?>" class="btn btn-sm btn-primary">Kelola Proyek</a>
                </div>
                <div class="card-body">
                    <?php if (empty($projects)): ?>
                        <div class="text-center p-4 text-muted">
                            <i class="bi bi-folder" style="font-size: 3rem;"></i>
                            <p class="mt-2">Belum Ada Proyek</p>
                            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary">Buat Proyek Pertama</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul Proyek</th>
                                        <th>Tipe</th>
                                        <th>Periode</th>
                                        <th>Progres</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($project->title) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($project->description ?? '') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="bi bi-calendar-<?= $project->type == 'weekly' ? 'week' : 'month' ?>"></i>
                                                <?= $project->type == 'weekly' ? 'Mingguan' : 'Bulanan' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= format_date($project->periode_start) ?> - <?= format_date($project->periode_end) ?>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar <?= $project->progress == 100 ? 'bg-success' : 'bg-primary' ?>"
                                                         style="width: <?= $project->progress ?>%"></div>
                                                </div>
                                                <span class="ml-2 small"><?= $project->progress ?>%</span>
                                            </div>
                                            <small class="text-muted">
                                                <?= $project->stats['done'] ?> / <?= $project->stats['total'] ?> tugas
                                            </small>
                                        </td>
                                        <td><?= status_badge($project->status) ?></td>
                                        <td>
                                            <a href="<?= base_url('projects/detail/' . $project->id) ?>"
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
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #4e73df; }
    .border-left-success { border-left: 4px solid #1cc88a; }
    .border-left-warning { border-left: 4px solid #f6c23e; }
    .border-left-info { border-left: 4px solid #36b9cc; }
    .border-left-danger { border-left: 4px solid #e74a3b; }
    .text-xs { font-size: 0.7rem; }
    .font-weight-bold { font-weight: 700; }
    .text-uppercase { text-transform: uppercase; }
    .fa-2x { font-size: 2rem; }
</style>
