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
          function( ReflectionAttribute $reflectionAttribute ){
            $newInstance = ReflectUtils::newInstance(
              $reflectionAttribute
            );

            return new StructureAttribute(
              $this->reflectionProperty->getName(),
              $newInstance->attributeType->name,
              $newInstance->get()
            );
          }
      );
    }

    public function getAttributes(
    ): array {
      return $this->attributes;
    }
  }
}