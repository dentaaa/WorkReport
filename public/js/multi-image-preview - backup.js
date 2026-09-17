document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('photos');
    const previewContainer = document.getElementById('preview-container');
    const errorMessage = document.getElementById('error-message');

    if (!input || !previewContainer) return;

    let selectedFiles = [];

    input.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);

        if (errorMessage) errorMessage.innerText = '';

        files.forEach(file => {

            if (!file.type.startsWith('image/')) return;
            // if (file.size > 2 * 1024 * 1024) return;

            selectedFiles.push(file);

            const reader = new FileReader();

            reader.onload = function (e) {
                const col = document.createElement('div');
                col.classList.add('col-4', 'mb-3', 'text-center');

                col.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded mb-2 preview-img">
                    <button type="button" class="btn btn-warning btn-sm remove-new">
                        Batal
                    </button>
                `;

                col.querySelector('.remove-new').addEventListener('click', () => {
                    previewContainer.removeChild(col);
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    updateInputFiles();
                });

                previewContainer.appendChild(col);
            };

            reader.readAsDataURL(file);
        });

        updateInputFiles();
    });

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }
});
