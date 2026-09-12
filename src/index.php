<?php

/**
 *  Define DevTools_Base_Dir
 */
defined( "DevTools_Base_Dir" ) || define(
  "DevTools_Base_Dir", realpath(
    dirname( __DIR__ ) 
  ) . DIRECTORY_SEPARATOR
);

echo DevTools_Base_Dir . PHP_EOL;
echo "My Apps";