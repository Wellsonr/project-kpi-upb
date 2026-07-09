<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Task Tracker</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?= isset($this->security) ? $this->security->get_csrf_hash() : '' ?>">
    <meta name="csrf-token-name" content="<?= isset($this->security) ? $this->security->get_csrf_token_name() : 'csrf_token' ?>">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css') ?>?v=<?= @filemtime(FCPATH.'assets/css/custom.css') ?: time() ?>" rel="stylesheet">

    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body>
<?php if (is_logged_in()): ?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1f2937;">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url() ?>">
            Task Tracker KPI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url(get_dashboard_url()) ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <?php if (has_permission('manage_projects')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('projects') ?>">
                        <i class="bi bi-folder"></i> Proyek
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('tasks') ?>">
                        <i class="bi bi-list-task"></i> <?= (has_permission('manage_tasks') || has_permission('view_all_tasks')) ? 'Tugas' : 'Tugas Saya' ?>
                    </a>
                </li>
                <?php if (has_permission('manage_users')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('users') ?>">
                        <i class="bi bi-people"></i> Pengguna
                    </a>
                </li>
                <?php endif; ?>
                <?php if (has_permission('manage_roles')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('roles') ?>">
                        <i class="bi bi-shield-lock"></i> Peran
                    </a>
                </li>
                <?php endif; ?>
                <?php if (has_permission('view_all_kpi')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('kpi') ?>">
                        <i class="bi bi-graph-up-arrow"></i> KPI
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <?php
                        $CI =& get_instance();
                        $CI->load->model('Notification_model');
                        $unread_count = $CI->Notification_model->unread_count(get_user_id());
                        ?>
                        <?php if ($unread_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $unread_count > 99 ? '99+' : $unread_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" id="notificationList" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <div class="text-center p-3">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= get_user_name() ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header"><?= get_user_role_display() ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('profile') ?>">
                            <i class="bi bi-person-circle"></i> Profil Saya
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="<?= is_logged_in() ? 'wrapper' : '' ?>">
    <?php if (is_logged_in()): ?>
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4><?= get_user_role_display() ?></h4>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="<?= base_url(get_dashboard_url()) ?>" class="<?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <?php if (has_permission('manage_projects')): ?>
            <li>
                <a href="<?= base_url('projects') ?>" class="<?= $this->uri->segment(1) == 'projects' ? 'active' : '' ?>">
                    <i class="bi bi-folder"></i> Proyek
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?= base_url('tasks') ?>" class="<?= $this->uri->segment(1) == 'tasks' ? 'active' : '' ?>">
                    <i class="bi bi-list-task"></i> <?= (has_permission('manage_tasks') || has_permission('view_all_tasks')) ? 'Tugas' : 'Tugas Saya' ?>
                </a>
            </li>
            <?php if (has_permission('manage_users')): ?>
            <li>
                <a href="<?= base_url('users') ?>" class="<?= $this->uri->segment(1) == 'users' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Pengguna
                </a>
            </li>
            <?php endif; ?>
            <?php if (has_permission('manage_roles')): ?>
            <li>
                <a href="<?= base_url('roles') ?>" class="<?= $this->uri->segment(1) == 'roles' ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock"></i> Peran
                </a>
            </li>
            <?php endif; ?>
            <?php if (has_permission('view_all_kpi')): ?>
            <li>
                <a href="<?= base_url('kpi') ?>" class="<?= $this->uri->segment(1) == 'kpi' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow"></i> KPI
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div id="content">
    <?php endif; ?>
