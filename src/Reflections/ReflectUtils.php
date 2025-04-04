<?php

namespace Websyspro\Server\Reflections;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Websyspro\Server\Commons\Util;

class ReflectUtils
{
  public static function getReflectClass(
    string | object $objectOrClass
  ): ReflectionClass {
    return new ReflectionClass($objectOrClass);
  }

  public static function getMethdos(
    string | object $objectOrClass
  ): array {
    return get_class_methods(
      $objectOrClass
    );
  }

  public static function getClassAttribte(
    ReflectionAttribute $reflectionAttribute
  ): ClassAttributes {
    return new ClassAttributes(
      $reflectionAttribute->getName(),
      $reflectionAttribute->getArguments()
    );
  }

  public static function getProperties(
    string | object $objectOrClass
  ): array {
    return (
      ReflectUtils::getReflectClass(
        $objectOrClass
      )->getProperties()
    );
  }

  public static function getClassInstance(
    string | object $objectOrClass
  ): ClassInstance {
    return new ClassInstance(
      $objectOrClass
    );
  }

  public static function getReflectMethod(
    string | object $objectOrClass,
    string $method
  ): ReflectionMethod {
    return new ReflectionMethod(
      $objectOrClass, $method
    );
  }

  public static function getParametersClass(
    string | object $objectOrClass
  ): array {
    $getflectClass = ReflectUtils::getReflectClass($objectOrClass);
    $getConstructor = $getflectClass->getConstructor();
    return is_null($getConstructor) === false
      ? Util::Mapper( $getConstructor->getParameters(), 
          fn( ReflectionParameter $reflectionParameter ) => (
            $reflectionParameter->getType()->getName()
          )
        )
      : [];
  }

  public static function setInstanceConstruct(
    string | object $objectOrClass
  ): mixed {
    $parameters = static::getParametersClass(
      $objectOrClass
    );
    
    if(is_null($parameters) !== true){
      $handleParameters = Util::Mapper(
        $parameters, fn( string $parameter ) => (
          static::setInstanceConstruct(
            $parameter
          )
        )
      );
      
      return call_user_func_array([
        ReflectUtils::getReflectClass(
          $objectOrClass
        ), "newInstance"
      ], $handleParameters );
    }

    return new $objectOrClass();
  } 

  public static function newInstance(
    ReflectionAttribute $reflectionAttribute
  ): object {
    return $reflectionAttribute->newInstance();
  }

  public static function isValidParameter(
    ReflectionParameter $reflectionAttribute
  ): bool {
    return Util::IsValidArray( $reflectionAttribute->getAttributes() )
        && Util::IsEmptyArray( $reflectionAttribute->getAttributes() ) === false;
  }
}
