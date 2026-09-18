<?php

/**
 * WebSocket Server Runtime
 * 
 * Runtime interno para iniciar o servidor WebSocket.
 * Não deve ser exportado como binário do Composer.
 * Usado pelo BrowserReloadHandler para rodar em processo separado.
 */
defined( "BASE_DIR" ) || define(
  "BASE_DIR", realpath(
    dirname( __DIR__, 1 ) 
  ) . DIRECTORY_SEPARATOR
);

/*
 * Autoload
 * Main
 * **/
require_once BASE_DIR . "vendor/autoload.php";
require_once BASE_DIR . "src/app.php";