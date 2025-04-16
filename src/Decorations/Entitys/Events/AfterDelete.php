<?php

namespace Websyspro\Server\Decorations\Entitys\Events;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AfterDelete
{
  public AttributeType $attributeType = AttributeType::EventAfterDelete;
  
  public function __construct(
    public readonly string $class
  ){}

  public function get(
  ): mixed {
    return call_user_func_array(
      [$this->class, "get" ], []
    );
  }
}