<?php

namespace Websyspro\Server\Decorators\Entity\Triggers
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class BeforeCreate
  {
    public AttributeType $attributeType = (
      AttributeType::TriggersBeforeCreate
    );

    public function __construct(
      private readonly string $triggerClass
    ){}

    public function get(
    ): callable {
      return fn() => call_user_func_array([
        $this->triggerClass, "execute"
      ], []);
    }
  }
}