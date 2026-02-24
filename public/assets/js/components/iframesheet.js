/**
 * Waranas Library - Iframesheet Engine (Lazy Load Version)
 */

const iframesheet = {}; // Cache de URLs convertidas em Blobs

/**
 * Inicializa o observador para carregar o conteúdo apenas quando necessário.
 */
function initIframesheet(placeholderId, ref, url, type, customClass = '') {
    const container = document.getElementById(placeholderId);
    if (!container) return;

    // Configura o Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(async (entry) => {
            // Se o elemento estiver visível (ou quase visível)
            if (entry.isIntersecting) {
                observer.unobserve(container); // Para de observar após disparar
                await loadIframeContent(container, ref, url, type, customClass);
            }
        });
    }, {
        rootMargin: '200px', // Carrega 200px antes de entrar no viewport
        threshold: 0.01
    });

    observer.observe(container);
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