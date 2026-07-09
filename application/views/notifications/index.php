<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-bell"></i> Notifikasi
            <?php if ($unread_count > 0): ?>
                <span class="badge bg-danger"><?= $unread_count ?></span>
            <?php endif; ?>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <?php if ($unread_count > 0): ?>
            <button onclick="markAllAsRead()" class="btn btn-outline-primary">
                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <?php if (empty($notifications)): ?>
                <div class="text-center p-5">
                    <i class="bi bi-bell-slash" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Notifikasi</h4>
                    <p class="text-muted">Semua notifikasi sudah dibaca!</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item <?= $notif->is_read ? '' : 'bg-light' ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <?php
                                $icon = 'bi-bell';
                                $color = 'text-primary';

                                switch ($notif->type) {
                                    case 'task_assigned':
                                        $icon = 'bi-person-plus';
                                        $color = 'text-primary';
                                        break;
                                    case 'deadline_reminder':
                                        $icon = 'bi-alarm';
                                        $color = 'text-warning';
                                        break;
                                    case 'new_comment':
                                        $icon = 'bi-chat-dots';
                                        $color = 'text-info';
                                        break;
                                    case 'status_update':
                                        $icon = 'bi-arrow-repeat';
                                        $color = 'text-success';
                                        break;
                                }
                                ?>
                                <i class="bi <?= $icon ?> <?= $color ?>"></i>
                                <?php if ($notif->task_id): ?>
                                    <a href="<?= base_url('tasks/detail/' . $notif->task_id) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($notif->message) ?>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($notif->message) ?>
                                <?php endif; ?>

                                <?php if (!$notif->is_read): ?>
                                    <span class="badge bg-primary ms-2">Baru</span>
                                <?php endif; ?>

                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?= format_datetime($notif->created_at) ?>
                                </small>
                                <?php if ($notif->project_title): ?>
                                    <small class="text-muted">
                                        | <i class="bi bi-folder"></i> <?= htmlspecialchars($notif->project_title) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <button onclick="deleteNotification(<?= $notif->id ?>)"
                                    class="btn btn-sm text-danger ms-2">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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

function markAllAsRead() {
    postForm('<?= base_url('notifications/mark_all_read') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(id) {
    if (confirm('Hapus notifikasi ini?')) {
        postForm('<?= base_url('notifications/delete/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

document.querySelectorAll('a[href*="tasks/detail"]').forEach(link => {
    link.addEventListener('click', function() {
        const notifItem = this.closest('.bg-light');
        if (notifItem) {
            notifItem.classList.remove('bg-light');
        }
    });
});
</script>
