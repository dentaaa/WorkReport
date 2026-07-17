document.addEventListener('DOMContentLoaded', function () {

    const status = document.getElementById('status');
    const wrapper = document.getElementById('continue-wrapper');

    // kalau elemen tidak ada → stop (biar aman dipakai global)
    if (!status || !wrapper) return;

    // sync saat load (handle old() & edit)
    if (status.value === 'continue') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }

    // handle perubahan
    status.addEventListener('change', function () {
        wrapper.style.display = this.value === 'continue' ? 'block' : 'none';
    });

    // focus ke error field
    const errorField = document.querySelector('.is-invalid');
    if (errorField) {
        errorField.focus();
        errorField.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});
