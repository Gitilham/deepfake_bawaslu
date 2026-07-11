'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const uploadForm = document.getElementById('uploadForm');
    if (!uploadForm) return;

    const uploadCard = document.getElementById('uploadCard');
    const videoInput = document.getElementById('video');
    const uploadZone = document.getElementById('uploadZone');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewPlayer = document.getElementById('previewPlayer');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const changeVideoBtn = document.getElementById('changeVideoBtn');
    const removeVideoBtn = document.getElementById('removeVideoBtn');
    const submitBtn = document.getElementById('submitBtn');
    const maxSizeMb = Number(uploadForm.dataset.maxSizeMb || 100);
    const maxDuration = Number(uploadForm.dataset.maxDurationSeconds || 0);
    let allowedExt = ['mp4', 'avi', 'mov', 'mkv'];

    try {
        const configured = JSON.parse(uploadForm.dataset.allowedExtensions || '[]');
        if (Array.isArray(configured) && configured.length) allowedExt = configured;
    } catch (_) {
        // Konfigurasi server sudah divalidasi; fallback aman dipakai bila atribut rusak.
    }

    const maxSizeBytes = maxSizeMb * 1024 * 1024;
    let selectedFile = null;
    let previewUrl = null;
    let isSubmitting = false;

    const formatFileSize = (bytes) => {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let index = 0;
        while (size >= 1024 && index < units.length - 1) {
            size /= 1024;
            index++;
        }
        return `${size.toFixed(size >= 10 ? 1 : 2)} ${units[index]}`;
    };

    const getExtension = (filename) => filename.split('.').pop().toLowerCase();

    const revokePreviewUrl = () => {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    };

    const clearVideo = () => {
        selectedFile = null;
        videoInput.value = '';
        previewPlayer.pause();
        previewPlayer.removeAttribute('src');
        previewPlayer.load();
        revokePreviewUrl();
        uploadPreview.classList.remove('show');
        uploadZone.classList.remove('hide');
        selectedFileName.textContent = '-';
        selectedFileSize.textContent = '-';
    };

    const validateFile = (file) => {
        if (!file) return 'Silakan pilih video terlebih dahulu.';
        if (!allowedExt.includes(getExtension(file.name))) return 'Format video tidak didukung. Gunakan MP4, AVI, MOV atau MKV.';
        if (file.size <= 0) return 'File video kosong.';
        if (file.size > maxSizeBytes) return `Ukuran video melebihi batas maksimal ${maxSizeMb} MB.`;
        return '';
    };

    const showPreview = (file) => {
        const error = validateFile(file);
        if (error) {
            alert(error);
            clearVideo();
            return;
        }
        selectedFile = file;
        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = formatFileSize(file.size);
        revokePreviewUrl();
        previewUrl = URL.createObjectURL(file);
        previewPlayer.src = previewUrl;
        previewPlayer.load();
        uploadZone.classList.add('hide');
        uploadPreview.classList.add('show');
    };

    videoInput.addEventListener('change', () => showPreview(videoInput.files[0]));
    changeVideoBtn.addEventListener('click', () => videoInput.click());
    removeVideoBtn.addEventListener('click', clearVideo);
    uploadZone.addEventListener('dragover', (event) => {
        event.preventDefault();
        uploadZone.classList.add('dragover');
    });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (event) => {
        event.preventDefault();
        uploadZone.classList.remove('dragover');
        const file = event.dataTransfer.files[0];
        if (!file) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        videoInput.files = transfer.files;
        showPreview(file);
    });

    previewPlayer.addEventListener('loadedmetadata', () => {
        if (maxDuration > 0 && Number.isFinite(previewPlayer.duration) && previewPlayer.duration > maxDuration) {
            alert(`Durasi video melebihi batas maksimal ${maxDuration} detik.`);
            clearVideo();
        }
    });

    uploadForm.addEventListener('submit', (event) => {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }
        const error = validateFile(selectedFile || videoInput.files[0]);
        if (error) {
            event.preventDefault();
            alert(error);
            return;
        }
        isSubmitting = true;
        uploadCard.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses';
        requestAnimationFrame(() => {
            previewPlayer.pause();
            previewPlayer.removeAttribute('src');
            previewPlayer.load();
            revokePreviewUrl();
        });
    });

    window.addEventListener('beforeunload', revokePreviewUrl);
});
