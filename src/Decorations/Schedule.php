<?php

namespace Websyspro\Server\Decorations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Schedule
{
  public function __construct(
    public readonly int $minute
  ){}
}