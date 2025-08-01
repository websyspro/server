<?php

namespace Websyspro\Server\Decorations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Scheduler
{
  public function __construct(
    public readonly int $minute
  ){}
}