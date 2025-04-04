<?php

namespace Websyspro\Server\Decorators\Entity\Generations
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;
  
  #[Attribute( Attribute::TARGET_PROPERTY )]
  class AutoIncrement
  {
    public AttributeType $attributeType = (
      AttributeType::AutoIncrement
    );

    public function get(
    ): string {
      return "auto_increment";
    }    
  }
}