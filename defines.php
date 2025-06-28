<?php

if(defined( "rootdir" ) === false){
  define("rootdir", dirname(__FILE__));
}

if(defined( "privatekey" ) === false){
  define("privatekey", file_get_contents( rootdir . "/certs/private.pem" ));
}

if(defined( "publickey" ) === false){
  define("publickey", file_get_contents( rootdir . "/certs/public.pem" ));
}

date_default_timezone_set("America/Sao_Paulo");