<?php

namespace Websyspro\Server\Decorators
{
  use Attribute;

  #[Attribute( Attribute::TARGET_CLASS )]
  class ControllerList
  {
    public function __construct(
      private readonly array $controllers = []
    ){}

    public function get(
    ): array {
      return $this->controllers;
    }
  }
}