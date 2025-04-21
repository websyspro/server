<?php

namespace Websyspro\Server\Decorations\Entitys\Events;

use Attribute;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AfterDelete
{
  public AttributeType $attributeType = AttributeType::EventAfterDelete;
  
  public function __construct(
    public readonly mixed $value
  ){}

  public function get(
  ): mixed {
    if( class_exists( $this->value ) === false){
      return $this->value;
    } else {
      return call_user_func_array(
        [ $this->value, "get" ], []
      );
    }

    return null;
  }
}