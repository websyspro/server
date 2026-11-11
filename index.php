<?php

// define BASE_DIR
defined( "BASE_DIR" ) || define(
  "BASE_DIR", str_replace( 
    "\\", "/", __DIR__ . DIRECTORY_SEPARATOR
  )
);

// Require Autoload/App
require_once BASE_DIR . "vendor/autoload.php";
require_once BASE_DIR . "src/app.php";