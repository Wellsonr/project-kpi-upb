<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-graph-up"></i> Dashboard KPI</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button onclick="recalculateKPI()" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i> Hitung Ulang
                </button>
                <a href="<?= base_url('kpi/report') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                </a>
            </div>
            <div class="btn-group">
                <button onclick="exportReport()" class="btn btn-success">
                    <i class="bi bi-download"></i> Ekspor CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Periode Awal</label>
                    <input type="date" class="form-control" name="start" value="<?= $period_start ?>" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode Akhir</label>
                    <input type="date" class="form-control" name="end" value="<?= $period_end ?>" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pilih Cepat</label>
                    <select class="form-select" onchange="setPeriod(this.value)">
                        <option value="">Pilih periode</option>
                        <option value="today">Hari Ini</option>
                        <option value="week" selected>Minggu Ini</option>
                        <option value="last_week">Minggu Lalu</option>
                        <option value="month">Bulan Ini</option>
                        <option value="last_month">Bulan Lalu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="<?= base_url('kpi/team_comparison?' . http_build_query(array('start' => $period_start, 'end' => $period_end))) ?>"
                       class="btn btn-primary w-100">
                        <i class="bi fa-people"></i> Lihat Perbandingan Lengkap
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- KPI Formula -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-calculator"></i> Rumus Perhitungan KPI
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-success">
                                <i class="bi bi-check-circle"></i> Tingkat Penyelesaian
                            </h6>
                            <p class="card-text mb-2">
                                <code>Tugas Selesai / Tugas Total × 100</code>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Bobot: <strong>30%</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bi bi-clock"></i> Tingkat Ketepatan Waktu
                            </h6>
                            <p class="card-text mb-2">
                                <code>Tugas Tepat Waktu / Tugas Selesai × 100</code>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Bobot: <strong>40%</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-warning">
                                <i class="bi bi-star"></i> Rata-rata Kualitas
                            </h6>
                            <p class="card-text mb-2">
                                <code>(Rating / 5) × 100</code>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Bobot: <strong>30%</strong></small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <h6 class="font-weight-bold">Skor Kinerja Total</h6>
                <p class="mb-0">
                    <code>(Penyelesaian × 30%) + (Ketepatan Waktu × 40%) + (Kualitas × 30%)</code>
                </p>
                <small class="text-muted">Maksimal: 100 poin</small>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tingkat Penyelesaian Tim</div>
                            <div class="h5 mb-0 font-weight-bold text-success"><?= $team_summary['avg_completion_rate'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tingkat Ketepatan Waktu Pengiriman</div>
                            <div class="h5 mb-0 font-weight-bold text-info"><?= $team_summary['avg_ontime_rate'] ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Skor Kinerja Tim</div>
                            <div class="h5 mb-0 font-weight-bold text-warning"><?= number_format($team_summary['avg_performance_score'], 1) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-trophy fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tugas</div>
                            <div class="h5 mb-0 font-weight-bold text-primary"><?= $team_summary['total_done'] ?>/<?= $team_summary['total_tasks'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-task fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Performers -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-trophy-fill"></i> Kinerja Terbaik
                    </h6>
                    <span class="badge bg-success">Minggu Ini</span>
                </div>
                <div class="card-body">
                    <?php if (empty($top_performers)): ?>
                        <p class="text-muted text-center">Tidak ada data</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($top_performers as $index => $performer): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">                                  
                                        <span class="badge bg-light text-dark">#<?= $index + 1 ?></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($performer['user_name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($performer['role_display']) ?></small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="h5 mb-0 text-success"><?= number_format($performer['performance_score'], 1) ?></div>
                                    <small class="text-muted">Skor</small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Role Comparison -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi fa-bar-chart"></i> Perbandingan Kinerja Peran
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="roleComparisonChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Performers Alert -->
    <?php if (!empty($bottom_performers)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="bi fa-exclamation-triangle"></i> Perlu Perhatian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($bottom_performers as $performer): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($performer['user_name']) ?></h6>
                                    <p class="card-text mb-1">
                                        <small class="text-muted"><?= htmlspecialchars($performer['role_display']) ?></small>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <small>Skor: <strong class="text-warning"><?= number_format($performer['performance_score'], 1) ?></strong></small>
                                        <small>Selesai: <?= $performer['tasks_done'] ?>/<?= $performer['tasks_assigned'] ?></small>
                                    </div>
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

<script src="https://cdn.jsdelivr.net
<script>
const roleData = <?= json_encode($role_comparison) ?>;

const labels = Object.keys(roleData).map(r => {
    const names = {video_editor: 'Video Editor', designer: 'Designer', socmed: 'Social Media'};
    return names[r] || r;
});

const completionData = Object.values(roleData).map(r => r.completion_rate);
const ontimeData = Object.values(roleData).map(r => r.ontime_rate);
const scoreData = Object.values(roleData).map(r => r.performance_score);

new Chart(document.getElementById('roleComparisonChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Tingkat Penyelesaian',
                data: completionData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Tingkat Ketepatan Waktu',
                data: ontimeData,
                backgroundColor: 'rgba(3, 18, 31, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Skor Kinerja',
                data: scoreData,
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
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

function setPeriod(period) {
    const url = new URL(window.location);
    const now = new Date();
    let start, end;

    switch(period) {
        case 'today':
            start = end = now.toISOString().split('T')[0];
            break;
        case 'week':
            start = new Date(now.setDate(now.getDate() - now.getDay() + 1)).toISOString().split('T')[0];
            end = new Date(now.setDate(now.getDate() - now.getDay() + 7)).toISOString().split('T')[0];
            break;
        case 'last_week':
            start = new Date(now.setDate(now.getDate() - now.getDay() - 6)).toISOString().split('T')[0];
            end = new Date(now.setDate(now.getDate() - now.getDay())).toISOString().split('T')[0];
            break;
        case 'month':
            start = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
            end = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
            break;
        case 'last_month':
            start = new Date(now.getFullYear(), now.getMonth() - 1, 1).toISOString().split('T')[0];
            end = new Date(now.getFullYear(), now.getMonth(), 0).toISOString().split('T')[0];
            break;
    }

    if (start && end) {
        url.searchParams.set('start', start);
        url.searchParams.set('end', end);
        window.location = url.toString();
    }
}

function recalculateKPI() {
    if (confirm('Hitung ulang semua KPI untuk periode ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('kpi/recalculate') ?>';

        const startInput = document.createElement('input');
        startInput.type = 'hidden';
        startInput.name = 'period_start';
        startInput.value = '<?= $period_start ?>';

        const endInput = document.createElement('input');
        endInput.type = 'hidden';
        endInput.name = 'period_end';
        endInput.value = '<?= $period_end ?>';

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'period_type';
        typeInput.value = 'weekly';

        form.appendChild(startInput);
        form.appendChild(endInput);
        form.appendChild(typeInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportReport() {
    const url = new URL('<?= base_url('kpi/export_report') ?>');
    url.searchParams.set('start', '<?= $period_start ?>');
    url.searchParams.set('end', '<?= $period_end ?>');
    window.location = url.toString();
}
</script>
