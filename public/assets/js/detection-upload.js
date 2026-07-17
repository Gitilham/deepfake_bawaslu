'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('uploadForm');
    if (!form) return;

    const byId = (id) => document.getElementById(id);
    const card = byId('uploadCard');
    const input = byId('video');
    const zone = byId('uploadZone');
    const preview = byId('uploadPreview');
    const player = byId('previewPlayer');
    const validation = byId('uploadValidation');
    const submit = byId('submitBtn');
    const progress = byId('uploadProgress');
    const progressLabel = byId('uploadProgressLabel');
    const title = byId('analysisTitle');
    const elapsed = byId('analysisElapsed');
    const maxSizeMb = Number(form.dataset.maxSizeMb || 500);
    const maxSizeBytes = maxSizeMb * 1024 * 1024;
    let extensions = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
    let selectedFile = null;
    let previewUrl = null;
    let activeRequest = null;
    let elapsedTimer = null;
    let insightTimer = null;
    let insightIndex = 0;
    let browserUploadStarted = 0;
    let browserUploadSeconds = null;

    try {
        const configured = JSON.parse(form.dataset.allowedExtensions || '[]');
        if (Array.isArray(configured) && configured.length) extensions = configured;
    } catch (_) { /* Server tetap melakukan validasi final. */ }

    const extensionOf = (name) => String(name).toLowerCase().split('.').pop();
    const fileSize = (bytes) => {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1048576) return `${(bytes / 1024).toFixed(1)} KB`;
        return `${(bytes / 1048576).toFixed(1)} MB`;
    };
    const showError = (text) => {
        validation.textContent = text;
        validation.hidden = false;
    };
    const clearError = () => {
        validation.textContent = '';
        validation.hidden = true;
    };
    const validate = (file) => {
        if (!file) return 'Silakan pilih video terlebih dahulu.';
        if (file.size < 1) return 'File video kosong.';
        if (file.size > maxSizeBytes) return `Ukuran video melebihi batas ${maxSizeMb} MB.`;
        if (!extensions.includes(extensionOf(file.name))) return `Format harus ${extensions.join(', ').toUpperCase()}.`;
        if (file.type && !file.type.startsWith('video/') && file.type !== 'application/octet-stream') return 'Tipe file tidak terlihat seperti video.';
        return '';
    };
    const revokePreview = () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = null;
    };
    const selectFile = (file) => {
        const error = validate(file);
        if (error) return showError(error);
        clearError();
        selectedFile = file;
        byId('selectedFileName').textContent = file.name;
        byId('selectedFileSize').textContent = fileSize(file.size);
        byId('selectedFileType').textContent = file.type || extensionOf(file.name).toUpperCase();
        revokePreview();
        previewUrl = URL.createObjectURL(file);
        player.src = previewUrl;
        zone.classList.add('hide');
        preview.classList.add('show');
    };
    const clearFile = () => {
        selectedFile = null;
        input.value = '';
        player.pause();
        player.removeAttribute('src');
        revokePreview();
        preview.classList.remove('show');
        zone.classList.remove('hide');
        clearError();
    };
    const setStage = (heading) => {
        title.textContent = heading;
    };
    const insights = [
        { icon: 'bi-film', label: 'Proses analisis', title: 'Video diperiksa dari banyak frame', text: 'Sistem membandingkan pola visual pada sejumlah frame, bukan hanya satu gambar.', heading: 'Menganalisis Rangkaian Frame' },
        { icon: 'bi-person-bounding-box', label: 'Pemeriksaan visual', title: 'Wajah dianalisis secara konsisten', text: 'Model memeriksa karakteristik wajah dan perubahan visual antarrangkaian frame.', heading: 'Memeriksa Pola Wajah' },
        { icon: 'bi-cpu', label: 'Model V21', title: 'Fitur visual diproses oleh model', text: 'Hasil encoder dan classifier digunakan backend untuk menentukan kecenderungan video.', heading: 'Mengolah Fitur Visual' },
        { icon: 'bi-shield-check', label: 'Perlu diingat', title: 'Hasil adalah verifikasi awal', text: 'Gunakan hasil deteksi sebagai bantuan dan lakukan pemeriksaan lanjutan untuk keputusan penting.', heading: 'Menyiapkan Hasil Analisis' }
    ];
    const showInsight = (index) => {
        const insight = insights[index % insights.length];
        const insightBox = byId('analysisInsight');
        insightBox.classList.remove('changing');
        void insightBox.offsetWidth;
        byId('analysisInsightIcon').innerHTML = `<i class="bi ${insight.icon}"></i>`;
        byId('analysisInsightLabel').textContent = insight.label;
        byId('analysisInsightTitle').textContent = insight.title;
        byId('analysisInsightText').textContent = insight.text;
        Array.from(byId('analysisInsightDots').children).forEach((dot, position) => dot.classList.toggle('active', position === index % insights.length));
        title.textContent = insight.heading;
        insightBox.classList.add('changing');
    };
    const startInsights = () => {
        if (insightTimer) window.clearInterval(insightTimer);
        insightIndex = 0;
        showInsight(insightIndex);
        insightTimer = window.setInterval(() => showInsight(++insightIndex), 3000);
    };
    const stopInsights = () => {
        if (insightTimer) window.clearInterval(insightTimer);
        insightTimer = null;
    };
    const beginElapsed = () => {
        const started = performance.now();
        elapsedTimer = window.setInterval(() => {
            const seconds = Math.floor((performance.now() - started) / 1000);
            elapsed.textContent = `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
        }, 250);
    };
    const reset = () => {
        activeRequest = null;
        card.classList.remove('loading');
        document.body.classList.remove('detection-loading');
        if (elapsedTimer) window.clearInterval(elapsedTimer);
        stopInsights();
        elapsedTimer = null;
        elapsed.textContent = '00:00';
        progress.value = 0;
        progressLabel.textContent = 'Upload 0%';
        setStage('Mengunggah Video');
        if (!submit.hasAttribute('data-model-unavailable')) submit.disabled = false;
        submit.innerHTML = '<i class="bi bi-shield-check me-1"></i> Mulai Deteksi';
    };
    const updateCsrf = (hash) => {
        if (!hash) return;
        const token = form.querySelector('input[type="hidden"]');
        if (token) token.value = hash;
    };
    const showResult = (result) => {
        const label = String(result.label || 'UNKNOWN').toUpperCase();
        const views = {
            REAL: ['success', 'Video Anda Terdeteksi Real', 'Sistem mendeteksi video ini sebagai video real.', '#059669'],
            DEEPFAKE: ['warning', 'Video Anda Terdeteksi Deepfake', 'Sistem mendeteksi video ini sebagai video deepfake.', '#dc2626'],
            MENCURIGAKAN: ['question', 'Hasil Video Mencurigakan', 'Backend menetapkan hasil pada batas keputusan dan perlu pemeriksaan lebih lanjut.', '#d97706'],
            NO_FACE: ['info', 'Wajah Tidak Terdeteksi', 'Sistem belum dapat menilai video karena wajah tidak cukup terdeteksi.', '#2563eb']
        };
        const view = views[label] || ['info', 'Analisis Video Selesai', 'Buka detail untuk melihat hasil lengkap.', '#475569'];
        Swal.fire({
            icon: view[0], title: `<strong>${view[1]}</strong>`, text: view[2],
            confirmButtonText: 'Lihat Detail', showDenyButton: true, denyButtonText: 'Deteksi Lagi',
            confirmButtonColor: view[3], denyButtonColor: '#64748b', allowOutsideClick: false
        }).then((choice) => {
            if (choice.isConfirmed && result.detail_url) window.location.href = result.detail_url;
            else window.location.reload();
        });
    };

    input.addEventListener('change', () => selectFile(input.files[0]));
    byId('changeVideoBtn').addEventListener('click', () => input.click());
    byId('removeVideoBtn').addEventListener('click', clearFile);
    zone.addEventListener('dragover', (event) => { event.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', (event) => {
        event.preventDefault();
        zone.classList.remove('dragover');
        const file = event.dataTransfer.files[0];
        if (!file) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        input.files = transfer.files;
        selectFile(file);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        if (activeRequest) return;
        const file = selectedFile || input.files[0];
        const error = validate(file);
        if (error) return showError(error);

        clearError();
        card.classList.add('loading');
        document.body.classList.add('detection-loading');
        submit.disabled = true;
        submit.textContent = 'Sedang Diproses...';
        beginElapsed();

        const xhr = new XMLHttpRequest();
        activeRequest = xhr;
        browserUploadStarted = performance.now();
        browserUploadSeconds = null;
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.upload.addEventListener('progress', (upload) => {
            if (!upload.lengthComputable) return;
            const percent = Math.min(100, Math.round((upload.loaded / upload.total) * 100));
            progress.value = percent;
            progressLabel.textContent = `Upload ${percent}%`;
            if (percent === 100) setStage('Mengirim ke Mesin Analisis');
        });
        xhr.upload.addEventListener('load', () => {
            browserUploadSeconds = (performance.now() - browserUploadStarted) / 1000;
            progress.value = 100;
            progressLabel.textContent = 'Upload selesai';
            setStage('Video Sedang Dianalisis');
            startInsights();
        });
        xhr.addEventListener('load', () => {
            let payload = null;
            try { payload = JSON.parse(xhr.responseText); } catch (_) { /* Ditangani sebagai respons tidak valid. */ }
            updateCsrf(payload && payload.csrf_hash);
            console.info('Detection browser timing', {
                browser_to_frontend_upload_seconds: browserUploadSeconds,
                total_browser_request_seconds: (performance.now() - browserUploadStarted) / 1000,
                status: xhr.status
            });
            if (xhr.status >= 200 && xhr.status < 300 && payload && payload.success) {
                setStage('Selesai');
                reset();
                showResult(payload.result);
                return;
            }
            const errors = payload && payload.errors ? Object.values(payload.errors).join(' ') : '';
            reset();
            showError(errors || (payload && payload.message) || 'Permintaan gagal diproses. Silakan coba kembali.');
        });
        xhr.addEventListener('error', () => { reset(); showError('Koneksi ke aplikasi terputus. Silakan coba kembali.'); });
        xhr.addEventListener('timeout', () => { reset(); showError('Waktu tunggu habis. Silakan coba kembali.'); });
        xhr.send(new FormData(form));
    });

    if (submit.disabled) submit.setAttribute('data-model-unavailable', 'true');
    window.addEventListener('beforeunload', revokePreview);
});
