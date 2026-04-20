document.addEventListener("DOMContentLoaded", function() {
    if (!window.WaranasAssets) return;

    window.addEventListener('load', function() {
        window.WaranasAssets.forEach(asset => {
            const img = new Image();
            img.src = asset.url;

            img.onload = function() {
                asset.targets.forEach(selector => {
                    document.querySelectorAll(selector).forEach(el => {
                        if (el.tagName === 'IMG') {
                            el.src = asset.url;
                        } else {
                            el.style.backgroundImage = `url('${asset.url}')`;
                        }
                        el.classList.add('loaded');
                    });
                });
            };
        });
    });
});