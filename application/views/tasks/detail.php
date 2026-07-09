<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-5 mt-3 pb-2 mb-3 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('tasks') ?>">Tugas</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($task->title) ?></li>
            </ol>
            </nav>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php if (has_permission('manage_tasks')): ?>
            <div class="btn-group me-2 flex-wrap flex-md-nowrap">
                <a href="<?= base_url('tasks/edit/' . $task->id) ?>" class="btn btn-outline-warning">
                    <i class="bi bi-pencil"></i> <span class="d-md-inline">Ubah</span>
                </a>
                <button onclick="confirmDuplicate()" class="btn btn-outline-secondary">
                    <i class="bi bi-copy"></i> <span class="d-none d-md-inline">Duplikasi ke Periode Berikutnya</span><span class="d-md-none">Duplikasi</span>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Task Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Tugas</h6>
                    <div>
                        <?= priority_badge($task->priority) ?>
                        <?= status_badge($task->status) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Deskripsi</h6>
                            <p><?= $task->description ? nl2br(htmlspecialchars($task->description)) : '<em class="text-muted">Tidak ada deskripsi</em>' ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Ditugaskan Kepada</h6>
                            <p>
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($task->assigned_to_name) ?>
                                <br><small class="text-muted"><?= htmlspecialchars($task->assigned_role_display) ?></small>
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Proyek</h6>
                            <p>
                                <?php if ($task->project_title): ?>
                                    <a href="<?= base_url('projects/detail/' . $task->project_id) ?>">
                                        <i class="bi bi-folder"></i> <?= htmlspecialchars($task->project_title) ?>
                                    </a>
                                <?php else: ?>
                                    <em class="text-muted">Tidak ada proyek</em>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted small">Tenggat Waktu</h6>
                            <p class="<?= is_deadline_overdue($task->deadline) ? 'text-danger' : (is_deadline_near($task->deadline) ? 'text-warning' : '') ?>">
                                <i class="bi bi-calendar-event"></i> <?= format_date($task->deadline) ?>
                                <?php
                                $days = days_remaining($task->deadline);
                                if ($days !== NULL):
                                    if ($days < 0): ?>
                                        <span class="badge bg-danger"><?= abs($days) ?> hari terlambat</span>
                                    <?php elseif ($days == 0): ?>
                                        <span class="badge bg-warning">Jatuh tempo hari ini</span>
                                    <?php elseif ($days <= 3): ?>
                                        <span class="badge bg-warning text-dark"><?= $days ?> hari lagi</span>
                                    <?php endif;
                                endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Dibuat Oleh</h6>
                            <p><i class="bi bi-person"></i> <?= htmlspecialchars($task->created_by_name) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Tag</h6>
                            <p>
                                <?php if (!empty($task->tags)): ?>
                                    <?php foreach ($task->tags as $tag): ?>
                                        <span class="badge" style="background-color: <?= $tag->color ?>; color: white;">
                                            <?= htmlspecialchars($tag->name) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <em class="text-muted">Tidak ada tag</em>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-chat-dots"></i> Komentar (<?= count($comments) ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Add Comment Form -->
                    <div class="mb-4">
                        <form id="commentForm">
                            <div class="input-group">
                                <input type="text" class="form-control" id="commentInput"
                                       placeholder="Tulis komentar..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div id="commentsList">
                        <?php if (empty($comments)): ?>
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-chat" style="font-size: 2rem;"></i>
                                <p class="mt-2">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                            <div class="card mb-2 border-light">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($comment->user_name) ?></strong>
                                            <small class="text-muted ms-2"><?= format_datetime($comment->created_at) ?></small>
                                            <span class="badge bg-info ms-2"><?= htmlspecialchars($comment->user_role) ?></span>
                                        </div>
                                        <?php if (has_permission('moderate_comments') || $comment->user_id == get_user_id()): ?>
                                        <button onclick="deleteComment(<?= $comment->id ?>)"
                                                class="btn btn-sm text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($comment->comment)) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history"></i> Riwayat Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($activities)): ?>
                        <p class="text-muted text-center small mb-0">Belum ada aktivitas tercatat</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($activities as $activity): ?>
                            <li class="d-flex mb-3">
                                <div class="me-3 text-muted"><i class="bi bi-dot"></i></div>
                                <div>
                                    <small><strong><?= htmlspecialchars($activity->user_name) ?></strong> — <?= htmlspecialchars($activity->description) ?></small>
                                    <br><small class="text-muted"><?= format_datetime($activity->created_at) ?></small>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-arrow-repeat"></i> Perbarui Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button onclick="updateStatus('pending')"
                                class="btn <?= $task->status == 'pending' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                            <i class="bi bi-clock"></i> Menunggu
                        </button>
                        <button onclick="updateStatus('on_progress')"
                                class="btn <?= $task->status == 'on_progress' ? 'btn-warning' : 'btn-outline-warning' ?>">
                            <i class="bi bi-arrow-repeat"></i> Sedang Dikerjakan
                        </button>
                        <button onclick="updateStatus('in_review')"
                                class="btn <?= $task->status == 'in_review' ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="bi bi-eye"></i> Ajukan Review
                        </button>
                        <?php if (has_permission('manage_tasks')): ?>
                        <button onclick="updateStatus('done')"
                                class="btn <?= $task->status == 'done' ? 'btn-success' : 'btn-outline-success' ?>">
                            <i class="bi bi-check-circle"></i> Setujui Selesai
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-paperclip"></i> Lampiran (<?= count($files) ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <?= form_open_multipart('tasks/upload_file/' . $task->id, array('id' => 'uploadForm')) ?>
                        <div class="mb-3">
                            <input type="file" class="form-control" name="userfile" id="fileInput"
                                   accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.mp4,.mov,.zip">
                            <small class="text-muted">Maks: 10MB (Gambar, PDF, Dokumen, Video)</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-upload"></i> Unggah File
                        </button>
                    <?= form_close() ?>

                    <div class="mt-3">
                        <?php if (empty($files)): ?>
                            <p class="text-muted text-center small">Tidak ada file terlampir</p>
                        <?php else: ?>
                            <?php foreach ($files as $file): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <i class="bi <?= $this->File_model->get_file_icon($file->file_name) ?>"></i>
                                    <small><?= htmlspecialchars($file->file_name) ?></small>
                                    <br><small class="text-muted">
                                        <?= number_format($file->file_size / 1024, 2) ?> KB
                                    </small>
                                </div>
                                <div>
                                    <a href="<?= base_url($file->file_path) ?>" target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <button onclick="deleteFile(<?= $file->id ?>)"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Task Meta -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Info Tugas</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Dibuat:</small>
                        <small><?= format_datetime($task->created_at) ?></small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Diperbarui:</small>
                        <small><?= format_datetime($task->updated_at) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function postForm(url, params) {
    var body = csrf_token_name + '=' + encodeURIComponent(csrf_hash);
    if (params) {
        body += '&' + params;
    }
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body
    });
}

function confirmDuplicate() {
    if (confirm('Duplikasi tugas ini ke periode berikutnya (tenggat +7 hari, atau +1 bulan untuk proyek bulanan)?')) {
        postForm('<?= base_url('tasks/duplicate/' . $task->id) ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= base_url('tasks/detail/') ?>' + data.task_id;
            } else {
                alert('Gagal menduplikasi tugas: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menduplikasi tugas');
        });
    }
}

function updateStatus(status) {
    if (confirm('Perbarui status tugas menjadi ' + status.replace('_', ' ') + '?')) {
        postForm('<?= base_url('tasks/update_status/' . $task->id) ?>', 'status=' + encodeURIComponent(status))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memperbarui status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    }
}

document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const comment = document.getElementById('commentInput').value;

    postForm('<?= base_url('tasks/add_comment/' . $task->id) ?>', 'comment=' + encodeURIComponent(comment))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menambah komentar: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambah komentar');
    });
});

function deleteComment(commentId) {
    if (confirm('Hapus komentar ini?')) {
        postForm('<?= base_url('tasks/delete_comment/') ?>' + commentId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus komentar');
            }
        });
    }
}

function deleteFile(fileId) {
    if (confirm('Hapus file ini?')) {
        postForm('<?= base_url('tasks/delete_file/') ?>' + fileId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus file: ' + (data.message || 'Unknown error'));
            }
        });
    }
}
</script>
