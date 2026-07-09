<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-plus-lg"></i> Buat Proyek</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('projects') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Proyek
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Proyek</h6>
                </div>
                <div class="card-body">
                    <?= form_open('projects/create', array('id' => 'createProjectForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="Masukkan judul proyek" required>
                            <?= form_error('title', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="3" placeholder="Masukkan deskripsi proyek"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Pilih tipe</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                                <?= form_error('type', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="periode_start" class="form-label">Awal Periode <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="periode_start"
                                       name="periode_start" required>
                                <?= form_error('periode_start', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="periode_end" class="form-label">Akhir Periode <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="periode_end"
                                       name="periode_end" required>
                                <?= form_error('periode_end', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Tenggat Waktu Proyek <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="deadline"
                                   name="deadline" required>
                            <?= form_error('deadline', '<div class="text-danger small mt-1">', '</div>') ?>
                            <small class="text-muted">Tenggat waktu akhir untuk semua tugas dalam proyek ini.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('projects') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Buat Proyek
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info"><i class="bi bi-info-circle"></i> Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Pilih <strong>Mingguan</strong> untuk rencana konten jangka pendek
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Pilih <strong>Bulanan</strong> untuk kampanye atau tema bulanan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Tetapkan tenggat waktu yang realistis sesuai kapasitas tim
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success"></i>
                            Setelah dibuat, Anda dapat menambah tugas dan menugaskannya ke anggota tim
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    var today = new Date();

    if (this.value === 'weekly') {
        var start = new Date(today);
        var end = new Date(today);
        end.setDate(end.getDate() + 6);
        var deadline = new Date(end);

        document.getElementById('periode_start').valueAsDate = start;
        document.getElementById('periode_end').valueAsDate = end;
        document.getElementById('deadline').valueAsDate = deadline;
    } else if (this.value === 'monthly') {
        var start = new Date(today.getFullYear(), today.getMonth(), 1);
        var end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        var deadline = new Date(end);

        document.getElementById('periode_start').valueAsDate = start;
        document.getElementById('periode_end').valueAsDate = end;
        document.getElementById('deadline').valueAsDate = deadline;
    }
});

document.getElementById('periode_start').addEventListener('change', function() {
    var startDate = new Date(this.value);
    var endDateInput = document.getElementById('periode_end');
    var deadlineInput = document.getElementById('deadline');

    if (endDateInput.valueAsDate && endDateInput.valueAsDate <= startDate) {
        endDateInput.valueAsDate = new Date(startDate.getTime() + 6 * 24 * 60 * 60 * 1000);
    }

    if (deadlineInput.valueAsDate && deadlineInput.valueAsDate <= startDate) {
        deadlineInput.valueAsDate = new Date(startDate.getTime() + 6 * 24 * 60 * 60 * 1000);
    }
});
</script>
