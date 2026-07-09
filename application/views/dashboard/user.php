<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard Saya</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="text-muted"><?= translate_date_id(date('l, d F Y')) ?></span>
        </div>
    </div>

    <!-- Welcome & Stats -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <div class="alert alert-primary">
                <h4 class="alert-heading"><i class="bi bi-hand-wave"></i> Selamat datang, <?= get_user_name() ?>!</h4>
                <p class="mb-0">Berikut ringkasan tugas Anda untuk hari ini dan minggu ini.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Menunggu</div>
                            <div class="h3 mb-0 font-weight-bold text-secondary"><?= $task_stats['pending'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock" style="font-size: 2rem; color: #6c757d;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dikerjakan</div>
                            <div class="h3 mb-0 font-weight-bold text-warning"><?= $task_stats['on_progress'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-repeat" style="font-size: 2rem; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                            <div class="h3 mb-0 font-weight-bold text-success"><?= $task_stats['done'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: #198754;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tugas</div>
                            <div class="h3 mb-0 font-weight-bold text-primary"><?= $task_stats['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-task" style="font-size: 2rem; color: #0d6efd;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks Alert -->
    <?php if (!empty($overdue_tasks)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="m-0"><i class="bi bi-exclamation-triangle"></i> Tugas Terlambat</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($overdue_tasks as $task): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 text-danger">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <?= htmlspecialchars($task->title) ?>
                                        </h6>
                                        <small class="text-muted">
                                            Jatuh tempo: <?= format_date($task->deadline) ?>
                                            <?php if ($task->project_title): ?>
                                                | Proyek: <?= htmlspecialchars($task->project_title) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?= priority_badge($task->priority) ?>
                                        <a href="<?= base_url('tasks/detail/' . $task->id) ?>"
                                           class="btn btn-sm btn-primary ms-2">
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Today's Tasks -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calendar-day"></i> Tugas Hari Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($today_tasks)): ?>
                        <div class="text-center p-4 text-muted">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            <p class="mt-2">Tidak ada tugas jatuh tempo hari ini</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($today_tasks as $task): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <?= htmlspecialchars($task->title) ?>
                                                <?= status_badge($task->status) ?>
                                            </h6>
                                            <p class="mb-1 small text-muted">
                                                <?php if ($task->project_title): ?>
                                                    <i class="bi bi-folder"></i> <?= htmlspecialchars($task->project_title) ?>
                                                <?php endif; ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> <?= format_date($task->deadline) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <?= priority_badge($task->priority) ?>
                                            <a href="<?= base_url('tasks/detail/' . $task->id) ?>"
                                               class="btn btn-sm btn-outline-primary ms-2">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- This Week's Tasks -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-calendar-week"></i> Tugas Minggu Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($week_tasks)): ?>
                        <div class="text-center p-4 text-muted">
                            <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                            <p class="mt-2">Tidak ada tugas jatuh tempo minggu ini</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($week_tasks as $task): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <?= htmlspecialchars($task->title) ?>
                                                <?= status_badge($task->status) ?>
                                            </h6>
                                            <p class="mb-1 small text-muted">
                                                <?php if ($task->project_title): ?>
                                                    <i class="bi bi-folder"></i> <?= htmlspecialchars($task->project_title) ?>
                                                <?php endif; ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> <?= format_date($task->deadline) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <?= priority_badge($task->priority) ?>
                                            <a href="<?= base_url('tasks/detail/' . $task->id) ?>"
                                               class="btn btn-sm btn-outline-primary ms-2">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Deadline Alerts -->
    <?php if (!empty($deadline_alerts)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0"><i class="bi bi-alarm"></i> Tenggat Mendatang</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($deadline_alerts as $task): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($task->title) ?></h6>
                                        <small class="text-muted">
                                            Jatuh tempo dalam <strong><?= days_remaining($task->deadline) ?> hari</strong>
                                            (<?= format_date($task->deadline) ?>)
                                        </small>
                                    </div>
                                    <div>
                                        <?= priority_badge($task->priority) ?>
                                        <a href="<?= base_url('tasks/detail/' . $task->id) ?>"
                                           class="btn btn-sm btn-primary ms-2">
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .text-xs { font-size: 0.7rem; }
    .font-weight-bold { font-weight: 700; }
    .text-uppercase { text-transform: uppercase; }
</style>
