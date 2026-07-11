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
    const selectedFileType = document.getElementById('selectedFileType');
    const changeVideoBtn = document.getElementById('changeVideoBtn');
    const removeVideoBtn = document.getElementById('removeVideoBtn');
    const submitBtn = document.getElementById('submitBtn');
    const uploadValidation = document.getElementById('uploadValidation');
    const analysisSteps = Array.from(document.querySelectorAll('#analysisSteps li'));
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
    let stageTimer = null;

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

    const getExtension = (filename) => {
        const parts = String(filename).toLowerCase().split('.');
        return parts.length > 1 ? parts.pop() : '';
    };

    const showValidation = (message) => {
        if (!uploadValidation) {
            window.alert(message);
            return;
        }
        uploadValidation.textContent = message;
        uploadValidation.hidden = false;
        uploadValidation.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };

    const clearValidation = () => {
        if (!uploadValidation) return;
        uploadValidation.textContent = '';
        uploadValidation.hidden = true;
    };

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
        selectedFileType.textContent = '-';
        clearValidation();
    };

    const validateFile = (file) => {
        if (!file) return 'Silakan pilih video terlebih dahulu.';
        if (!allowedExt.includes(getExtension(file.name))) {
            return `Format file tidak didukung. Gunakan video dengan format ${allowedExt.map((item) => item.toUpperCase()).join(', ')}.`;
        }
        if (file.size <= 0) return 'File video kosong.';
        if (file.size > maxSizeBytes) return `Ukuran video melebihi batas maksimal ${maxSizeMb} MB.`;
        return '';
    };

    const showPreview = (file) => {
        const error = validateFile(file);
        if (error) {
            clearVideo();
            showValidation(error);
            return;
        }
        clearValidation();
        selectedFile = file;
        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = formatFileSize(file.size);
        selectedFileType.textContent = file.type || getExtension(file.name).toUpperCase();
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
        try {
            const transfer = new DataTransfer();
            transfer.items.add(file);
            videoInput.files = transfer.files;
            showPreview(file);
        } catch (_) {
            showValidation('Browser tidak dapat menerima file yang ditarik. Silakan klik Pilih Video.');
        }
    });

    uploadForm.addEventListener('reset', () => window.setTimeout(clearVideo, 0));

    previewPlayer.addEventListener('loadedmetadata', () => {
        if (maxDuration > 0 && Number.isFinite(previewPlayer.duration) && previewPlayer.duration > maxDuration) {
            clearVideo();
            showValidation(`Durasi video melebihi batas maksimal ${maxDuration} detik.`);
        }
    });

    const startStagePresentation = () => {
        if (!analysisSteps.length) return;
        let activeIndex = 0;
        const setActiveStep = () => {
            analysisSteps.forEach((step, index) => step.classList.toggle('active', index === activeIndex));
            activeIndex = (activeIndex + 1) % analysisSteps.length;
        };
        setActiveStep();
        stageTimer = window.setInterval(setActiveStep, 1800);
    };

    const resetSubmittingState = () => {
        isSubmitting = false;
        uploadCard.classList.remove('loading');
        if (stageTimer) {
            window.clearInterval(stageTimer);
            stageTimer = null;
        }
        analysisSteps.forEach((step, index) => step.classList.toggle('active', index === 0));
        if (!submitBtn.hasAttribute('data-model-unavailable')) {
            submitBtn.disabled = false;
        }
        submitBtn.innerHTML = '<i class="bi bi-shield-check me-1"></i> Mulai Deteksi';
    };

    uploadForm.addEventListener('submit', (event) => {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }
        const error = validateFile(selectedFile || videoInput.files[0]);
        if (error) {
            event.preventDefault();
            showValidation(error);
            return;
        }
        clearValidation();
        isSubmitting = true;
        uploadCard.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Sedang Mengunggah...';
        startStagePresentation();
        requestAnimationFrame(() => {
            previewPlayer.pause();
            previewPlayer.removeAttribute('src');
            previewPlayer.load();
            revokePreviewUrl();
        });
    });

    if (submitBtn.disabled) submitBtn.setAttribute('data-model-unavailable', 'true');
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) resetSubmittingState();
    });
    window.addEventListener('beforeunload', revokePreviewUrl);
});
