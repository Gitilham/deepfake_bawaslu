# Fase Kedua: Direct Browser Upload

Arsitektur utama tetap `Browser -> CodeIgniter -> FastAPI`. Direct upload browser ke FastAPI belum diterapkan karena akan memindahkan batas keamanan dan membutuhkan CORS ketat, token upload berumur pendek, autentikasi backend, validasi kepemilikan, rate limiting, callback atau polling bertanda tangan, idempotency, serta sinkronisasi database.

Fase kedua yang aman perlu merancang:

- endpoint pembuatan upload job terautentikasi di CodeIgniter;
- token sekali pakai dan pembatasan ukuran/format di FastAPI;
- penyimpanan hasil yang tidak dapat dipalsukan browser;
- callback bertanda tangan atau polling server-to-server;
- request ID dan status job;
- kebijakan cleanup dan kegagalan parsial.

Tanpa komponen tersebut, direct upload berisiko membocorkan backend, melewati otorisasi, dan memungkinkan manipulasi payload hasil.
