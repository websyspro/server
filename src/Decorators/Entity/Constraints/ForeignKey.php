<?php

namespace Websyspro\Server\Decorators\Entity\Constraints
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class ForeignKey
  {
    public AttributeType $attributeType = (
      AttributeType::Foreigns
    );

    public function __construct(
      private readonly string $entityClass
    ){}

    public function get(
    ): string {
      return $this->entityClass;
    }
  }
}