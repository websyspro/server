<?php

namespace Websyspro\Server\Decorations\Entitys\Requireds;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class NotNull
{
  public AttributeType $attributeType = AttributeType::Requireds;
}