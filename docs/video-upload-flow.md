# Alur Upload Video

1. Browser memeriksa keberadaan, ukuran, extension, MIME yang masuk akal, dan file kosong untuk UX.
2. XHR mengirim `FormData` ke controller CodeIgniter dengan CSRF dan menampilkan byte upload aktual.
3. CodeIgniter mengulangi validasi final dari `DEEPFAKE_MAX_VIDEO_MB` dan `DEEPFAKE_ALLOWED_VIDEO_FORMATS`.
4. `DetectionWorkflowService` mengambil `getTempName()` tanpa membaca isi video.
5. `DeepfakeApiClient` membungkus path tersebut sebagai `CURLFile` pada field multipart `video` dan POST satu kali ke `/predict-video`.
6. Respons dinormalisasi. Nilai keputusan tidak dihitung ulang; prioritasnya `final_decision`, `result`, lalu fallback legacy.
7. Setelah sukses, temporary upload dipindahkan satu kali ke nama acak di `writable/uploads/videos`.
8. Hasil utama dan frame disimpan dalam transaksi. Kegagalan database memicu rollback dan penghapusan file yang baru dipindahkan.

Nama asli hanya menjadi metadata dan nama multipart yang sudah dibatasi dengan `basename()`. Path permanen selalu memakai nama acak. File gagal tidak dipublikasikan. Temporary file yang tidak dipindahkan dikelola oleh lifecycle upload PHP.

Tidak ada Base64 karena ukurannya membesar dan memerlukan seluruh video berada di RAM. Multipart mengizinkan cURL membaca langsung dari file temporary.

