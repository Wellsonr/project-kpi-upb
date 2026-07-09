<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-file-earmark-bar-graph"></i> Laporan KPI</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="bi bi-printer"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Report Controls -->
    <div class="card shadow mb-4 no-print">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Jenis Laporan</label>
                    <select class="form-select" name="type" onchange="this.form.submit()">
                        <option value="weekly" <?= $period_type == 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                        <option value="monthly" <?= $period_type == 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode yang Ditampilkan</label>
                    <select class="form-select" name="count" onchange="this.form.submit()">
                        <option value="4" <?= $period_count == 4 ? 'selected' : '' ?>>4 Periode Terakhir</option>
                        <option value="8" <?= $period_count == 8 ? 'selected' : '' ?>>8 Periode Terakhir</option>
                        <option value="12" <?= $period_count == 12 ? 'selected' : '' ?>>12 Periode Terakhir</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        Menampilkan <strong><?= $period_count ?></strong> <?= $period_type ?> laporan
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Content -->
    <div class="report-content">
        <?php foreach ($report['periods'] as $period): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 text-white"><?= $period['label'] ?></h6>
                <small><?= $period['start'] ?> hingga <?= $period['end'] ?></small>
            </div>
            <div class="card-body">
                <?php if (empty($period['users']) || empty(array_filter($period['users'], function($u) { return $u['tasks_assigned'] > 0; }))): ?>
                    <p class="text-muted text-center">Tidak ada aktivitas pada periode ini</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Peran</th>
                                    <th>Ditugaskan</th>
                                    <th>Selesai</th>
                                    <th>Penyelesaian</th>
                                    <th>Ketepatan Waktu</th>
                                    <th>Kualitas</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                usort($period['users'], function($a, $b) {
                                    return $b['performance_score'] <=> $a['performance_score'];
                                });
                                ?>

                                <?php foreach ($period['users'] as $user): ?>
                                    <?php if ($user['tasks_assigned'] > 0): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['user_name']) ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= htmlspecialchars($user['role_display']) ?></span>
                                        </td>
                                        <td><?= $user['tasks_assigned'] ?></td>
                                        <td><?= $user['tasks_done'] ?></td>
                                        <td>
                                            <span class="badge <?= $user['completion_rate'] >= 80 ? 'bg-success' : ($user['completion_rate'] >= 50 ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= number_format($user['completion_rate'], 1) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $user['ontime_rate'] >= 80 ? 'bg-success' : ($user['ontime_rate'] >= 50 ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= number_format($user['ontime_rate'], 1) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <?= $user['quality_avg'] > 0 ? number_format($user['quality_avg'], 1) : '-' ?>
                                        </td>
                                        <td>
                                            <strong class="<?= $user['performance_score'] >= 80 ? 'text-success' : ($user['performance_score'] >= 60 ? 'text-warning' : 'text-danger') ?>">
                                                <?= number_format($user['performance_score'], 1) ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }

    .report-content {
        width: 100%;
    }

    .card {
        page-break-inside: avoid;
    }

    table {
        font-size: 10pt;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    .badge {
        border: 1px solid #dee2e6;
    }
}
</style>
