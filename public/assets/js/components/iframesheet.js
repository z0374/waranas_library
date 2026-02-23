/**
 * Waranas Library - Iframesheet Engine
 * * Responsável por:
 * 1. Carregar sites em memória RAM (Blob) para evitar re-downloads.
 * 2. Corrigir caminhos relativos (CSS/JS/IMG) injetando a tag <base>.
 */

const iframesheet = {}; // Cache de URLs convertidas em Blobs

async function initIframesheet(placeholderId, ref, url, type, customClass = '') {
    const container = document.getElementById(placeholderId);
    if (!container) return;

    if (!iframesheet[ref]) {
        try {
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
            iframesheet[ref] = url; 
        }
    }

    const el = document.createElement(type);
    el.src = iframesheet[ref];
    el.style.width = "100%";
    el.style.height = "100%";
    el.style.border = "none";
    
    // --- APLICAÇÃO DA CLASSE OPCIONAL ---
    if (customClass !== '') {
        el.className = customClass;
    }
    
    if (type === 'iframe') {
        el.setAttribute('allowfullscreen', '');
    }
    
    container.innerHTML = '';
    container.appendChild(el);
}