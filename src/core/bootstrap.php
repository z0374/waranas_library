<?php

// 1. Carrega as variáveis globais
require_once ROOT_PATH . '/src/globals.php';

// 2. Carrega os SVGs
require_once ROOT_PATH . '/src/svg.php';

// 3. Carrega todas as funções do núcleo (core)
require_once ROOT_PATH . '/src//core/renderer.php';
require_once ROOT_PATH . '/src/core/cache.php';
require_once ROOT_PATH . '/src/core/database.php';
require_once ROOT_PATH . '/src/core/request.php';
require_once ROOT_PATH . '/src/core/security.php';

// 4. Carrega todas as funções de ajuda (helpers)
foreach (glob(ROOT_PATH . '/src/helpers/*.php') as $filename) {
    require_once $filename;
}

// 5. Carrega todas as funções de componentes
foreach (glob(ROOT_PATH . '/src/components/*.php') as $filename) {
    require_once $filename;
}

