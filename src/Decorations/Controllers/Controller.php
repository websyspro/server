<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\AttributeType;

#[Attribute( Attribute::TARGET_CLASS )]
class Controller
{
  public AttributeType $attributeType = AttributeType::Controller;

  public function __construct(
    public readonly string $name
  ){}
}