<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-list-task"></i> Tugas</h1>
        <?php if (has_permission('manage_tasks')): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('tasks/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tugas Baru
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= isset($filters['status']) && $filters['status'] == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="on_progress" <?= isset($filters['status']) && $filters['status'] == 'on_progress' ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                        <option value="in_review" <?= isset($filters['status']) && $filters['status'] == 'in_review' ? 'selected' : '' ?>>Menunggu Review</option>
                        <option value="done" <?= isset($filters['status']) && $filters['status'] == 'done' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Proyek</label>
                    <select name="project_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Proyek</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project->id ?>" <?= isset($filters['project_id']) && $filters['project_id'] == $project->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($project->title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                           value="<?= isset($filters['search']) ? htmlspecialchars($filters['search']) : '' ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <?php if (!empty($filters)): ?>
                    <div class="col-12">
                        <a href="<?= base_url('tasks') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> Hapus Filter
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Task Stats (for admin) -->
    <?php if (has_permission('view_all_tasks')): ?>
    <?php
    $all_stats = $this->Task_model->get_stats();
    ?>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-secondary"><?= $all_stats['pending'] ?></h5>
                    <p class="card-text text-muted">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?= $all_stats['on_progress'] ?></h5>
                    <p class="card-text text-muted">Sedang Dikerjakan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= $all_stats['done'] ?></h5>
                    <p class="card-text text-muted">Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $all_stats['total'] ?></h5>
                    <p class="card-text text-muted">Total</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tasks Table -->
    <div class="card shadow">
        <div class="card-body">
            <?php if (empty($tasks)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-clipboard-data" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Tugas</h4>
                    <p class="text-muted">
                        <?php if (has_permission('manage_tasks')): ?>
                            Buat tugas pertama Anda atau sesuaikan filter.
                        <?php else: ?>
                            Anda belum memiliki tugas yang ditugaskan.
                        <?php endif; ?>
                    </p>
                    <?php if (has_permission('manage_tasks')): ?>
                        <a href="<?= base_url('tasks/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Buat Tugas
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tugas</th>
                                <?php if (has_permission('view_all_tasks')): ?>
                                <th>Ditugaskan Kepada</th>
                                <?php endif; ?>
                                <th>Proyek</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td>
                                    <h6 class="mb-1"><?= htmlspecialchars($task->title) ?></h6>
                                    <?php if (!empty($task->description)): ?>
                                        <small class="text-muted"><?= htmlspecialchars(substr($task->description, 0, 50)) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <?php if (has_permission('view_all_tasks')): ?>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    <?= htmlspecialchars($task->assigned_to_name) ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($task->assigned_role_display) ?></small>
                                </td>
                                <?php endif; ?>
                                <td>
                                    <?php if ($task->project_title): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($task->project_title) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="<?= is_deadline_overdue($task->deadline) ? 'text-danger' : (is_deadline_near($task->deadline) ? 'text-warning' : '') ?>">
                                        <?= format_date($task->deadline) ?>
                                    </small>
                                </td>
                                <td><?= status_badge($task->status) ?></td>
                                <td><?= priority_badge($task->priority) ?></td>
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
