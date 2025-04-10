<?php

namespace Websyspro\Server\Decorations\Databases;

use Attribute;

#[Attribute( Attribute::TARGET_CLASS )]
class EntityList
{
  public function __construct(
    public readonly array $items = []
  ){}  
}