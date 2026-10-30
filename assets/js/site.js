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
document.querySelectorAll('.copy-payload').forEach(function(btn){
    btn.addEventListener('click', async function(){
        const value=btn.dataset.payload||'';
        try {
            await navigator.clipboard.writeText(value);
            btn.textContent='Đã sao chép';
        } catch(e) {
            const field=document.querySelector('input[name="term"],input[name="q"]');
            if(field){field.value=value;field.focus();field.select();btn.textContent='Đã điền vào ô';}
        }
    });
});
