<?php

namespace Websyspro\Server\Decorations\Entitys\Columns;

use Attribute;
use Websyspro\Server\Enums\Entitys\ColumnType;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Datetime
{
  public AttributeType $attributeType = AttributeType::Column;
  public ColumnType $columnType = ColumnType::Datetime;

  public function type(
  ): object {
    return (object)[
      "type" => "datetime",
      "args" => ""
    ];
  } 
}