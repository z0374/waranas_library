/**
 * Waranas Library - Iframesheet Engine
 * * Responsável por:
 * 1. Carregar sites em memória RAM (Blob) para evitar re-downloads.
 * 2. Corrigir caminhos relativos (CSS/JS/IMG) injetando a tag <base>.
 */

const iframesheet = {}; // Cache de URLs convertidas em Blobs

async function initIframesheet(placeholderId, ref, url, type) {
    const container = document.getElementById(placeholderId);
    if (!container) return; // Se o placeholder não existir, aborta

    // 1. Verifica se o conteúdo já está na memória RAM
    if (!iframesheet[ref]) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Erro ao carregar: ' + response.status);
            
            let html = await response.text();
            
            // 2. LÓGICA DE CORREÇÃO DE URL (Resolve o problema do memory-game)
            let baseUrl = url;
            
            // Se a URL aponta para um ficheiro (ex: index.php, style.css), removemos o nome do ficheiro para pegar a pasta
            // Regex verifica se termina com .extensão (2 a 4 letras/números)
            if (baseUrl.match(/\.[a-zA-Z0-9]{2,5}$/)) {
                baseUrl = baseUrl.substring(0, baseUrl.lastIndexOf('/') + 1);
            } 
            // Se a URL não termina em barra e não parece ser um ficheiro, adicionamos a barra final
            // Ex: .../portifolio/memory-game vira .../portifolio/memory-game/
            else if (!baseUrl.endsWith('/')) {
                baseUrl += '/';
            }

            const baseTag = `<base href="${baseUrl}">`;

            // 3. INJEÇÃO DA TAG <BASE>
            // Inserimos a tag o mais cedo possível para garantir que todos os links relativos funcionem
            if (html.includes('<head>')) {
                html = html.replace('<head>', `<head>\n    ${baseTag}`);
            } else if (html.includes('<html>')) {
                html = html.replace('<html>', `<html>\n<head>\n    ${baseTag}\n</head>`);
            } else {
                html = baseTag + '\n' + html;
            }

            // 4. CRIAÇÃO DO BLOB
            const blob = new Blob([html], { type: 'text/html' });
            iframesheet[ref] = URL.createObjectURL(blob);
            
        } catch (e) {
            console.warn(`Iframesheet: Falha no Blob para ${url}. A usar URL direta.`, e);
            iframesheet[ref] = url; // Fallback de segurança: carrega a URL normal se der erro (ex: CORS)
        }
    }

    // 5. RENDERIZAÇÃO DO ELEMENTO
    const el = document.createElement(type); // cria 'iframe' ou 'embed'
    el.src = iframesheet[ref];
    el.style.width = "100%";
    el.style.height = "100%";
    el.style.border = "none";
    
    // Atributos específicos para iframes
    if (type === 'iframe') {
        el.setAttribute('allowfullscreen', '');
        el.setAttribute('loading', 'lazy');
    }
    
    // Limpa o placeholder e insere o novo elemento
    container.innerHTML = '';
    container.appendChild(el);
}