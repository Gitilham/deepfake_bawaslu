# Analisis Skema Database

## Ruang lingkup audit

Analisis dilakukan terhadap konfigurasi proyek, route, filter, seluruh model, controller, view, library, command, test, README, `.env.example`, migration lama, dan riwayat Git. Tidak ditemukan backup SQL atau migration pembentuk tabel. Tiga migration tanggal 2026-07-11 hanya menambah kolom/data bila tabel dasar sudah ada; pada database kosong ketiganya berhenti tanpa membuat tabel.

Direktori `app/Validation` dan `app/Entities` tidak ada. `app/Helpers` hanya berisi `.gitkeep`. Test database bawaan menggunakan tabel contoh `factories` di namespace test dan bukan bagian skema aplikasi.

## Inventaris tabel dan relasi

| Tabel | Sumber utama | Relasi |
|---|---|---|
| `roles` | `RoleModel`, `AuthController`, filter role | induk `users.role_id` |
| `users` | `UserModel`, auth, profil, admin user, session/filter | milik `roles`; induk deteksi, review, dan konten edukasi |
| `system_settings` | `SystemSettingModel`, `ApiSettingController`, `FlaskApiService`, command maintenance | tidak memiliki FK |
| `video_detections` | `VideoDetectionModel`, controller deteksi/history/report/dashboard, command cleanup | milik user; opsional direview user admin |
| `detection_frames` | `DetectionFrameModel`, controller deteksi dan detail | milik satu deteksi |
| `flask_api_logs` | `FlaskApiLogModel`, `FlaskApiService`, command prune | opsional terkait satu deteksi |
| `education_contents` | `EducationContentModel`, `EducationController` | opsional dibuat oleh user |

## `roles`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | `RoleModel::$primaryKey`; sama dengan `users.role_id` |
| `role_name` | VARCHAR(50) | NOT NULL | UNIQUE | login/filter membandingkan nilai `admin` dan `user` |
| `description` | VARCHAR(255) | NULL | | field model |
| `created_at` | DATETIME | NULL | | timestamp model |
| `updated_at` | DATETIME | NULL | | timestamp model |

## `users`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | ID session dan seluruh relasi pengguna |
| `role_id` | BIGINT UNSIGNED | NOT NULL | FK, INDEX | join `roles.id = users.role_id` |
| `full_name` | VARCHAR(150) | NOT NULL | | validasi form maksimal 150 |
| `email` | VARCHAR(191) | NOT NULL | UNIQUE | login dan `is_unique[users.email]` |
| `password` | VARCHAR(255) | NOT NULL | | kode memakai `password_verify()` dan `password_hash()` pada field `password` |
| `phone` | VARCHAR(30) | NULL | | registrasi/profil, maksimal 30 |
| `address` | TEXT | NULL | | profil opsional |
| `is_active` | BOOLEAN/TINYINT(1) | NOT NULL DEFAULT 1 | INDEX | auth dan toggle status |
| `last_login` | DATETIME | NULL | | diperbarui setelah login |
| `created_at` | DATETIME | NULL | INDEX komposit | timestamp model dan urutan daftar |
| `updated_at` | DATETIME | NULL | | timestamp model |
| `deleted_at` | DATETIME | NULL | INDEX komposit | soft delete model dan seluruh query aktif |

FK `users.role_id -> roles.id` memakai `RESTRICT/RESTRICT`: role yang masih dipakai tidak boleh dihapus. Index tambahan `(role_id, deleted_at, id)` mendukung daftar user berdasarkan role dan urutan ID.

## `system_settings`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | model |
| `setting_key` | VARCHAR(191) | NOT NULL | UNIQUE | lookup dan upsert logis `getValue`/`setValue` |
| `setting_value` | TEXT | NULL | | URL, endpoint, boolean, angka, dan daftar ekstensi disimpan sebagai string |
| `description` | VARCHAR(255) | NULL | | field model/migration lama |
| `created_at` | DATETIME | NULL | | timestamp model |
| `updated_at` | DATETIME | NULL | | timestamp model |

Key aktual: `flask_api_base_url`, `flask_api_predict_endpoint`, `max_video_size_mb`, `allowed_video_types`, `store_raw_video`, `raw_video_retention_days`, `store_full_api_response`, `api_log_payload_max_bytes`, `store_frame_metadata`, `frame_metadata_retention_days`, `health_cache_seconds`, `api_success_log_retention_days`, dan `api_error_log_retention_days`.

## `video_detections`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | model dan detail route |
| `user_id` | BIGINT UNSIGNED | NOT NULL | FK, INDEX | uploader dari session; join/history |
| `original_filename` | VARCHAR(255) | NOT NULL | | nama upload klien |
| `stored_filename` | VARCHAR(255) | NULL | | migration lama membuat nullable untuk mode tanpa penyimpanan raw video |
| `file_path` | VARCHAR(255) | NULL | INDEX parsial via komposit waktu | cleanup dan video lokal |
| `file_mime` | VARCHAR(100) | NULL | | MIME upload |
| `file_size` | BIGINT UNSIGNED | NULL | | ukuran byte dari upload |
| `status` | VARCHAR(30) | NOT NULL DEFAULT `pending` | INDEX | `pending`, `processing`, `completed`, `failed` |
| `predicted_label` | VARCHAR(30) | NULL DEFAULT `UNKNOWN` | INDEX | kode lama memakai `REAL`, `DEEPFAKE`, `UNKNOWN`; migration/test baru mengantisipasi status lain |
| `confidence` | DECIMAL(10,6) | NULL | | skor hasil model |
| `real_score` | DECIMAL(10,6) | NULL | | skor hasil model |
| `fake_score` | DECIMAL(10,6) | NULL | | skor hasil model |
| `duration_seconds` | DECIMAL(12,3) | NULL | | durasi video dapat pecahan detik |
| `api_response_json` | LONGTEXT | NULL | | controller melakukan `json_encode` dan model memperlakukannya sebagai string |
| `error_message` | TEXT | NULL | | error Flask/exception |
| `request_id` | VARCHAR(64) | NULL | INDEX | metadata respons baru dari migration/test |
| `binary_prediction` | VARCHAR(20) | NULL | | metadata respons baru |
| `requires_manual_review` | BOOLEAN/TINYINT(1) | NOT NULL DEFAULT 0 | INDEX | metadata audit |
| `review_status` | VARCHAR(20) | NOT NULL DEFAULT `unreviewed` | INDEX | controller admin membaca dan mengubah menjadi `reviewed` |
| `reviewed_by` | BIGINT UNSIGNED | NULL | FK, INDEX | ID admin dari session |
| `reviewed_at` | DATETIME | NULL | | waktu review |
| `model_version` | VARCHAR(100) | NULL | | metadata model Flask |
| `threshold` | DECIMAL(10,6) | NULL | | ambang keputusan model |
| `margin` | DECIMAL(10,6) | NULL | | margin keputusan model |
| `confidence_note` | TEXT | NULL | | penjelasan model |
| `decision_rule` | TEXT | NULL | | aturan keputusan model |
| `decision_explanation` | TEXT | NULL | | penjelasan keputusan model |
| `frames_used` | INT UNSIGNED | NULL | | metadata pemrosesan |
| `face_detected_count` | INT UNSIGNED | NULL | | metadata wajah |
| `min_face_frames` | INT UNSIGNED | NULL | | konfigurasi batas model |
| `api_latency_ms` | INT UNSIGNED | NULL | | latensi request Flask |
| `file_deleted_at` | DATETIME | NULL | | command cleanup |
| `created_at` | DATETIME | NULL | INDEX | filter tanggal, urutan, retention |
| `updated_at` | DATETIME | NULL | | timestamp model |
| `deleted_at` | DATETIME | NULL | INDEX | soft delete model |

FK `user_id -> users.id` memakai `RESTRICT` agar histori tidak hilang. FK `reviewed_by -> users.id` memakai `SET NULL` agar histori review tetap ada jika reviewer dihapus secara fisik. Index utama: `(user_id, deleted_at, id)`, `(deleted_at, created_at)`, `(predicted_label, deleted_at, created_at)`, `(status, deleted_at, created_at)`, `review_status`, `request_id`, dan `requires_manual_review`.

## `detection_frames`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | model |
| `detection_id` | BIGINT UNSIGNED | NOT NULL | FK, INDEX | detail berdasarkan deteksi |
| `frame_time` | DECIMAL(12,3) | NULL | INDEX komposit | urutan waktu frame |
| `label` | VARCHAR(30) | NULL DEFAULT `UNKNOWN` | | hasil frame |
| `confidence`, `real_score`, `fake_score` | DECIMAL(10,6) | NULL | | skor frame |
| `frame_path` | VARCHAR(255) | NULL | | path opsional |
| `source_frame_index` | INT UNSIGNED | NULL | | metadata respons baru |
| `face_detected` | BOOLEAN/TINYINT(1) | NOT NULL DEFAULT 0 | | metadata respons baru |
| `face_confidence` | DECIMAL(10,6) | NULL | | skor wajah |
| `crop_method` | VARCHAR(50) | NULL | | metode crop |
| `repeated_frame` | BOOLEAN/TINYINT(1) | NOT NULL DEFAULT 0 | | penanda frame berulang |
| `bbox_json` | TEXT | NULL | | model menyimpan JSON sebagai string |
| `frame_status` | VARCHAR(50) | NULL | | status pemrosesan frame |
| `note` | TEXT | NULL | | catatan frame |
| `created_at`, `updated_at` | DATETIME | NULL | created di-index | timestamp model/retention |

FK `detection_id -> video_detections.id` memakai `RESTRICT`; tidak ditemukan kode penghapusan fisik deteksi beserta frame. Index `(detection_id, frame_time)` mendukung detail terurut dan `created_at` mendukung retention.

## `flask_api_logs`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | model |
| `detection_id` | BIGINT UNSIGNED | NULL | FK, INDEX | test koneksi tidak memiliki deteksi; prediksi memilikinya |
| `endpoint` | VARCHAR(2048) | NOT NULL | | URL request |
| `request_method` | VARCHAR(10) | NOT NULL | | `GET`/`POST` |
| `http_status` | SMALLINT UNSIGNED | NULL | | status dapat 0 ketika cURL gagal |
| `request_payload` | TEXT | NULL | | JSON ringkas request |
| `response_payload` | LONGTEXT | NULL | | respons dapat besar dan diperlakukan sebagai string |
| `latency_ms` | INT UNSIGNED | NULL | | latensi cURL |
| `error_message` | TEXT | NULL | INDEX komposit | command membedakan sukses/error berdasarkan null |
| `created_at` | DATETIME | NOT NULL | INDEX | retention log |

FK `detection_id -> video_detections.id` memakai `SET NULL` untuk mempertahankan log. Index `(detection_id, created_at)` serta `(created_at, id)` mendukung relasi dan pruning. `error_message` tidak dijadikan index langsung karena bertipe TEXT; pruning tetap menggunakan index waktu lalu mengevaluasi null/error.

## `education_contents`

| Kolom | Tipe | Null/default | Key | Dasar teknis |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | NOT NULL | PK | model |
| `title` | VARCHAR(255) | NOT NULL | | field model/view |
| `slug` | VARCHAR(191) | NOT NULL | UNIQUE | identitas konten |
| `content` | LONGTEXT | NOT NULL | | isi edukasi |
| `is_active` | BOOLEAN/TINYINT(1) | NOT NULL DEFAULT 1 | INDEX komposit | query konten aktif |
| `created_by` | BIGINT UNSIGNED | NULL | FK | field model, pembuat opsional |
| `created_at`, `updated_at`, `deleted_at` | DATETIME | NULL | INDEX komposit | timestamp dan soft delete model |

FK `created_by -> users.id` memakai `SET NULL` agar konten tidak hilang ketika pembuat dihapus. Index `(is_active, deleted_at, id)` sesuai query model.

## Ketidakkonsistenan kode

1. `Admin\DetectionController` dan `Admin\ReportController` memanggil `VideoDetectionModel::paginateWithUser()` dan `getReportSummary()`, tetapi kedua method tidak ada pada model saat ini.
2. `Admin\DashboardController` memanggil `getAllWithUser([], 10)`, sedangkan signature model hanya menerima satu argumen; PHP akan mengabaikan argumen ekstra pada method user-defined saat ini, tetapi limit 10 tidak diterapkan.
3. Unit test `FlaskApiServiceTest` memanggil `normalizePredictionResponse()`, `extractErrorMessage()`, dan `buildCompactLog()`, tetapi method tersebut tidak ada pada library saat ini.
4. Migration metadata baru menyediakan label/status respons seperti `MENCURIGAKAN` dan `NO_FACE`, sedangkan controller upload lama membatasi `prediction` ke `REAL`, `DEEPFAKE`, atau `UNKNOWN` dan belum mengisi seluruh kolom metadata baru.
5. `User\ProfileController` memiliki fallback ke `password_hash`, tetapi `UserModel::$allowedFields`, auth, dan seluruh kode aktual memakai kolom `password`; skema hanya membuat `password`.
6. Tiga migration lama memiliki `down()` non-destruktif dan merupakan migration tambahan. Migration pembentuk tabel baru mencakup seluruh kolom final agar database kosong tetap lengkap walaupun migration lama sudah tercatat sebagai no-op.
7. Tidak ada route aktif untuk `User\VideoController`; kolom path tetap diperlukan oleh upload dan command cleanup.

Ketidakkonsistenan di atas tidak diperbaiki karena pekerjaan ini dibatasi pada database dan tidak boleh mengubah business logic.

## Asumsi terpaksa

1. ID numerik dipilih `BIGINT UNSIGNED` untuk seluruh PK/FK karena migration lama mendefinisikan `reviewed_by` sebagai BIGINT dan aplikasi melakukan cast ID ke integer.
2. Kolom skor menggunakan `DECIMAL(10,6)` berdasarkan migration metadata lama; durasi/frame time memakai `DECIMAL(12,3)` karena tidak ada kontrak presisi eksplisit.
3. `education_contents.slug` dibuat unik karena merupakan identitas slug, walaupun route detail slug belum tersedia.
4. `created_by` pada konten edukasi diperlakukan sebagai FK user karena namanya dan pola domain, walaupun belum ada controller CRUD konten pada source saat ini.
5. Tidak ada data edukasi palsu yang di-seed; halaman dapat berjalan dengan daftar kosong.
