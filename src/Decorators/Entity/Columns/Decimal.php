<?php

namespace Websyspro\Server\Decorators\Entity\Columns
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class Decimal
  {
    public AttributeType $attributeType = (
      AttributeType::Columns
    );

    public function __construct(
      private readonly int $countNumbers = 10,
      private readonly int $countNumbersFloats = 4
    ){}

    public function get(
    ): string {
      return "decimal({$this->countNumbers},{$this->countNumbersFloats})";
    }
  }
}