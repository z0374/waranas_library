<?php

function addSvg($input, $idSymbol) {
    global $SVG;
    libxml_use_internal_errors(true); // Suprime erros de validação HTML/XML

    // 1. Lógica de Leitura (Arquivo ou String)
    $svgContent = '';
    
    // Verifica se é arquivo e existe
    if (file_exists($input) && is_file($input)) {
        // file_get_contents lê o arquivo
        // trim() remove espaços/quebras de linha inúteis no início/fim que causam erros
        $svgContent = trim(file_get_contents($input));
    } else {
        $svgContent = trim($input);
    }

    if (empty($svgContent)) return;

    // 2. Prepara o Sprite (Destino)
    $domSprite = new DOMDocument();
    $domSprite->preserveWhiteSpace = false;
    $domSprite->formatOutput = true; 

    // Inicializa ou carrega o sprite existente
    if (empty($SVG)) {
        $rootSprite = $domSprite->createElement('svg');
        $rootSprite->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        $rootSprite->setAttribute('style', 'display: none;');
        $domSprite->appendChild($rootSprite);
    } else {
        // Se falhar ao carregar o existente, cria novo
        if (!$domSprite->loadXML($SVG)) {
            $rootSprite = $domSprite->createElement('svg');
            $rootSprite->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            $rootSprite->setAttribute('style', 'display: none;');
            $domSprite->appendChild($rootSprite);
        }
    }

    // 3. Processa o SVG de entrada
    $domIcone = new DOMDocument();
    $domIcone->preserveWhiteSpace = false;
    
    // O loadXML é inteligente: ele lê cabeçalhos <?xml e DOCTYPE
    // mas monta a árvore DOM limpa para nós manipularmos.
    if (!$domIcone->loadXML($svgContent)) {
        libxml_clear_errors();
        return; // Arquivo inválido ou corrompido, ignora silenciosamente
    }

    $rootIcone = $domIcone->documentElement; // Pega a tag <svg>
    
    // Validação extra: garante que é um SVG
    if ($rootIcone->nodeName !== 'svg') return;

    $viewBox = $rootIcone->getAttribute('viewBox');

    // 4. Limpeza de Duplicatas (XPath)
    $xpath = new DOMXPath($domSprite);
    $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
    // Procura symbol com mesmo ID no namespace correto
    $nodesAntigos = $xpath->query("//svg:symbol[@id='$idSymbol']");
    foreach ($nodesAntigos as $node) {
        $node->parentNode->removeChild($node);
    }

    // 5. Criação do Símbolo
    $symbol = $domSprite->createElement('symbol');
    $symbol->setAttribute('id', $idSymbol);
    if (!empty($viewBox)) $symbol->setAttribute('viewBox', $viewBox);

    foreach ($rootIcone->childNodes as $child) {
        $nodeImportado = $domSprite->importNode($child, true);
        $symbol->appendChild($nodeImportado);
    }

    $domSprite->documentElement->appendChild($symbol);

    // Salva na global
    $SVG = $domSprite->saveXML($domSprite->documentElement);
    libxml_clear_errors();
}