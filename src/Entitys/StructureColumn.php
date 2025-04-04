<?php

namespace Websyspro\Server\Entitys
{
  use ReflectionProperty;

  class StructureColumn
  {
    public readonly string $name;
    public readonly string $type;

    public function __construct(
      private readonly ReflectionProperty $reflectionProperty
    ){
      $this->name = $reflectionProperty->getName();
      $this->type = "";
    }
  }
}