<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;

#[Attribute( Attribute::TARGET_CLASS )]
class ControllerList
{
  public function __construct(
    public readonly array $controllers
  ){}  
}