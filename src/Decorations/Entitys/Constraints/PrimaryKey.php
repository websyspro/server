<?php

namespace Websyspro\Server\Decorations\Entitys\Constraints;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class PrimaryKey
{
  public AttributeType $attributeType = AttributeType::PrimaryKey;
}