<?php

namespace Websyspro\Server\Decorations\Conntrollers;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Enums\Request\RequestType;
use Websyspro\Server\Server\RequestData;

#[Attribute( Attribute::TARGET_PARAMETER )]
class File
{
  public AttributeType $attributeType = AttributeType::Parameter;

  public function __construct(
    public readonly string | null $key = null
  ){}

  public function execute(
  ): array | object | string | null {
    $requestBody = RequestData::getFiles(
      RequestType::FILE
    );
    
    if( is_array( $requestBody )){
      if( is_null( $this->key ) === false ){
        if( isset( $requestBody[ $this->key ] )){
          return $requestBody[ $this->key ];
        } else return null;
      }

      return $requestBody;
    }

    return null;
  }
}