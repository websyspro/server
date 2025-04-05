<?php

if( defined( "connect" ) === false ){
  define( "connect", (object)[
    "prefix" => "BC9876_",
    "database" => "LittleShop",
    "type" => "mysql",
    "hostname" => "localhost",
    "username" => "root",
    "password" => "@Qazwsx190483",
    "port" => "3307"
  ]);

  // define( "connect", (object)[
  //   "type" => "sqlserver",
  //   "prefix" => "",
  //   "database" => "pnld_crm_api_production",
  //   "hostname" => "localhost",
  //   "username" => "sa",
  //   "password" => "@Qazwsx190483",
  //   "port" => "1433"
  // ]);
}