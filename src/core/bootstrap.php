<?php
// Define o caminho raiz do projeto para facilitar a inclusão de arquivos.
if (!defined('SRC_PATH')) {
    define('SRC_PATH', __DIR__);
}

// 1. Carrega as variáveis globais que armazenarão o estado da página.
require_once SRC_PATH . '/globals.php';

// 2. Carrega as definições de SVGs.
require_once SRC_PATH . '/svg.php';

// 3. Carrega o núcleo da aplicação (funções essenciais).
require_once SRC_PATH . '/core/renderer.php';
require_once SRC_PATH . '/core/cache.php';
require_once SRC_PATH . '/core/database.php';
require_once SRC_PATH . '/core/request.php';
require_once SRC_PATH . '/core/security.php';

// 4. Carrega todas as funções utilitárias (helpers).
foreach (glob(SRC_PATH . '/helpers/*.php') as $filename) {
    require_once $filename;
}

// 5. Carrega todos os componentes disponíveis.
foreach (glob(SRC_PATH . '/components/*.php') as $filename) {

    require_once $filename;
}