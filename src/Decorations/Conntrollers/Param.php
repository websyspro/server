<?php

namespace Websyspro\Server\Decorations\Conntrollers;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Server\RequestData;

#[Attribute( Attribute::TARGET_PARAMETER )]
class Param
{
  public AttributeType $attributeType = AttributeType::Parameter;

  public function __construct(
    public readonly string | null $key = null
  ){}

  public function execute(
    array $controllerEndpoint = [],
    array $requestEndpoint = []
  ): array | object | string | null {
    $requestQuery = RequestData::getParams(
      $controllerEndpoint,
      $requestEndpoint
    );
    
    if( is_array( $requestQuery )){
      if( is_null( $this->key ) === false ){
        if( isset( $requestQuery[ $this->key ] )){
          return $requestQuery[ $this->key ];
        } else return null;
      }

      return $requestQuery;
    }

    return null;
  }
}