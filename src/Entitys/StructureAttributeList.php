<?php

namespace Websyspro\Server\Entitys
{
  use ReflectionAttribute;
  use ReflectionProperty;
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Reflections\ReflectUtils;

  class StructureAttributeList
  {
    public readonly array $attributes;

    public function __construct(
      private readonly ReflectionProperty $reflectionProperty
    ){
      $this->attributes = Util::Mapper(
        $this->reflectionProperty->getAttributes(), 
          fn( ReflectionAttribute $reflectionAttribute ) => (
            new StructureAttribute(
              $this->reflectionProperty->getName(),
              ReflectUtils::newInstance($reflectionAttribute)->attributeType->name,
              ReflectUtils::newInstance($reflectionAttribute)->get()
            )
          )
      );
    }

    public function getAttributes(
    ): array {
      return $this->attributes;
    }
  }
}