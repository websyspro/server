<?php

namespace Websyspro\Server\Decorators\Entity\Constraints
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class Unique
  {
    public AttributeType $attributeType = (
      AttributeType::Uniques
    );
        
    public function __construct(
      public readonly int $order = 1
    ){}

    public function get(
    ): int {
      return $this->order;
    }
  }    
}