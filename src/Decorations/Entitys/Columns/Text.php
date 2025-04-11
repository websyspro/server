<?php

namespace Websyspro\Server\Decorations\Entitys\Columns;

use Attribute;
use Websyspro\Server\Enums\Entitys\ColumnType;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Text
{
  public AttributeType $attributeType = AttributeType::Column;
  public ColumnType $columnType = ColumnType::Text;

  public function __construct(
    public readonly int $size = 255
  ){}

  public function type(
  ): object {
    return (object)[
      "type" => "text",
      "args" => "{$this->size}"
    ];
  } 
}