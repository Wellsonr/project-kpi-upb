<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2"><i class="bi bi-gear"></i> Pengaturan KPI</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('kpi') ?>">KPI</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </nav>
        </div>
    </div>

    <?= form_open('kpi/settings') ?>
        <?= csrf_field() ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bobot Skor Kinerja</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Bobot dipakai untuk menghitung skor kinerja gabungan (idealnya berjumlah 100).</p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Penyelesaian Tugas (%)</label>
                        <input type="number" class="form-control" name="completion_weight" min="0" max="100"
                               value="<?= htmlspecialchars($settings['completion_weight']) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ketepatan Waktu (%)</label>
                        <input type="number" class="form-control" name="ontime_weight" min="0" max="100"
                               value="<?= htmlspecialchars($settings['ontime_weight']) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kualitas (%)</label>
                        <input type="number" class="form-control" name="quality_weight" min="0" max="100"
                               value="<?= htmlspecialchars($settings['quality_weight']) ?>">
                    </div>
                </div>
                <p class="text-muted small mb-0">
                    Rating kualitas tugas dibatasi <?= htmlspecialchars($settings['min_quality_score']) ?>–<?= htmlspecialchars($settings['max_quality_score']) ?>.
                    Auto-recalculate KPI harian: <strong><?= $settings['auto_calculate'] == '1' ? 'Aktif' : 'Nonaktif' ?></strong>
                    (diatur lewat <code>kpi_settings</code>, dijalankan oleh <code>php index.php cron run</code>).
                </p>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Target Skor Kinerja per Peran</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Target dipakai sebagai pembanding "capaian vs target" di perbandingan tim.</p>
                <div class="row">
                    <?php foreach ($roles as $role): ?>
                        <?php if ($role->name === 'admin') continue; ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><?= htmlspecialchars($role->display_name) ?></label>
                            <input type="number" class="form-control" name="target_<?= $role->id ?>" min="0" max="100" step="0.1"
                                   value="<?= isset($role_targets[$role->id]) ? htmlspecialchars($role_targets[$role->id]) : '80' ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Simpan Pengaturan
        </button>
    <?= form_close() ?>
</div>
