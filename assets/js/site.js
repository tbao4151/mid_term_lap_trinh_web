'use strict';
// Use original CÁ'S HOA product photos. Local files are preferred, GitHub raw is fallback.
document.querySelectorAll('img[data-photo]').forEach(function (img) {
    img.addEventListener('error', function () {
        if (img.dataset.fallbackUsed) return;
        img.dataset.fallbackUsed='1';
        const file=img.dataset.photo;
        if (!/^[a-z0-9-]+\.png$/.test(file)) return;
        img.src='https://raw.githubusercontent.com/tbao4151/Lap_trinh_web/main/assets/images/'+file;
    });
});
// Filling the local input directly is clearer than relying on clipboard permission.
document.querySelectorAll('.copy-payload').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const value = btn.dataset.payload || '';
        const field = document.querySelector('input[name="term"], input[name="q"]');
        if (!field) return;
        field.value = value;
        field.focus();
        field.setSelectionRange(field.value.length, field.value.length);
        btn.textContent = 'Đã điền vào ô nhập';
        // The input stays editable; sending it remains an explicit form action.
    });
});
