# Pengukuran Performa Deteksi

Log CodeIgniter `Detection timing` berisi request ID, ukuran, MIME, validasi frontend-server, durasi request ke backend, `processing_seconds` backend, waktu database, total request, HTTP status, dan status akhir. Log tidak memuat binary video atau data pribadi.

Overhead request backend dapat diperkirakan:

`frontend_to_backend_seconds - backend_processing_seconds`

Nilainya mencakup pengiriman CodeIgniter ke backend, antrean, serialisasi respons, dan jaringan. Progress XHR hanya mewakili browser ke CodeIgniter. Setelah mencapai 100%, elapsed timer terus berjalan tanpa persentase inferensi palsu.

Benchmark manual:

1. Gunakan video yang sama dan kondisi jaringan serupa minimal tiga kali.
2. Catat waktu upload di UI, `total_request_seconds`, `frontend_to_backend_seconds`, dan `backend_processing_seconds` dengan request ID yang sama.
3. Bandingkan median, bukan satu hasil.
4. Pantau log container frontend dan backend untuk request ID tersebut.

Perintah log manual setelah container dijalankan pengguna:

```powershell
docker logs --tail 200 -f deepfake-frontend
docker logs --tail 200 -f deepfake-backend
```

Pengukuran belum dijalankan oleh Codex.

## Mengganti backend

Ubah `DEEPFAKE_API_BASE_URL` di `.env`, tanpa slash penutup. Endpoint dan timeout dapat diubah melalui variabel `DEEPFAKE_API_*` lainnya. Karena `.env` dimasukkan ke container saat startup, recreate container frontend setelah perubahan. Jangan mengirim Base URL melalui form upload atau hidden input.
