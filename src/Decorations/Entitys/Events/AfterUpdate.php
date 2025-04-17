<?php

namespace Websyspro\Server\Decorations\Entitys\Events;

use Attribute;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AfterUpdate
{
  public AttributeType $attributeType = AttributeType::EventAfterUpdate;
  
  public function __construct(
    public readonly mixed $value
  ){}

  public function get(
  ): mixed {
    if( Util::isNotClass( $this->value )){
      return $this->value;
    } else if( class_exists( $this->value )){
      return call_user_func_array(
        [ $this->value, "get" ], []
      );
    }

    return null;
  }
}