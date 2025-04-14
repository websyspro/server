<?php

namespace Websyspro\Server\Commons;

use ReflectionFunction;

class Util
{
  public static function hasCli(
  ): bool {
    return (
      php_sapi_name() 
        === "cli"
    );
  }

  public static function Reduce(
    array $MapperArr,
    mixed $MapperCurr,     
    callable $MapperEvt,
  ): mixed {
    return array_reduce(
      $MapperArr, $MapperEvt, $MapperCurr
    );      
  }  

  public static function Mapper(
    array | object $MapperArr,
    callable $MapperEvt,
    array $MapperArrResult = []      
  ): array {
    foreach( $MapperArr as $key => $val ){
      $MapperArrResult[$key] = (
        new ReflectionFunction($MapperEvt)
      )->getNumberOfParameters() === 2 
        ? $MapperEvt( $val, $key ) 
        : $MapperEvt( $val );
    }

    return $MapperArrResult;      
  }
  
  public static function Filter(
    array $MapperArr,
    callable $MapperEvt,
    array $MapperArrResult = []    
  ): array {
    foreach( $MapperArr as $key => $val ){
      if ((
        new ReflectionFunction($MapperEvt)
      )->getNumberOfParameters() === 2) {
        if ( $MapperEvt( $val, $key ) === true ) {
          $MapperArrResult[ $key ] = $val;
        }
      } else {
        if( $MapperEvt( $val ) === true) {
          $MapperArrResult[ $key ] = $val;
        }
      }
    }

    return $MapperArrResult;
  }

  public static function FilterByKey(
    array $MapperArr,
    callable $MapperEvt,
    array $MapperArrResult = []    
  ): array {
    foreach( $MapperArr as $key => $val ){
      if( $MapperEvt( $key ) === true) {
        $MapperArrResult[ $key ] = $val;
      }
    }

    return $MapperArrResult;
  }

  public static function Join(
    string $joinStr,
    array $joinArr
  ): string {
    return implode(
      $joinStr,
      $joinArr
    );
  }

  public static function JoinColumns(
    array $joinArr
  ): string {
    return implode(
      ", ", $joinArr
    );
  }

  public static function arrayCountEquais(
    array $first,
    array $second
  ): bool {
    return sizeof($first) 
       === sizeof($second);
  }  

  public static function arrayEquais(
    array $first,
    array $second
  ): bool {
    if(sizeof($first) !== sizeof($second)){
      return false;
    }

    sort($first);
    sort($second);

    return array_values($first) 
       === array_values($second);
  }

  public static function ValueOfArray(
    array $arrList = []
  ): mixed {
    return end(
      $arrList
    );
  }

  public static function ParseRequestUri(
    string $requestUri
  ): string {
    return preg_replace(
      [ "/(?<=[^\/])\?/", "/^\/*/", "/\/*$/"  ], 
      [ "/?", "" ], $requestUri 
    );
  }

  public static function DropParamsFromUri(
    string $requestUri
  ): string {
    return preg_replace(
      "/\?.+/", "", $requestUri
    );
  }

  public static function camelToKebab(
    string $string
  ): string {
    return strtolower(
      preg_replace(
        "/([a-z])([A-Z])/", "$1-$2", $string
      )
    );
  }

  public static function getData(
    string $database
  ): string {
    return sprintf( "%s%s", connect->prefix, (
      preg_replace( "/Database$/", "", $database )
    ));
  }

  public static function getModuleFromController(
    string $controller
  ): string {
    return Util::getController(
      $controller
    );
  }

  public static function getController(
    string $controller
  ): string {
    return Util::camelToKebab(
      preg_replace(
        "/Controller.*$/", "",preg_replace(
          "/^.*\\\\/", "", $controller
        )
      )
    );
  }

  public static function getEntity(
    string $controller
  ): string {
    return preg_replace(
      "/Entity.*$/", "",preg_replace(
        "/^.*\\\\/", "", $controller
      )
    );
  }  
}