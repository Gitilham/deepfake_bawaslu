# Audit dan Optimasi Deteksi Frontend

## Alur sebelum optimasi

Browser mengirim form multipart ke CodeIgniter. Controller memvalidasi file, memindahkan temporary upload ke `writable/uploads/videos`, membuat record `pending`, lalu `FlaskApiService` mengirim file permanen tersebut sebagai `CURLFile` ke backend. Sesudah respons, record diperbarui dan detail frame disimpan.

Video sebelumnya **tidak** memakai Base64, JSON binary, atau `file_get_contents()`. File sudah dikirim multipart dan tidak dibaca penuh ke memori PHP. Health check juga tidak dipanggil sebelum prediksi.

Satu binary video ditulis oleh PHP ke temporary upload dan dipindahkan ke satu file permanen. Tidak ditemukan salinan permanen kedua. Jadi pada sisi frontend terdapat nol operasi `copy`, satu penulisan temporary dari request, dan satu `move` ke path permanen. Namun file permanen dan record pending dibuat sebelum diketahui bahwa backend berhasil, sehingga kegagalan dapat meninggalkan video/record gagal. Form submit penuh juga membuat tahap upload dan inferensi tidak dapat dibedakan oleh pengguna.

## Alur setelah optimasi

`Browser -> PHP temporary upload -> DeepfakeApiClient multipart -> respons backend -> move satu kali ke writable/uploads/videos -> transaksi database -> hasil`.

- `DetectionWorkflowService` memakai path temporary asli.
- `DeepfakeApiClient` membuat `CURLFile`; binary tidak disalin ke variabel PHP.
- POST prediksi dilakukan satu kali tanpa retry dan tanpa health check pendahuluan.
- Video baru dipindahkan ke penyimpanan permanen setelah prediksi sukses.
- Database baru menerima record sukses setelah respons valid; detail frame berada dalam transaksi yang sama.
- Jika penyimpanan database gagal, file permanen yang baru dibuat dibersihkan.
- `DeepfakeResponseNormalizer` memprioritaskan `final_decision`, kemudian fallback kompatibilitas lama.
- Browser memakai XHR untuk progress upload nyata. Sesudah upload 100%, UI hanya menampilkan elapsed time karena backend sinkron tidak menyediakan progress inferensi.

Jumlah frontend setelah optimasi tetap nol `copy`, satu penulisan temporary, dan paling banyak satu `move`. Optimasi tidak mengurangi write upload yang memang wajib; optimasi mengubah urutan agar file gagal tidak dipindahkan menjadi file permanen. Backend tetap dapat membuat temporary file sendiri sebagai bagian penerimaan multipart.

## Bottleneck dan risiko

Durasi terbesar diperkirakan berasal dari transfer video dan inferensi backend sekitar 18 detik. Klaim peningkatan performa belum dapat dibuat tanpa benchmark. Risiko yang perlu diuji: limit reverse proxy, disk permission `writable`, timeout jaringan server, format WebM yang harus juga didukung backend, dan respons backend untuk video sangat besar.

Optimasi yang sengaja tidak diterapkan: kompresi, transcoding, perubahan resolusi/FPS/codec, ekstraksi frame di browser, upload langsung browser ke FastAPI, retry POST, threshold atau probability flip di frontend.

## Konfigurasi

Sumber runtime berada di `Config\\DeepfakeApi` dan environment `DEEPFAKE_*`. PHP memakai `upload_max_filesize=500M`, `post_max_size=510M`, `memory_limit=256M`, serta timeout 1000 detik. `post_max_size` harus selalu lebih besar dari `upload_max_filesize`.
Apache memakai `Timeout 1000` dari `apache-detection.conf`, lebih besar dari timeout backend 900 detik. Perubahan ini baru berlaku setelah image frontend dibangun ulang oleh pengguna.
