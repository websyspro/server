<?php

namespace Websyspro\Server\Commons;

use ReflectionFunction;

class Util
{
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

  public static function ValueOfArray(
    array $arrList = []
  ): mixed {
    return end(
      $arrList
    );
  }
}