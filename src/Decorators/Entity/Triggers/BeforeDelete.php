<?php

namespace Websyspro\Server\Decorators\Entity\Triggers
{
  use Attribute;
  use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  #[Attribute( Attribute::TARGET_PROPERTY )]
  class BeforeDelete
  {
    public AttributeType $attributeType = (
      AttributeType::TriggersBeforeDelete
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