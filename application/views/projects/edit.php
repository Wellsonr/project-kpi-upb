<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-pencil"></i> Ubah Proyek</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('projects/detail/' . $project->id) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Proyek
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Informasi Proyek</h6>
                </div>
                <div class="card-body">
                    <?= form_open('projects/edit/' . $project->id, array('id' => 'editProjectForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= set_value('title', $project->title) ?>"
                                   placeholder="Masukkan judul proyek" required>
                            <?= form_error('title', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="3" placeholder="Masukkan deskripsi proyek"><?= set_value('description', $project->description) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Pilih tipe</option>
                                    <option value="weekly" <?= set_select('type', 'weekly', $project->type == 'weekly') ?>>Mingguan</option>
                                    <option value="monthly" <?= set_select('type', 'monthly', $project->type == 'monthly') ?>>Bulanan</option>
                                </select>
                                <?= form_error('type', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="periode_start" class="form-label">Awal Periode <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="periode_start"
                                       name="periode_start"
                                       value="<?= set_value('periode_start', date('Y-m-d', strtotime($project->periode_start))) ?>"
                                       required>
                                <?= form_error('periode_start', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="periode_end" class="form-label">Akhir Periode <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="periode_end"
                                       name="periode_end"
                                       value="<?= set_value('periode_end', date('Y-m-d', strtotime($project->periode_end))) ?>"
                                       required>
                                <?= form_error('periode_end', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deadline" class="form-label">Tenggat Waktu Proyek <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="deadline"
                                       name="deadline"
                                       value="<?= set_value('deadline', date('Y-m-d', strtotime($project->deadline))) ?>"
                                       required>
                                <?= form_error('deadline', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?= set_select('status', 'active', $project->status == 'active') ?>>Aktif</option>
                                    <option value="completed" <?= set_select('status', 'completed', $project->status == 'completed') ?>>Selesai</option>
                                    <option value="archived" <?= set_select('status', 'archived', $project->status == 'archived') ?>>Diarsipkan</option>
                                </select>
                                <?= form_error('status', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('projects/detail/' . $project->id) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Perbarui Proyek
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Statistik Proyek</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Tugas:</span>
                        <strong><?= $project->task_count ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Dibuat Oleh:</span>
                        <strong><?= htmlspecialchars($project->creator_name) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Dibuat Pada:</span>
                        <strong><?= format_date($project->created_at) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
