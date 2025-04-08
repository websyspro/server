<?php

namespace Websyspro\Server\Decorations\Entitys\Columns;

use Attribute;
use Websyspro\Server\Enums\Entitys\ColumnType;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Decimal
{
  public AttributeType $attributeType = AttributeType::Column;
  public ColumnType $columnType = ColumnType::Date;

  public function __construct(
    public readonly int $numberOfDigits = 10,
    public readonly int $numberDigitsAfterTheComma = 2
  ){}
}