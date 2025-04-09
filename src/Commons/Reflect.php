<?php

namespace Websyspro\Server\Commons;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Reflect
{
  public ReflectionClass $reflectionClass;

  public function __construct(
    private readonly string $objectClass
  ){
    $this->reflectionClass = (
      new ReflectionClass(
        $this->objectClass
      )
    );
  }

  public function getAttriutes(
  ): array {
    return Util::Mapper(
      $this->reflectionClass->getAttributes(), (
        fn( ReflectionAttribute $reflectionAttribute ) => (
          $reflectionAttribute->newInstance()
        )
      )
    );
  }

  public function getAttributesByProperties(
  ): array {
    return Util::Mapper(
      $this->reflectionClass->getProperties(), (
        fn( ReflectionProperty $reflectionProperty ) => (
          new IProperty(
            $reflectionProperty->getName(),
            $reflectionProperty->getAttributes()
          )
        )
      )
    );
  }

  public function getMethods(
  ): array {
    return Util::Mapper(
      $this->reflectionClass->getMethods(), (
        fn( ReflectionMethod $reflectionMethod ) => (
          new ReflectMethod( $reflectionMethod )
        )
      )
    );
  }
}