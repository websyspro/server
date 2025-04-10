<?php

namespace Websyspro\Server\Decorations\Entitys\Generations;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AutoIncrement
{
  public AttributeType $attributeType = AttributeType::Generations;
}