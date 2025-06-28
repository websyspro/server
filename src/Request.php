<?php

namespace Websyspro\Server;

use Websyspro\Commons\Util;
use Websyspro\Jwt\Decode;
use Websyspro\Server\Enums\RequestType;

class Request
{
  public string $uri;
  public string $method;

  public string $base;
  public string $ver;
  public string $module;
  public string $controller;

  public array $endpoint;

  public function __construct(
  ){
    $this->setEnvironment();
    $this->setEnvironmentPaths();
  }

  private function setEnvironment(
  ): void {
    [ "REQUEST_URI" => $this->uri, 
      "REQUEST_METHOD" => $this->method
    ] = $_SERVER; 
  
    $this->uri = preg_replace(
      [ "/(?<=[^\/])\?/", "/^\/*/", "/\/*$/"  ], 
      [ "/?", "" ], $this->uri
    );  
  }

  private function setEnvironmentPaths(
  ): void {
    [ $this->base, $this->ver, $this->module, $this->controller 
    ] = explode( "/", $this->uri );

    $this->endpoint = Util::Where(
      array_slice( explode( 
        "/", preg_replace( "/\?.+/", "", $this->uri )
      ), 4 ), fn( string $path ) => $path !== ""
    );
  }

  public static function data(
		string | null $key,
		RequestType $requestType,
		array $controllerEndpoint = [],
    array $requestEndpoint = []
	): array | object | string | null {
		$requestData = match($requestType)
		{
			RequestType::FILE => RequestData::getFile( RequestType::FILE ),
			RequestType::BODY => RequestData::getBody( RequestType::BODY ),
			RequestType::QUERY => RequestData::getQuery( RequestType::QUERY ),
			RequestType::PARAMS => RequestData::getParams(
				$controllerEndpoint, $requestEndpoint
			)
		};
		
		if( is_array( $requestData )){
			if( is_null( $key ) === false ){
				if( isset( $requestData[ $key ] )){
					return $requestData[ $key ];
				} else return null;
			}

			return $requestData;
		}

		return null;
	}

  public static function AccessToken(
  ): Decode|null {
    ["HTTP_AUTHORIZATION" => $httpAuthorization] = $_SERVER;

    if($httpAuthorization === null){
      return null;
    }

    [$bearer, $token] = explode(
      " ", $httpAuthorization
    );

    if($bearer === null || $bearer !== "Bearer"){
      return null;
    }

    if($token === null){
      return null;
    }

    return new Decode(
      $token, publickey
    );
  }
}