<?php

namespace Websyspro\Server\Decorators\Entity\Columns
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class Text
  {
    public AttributeType $attributeType = (
      AttributeType::Columns
    );

    public function __construct(
      private readonly int $args = 255
    ){}

    public function get(
    ): string {
      return "varchar({$this->args})";
    }
  }
}