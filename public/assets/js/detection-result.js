'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const configElement = document.getElementById('resultAlertConfig');
    if (!configElement) return;

    let config;
    try {
        config = JSON.parse(configElement.dataset.config || '{}');
    } catch (_) {
        return;
    }

    if (!config.show) return;

    const messages = {
        real: {
            icon: 'success',
            title: 'Video Cenderung Asli',
            text: 'Berdasarkan hasil analisis sistem, video ini tidak menunjukkan indikasi kuat manipulasi deepfake.',
            note: 'Hasil ini merupakan prediksi sistem dan bukan jaminan mutlak keaslian video.',
            confirm: 'Lihat Hasil Lengkap',
            cancel: 'Deteksi Video Lain',
            color: '#07845c'
        },
        deepfake: {
            icon: 'warning',
            title: 'Video Terdeteksi Deepfake',
            text: 'Sistem menemukan indikasi manipulasi deepfake pada video yang Anda unggah.',
            note: 'Periksa kembali sumber video dan lakukan verifikasi tambahan sebelum mempercayai atau menyebarkannya.',
            confirm: 'Lihat Hasil Lengkap',
            cancel: 'Periksa Video Lain',
            color: '#b31312'
        },
        uncertain: {
            icon: 'question',
            title: 'Hasil Belum Meyakinkan',
            text: 'Skor video asli dan deepfake cukup berdekatan. Sistem belum dapat memberikan kesimpulan yang kuat.',
            note: 'Video sebaiknya diperiksa lebih lanjut secara manual.',
            confirm: 'Lihat Detail Analisis',
            cancel: 'Periksa Video Lain',
            color: '#b86400'
        },
        failed: {
            icon: 'error',
            title: 'Video Gagal Dianalisis',
            text: 'Terjadi kendala saat menganalisis video. Silakan coba kembali menggunakan video lain.',
            note: '',
            confirm: 'Coba Lagi',
            cancel: '',
            color: '#b31312'
        }
    };

    const message = messages[config.type];
    if (!message) return;

    if (typeof window.Swal === 'undefined') {
        window.alert(`${message.title}\n\n${message.text}${message.note ? `\n\n${message.note}` : ''}`);
        if (config.type === 'failed' && config.otherUrl) window.location.assign(config.otherUrl);
        return;
    }

    const options = {
        icon: message.icon,
        title: message.title,
        html: `<p>${message.text}</p>${message.note ? `<small class="result-alert-note">${message.note}</small>` : ''}`,
        confirmButtonText: message.confirm,
        confirmButtonColor: message.color,
        customClass: { popup: 'result-alert-popup' },
        allowOutsideClick: false,
        reverseButtons: true
    };

    if (message.cancel) {
        options.showCancelButton = true;
        options.cancelButtonText = message.cancel;
        options.cancelButtonColor = '#64748b';
    }

    window.Swal.fire(options).then((result) => {
        const goToAnotherVideo = result.dismiss === window.Swal.DismissReason.cancel || config.type === 'failed';
        if (goToAnotherVideo && config.otherUrl) window.location.assign(config.otherUrl);
    });
});
