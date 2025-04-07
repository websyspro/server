<?php

namespace Websyspro\Server\Commons;

use ReflectionClass;
use ReflectionParameter;

class ReflectDependences
{
  public static function getDependences(
    string $entityClass
  ): object {
    $reflectionClass = (
      new ReflectionClass(
        $entityClass
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
            $entityClass
          ), "newInstance"
        ], $getParametersList );
      }

      return new $entityClass();
    }

    return new $entityClass();
  }
}