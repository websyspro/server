<?php

namespace Websyspro\Server\Decorators
{
  use Websyspro\Server\Enums\RequestType;
  use Websyspro\Server\Http\Request;
  use Attribute;

  #[Attribute(Attribute::TARGET_PARAMETER)]
  class File
  {
    public function __construct(
      public string | null $key = null
    ){}

    public function execute(
    ): mixed {
      $requestFile = Request::getFiles(
        RequestType::FILE
      );

      if( is_array( $requestFile )){
        if( is_null( $this->key ) === false ){
          if( isset( $requestFile[ $this->key ] )){
            return $requestFile[ $this->key ];
          } else return null;
        }

        return $requestFile;
      }

      return null;
    }
  }
}