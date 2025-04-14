<?php

namespace Websyspro\Server\Server;

use ReflectionAttribute;
use Websyspro\Server\Commons\ReflectDependences;
use Websyspro\Server\Commons\ReflectMethod;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Decorations\Conntrollers\Param;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IAttributesByProperty;
use Websyspro\Server\Interfaces\Reflections\IParameter;

class ControllerStructureMethod
{
  public string $method;
  public array $endpoint;
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
    [ $attribute ] = (
      Util::Filter( $this->reflectMethod->attributes, (
        fn( ReflectionAttribute $attribute ) => (
          $attribute->newInstance()->attributeType === 
            AttributeType::Endpoint
        ))
      )
    );

    if( $attribute !== null ){
      $attribute = $attribute->newInstance();

      $this->method = $attribute->methodType->name;
      $this->endpoint = empty( $attribute->endpoint )
        ? [] : explode( "/", Util::ParseRequestUri( $attribute->endpoint ));
    }
  }

  private function setMiddlewares(
  ): void {
    $this->middlewares = Util::Filter( 
      $this->reflectMethod->attributes, (
        fn( ReflectionAttribute $attribute ) => (
          $attribute->newInstance()->attributeType === 
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
        fn( IParameter $parameter ) => $parameter
      )
    );
  }

  private function setClassInstance(
  ): object {
    if(method_exists( $this->className, "__construct" )){
      return (
        ReflectDependences::getDependences(
          $this->className
        )
      );
    }

    return new $this->className();
  }

  private function getMethodName(
  ): string {
    return $this->methodName;
  }  

  public function setExecute(
    Request $request
  ): void {
    $response = call_user_func_array([ 
      $this->setClassInstance(), 
      $this->getMethodName()
    ], Util::Mapper(
      $this->properties, (
        fn( IParameter $parameter ) => (
          Util::ValueOfArray(
            Util::Mapper( $parameter->attributes, (
              fn( IAttributesByProperty $attributesByProperty ) => (
                $attributesByProperty->reflectionAttribute->getName() === Param::class
                  ? $attributesByProperty->reflectionAttribute
                      ->newInstance()->execute( $this->endpoint, $request->endpoint )
                  : $attributesByProperty->reflectionAttribute
                      ->newInstance()->execute()    
              )
            ))
          )
        )
      )
    ));

    if( $response instanceof Response ){
      exit( $response->context());
    }
  }

  private function setUnReflectMethod(
  ): void {
    unset( $this->reflectMethod );
  }
}