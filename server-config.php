<?php

use Websyspro\Connection\Enums\DriverType;

/*
 * define BASE_DIR
 * **/
defined( "BASE_DIR" ) || define(
  "BASE_DIR", realpath(
    dirname( __DIR__ ) 
  ) . DIRECTORY_SEPARATOR
);

/*
 * Autoload BASE_DIR
 * **/
if( file_exists(  BASE_DIR . "vendor/autoload.php" )){
  require_once BASE_DIR . "vendor/autoload.php";
}

/*
 * define CONNECT_DETAILS
 * **/
if( defined( "CONNECT_DETAILS" ) === false ){
  define( "CONNECT_DETAILS", (object)[
    "driver" => DriverType::SqlServer,
    "host" => "",
    "port" => "",
    "name" => "",
    "user" => "",
    "pass" => ""
  ]);
}

/*
 * define SERVER_TOOLS
 * **/
if( defined( "SERVER_TOOLS" ) === false ){
  define( "SERVER_TOOLS", [
    "port" => 3000,
    "apiVersion" => 1,
    "maxRequests" => 1500,
    "keepAliveTimeout" => 30,
    "isWorker" => true
  ]);
}