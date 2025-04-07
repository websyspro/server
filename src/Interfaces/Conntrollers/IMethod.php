<?php

namespace Websyspro\Server\Interfaces\Conntrollers;

use ReflectionAttribute;
use ReflectionProperty;
use TestService;
use Websyspro\Server\Commons\ReflectDependences;
use Websyspro\Server\Commons\ReflectMethod;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IAttributesByProperty;
use Websyspro\Server\Interfaces\Reflections\IParameter;

class IMethod
{
  public string $method;
  public string $endpoint;
  public mixed $methodName;
  public mixed $className;
  public array $properties = [];
  public array $middlewares = [];
  
  public function __construct(
    private ReflectMethod $reflectMethod
  ){
    $this->setMethods();
    $this->setMiddlewares();
    $this->setMethodName();
    $this->setClasseName();
    $this->setProperties();
    $this->setUnReflectMethod();
  }

  private function setMethods(
  ): void {
    [ $attribute ] = Util::Filter( 
      $this->reflectMethod->attributes, (
        fn( object $attribute ) => (
          $attribute->attributeType === 
            AttributeType::Endpoint
        )
      )
    );

    if( $attribute !== null ){
      $this->endpoint = $attribute->endpoint;
      $this->method = $attribute->methodType->name;
    }
  }

  private function setMiddlewares(
  ): void {
    $this->middlewares = Util::Filter( 
      $this->reflectMethod->attributes, (
        fn( object $attribute ) => (
          $attribute->attributeType === 
            AttributeType::Middleware
        )
      )
    );
  }

  private function setMethodName(
  ): void {
    $this->methodName = (
      $this->reflectMethod->methodName
    );
  }

  private function setClasseName(
  ): void {
    $this->className = (
      $this->reflectMethod->className
    );
  }

  private function setProperties(
  ): void {
    $this->properties = Util::Mapper(
      $this->reflectMethod->properties, (
        fn( IParameter $parameter  ) => $parameter
      )
    );
  }

  private function setClassInstance(
  ): object {
    return (
      ReflectDependences::getDependences(
        $this->className
      )
    );
  }

  private function getMethodName(
  ): string {
    return $this->methodName;
  }  

  public function setExecute(
  ): void {
    call_user_func_array([ 
      $this->setClassInstance(), 
      $this->getMethodName()
    ], Util::Mapper(
      $this->properties, (
        fn( IParameter $parameter ) => (
          Util::ValueOfArray(
            Util::Mapper( $parameter->attributes, (
              fn( IAttributesByProperty $attributesByProperty ) => (
                $attributesByProperty->reflectionAttribute
                  ->newInstance()
                  ->execute()
              )
            ))
          )
        )
      )
    ));
  }

  private function setUnReflectMethod(
  ): void {
    unset( $this->reflectMethod );
  }
}