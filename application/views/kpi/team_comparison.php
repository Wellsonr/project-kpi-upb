<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2"><i class="bi fa-people"></i> Perbandingan Tim</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('kpi') ?>">KPI</a></li>
                    <li class="breadcrumb-item active">Perbandingan Tim</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button onclick="exportReport()" class="btn btn-success">
                <i class="bi bi-download"></i> Ekspor CSV
            </button>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Periode Awal</label>
                    <input type="date" class="form-control" name="start" value="<?= $period_start ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Periode Akhir</label>
                    <input type="date" class="form-control" name="end" value="<?= $period_end ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Role Comparison Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kinerja berdasarkan Peran</h6>
        </div>
        <div class="card-body">
            <canvas id="roleComparisonChart" height="500"></canvas>
        </div>
    </div>

    <!-- Team Ranking Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Peringkat Kinerja Individu</h6>
        </div>
        <div class="card-body">
            <?php if (empty($users_kpi)): ?>
                <p class="text-muted text-center">Tidak ada data</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Peringkat</th>
                                <th>Pengguna</th>
                                <th>Peran</th>
                                <th>Tugas</th>
                                <th>Penyelesaian</th>
                                <th>Ketepatan Waktu</th>
                                <th>Kualitas</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_kpi as $index => $kpi): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">#<?= $index + 1 ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                <?= strtoupper(substr($kpi['user_name'], 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0"><?= htmlspecialchars($kpi['user_name']) ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= htmlspecialchars($kpi['role_display']) ?></span>
                                </td>
                                <td>
                                    <?= $kpi['tasks_done'] ?>/<?= $kpi['tasks_assigned'] ?>
                                    <?php if ($kpi['tasks_overdue'] > 0): ?>
                                        <span class="badge bg-danger ms-1"><?= $kpi['tasks_overdue'] ?> terlambat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px; width: 60px;">
                                            <div class="progress-bar bg-success" style="width: <?= $kpi['completion_rate'] ?>%"></div>
                                        </div>
                                        <small><?= number_format($kpi['completion_rate'], 1) ?>%</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px; width: 60px;">
                                            <div class="progress-bar bg-info" style="width: <?= $kpi['ontime_rate'] ?>%"></div>
                                        </div>
                                        <small><?= number_format($kpi['ontime_rate'], 1) ?>%</small>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($kpi['quality_avg'] > 0): ?>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?= $i <= round($kpi['quality_avg']) ? '-fill' : '-half' ?> text-warning" style="font-size: 0.8rem;"></i>
                                            <?php endfor; ?>
                                            <small class="ms-1">(<?= number_format($kpi['quality_avg'], 1) ?>)</small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <h5 class="mb-0 <?= $kpi['performance_score'] >= 80 ? 'text-success' : ($kpi['performance_score'] >= 60 ? 'text-warning' : 'text-danger') ?>">
                                        <?= number_format($kpi['performance_score'], 1) ?>
                                        <?php if ($kpi['trend']['delta'] !== null): ?>
                                            <?php if ($kpi['trend']['direction'] == 'up'): ?>
                                                <small class="text-success"><i class="bi bi-arrow-up-short"></i><?= number_format(abs($kpi['trend']['delta']), 1) ?></small>
                                            <?php elseif ($kpi['trend']['direction'] == 'down'): ?>
                                                <small class="text-danger"><i class="bi bi-arrow-down-short"></i><?= number_format(abs($kpi['trend']['delta']), 1) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted"><i class="bi bi-dash"></i></small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </h5>
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
const roleData = <?= json_encode($role_comparison) ?>;

const labels = Object.keys(roleData).map(r => {
    const names = {video_editor: 'Video Editor', designer: 'Designer', socmed: 'Social Media'};
    return names[r] || r;
});

const scoreData = Object.values(roleData).map(r => r.performance_score);
const completionData = Object.values(roleData).map(r => r.completion_rate);
const ontimeData = Object.values(roleData).map(r => r.ontime_rate);
const targetData = Object.values(roleData).map(r => r.target);

new Chart(document.getElementById('roleComparisonChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Skor Kinerja',
                data: scoreData,
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                borderRadius: 5
            },
            {
                label: 'Target',
                data: targetData,
                type: 'line',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderDash: [6, 4],
                borderWidth: 2,
                pointRadius: 0,
                fill: false
            },
            {
                label: 'Tingkat Penyelesaian',
                data: completionData,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Tingkat Ketepatan Waktu',
                data: ontimeData,
               backgroundColor: 'rgba(3, 18, 31, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
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

function exportReport() {
    const url = new URL('<?= base_url('kpi/export_report') ?>');
    url.searchParams.set('start', '<?= $period_start ?>');
    url.searchParams.set('end', '<?= $period_end ?>');
    window.location = url.toString();
}
</script>
