<?php

namespace Websyspro\Server\Decorations\Entitys\Statistics;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Index
{
  public AttributeType $attributeType = AttributeType::Indexes;

  public function __construct(
    public readonly int $indexGroup = 1
  ){}
}