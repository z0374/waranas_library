const iframesheet = {
    loadQueue: 0,
    delayPerRequest: 800 // 800ms entre cada fetch para evitar sobrecarga (Staggered Load)
};

function initIframesheet(placeholderId, ref, url, type, customClass = '', internalData = null) {
    const container = document.getElementById(placeholderId);
    if (!container) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                observer.unobserve(container);
                
                if (internalData !== null && internalData !== "") {
                    // Carrega instantâneo se o PHP já trouxe o conteúdo
                    renderIframe(container, url, type, customClass, internalData);
                } else {
                    // Carregamento gradual (Lazy + Staggered)
                    const delay = iframesheet.loadQueue * iframesheet.delayPerRequest;
                    iframesheet.loadQueue++;

                    setTimeout(() => {
                        fetchAndRender(container, url, type, customClass);
                    }, delay);
                }
            }
        });
    }, { rootMargin: '100px' });

    observer.observe(container);
}

async function fetchAndRender(container, url, type, customClass) {
    try {
        const response = await fetch(url);
        const html = await response.text();
        renderIframe(container, url, type, customClass, html);
    } catch (e) {
        // Fallback para carregamento direto se o fetch falhar
        renderIframe(container, url, type, customClass, null, true);
    }
}

function renderIframe(container, url, type, customClass, html, isFallback = false) {
    const el = document.createElement(type);
    
    if (isFallback) {
        el.src = url;
    } else {
        const urlObj = new URL(url, window.location.origin);
        const baseUrl = urlObj.origin + urlObj.pathname;
        
        // Injeta a base para que CSS e imagens relativos funcionem dentro do Blob
        const baseTag = `<base href="${baseUrl}">`;
        const finalHtml = html.includes('<head>') 
            ? html.replace('<head>', `<head>${baseTag}`) 
            : baseTag + html;

        const blob = new Blob([finalHtml], { type: 'text/html' });
        el.src = URL.createObjectURL(blob);
    }

    el.className = customClass;
    el.style.cssText = "width:100%; height:100%; border:none;";
    
    container.innerHTML = '';
    container.appendChild(el);
}