

const iframesheet = {
    refs: {},
    loadQueue: 0,
    delayPerRequest: 800 // 800ms entre cada requisição para ser gradual
};

function initIframesheet(placeholderId, ref, url, type, customClass = '', internalData = null) {
    const container = document.getElementById(placeholderId);
    if (!container) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                observer.unobserve(container);
                
                // Se o dado veio do servidor, carrega instantâneo
                if (internalData !== null) {
                    renderIframe(container, ref, url, type, customClass, internalData);
                } else {
                    // Se falhou no servidor (timeout), entra na fila gradual
                    const currentDelay = iframesheet.loadQueue * iframesheet.delayPerRequest;
                    iframesheet.loadQueue++;

                    setTimeout(() => {
                        fetchAndRender(container, ref, url, type, customClass);
                    }, currentDelay);
                }
            }
        });
    }, { rootMargin: '100px' });

    observer.observe(container);
}

async function fetchAndRender(container, ref, url, type, customClass) {
    try {
        const response = await fetch(url);
        const html = await response.text();
        renderIframe(container, ref, url, type, customClass, html);
    } catch (e) {
        console.error("Erro no Lazy Load gradual:", e);
        // Fallback direto para a URL original
        renderIframe(container, ref, url, type, customClass, null, true);
    }
}

function renderIframe(container, ref, url, type, customClass, html, isFallback = false) {
    const el = document.createElement(type);
    
    if (isFallback) {
        el.src = url;
    } else {
        const baseUrl = url.startsWith('http') ? url : window.location.origin + url;
        const finalHtml = html.replace('<head>', `<head><base href="${baseUrl}">`);
        const blob = new Blob([finalHtml], { type: 'text/html' });
        el.src = URL.createObjectURL(blob);
    }

    el.className = customClass;
    el.style.width = "100%";
    el.style.height = "100%";
    el.style.border = "none";
    
    container.innerHTML = '';
    container.appendChild(el);
}

/**
 * Lógica interna de fetch e injeção do conteúdo (executada em Lazy Load)
 */
async function loadIframeContent(container, ref, url, type, customClass) {
    if (!iframesheet[ref]) {
        try {
            // O fetch acontece de forma assíncrona, sem travar o carregamento da página
            const response = await fetch(url);
            let html = await response.text();
            
            const baseUrl = url.endsWith('/') ? url : url.substring(0, url.lastIndexOf('/') + 1);
            const baseTag = `<base href="${baseUrl}">`;

            if (html.includes('<head>')) {
                html = html.replace('<head>', `<head>\n    ${baseTag}`);
            } else {
                html = baseTag + '\n' + html;
            }

            const blob = new Blob([html], { type: 'text/html' });
            iframesheet[ref] = URL.createObjectURL(blob);
        } catch (e) {
            console.error("Erro ao carregar iframesheet:", e);
            iframesheet[ref] = url; // Fallback para URL direta em caso de erro
        }
    }

    const el = document.createElement(type);
    el.src = iframesheet[ref];
    el.style.width = "100%";
    el.style.height = "100%";
    el.style.border = "none";
    
    // Atributo nativo de lazy loading para suporte extra do browser
    if (type === 'iframe') {
        el.setAttribute('loading', 'lazy'); 
        el.setAttribute('allowfullscreen', '');
    }
    
    if (customClass !== '') {
        el.className = customClass;
    }
    
    container.innerHTML = '';
    container.appendChild(el);
}