<?php

namespace Websyspro\Server\Decorators\Entity\Columns
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class Time
  {
    public AttributeType $attributeType = (
      AttributeType::Columns
    );

    public function get(
    ): string {
      return "time";
    }
  }
}