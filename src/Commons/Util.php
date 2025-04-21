<?php

namespace Websyspro\Server\Commons;

use DateTime;
use ReflectionFunction;
use Websyspro\Server\Enums\Entitys\ColumnParse;
use Websyspro\Server\Server\Application;

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

  public static function isAssociative(
    array $arr
  ): bool {
    return array_keys($arr) !== (
      range( 0, count($arr) - 1 )
    );
  }

  public static function MapperByKey(
    array | object $MapperArr,
    callable $MapperEvt,
    array $MapperArrResult = []      
  ): array {
    foreach( $MapperArr as $key => $val ){
      $MapperArrResult[ $key ] = $MapperEvt( $key );
    }

    return $MapperArrResult;     
  }
  
  public static function Filter(
    array | object $MapperArr,
    callable $MapperEvt,
    array $MapperArrResult = []    
  ): array {
    $keyOrder = 0;
    $isAssociative = (
      Util::isAssociative(
        $MapperArr
      ) === false
    );

    foreach( $MapperArr as $key => $val ){
      if ((
        new ReflectionFunction($MapperEvt)
      )->getNumberOfParameters() === 2) {
        if ( $MapperEvt( $val, $isAssociative ? $keyOrder++ : $key ) === true ) {
          $MapperArrResult[ $isAssociative ? $keyOrder : $key ] = $val;
        }
      } else {
        if( $MapperEvt( $val ) === true) {
          $MapperArrResult[ $isAssociative ? $keyOrder++ : $key ] = $val;
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

  public static function CreateArray(
    array $arr,
    string $key,
    mixed $val
  ): array {
    $arr[ $key ] = $val;
    return $arr;
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

  public static function JoinScripts(
    array $joinArr
  ): string {
    return implode(
      "; ", $joinArr
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

  public static function isNotClass(
    mixed $mixed
  ): bool {
    return is_numeric( $mixed )
        || is_string( $mixed );
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
    string $entity
  ): string {
    return (
      Util::ValueOfArray(
        Util::FilterByKey(
          Application::$entitys, (
            fn( string $entityKey ) => (
              $entity === $entityKey
            )
          )
        )
      )
    );
  }
  
  public static function parseDatabase(
    string $database
  ): string {
    return preg_replace( "/Database$/", "", preg_replace(
      "/^.*\\\\/", "", $database
    ));
  }

  public static function parseEntity(
    string $controller
  ): string {
    return preg_replace(
      "/Entity.*$/", "",preg_replace(
        "/^.*\\\\/", "", $controller
      )
    );
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
        "/Controller.*$/", "", preg_replace(
          "/^.*\\\\/", "", $controller
        )
      )
    );
  }

  public static function quote(
    string $value
  ): string {
    return "'{$value}'";
  }

  public static function decimalParse(
    string $parseDecimal
  ): string {
    return preg_replace(
      [ "/\./", "/,/" ], [ "", "." ], $parseDecimal
    );
  }

  public static function datetimeParse(
    string $parseDate
  ): string {
    if( preg_match( ColumnParse::DatetimeBr->value, $parseDate )){
      return preg_replace( ColumnParse::DatetimeBr->value,
        "$3-$2-$1 $4", $parseDate
      );
    }

    return $parseDate;
  }

  public static function dateParse(
    string $parseDate
  ): string {
    if( preg_match( ColumnParse::DateBr->value, $parseDate )){
      return preg_replace( ColumnParse::DateBr->value,
        "$3-$2-$1", $parseDate
      );
    }

    return $parseDate;
  } 
  
  public static function flagParse(
    bool | int | string $parseFlag
  ): int {
    if( is_bool( $parseFlag )){
      return $parseFlag ? 1 : 0;
    } else
    if( is_string( $parseFlag )){
      return filter_var( $parseFlag, FILTER_VALIDATE_BOOLEAN );
    }

    return $parseFlag;
  }
}