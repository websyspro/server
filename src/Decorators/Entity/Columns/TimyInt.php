<?php

namespace Websyspro\Server\Decorators\Entity\Columns
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class TimyInt
  {
    public AttributeType $attributeType = (
      AttributeType::Columns
    );

    public function __construct(){}

    public function get(
    ): string {
      return "tinyint";
    }
  }
}