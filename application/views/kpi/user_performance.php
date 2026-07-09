<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2"><i class="bi bi-person-badge"></i> Kinerja Pengguna</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('kpi') ?>">KPI</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($user->name) ?></li>
                </ol>
            </nav>
        </div>
        <?php if (has_permission('view_all_kpi')): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
            <select class="form-select" onchange="changeUser(this.value)" style="width: 200px;">
                <option value="">Pilih Pengguna</option>
                <?php
                $users = $this->User_model->get_all(1);
                foreach ($users as $u):
                    ?>
                    <option value="<?= $u->id ?>" <?= $u->id == $user->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>

    <!-- User Info Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        <?= strtoupper(substr($user->name, 0, 1)) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-1"><?= htmlspecialchars($user->name) ?></h3>
                    <p class="text-muted mb-2">
                        <span class="badge bg-info"><?= htmlspecialchars($user->role_display_name) ?></span>
                        <span class="ms-2"><?= htmlspecialchars($user->email) ?></span>
                    </p>
                    <?php if ($ranking): ?>
                        <p class="mb-0">
                            <strong class="text-primary">Peringkat Tim:</strong>
                            #<?= $ranking['rank'] ?> dari <?= $ranking['total'] ?>
                            (<?= $ranking['percentile'] ?>% teratas)
                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-end">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success mb-0">
                                <?= number_format($current_kpi['performance_score'], 1) ?>
                                <?php if ($trend['delta'] !== null): ?>
                                    <?php if ($trend['direction'] == 'up'): ?>
                                        <small class="text-success"><i class="bi bi-arrow-up-short"></i><?= number_format(abs($trend['delta']), 1) ?></small>
                                    <?php elseif ($trend['direction'] == 'down'): ?>
                                        <small class="text-danger"><i class="bi bi-arrow-down-short"></i><?= number_format(abs($trend['delta']), 1) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted"><i class="bi bi-dash"></i></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </h4>
                            <small class="text-muted">Skor <?= $trend['delta'] !== null ? '(vs periode lalu)' : '' ?></small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-0"><?= $current_kpi['completion_rate'] ?>%</h4>
                            <small class="text-muted">Selesai</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning mb-0"><?= $current_kpi['ontime_rate'] ?>%</h4>
                            <small class="text-muted">Tepat Waktu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Breakdown -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tren Kinerja (12 Minggu)</h6>
                </div>
                <div class="card-body">
                    <canvas id="performanceTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Minggu Ini</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tugas Selesai</span>
                            <strong><?= $current_kpi['tasks_done'] ?>/<?= $current_kpi['tasks_assigned'] ?></strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $current_kpi['completion_rate'] ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pengiriman Tepat Waktu</span>
                            <strong><?= number_format($current_kpi['ontime_rate'], 1) ?>%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: <?= $current_kpi['ontime_rate'] ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Skor Kualitas</span>
                            <strong><?= $current_kpi['quality_avg'] ?>/5.0</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: <?= ($current_kpi['quality_avg'] / 5) * 100 ?>%"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between text-muted">
                        <span>Tugas Terlambat:</span>
                        <strong class="text-danger"><?= $current_kpi['tasks_overdue'] ?></strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Revisi:</span>
                        <strong class="text-warning"><?= $current_kpi['tasks_revised'] ?></strong>
                    </div>

                    <?php if (isset($team_avg_score)): ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Rata-rata Tim:</span>
                        <strong><?= number_format($team_avg_score, 1) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Selisih:</span>
                        <strong class="<?= $current_kpi['performance_score'] >= $team_avg_score ? 'text-success' : 'text-danger' ?>">
                            <?= $current_kpi['performance_score'] >= $team_avg_score ? '+' : '' ?>
                            <?= number_format($current_kpi['performance_score'] - $team_avg_score, 1) ?>
                        </strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tugas Terbaru</h6>
        </div>
        <div class="card-body">
            <?php if (empty($recent_tasks)): ?>
                <p class="text-muted text-center">Tidak ada tugas</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tugas</th>
                                <th>Proyek</th>
                                <th>Status</th>
                                <th>Tenggat</th>
                                <th>Kualitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_tasks, 0, 10) as $task): ?>
                            <tr>
                                <td>
                                    <h6 class="mb-0"><?= htmlspecialchars($task->title) ?></h6>
                                </td>
                                <td>
                                    <?= $task->project_title ? htmlspecialchars($task->project_title) : '-' ?>
                                </td>
                                <td><?= status_badge($task->status) ?></td>
                                <td>
                                    <small class="<?= is_deadline_overdue($task->deadline) ? 'text-danger' : '' ?>">
                                        <?= format_date($task->deadline) ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($task->quality_score): ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?= $i <= $task->quality_score ? '-fill' : '' ?> text-warning"></i>
                                        <?php endfor; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
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

<script src="https://cdn.jsdelivr.net
<script>
const chartData = <?= json_encode($chart_data) ?>;

new Chart(document.getElementById('performanceTrendChart'), {
    type: 'line',
    data: {
        labels: chartData.labels,
        datasets: [
            {
                label: 'Skor Kinerja',
                data: chartData.performance_scores,
                borderColor: 'rgb(255, 206, 86)',
                backgroundColor: 'rgba(255, 206, 86, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Tingkat Penyelesaian',
                data: chartData.completion_rates,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Tingkat Ketepatan Waktu',
                data: chartData.ontime_rates,
                backgroundColor: 'rgba(3, 18, 31, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function changeUser(userId) {
    if (userId) {
        window.location = '<?= base_url('kpi/user_performance') ?>/' + userId;
    }
}
</script>
