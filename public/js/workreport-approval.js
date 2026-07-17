document.addEventListener('DOMContentLoaded', function () {

    const approve = document.getElementById('approveAction');
    const reject = document.getElementById('rejectAction');
    const reasonContainer = document.getElementById('reasonContainer');
    const reason = document.getElementById('rejectionReason');
    const form = document.getElementById('approvalForm');

    const reviewButton =
        document.getElementById('btnReviewReport');

    if (
        !approve ||
        !reject ||
        !reasonContainer ||
        !reason ||
        !form ||
        !reviewButton
    ) {
        return;
    }

    const approveRoute =
    reviewButton.dataset.approveRoute;

    const rejectRoute =
        reviewButton.dataset.rejectRoute;

    // Jika halaman bukan show.blade atau modal belum ada
    if (!approve || !reject || !reasonContainer || !reason) {
        return;
    }

    function toggleReason() {

    if (reject.checked) {

        reasonContainer.style.display = 'block';

        reason.required = true;

        form.action = rejectRoute;

    } else {

        reasonContainer.style.display = 'none';

        reason.required = false;

        reason.value = '';

        form.action = approveRoute;

    }

}

    approve.addEventListener('change', toggleReason);
    reject.addEventListener('change', toggleReason);

    form.addEventListener('submit', function () {
    console.log('Submit ke:', form.action);
    });

    // console.log(form.action);
    toggleReason();

});
