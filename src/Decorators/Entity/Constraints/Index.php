<?php

namespace Websyspro\Server\Decorators\Entity\Constraints
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class Index
  {
    public AttributeType $attributeType = (
      AttributeType::Indexes
    );
        
    public function __construct(
      public int $order = 1
    ){}

    public function get(
    ): int {
      return $this->order;
    }
  }    
}