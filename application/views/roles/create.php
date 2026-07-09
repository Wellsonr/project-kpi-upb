<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="bi bi-shield-plus"></i> Tambah Peran</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('roles') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Peran
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Peran</h6>
                </div>
                <div class="card-body">
                    <?= form_open('roles/create') ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Kunci Peran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="cth. content_lead" value="<?= set_value('name') ?>" required>
                            <div class="form-text">Identifier internal: hanya huruf, angka, dan garis bawah.</div>
                            <?= form_error('name', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label for="display_name" class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="display_name" name="display_name"
                                   placeholder="cth. Content Lead" value="<?= set_value('display_name') ?>" required>
                            <?= form_error('display_name', '<div class="text-danger small mt-1">', '</div>') ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hak Akses</label>
                            <?php foreach ($all_permissions as $permission): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="<?= $permission->id ?>" id="perm_<?= $permission->id ?>">
                                    <label class="form-check-label" for="perm_<?= $permission->id ?>">
                                        <?= htmlspecialchars($permission->label) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('roles') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Buat Peran
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
