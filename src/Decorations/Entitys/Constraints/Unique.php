<?php

namespace Websyspro\Server\Decorations\Entitys\Constraints;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Unique
{
  public AttributeType $attributeType = AttributeType::Uniques;

  public function __construct(
    public readonly int $uniqueGroup = 1
  ){}
}