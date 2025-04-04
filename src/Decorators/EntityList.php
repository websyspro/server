<?php

namespace Websyspro\Server\Decorators
{
  use Attribute;

  #[Attribute( Attribute::TARGET_CLASS )]
  class EntityList
  {
    public function __construct(
      private readonly array $entitys = []
    ){}

    public function get(
    ): array {
      return $this->entitys;
    }
  }
}