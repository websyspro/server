<?php

namespace Websyspro\Server\Decorators
{
  use Attribute;
  use Websyspro\Server\Http\Request;

  #[Attribute(Attribute::TARGET_PARAMETER)]
  class Query
  {
    public function __construct(
      public string | null $key = null
    ){}

    public function execute(
    ): mixed {
      $requestQuery = Request::getQuery();
      
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
}