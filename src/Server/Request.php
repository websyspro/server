<?php

namespace Websyspro\Server\Server;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Request\RequestType;

class Request
{
  public string $requestUri;
  public string $requestMethod;

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
    [ "REQUEST_URI" => $this->requestUri, 
      "REQUEST_METHOD" => $this->requestMethod
    ] = $_SERVER; 
  
    $this->requestUri = (
      Util::ParseRequestUri(
        $this->requestUri
      )
    );
  }

  private function setEnvironmentPaths(
  ): void {
    [ $this->base, $this->ver, $this->module, $this->controller 
    ] = explode( "/", $this->requestUri );

    $this->endpoint = Util::Filter(
      array_slice( explode( 
        "/", Util::DropParamsFromUri( $this->requestUri )
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
}