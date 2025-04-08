<?php

namespace Websyspro\Server\Decorations\Entitys\Columns;

use Attribute;
use Websyspro\Server\Enums\Entitys\ColumnType;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Date
{
  public AttributeType $attributeType = AttributeType::Column;
  public ColumnType $columnType = ColumnType::Date;
}