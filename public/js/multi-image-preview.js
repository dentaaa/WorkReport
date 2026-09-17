
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photos');
    const previewContainer = document.getElementById('preview-container');
    const errorMessage = document.getElementById('error-message');

    if (!input || !previewContainer) return;

    let selectedFiles = [];
    let isProcessing = false;

    const MAX_DIMENSION = 1600;
    const JPEG_QUALITY = 0.85;

    input.addEventListener('change', async function (e) {
        const files = Array.from(e.target.files);

        // Kosongkan input agar file yang sama bisa dipilih kembali.
        input.value = '';

        if (errorMessage) errorMessage.innerText = '';

        if (isProcessing) return;

        isProcessing = true;
        input.disabled = true;

        try {
            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;

                try {
                    const compressedFile = await compressImage(file);

                    selectedFiles.push(compressedFile);
                    addPreview(compressedFile);
                } catch (error) {
                    console.error('Gagal memproses gambar:', file.name, error);

                    // Jika kompresi gagal, tetap gunakan file asli.
                    selectedFiles.push(file);
                    addPreview(file);

                    if (errorMessage) {
                        errorMessage.innerText =
                            'Sebagian foto gagal dikompres dan akan diunggah dengan ukuran asli.';
                    }
                }
            }

            updateInputFiles();
        } finally {
            isProcessing = false;
            input.disabled = false;
        }
    });

    async function compressImage(file) {
        // GIF dapat berupa animasi, jadi jangan dikonversi ke JPEG.
        if (file.type === 'image/gif') {
            return file;
        }

        const image = await loadImage(file);

        const originalWidth = image.naturalWidth;
        const originalHeight = image.naturalHeight;

        if (!originalWidth || !originalHeight) {
            throw new Error('Dimensi gambar tidak valid.');
        }

        const scale = Math.min(
            1,
            MAX_DIMENSION / Math.max(originalWidth, originalHeight)
        );

        const width = Math.round(originalWidth * scale);
        const height = Math.round(originalHeight * scale);

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;

        const context = canvas.getContext('2d');

        if (!context) {
            throw new Error('Canvas tidak tersedia.');
        }

        // Latar putih untuk mencegah area transparan menjadi hitam.
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, width, height);

        // Gambar utuh dengan rasio aspek asli, tanpa crop.
        context.drawImage(image, 0, 0, width, height);

        const blob = await new Promise((resolve, reject) => {
            canvas.toBlob(
                result => {
                    if (result) {
                        resolve(result);
                    } else {
                        reject(new Error('Gagal membuat hasil kompresi.'));
                    }
                },
                'image/jpeg',
                JPEG_QUALITY
            );
        });

        const originalName = file.name.replace(/\.[^/.]+$/, '');
        const newName = `${originalName}.jpg`;

        // Jika hasil kompresi tidak lebih kecil, pakai file asli.
        if (blob.size >= file.size) {
            return file;
        }

        return new File([blob], newName, {
            type: 'image/jpeg',
            lastModified: Date.now()
        });
    }

    function loadImage(file) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            const objectUrl = URL.createObjectURL(file);

            image.onload = function () {
                URL.revokeObjectURL(objectUrl);
                resolve(image);
            };

            image.onerror = function () {
                URL.revokeObjectURL(objectUrl);
                reject(new Error('File gambar tidak dapat dibaca.'));
            };

            image.src = objectUrl;
        });
    }

    function addPreview(file) {
        const col = document.createElement('div');
        col.classList.add('col-4', 'mb-3', 'text-center');

        const image = document.createElement('img');
        image.classList.add('img-fluid', 'rounded', 'mb-2', 'preview-img');
        image.alt = file.name;

        const button = document.createElement('button');
        button.type = 'button';
        button.classList.add('btn', 'btn-warning', 'btn-sm', 'remove-new');
        button.textContent = 'Batal';

        const objectUrl = URL.createObjectURL(file);
        image.src = objectUrl;

        image.onload = function () {
            URL.revokeObjectURL(objectUrl);
        };

        button.addEventListener('click', function () {
            previewContainer.removeChild(col);
            selectedFiles = selectedFiles.filter(f => f !== file);
            updateInputFiles();
        });

        col.appendChild(image);
        col.appendChild(button);
        previewContainer.appendChild(col);
    }

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }
});
