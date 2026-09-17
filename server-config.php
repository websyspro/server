<?php

use Websyspro\Server\Interfaces\ServerTools;

/**
 * Server Server Runtime
 */
defined( "DevTools_Base_Dir" ) || define(
  "DevTools_Base_Dir", realpath(
    dirname( __DIR__ ) 
  ) . DIRECTORY_SEPARATOR
);

/**
 * AutoLoad
 */
if( file_exists(  DevTools_Base_Dir . "vendor/autoload.php" )){
  require_once DevTools_Base_Dir . "vendor/autoload.php";
}

/**
 * Config
 */
if( file_exists(  DevTools_Base_Dir . "server-config.php" )){
  return new ServerTools(
    port: 8080, 
    apiVersion: 1, 
    keepAliveTimeout: 30, 
    maxRequests: 1000
  );
}

/**
 * Fallback
 */
return null;