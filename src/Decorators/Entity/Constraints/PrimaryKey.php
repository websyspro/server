<?php

namespace Websyspro\Server\Decorators\Entity\Constraints
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class PrimaryKey
  {
    public AttributeType $attributeType = (
      AttributeType::PrimaryKey
    );

    public function get(
    ): string {
      return "primary key";
    }
  }
}