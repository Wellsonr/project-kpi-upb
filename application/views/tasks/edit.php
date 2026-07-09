<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-pencil"></i> Ubah Tugas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('tasks/detail/' . $task->id) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Tugas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Informasi Tugas</h6>
                </div>
                <div class="card-body">
                    <?= form_open('tasks/edit/' . $task->id, array('id' => 'editTaskForm')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= set_value('title', $task->title) ?>"
                                   placeholder="Masukkan judul tugas" required>
                            <?= form_error('title', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="4" placeholder="Masukkan deskripsi tugas"><?= set_value('description', $task->description) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_id" class="form-label">Proyek <span class="text-danger">*</span></label>
                                <select class="form-select" id="project_id" name="project_id" required>
                                    <option value="">Pilih proyek</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project->id ?>"
                                                <?= set_select('project_id', $project->id, $task->project_id == $project->id) ?>>
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
                                        <option value="<?= $user->id ?>"
                                                <?= set_select('assigned_to', $user->id, $task->assigned_to == $user->id) ?>>
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
                                       name="deadline"
                                       value="<?= set_value('deadline', date('Y-m-d', strtotime($task->deadline))) ?>"
                                       required>
                                <?= form_error('deadline', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" <?= set_select('priority', 'low', $task->priority == 'low') ?>>Rendah</option>
                                    <option value="medium" <?= set_select('priority', 'medium', $task->priority == 'medium') ?>>Sedang</option>
                                    <option value="high" <?= set_select('priority', 'high', $task->priority == 'high') ?>>Tinggi</option>
                                </select>
                                <?= form_error('priority', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" <?= set_select('status', 'pending', $task->status == 'pending') ?>>Menunggu</option>
                                    <option value="on_progress" <?= set_select('status', 'on_progress', $task->status == 'on_progress') ?>>Sedang Dikerjakan</option>
                                    <option value="in_review" <?= set_select('status', 'in_review', $task->status == 'in_review') ?>>Menunggu Review</option>
                                    <option value="done" <?= set_select('status', 'done', $task->status == 'done') ?>>Selesai</option>
                                </select>
                                <?= form_error('status', '<div class="text-danger small mt-1">', '</div>') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label">Tag</label>
                                <select class="form-select" id="tags" name="tags[]" multiple>
                                    <?php
                                    $task_tag_ids = array();
                                    if (!empty($task->tags)) {
                                        foreach ($task->tags as $tt) {
                                            $task_tag_ids[] = $tt->id;
                                        }
                                    }
                                    ?>
                                    <?php foreach ($tags as $tag): ?>
                                        <option value="<?= $tag->id ?>"
                                                data-color="<?= $tag->color ?>"
                                                <?= in_array($tag->id, $task_tag_ids) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tag->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('tasks/detail/' . $task->id) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Perbarui Tugas
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
