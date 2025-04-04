<?php

namespace Websyspro\Server\Decorators
{
  use Websyspro\Server\Enums\RequestType;
  use Websyspro\Server\Http\Request;
  use Attribute;

  #[Attribute(Attribute::TARGET_PARAMETER)]
  class Body
  {
    public function __construct(
      public string | null $key = null
    ){}

    public function execute(
    ): mixed {
      $requestBody = Request::getBody(
        RequestType::BODY
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
}