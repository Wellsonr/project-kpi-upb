<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-person-circle"></i> Profil Saya</h1>
    </div>

    <div class="card shadow" style="max-width: 600px;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-calendar-event"></i> Google Calendar
            </h6>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <?= $this->session->flashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($google_tokens): ?>
                <p class="text-success mb-3">
                    <i class="bi bi-check-circle-fill"></i> Terhubung sejak
                    <?= format_datetime($google_tokens->connected_at) ?>
                </p>
                <p class="text-muted small">
                    Task yang ditugaskan ke kamu akan otomatis muncul di calendar
                    Google bernama "Task Tracker".
                </p>
                <?= form_open('profile/disconnect') ?>
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Putuskan koneksi Google Calendar?')">
                        <i class="bi bi-x-circle"></i> Putuskan Koneksi
                    </button>
                <?= form_close() ?>
            <?php else: ?>
                <p class="text-muted mb-3">
                    Belum terhubung. Hubungkan akun Google kamu supaya task yang
                    ditugaskan ke kamu otomatis muncul sebagai reminder di
                    Google Calendar.
                </p>
                <a href="<?= base_url('profile/connect') ?>" class="btn btn-primary">
                    <i class="bi bi-google"></i> Connect Google Calendar
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
