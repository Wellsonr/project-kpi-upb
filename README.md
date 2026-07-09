# Task Tracker - Social Media Management System

A web-based task tracking application for social media management teams, built with CodeIgniter 3, PHP, MySQL, and Bootstrap 5.

## Prasyarat Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx dengan mod_rewrite aktif
- CodeIgniter 3 framework

## Features

- **Role-based Access Control**: Admin, Video Editor, Designer, Social Media Specialist
- **Project Management**: Create weekly/monthly projects with deadlines
- **Task Management**: Assign tasks to team members with status tracking
- **Notifications**: Real-time alerts for task assignments, deadlines, and comments
- **File Uploads**: Attach evidence files to tasks (images, documents, videos)
- **Comments**: Team collaboration on tasks
- **Dashboard**: Overview of tasks, progress, and deadlines


## Instalasi

### 1. Download CodeIgniter 3

Download CodeIgniter 3 dari https://codeigniter.com/download atau gunakan:

```bash
cd /path/to/project-kpi
composer require codeigniter/framework
```

Pindahkan folder `system` CodeIgniter ke root proyek:
```bash
# Jika menggunakan composer
cp -r vendor/codeigniter/framework/system system

# Atau download manual dan letakkan folder system
```

### 2. Setup Database

Buat database MySQL dan import schema:

```bash
mysql -u root -p
CREATE DATABASE task_tracker_db;
USE task_tracker_db;
SOURCE database/schema.sql;
```

### 3. Konfigurasi

Salin `.env.example` menjadi `.env`, lalu isi kredensial database Anda
(`.env` sudah di-gitignore sehingga kredensial asli tidak pernah ter-commit):

```bash
cp .env.example .env
```

```env
DB_HOSTNAME=localhost
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
DB_DATABASE=task_tracker_db
```

Update base URL di `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/project-kpi/';
```

### 4. Izin File (File Permissions)

Set izin tulis untuk uploads:

```bash
chmod -R 755 uploads
chmod -R 755 application/logs
chmod -R 755 application/cache
```

### 5. Akses Aplikasi

Buka browser dan navigasikan ke:
```
http://localhost/project-kpi/
```

## Pengguna Default

| Peran | Email | Password |
|------|-------|----------|
| Admin | admin@test.com | admin123 |
| Video Editor | editor@test.com | admin123 |
| Designer | designer@test.com | admin123 |
| Social Media | socmed@test.com | admin123 |

**Penting**: Ubah password default setelah login pertama!

## Struktur Proyek

```
project-kpi/
├── application/
│   ├── config/          # File konfigurasi
│   ├── controllers/     # Controllers (Auth, Dashboard, Projects, Tasks, Users, Notifications)
│   ├── models/          # Models (User, Project, Task, Tag, Comment, File, Notification)
│   ├── views/           # View files (layouts, auth, dashboard, projects, tasks, users, notifications)
│   └── helpers/         # Custom helpers (auth_helper)
├── assets/
│   ├── css/             # Custom CSS
│   └── js/              # Custom JavaScript
├── database/
│   └── schema.sql       # Skema database
├── uploads/
│   └── tasks/           # Lampiran tugas
├── system/              # Core CodeIgniter (download terpisah)
└── index.php            # Entry point
```

## Pengembangan

### Menambah Fitur Baru

1. **Controller**: Buat di `application/controllers/`
2. **Model**: Buat di `application/models/`
3. **View**: Buat di `application/views/`
4. **Routes**: Update `application/config/routes.php`
5. **Menu**: Update sidebar di `application/views/layouts/sidebar.php`

### Perubahan Database

Buat file migration atau update `database/schema.sql` untuk perubahan skema.

## Setup Integrasi Google Calendar

Setiap pengguna dapat menghubungkan Google Calendar mereka sendiri agar tugas yang ditugaskan kepada mereka
otomatis muncul sebagai event di "Task Tracker" calendar khusus. Ini
memerlukan setup satu kali proyek Google Cloud sebelum siapapun dapat menghubungkan:

1. Buka [console.cloud.google.com](https://console.cloud.google.com), buat
   proyek (atau gunakan yang sudah ada).
2. Aktifkan **Google Calendar API**: APIs & Services → Library → search
   "Google Calendar API" → Enable.
3. Konfigurasi **OAuth consent screen**: APIs & Services → OAuth consent
   screen. User Type "External" (kecuali seluruh tim Anda dalam satu Google
   Workspace organization, maka "Internal"). Tambahkan scope
   `https://www.googleapis.com/auth/calendar`.
4. Buat **OAuth Client ID**: APIs & Services → Credentials → Create
   Credentials → OAuth client ID → Web application. Redirect URI:
   `https://domain-anda/profile/google_callback`.
5. Isi **Client ID** dan **Client Secret** di `.env`:
   ```env
   GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=xxxxx
   ```
6. **Selama aplikasi belum lulus verifikasi Google** (verifikasi bisa
   memakan waktu mingguan untuk "External" consent screen), hanya email yang ditambahkan sebagai
   **Test users** di consent screen yang dapat menghubungkan. Tambahkan email Google setiap
   anggota tim di sana, atau mereka akan melihat error "app not verified"
   saat mencoba menghubungkan.

Setelah diatur, setiap pengguna menghubungkan dari **Profil Saya** (menu kanan atas) →
**Connect Google Calendar**.

## Lisensi

Proyek ini open source dan tersedia di bawah MIT License.
