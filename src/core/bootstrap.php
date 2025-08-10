<?php

// Define o caminho raiz do projeto para facilitar a inclusão de arquivos.
define('ROOT_PATH', dirname(__DIR__));

// 1. Carrega as variáveis globais
require_once ROOT_PATH . '/globals.php';

// 2. Carrega os SVGs
require_once ROOT_PATH . '/svg.php';

// 3. Carrega todas as funções do núcleo (core)
require_once ROOT_PATH . '/core/renderer.php';
require_once ROOT_PATH . '/core/cache.php';
require_once ROOT_PATH . '/core/database.php';
require_once ROOT_PATH . '/core/request.php';
require_once ROOT_PATH . '/core/security.php';

// 4. Carrega todas as funções de ajuda (helpers)
foreach (glob(ROOT_PATH . '/helpers/*.php') as $filename) {
    require_once $filename;
}

// 5. Carrega todas as funções de componentes
foreach (glob(ROOT_PATH . '/components/*.php') as $filename) {
    require_once $filename;
}