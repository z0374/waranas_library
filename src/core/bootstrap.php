<?php

// 1. Carrega as variáveis globais
require_once ROOT_PATH_WARANAS_LIB . '/src/globals.php';

// 2. Carrega os SVGs
require_once ROOT_PATH_WARANAS_LIB . '/src/utils/addedSvg.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/components/svg.php';

// 3. Carrega todas as funções do núcleo (core)
require_once ROOT_PATH_WARANAS_LIB . '/src/core/renderer.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/cache.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/cacheVerify.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/security.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/database.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/env.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/request.php';
require_once ROOT_PATH_WARANAS_LIB . '/src/core/security.php';

// 4. Carrega todas as funções de ajuda (helpers)
foreach (glob(ROOT_PATH_WARANAS_LIB . '/src/helpers/*.php') as $filename) {
    require_once $filename;
}

// 5. Carrega todas as funções de utilidades (utils)
foreach (glob(ROOT_PATH_WARANAS_LIB . '/src/utils/*.php') as $filename) {
    require_once $filename;
}

// 6. Carrega todas as funções de componentes
foreach (glob(ROOT_PATH_WARANAS_LIB . '/src/components/*.php') as $filename) {
    require_once $filename;
}

