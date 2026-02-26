<?php

// Define o caminho raiz do projeto para facilitar a inclusão de arquivos.
define('ROOT_PATH_WARANAS_LIB', __DIR__);

require_once ROOT_PATH_WARANAS_LIB . '/src/core/bootstrap.php';

if (isset($GLOBALS['WARANAS_INTERNAL_REQ'])) {
    return; 
}