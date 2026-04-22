/**
 * Waranas Preloader JS
 * Executa o download das imagens apenas após o evento 'load' da janela.
 */
(function() {
    window.addEventListener('load', function() {
        if (!window.WaranasAssets || !Array.isArray(window.WaranasAssets)) {
            return;
        }

        console.log("Waranas: Iniciando carregamento de " + window.WaranasAssets.length + " ativos.");

        window.WaranasAssets.forEach(function(asset) {
            var img = new Image();
            img.src = asset.url;

            img.onload = function() {
                asset.targets.forEach(function(selector) {
                    var elements = document.querySelectorAll(selector);
                    elements.forEach(function(el) {
                        if (el.tagName === 'IMG') {
                            el.src = asset.url;
                        } else {
                            el.style.backgroundImage = "url('" + asset.url + "')";
                        }
                        // Ativa transição suave se definido no CSS
                        el.style.opacity = "1";
                        el.classList.add('waranas-loaded');
                    });
                });
            };
            
            img.onerror = function() {
                console.error("Waranas: Falha ao carregar " + asset.url);
            };
        });
    });
})();