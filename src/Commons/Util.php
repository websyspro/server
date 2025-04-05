<?php

namespace Websyspro\Server\Commons;

use ReflectionFunction;

class Util
{
  public static function ParseRequestUri(
    string $requestUri
  ): string {
    return preg_replace(
      [ "/^\/*/", "/\/\?/", "/\/*$/" ], "", $requestUri
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
    array $MapperArr,
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

    return array_values($MapperArrResult);
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

  public static function isValidArray(
    array $isValidArr = []
  ): bool {
    return is_array( $isValidArr ) && empty( $isValidArr );
  }

  public static function isEmptyArray(
    array $value = []
  ): bool {
    return is_array( $value ) && empty( $value ) === true;
  }

  public static function join(
    string $joinStr,
    array $joinList
  ): string {
    return implode(
      $joinStr,
      $joinList
    );
  } 

  public static function Log(
    string $logText
  ): void {
    $currentDate = date( "d/m/Y, H:i:s" );

    $stdoutStream = fopen('php://stdout', 'w'); 
    fwrite( $stdoutStream, "\x1b[32mWebSysPro - \x1b[37m{$currentDate}\x1b[32m LOG \x1b[33m[Server]\x1b[37m \x1b[32m{$logText}\x1b[37m \x1b[37m - ms\n" );
    fclose( $stdoutStream );
  }
}