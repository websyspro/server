<?php

namespace Websyspro\Server\Entitys
{
  class StructureAttribute
  {
    public function __construct(
      public readonly string $name,
      public readonly string $type,
      public readonly mixed $args
    ){}
  }
}