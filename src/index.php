<?php

/**
 *  Define DevTools_Base_Dir
 */
defined( "DevTools_Base_Dir" ) || define(
  "DevTools_Base_Dir", realpath(
    dirname( __DIR__ ) 
  ) . DIRECTORY_SEPARATOR
);

/**
 * Define Main 
 */
if( file_exists( DevTools_Base_Dir . "main.php" )){
  require_once DevTools_Base_Dir . "main.php";
}