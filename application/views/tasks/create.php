<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-plus-lg"></i> Buat Tugas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('tasks') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Tugas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tugas</h6>
                </div>
                <div class="card-body">
                    <?= form_open_multipart('tasks/create', array('id' => 'createTaskForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="Masukkan judul tugas" required>
                            <?= form_error('title', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="4" placeholder="Masukkan deskripsi tugas"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_id" class="form-label">Proyek <span class="text-danger">*</span></label>
                                <select class="form-select" id="project_id" name="project_id" required>
                                    <option value="">Pilih proyek</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project->id ?>"
                                                <?= $selected_project == $project->id ? 'selected' : '' ?>
                                                data-deadline="<?= date('Y-m-d', strtotime($project->deadline)) ?>">
                                            <?= htmlspecialchars($project->title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('project_id', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="assigned_to" class="form-label">Tugaskan Kepada <span class="text-danger">*</span></label>
                                <select class="form-select" id="assigned_to" name="assigned_to" required>
                                    <option value="">Pilih pengguna</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id ?>" data-role="<?= $user->role_name ?>">
                                            <?= htmlspecialchars($user->name) ?> (<?= $user->role_display_name ?>)
                                            <?php if (isset($active_task_counts[$user->id])): ?>
                                                — <?= $active_task_counts[$user->id] ?> aktif
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('assigned_to', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="deadline" class="form-label">Tenggat Waktu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="deadline"
                                       name="deadline" required>
                                <?= form_error('deadline', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Rendah</option>
                                    <option value="medium" selected>Sedang</option>
                                    <option value="high">Tinggi</option>
                                </select>
                                <?= form_error('priority', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tags" class="form-label">Tag</label>
                                <select class="form-select" id="tags" name="tags[]" multiple>
                                    <?php foreach ($tags as $tag): ?>
                                        <option value="<?= $tag->id ?>"
                                                data-color="<?= $tag->color ?>">
                                            <?= htmlspecialchars($tag->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tag Terpilih</label>
                            <div id="selectedTags" class="d-flex flex-wrap gap-2">
                                <!-- Tags will appear here -->
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('tasks') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Buat Tugas
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
                            Tugaskan tugas ke anggota tim tertentu
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Tetapkan tenggat yang realistis berdasarkan prioritas
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Gunakan tag untuk mengelompokkan tugas
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success"></i>
                            Tugas berprioritas tinggi muncul di atas
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('project_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const projectDeadline = selectedOption.getAttribute('data-deadline');
    if (projectDeadline) {
        document.getElementById('deadline').value = projectDeadline;
    }
});

document.getElementById('tags').addEventListener('change', function() {
    const selectedTagsDiv = document.getElementById('selectedTags');
    selectedTagsDiv.innerHTML = '';

    Array.from(this.selectedOptions).forEach(option => {
        const color = option.getAttribute('data-color') || '#007bff';
        const tag = document.createElement('span');
        tag.className = 'badge';
        tag.style.backgroundColor = color;
        tag.style.color = 'white';
        tag.textContent = option.text;
        selectedTagsDiv.appendChild(tag);
    });
});
</script>
