<?php

namespace Websyspro\Server\Decorations\Entitys\Constraints;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class ForeignKey
{
  public AttributeType $attributeType = AttributeType::Foreigns;

  public function __construct(
    public readonly string $referenceClass
  ){}
}