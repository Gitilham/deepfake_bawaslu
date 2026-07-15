# Setup Database Deepfake Bawaslu

## Konfigurasi Docker

Gunakan konfigurasi berikut pada `.env` frontend:

```ini
database.default.hostname = deepfake-db
database.default.database = deepfake_bawaslu
database.default.username = deepfake_user
database.default.password = DeepfakeDb2026
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Container frontend dan MySQL harus berada pada network yang sama:

```powershell
docker network inspect deepfake-net
docker network connect deepfake-net deepfake-frontend
```

## Menjalankan migration

```powershell
docker exec deepfake-frontend php spark migrate:status
docker exec deepfake-frontend php spark migrate
docker exec deepfake-frontend php spark migrate:status
```

## Menjalankan seeder

```powershell
docker exec deepfake-frontend php spark db:seed DatabaseSeeder
```

Seeder aman dijalankan ulang: role, admin, dan setting yang sudah ada tidak ditambahkan kembali.

## Akun admin development

- Email: `admin@deepfake.local`
- Password: `Admin123!`
- Role: `admin`
- Status: aktif

Ganti password setelah login bila environment akan dipakai selain development lokal.

## Status dan rollback migration

Lihat status:

```powershell
docker exec deepfake-frontend php spark migrate:status
```

Rollback batch terakhir hanya pada database development yang boleh dikosongkan:

```powershell
docker exec deepfake-frontend php spark migrate:rollback
docker exec deepfake-frontend php spark migrate
docker exec deepfake-frontend php spark db:seed DatabaseSeeder
```

Migration lama tanggal 2026-07-11 bersifat tambahan dan memiliki rollback non-destruktif. Migration tanggal 2026-07-15 adalah pembentuk tabel lengkap dan menghapus tabel dalam urutan rollback terbalik yang aman.

## Memeriksa tabel MySQL

```powershell
docker exec deepfake-db mysql -udeepfake_user -pDeepfakeDb2026 -D deepfake_bawaslu -e "SHOW TABLES;"
docker exec deepfake-db mysql -udeepfake_user -pDeepfakeDb2026 -D deepfake_bawaslu -e "SHOW CREATE TABLE users\G"
docker exec deepfake-db mysql -udeepfake_user -pDeepfakeDb2026 -D deepfake_bawaslu -e "SELECT id, full_name, email, is_active FROM users;"
docker exec deepfake-db mysql -udeepfake_user -pDeepfakeDb2026 -D deepfake_bawaslu -e "SELECT setting_key, setting_value FROM system_settings ORDER BY setting_key;"
```

## Error foreign key

1. Pastikan seluruh tabel menggunakan InnoDB dan tipe PK/FK sama-sama `BIGINT UNSIGNED`.
2. Pastikan migration dijalankan berurutan; jangan membuat tabel anak secara manual sebelum tabel induk.
3. Periksa detail dengan `SHOW ENGINE INNODB STATUS\G` memakai akun root MySQL.
4. Jangan menonaktifkan `FOREIGN_KEY_CHECKS` sebagai solusi permanen.
5. Jika database development setengah terbentuk, reset menggunakan prosedur di bawah lalu jalankan migration dari awal.

## Reset database development

Perintah berikut menghapus seluruh data. Jalankan hanya pada database development:

```powershell
docker exec deepfake-db mysql -uroot -pRootDeepfake2026 -e "DROP DATABASE IF EXISTS deepfake_bawaslu; CREATE DATABASE deepfake_bawaslu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON deepfake_bawaslu.* TO 'deepfake_user'@'%'; FLUSH PRIVILEGES;"
docker exec deepfake-frontend php spark migrate
docker exec deepfake-frontend php spark db:seed DatabaseSeeder
```

## Peringatan produksi

Jangan menjalankan `php spark migrate:refresh`, reset database, atau rollback pada database produksi yang berisi data penting. Ambil backup dan uji migration pada salinan database terlebih dahulu.
