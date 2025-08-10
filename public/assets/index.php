<?php

// Define um caminho absoluto para o diretório 'src' para evitar problemas com includes.
define('SRC_PATH', __DIR__ . '/../src');

// 1. Carrega o bootstrap, que inicializa tudo.
require_once SRC_PATH . '/core/bootstrap.php';

// --- Exemplo de Construção de uma Página ---

// 2. Define as configurações da página.
$title[] = 'Biblioteca Waranãs';
$lang = 'pt-br';
$bgbody[] = '#f0f2f5'; // Um fundo cinza claro e suave

// 3. Constrói o Cabeçalho (<header>)
$menuItens = [
    ['url' => '/', 'content' => 'Home'],
    ['url' => '/componentes', 'content' => 'Componentes'],
    ['url' => '/contato', 'content' => 'Contato'],
];
$header[] = menu($menuItens, '#2c3e50'); // Cria um menu com um fundo azul escuro

// 4. Constrói o Conteúdo Principal (<main>)
$main[] = doIt('A', 'h1', 'style="color: #333;"', 'Componentes Disponíveis');
$main[] = textblock(
    'Esta página demonstra como os componentes da biblioteca são carregados e renderizados. Cada componente carrega seu próprio CSS e JavaScript sob demanda, garantindo a máxima performance.',
    '#ffffff', // Fundo branco para o bloco de texto
    '1.1rem'   // Tamanho da fonte
);

// Adicionando um componente de Slideshow
$slides = [
    ['url' => '#', 'image' => 'https://via.placeholder.com/800x300/3498db/ffffff?text=Slide+1'],
    ['url' => '#', 'image' => 'https://via.placeholder.com/800x300/e74c3c/ffffff?text=Slide+2'],
    ['url' => '#', 'image' => 'https://via.placeholder.com/800x300/2ecc71/ffffff?text=Slide+3'],
];
$main[] = slideshow('demoSlider', $slides, 30, 5000); // id, array, tamanho, autoplay (5s)

// Adicionando um menu Acordeão (Hamburguer)
$hamburguerItens = [
    ['title' => 'O que é a Biblioteca?', 'content' => 'É uma coleção de componentes PHP para acelerar a criação de interfaces web.'],
    ['title' => 'Como funciona?', 'content' => 'As funções PHP geram HTML e registram seus próprios assets (CSS/JS), que são carregados sob demanda.'],
    ['title' => 'É segura?', 'content' => 'Foram implementadas práticas de segurança como escapar saídas (prevenção de XSS) e o uso de prepared statements (prevenção de SQL Injection).'],
];
$main[] = hamburguer('accordeon', $hamburguerItens, 5);

// 5. Constrói o Rodapé (<footer>)
$footerContent[] = doIt('A', 'p', '', '&copy; ' . date('Y') . ' Biblioteca Waranãs. Todos os direitos reservados.');
$footer[] = implode('', $footerContent);

// 6. Renderiza a página completa.
// 'real-time' exibe o HTML na hora.
// 'cache' salvaria o HTML em um arquivo para ser servido mais rapidamente.
html('real-time');