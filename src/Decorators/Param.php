<?php

namespace Websyspro\Server\Decorators
{
  use Attribute;
  use Websyspro\Server\Http\Request;

  #[Attribute(Attribute::TARGET_PARAMETER)]
  class Param
  {
    public function __construct(
      public string | null $key = null
    ){}

    public function execute(
      array $controllerEndpoint = [],
      array $requestEndpoint = []
    ): mixed {
      $requestParams = Request::getParams(
        $controllerEndpoint, $requestEndpoint
      );
      
      if( is_array( $requestParams )){
        if( is_null( $this->key ) === false ){
          if( isset( $requestParams[ $this->key ] )){
            return $requestParams[ $this->key ];
          } else return null;
        }

        return $requestParams;
      }

      return null;
    }
  }
}