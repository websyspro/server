<?php

namespace Websyspro\Server\Decorations\Entitys\Events;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AfterUpdate
{
  public AttributeType $attributeType = AttributeType::EventfterUpdate;
  
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