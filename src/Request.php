<?php

namespace Websyspro\Server;

use Websyspro\Commons\Util;
use Websyspro\Jwt\Decode;
use Websyspro\Server\Enums\RequestType;

class Request
{
  public string $uri;
  public string $method;

  public string|null $base;
  public string|null $ver;
  public string|null $module;
  public string|null $controller;

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

    $this->endpoint = Util::where(
      array_slice( explode( 
        "/", preg_replace( "/\?.+/", "", $this->uri )
      ), 4 ), fn( string $path ) => $path !== ""
    );
  }

  public static function data(
		string|null $key,
    string $instanceType,
		RequestType $requestType,
		array $controllerEndpoint = [],
    array $requestEndpoint = []
	): mixed {
		$requestData = match($requestType)
		{
			RequestType::file => RequestData::getFile( RequestType::file ),
			RequestType::body => RequestData::getBody( RequestType::body ),
			RequestType::query => RequestData::getQuery( RequestType::query ),
			RequestType::params => RequestData::getParams(
				$controllerEndpoint, $requestEndpoint
			)
		};

		if( is_array( $requestData )){
			if( is_null( $key ) === false ){
				if( isset( $requestData[ $key ] )){
          if(Util::isPrimitiveType($instanceType)){
            return $requestData[$key];
          } else {
					  return Util::hydrateObject(
              $requestData[$key], $instanceType
            );
          }
				} else return null;
			}

      if(Util::isPrimitiveType($instanceType)){
        return $requestData;
      } else {
        return Util::hydrateObject(
          $requestData, $instanceType
        );
      }
		}

		return null;
	}

  public static function accessToken(
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