<?php

namespace Websyspro\Server\Commons;

use ReflectionClass;
use ReflectionParameter;

class ReflectDependences
{
  public static function getDependences(
    string $objectClass
  ): object {
    $reflectionClass = (
      new ReflectionClass(
        $objectClass
      )
    );

    if( $reflectionClass ){
      $getParameters = (
        $reflectionClass
          ->getConstructor()
          ->getParameters()
      );

      if( $getParameters ){
        $getParametersList = Util::Mapper(
          $getParameters, (
            fn( ReflectionParameter $reflectionParameter ) => (
              ReflectDependences::getDependences(
                $reflectionParameter->name
              )
            )
          )
        );

        return call_user_func_array([
          new ReflectionClass(
            $objectClass
          ), "newInstance"
        ], $getParametersList );
      }

      return new $objectClass();
    }

    return new $objectClass();
  }
}