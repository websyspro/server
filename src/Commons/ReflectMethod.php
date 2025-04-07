<?php

namespace Websyspro\Server\Commons;

use ReflectionAttribute;
use ReflectionMethod;
use ReflectionParameter;
use Websyspro\Server\Interfaces\Reflections\IParameter;

class ReflectMethod
{
  public readonly array $attributes;
  public readonly array $properties;
  public readonly string $methodName;
  public readonly string $className;

  public function __construct(
    public readonly ReflectionMethod $reflectionMethod,
  ){
    $this->setAttributes();
    $this->setProperties();
    $this->setMethodName();
    $this->setClassName();
  }

  private function setAttributes(
  ): void {
    $this->attributes = Util::Mapper(
      $this->reflectionMethod->getAttributes(), (
        fn( ReflectionAttribute $reflectionAttribute ) => (
          $reflectionAttribute
        )
      )
    );    
  }

  private function setProperties(
  ): void {
    $this->properties = Util::Mapper(
      $this->reflectionMethod->getParameters(), (
        fn( ReflectionParameter $reflectionParameters ) => (
          new IParameter( $reflectionParameters )
        )
      )
    );
  }

  private function setMethodName(
  ): void {
    $this->methodName = (
      $this->reflectionMethod->name
    );
  }

  private function setClassName(
  ): void {
    $this->className = (
      $this->reflectionMethod->class
    );
  }  
}