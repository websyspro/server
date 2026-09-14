<?php

/**
 * WebSocket Server Runtime
 * 
 * Runtime interno para iniciar o servidor WebSocket.
 * Não deve ser exportado como binário do Composer.
 * Usado pelo BrowserReloadHandler para rodar em processo separado.
 */
defined( "DevTools_Base_Dir" ) || define(
  "DevTools_Base_Dir", realpath(
    dirname( __DIR__, 1 ) 
  ) . DIRECTORY_SEPARATOR
);

/*
 * Autoload
 * Main
 * **/
require_once DevTools_Base_Dir . "vendor/autoload.php";
require_once DevTools_Base_Dir . "src/app.php";