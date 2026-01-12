<?php

/**
 * Injeta conteúdo HTML/XML dentro de um elemento específico pelo ID.
 * * @param string $htmlTemplate O conteúdo HTML original (string ou lido de arquivo).
 * @param string $targetId O ID do elemento onde o conteúdo será inserido.
 * @param string $contentToInject O HTML ou SVG que você quer colocar lá dentro.
 * @return string O HTML final modificado.
 */
function appendHTML($htmlTemplate, $targetId, $contentToInject) {
    // 1. Configuração Inicial
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Suprime erros de parsing do HTML5

    // 2. Carregamento com Hack de UTF-8
    // O DOMDocument é ruim com UTF-8 por padrão. Adicionamos esse cabeçalho XML 
    // para forçar ele a entender acentos corretos, depois removemos na saída.
    // As flags NOIMPLIED e NODEFDTD evitam que ele adicione <html> e <body> automaticamente.
    $dom->loadHTML(
        '<?xml encoding="utf-8" ?>' . $htmlTemplate, 
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );

    // 3. Busca o Elemento Alvo
    $targetNode = $dom->getElementById($targetId);

    if ($targetNode) {
        // 4. Cria o Fragmento (A "Prancheta")
        $fragment = $dom->createDocumentFragment();
        
        // Tenta adicionar o conteúdo como XML/HTML
        // O appendXML é ideal para SVGs ou HTML bem formatado.
        // Se o conteúdo for texto puro, use $fragment->appendChild($dom->createTextNode($contentToInject));
        $success = @$fragment->appendXML($contentToInject);

        if ($success) {
            $targetNode->appendChild($fragment);
        } else {
            // Fallback: Se o XML falhar (HTML muito sujo), tenta tratar como texto ou logar erro
            // Aqui optamos por não quebrar o site
            error_log("Erro ao injetar conteúdo no ID: $targetId");
        }
    }

    // 5. Salva e Limpa
    $output = $dom->saveHTML();
    libxml_clear_errors();

    // Remove o cabeçalho XML fake que colocamos no início
    return str_replace('<?xml encoding="utf-8" ?>', '', $output);
}