<?php

namespace Websyspro\Server\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller {
  public function __construct(
    private readonly string $name 
  ){}

  public function getName(
  ): string {
    return $this->name;
  }
}