<?php

namespace Websyspro\Server\Decorators\Entity\Requireds
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class NotNull
  {
    public AttributeType $attributeType = (
      AttributeType::Requireds
    );

    public function get(
    ): string {
      return "not null";
    }
  }    
}